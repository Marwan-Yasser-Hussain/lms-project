<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Exports\UsersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()
            ->when($request->search, fn($q) => $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'))
            ->when($request->status, fn($q) => $q->where('is_active', $request->status === 'active'))
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->withCount('enrollments')
            ->with(['subscriptions' => fn($q) => $q->where('status', 'active')->where('ends_at', '>=', now())->with('plan')])
            ->latest();

        // If no role filter, show both admins and users (exclude only super hidden roles if any)
        if (! $request->filled('role')) {
            $query->whereIn('role', ['admin', 'user']);
        }

        $perPage = in_array((int) $request->per_page, [10, 25, 50, 100]) ? (int) $request->per_page : 20;
        $users = $query->paginate($perPage)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'role'     => ['required', 'in:admin,user'],
            'password' => ['required', 'confirmed', Password::min(8)],
            'phone'    => ['nullable', 'string', 'max:30'],
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'password'  => Hash::make($request->password),
            'phone'     => $request->phone,
            'is_active' => true,
        ]);

        return back()->with('success', 'User created successfully.');
    }

    public function exportExcel(Request $request)
    {
        $filename = 'users_' . now()->format('Y-m-d_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(
            new UsersExport(
                $request->search ?? '',
                $request->role   ?? '',
                $request->status ?? ''
            ),
            $filename
        );
    }

    public function exportPdf(Request $request)
    {
        $query = User::query()
            ->when($request->search, fn($q) => $q
                ->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'))
            ->when($request->status, fn($q) => $q->where('is_active', $request->status === 'active'))
            ->when($request->role, fn($q) => $q->where('role', $request->role))
            ->when(!$request->filled('role'), fn($q) => $q->whereIn('role', ['admin', 'user']))
            ->withCount('enrollments')
            ->latest();

        $users = $query->get();

        $pdf = Pdf::loadView('exports.users_pdf', [
            'users'  => $users,
            'search' => $request->search ?? '',
            'role'   => $request->role   ?? '',
            'status' => $request->status ?? '',
        ])->setPaper('a4', 'landscape');

        return $pdf->download('users_' . now()->format('Y-m-d_His') . '.pdf');
    }

    public function show(User $user)
    {
        $user->load(['enrollments.course', 'subscriptions.plan', 'certificates.course']);
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email,' . $user->id],
            'role'     => ['required', 'in:admin,user'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'is_active'=> ['boolean'],
        ]);

        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'phone'     => $request->phone,
            'is_active' => $request->boolean('is_active', $user->is_active),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'User updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }
}
