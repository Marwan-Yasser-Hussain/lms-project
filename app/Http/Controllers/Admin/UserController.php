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

        // Use ar-php to reshape Arabic text (connect letters + handle RTL BiDi).
        // utf8Glyphs() converts Unicode Arabic to presentation-form glyphs that
        // DomPDF can render correctly — connected and in the right visual order.
        $arabic = new \ArPHP\I18N\Arabic();
        $users->each(function ($user) use ($arabic) {
            if (preg_match('/\p{Arabic}/u', $user->name)) {
                $user->name = $arabic->utf8Glyphs($user->name, 500);
            }
            if ($user->phone && preg_match('/\p{Arabic}/u', $user->phone)) {
                $user->phone = $arabic->utf8Glyphs($user->phone, 500);
            }
        });

        $html = view('exports.users_pdf', [
            'users'  => $users,
            'search' => $request->search ?? '',
            'role'   => $request->role   ?? '',
            'status' => $request->status ?? '',
        ])->render();

        $pdf = Pdf::setOption(['convert_entities' => false])
            ->loadHTML($html, 'UTF-8')
            ->setPaper('a4', 'landscape');

        $filename = 'users_' . now()->format('Y-m-d_His') . '.pdf';

        return response($pdf->output(), 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'              => 'no-cache',
            'Expires'             => '0',
        ]);
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

    public function bulkDelete(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('ids', '')), 'is_numeric');

        if (empty($ids)) {
            return back()->with('error', 'No users selected.');
        }

        // Prevent the currently logged-in admin from deleting themselves
        $ids = array_diff($ids, [auth()->id()]);

        $count = User::whereIn('id', $ids)->delete();

        return back()->with('success', "{$count} user(s) deleted successfully.");
    }
    // ─── Import: Step 1 — parse uploaded file ────────────────────────────────

    public function importPreview(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ]);

        $raw   = \Maatwebsite\Excel\Facades\Excel::toArray(new \App\Imports\RawImport(), $request->file('file'));
        $sheet = $raw[0] ?? [];

        if (count($sheet) < 2) {
            return response()->json(['error' => 'The file appears to be empty or has no data rows.'], 422);
        }

        // Detect header row
        $headerRowIndex = 0;
        foreach ($sheet as $i => $row) {
            $normalised = array_map(fn($c) => strtolower(trim((string)$c)), $row);
            if (in_array('name', $normalised) || in_array('email', $normalised)) {
                $headerRowIndex = $i;
                break;
            }
        }

        $headers  = array_map(fn($c) => strtolower(trim((string)$c)), $sheet[$headerRowIndex]);
        $dataRows = array_slice($sheet, $headerRowIndex + 1);

        $col = [];
        foreach (['name','email','phone','role','password'] as $field) {
            $idx = array_search($field, $headers);
            $col[$field] = $idx !== false ? $idx : null;
        }

        $rows = [];
        foreach ($dataRows as $row) {
            $values = array_filter(array_map('trim', array_map('strval', $row)));
            if (empty($values)) continue;

            $rows[] = [
                'name'     => $col['name']     !== null ? trim((string)($row[$col['name']]     ?? '')) : '',
                'email'    => $col['email']    !== null ? trim((string)($row[$col['email']]    ?? '')) : '',
                'phone'    => $col['phone']    !== null ? trim((string)($row[$col['phone']]    ?? '')) : '',
                'role'     => $col['role']     !== null ? strtolower(trim((string)($row[$col['role']]     ?? 'user'))) : 'user',
                'password' => $col['password'] !== null ? trim((string)($row[$col['password']] ?? '')) : '',
            ];
        }

        if (empty($rows)) {
            return response()->json(['error' => 'No data rows found after the header.'], 422);
        }

        return response()->json(['rows' => $rows]);
    }

    // ─── Import: Step 2 — confirm and create ─────────────────────────────────

    public function importConfirm(Request $request)
    {
        $rows = $request->input('rows', []);

        if (empty($rows)) {
            return response()->json(['error' => 'No rows to import.'], 422);
        }

        $created = 0;
        $errors  = [];

        foreach ($rows as $i => $row) {
            $name     = trim($row['name']     ?? '');
            $email    = strtolower(trim($row['email']    ?? ''));
            $phone    = trim($row['phone']    ?? '') ?: null;
            $role     = in_array($row['role'] ?? 'user', ['admin','user']) ? $row['role'] : 'user';
            $password = trim($row['password'] ?? '');

            $rowErrors = [];
            if ($name === '')                                      $rowErrors[] = 'Name is required.';
            if ($email === '')                                     $rowErrors[] = 'Email is required.';
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))   $rowErrors[] = 'Email is invalid.';
            elseif (User::where('email', $email)->exists())       $rowErrors[] = "Email '{$email}' already exists.";
            if ($password !== '' && strlen($password) < 8)        $rowErrors[] = 'Password must be at least 8 characters.';

            if (!empty($rowErrors)) {
                $errors[] = ['row' => $i + 1, 'name' => $name, 'email' => $email, 'messages' => $rowErrors];
                continue;
            }

            User::create([
                'name'      => $name,
                'email'     => $email,
                'phone'     => $phone,
                'role'      => $role,
                'password'  => Hash::make($password !== '' ? $password : \Illuminate\Support\Str::random(12)),
                'is_active' => true,
            ]);
            $created++;
        }

        return response()->json(compact('created', 'errors'));
    }

    // ─── Import: Download blank template ─────────────────────────────────────

    public function importTemplate()
    {
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\UsersImportTemplate(),
            'users_import_template.xlsx'
        );
    }
}
