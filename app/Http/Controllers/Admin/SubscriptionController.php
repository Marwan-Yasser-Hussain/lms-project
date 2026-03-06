<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $plans = SubscriptionPlan::withCount('subscriptions')->orderBy('sort_order')->get();

        $subscriptions = Subscription::with(['user', 'plan'])
            ->when($request->search, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%')))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return view('admin.subscriptions.index', compact('plans', 'subscriptions'));
    }

    // -- Plan CRUD --

    public function createPlan()
    {
        return view('admin.subscriptions.create-plan');
    }

    public function storePlan(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'duration_days'=> 'required|integer|min:1',
            'features'     => 'nullable|array',
            'features.*'   => 'string',
            'is_popular'   => 'boolean',
            'is_active'    => 'boolean',
            'sort_order'   => 'integer',
        ]);

        $validated['features'] = array_filter($validated['features'] ?? []);

        SubscriptionPlan::create($validated);
        return redirect()->route('admin.subscriptions.index')->with('success', 'Plan created!');
    }

    public function editPlan(SubscriptionPlan $plan)
    {
        return view('admin.subscriptions.edit-plan', compact('plan'));
    }

    public function updatePlan(Request $request, SubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'description'  => 'nullable|string',
            'price'        => 'required|numeric|min:0',
            'duration_days'=> 'required|integer|min:1',
            'features'     => 'nullable|array',
            'features.*'   => 'string',
            'is_popular'   => 'boolean',
            'is_active'    => 'boolean',
            'sort_order'   => 'integer',
        ]);

        $validated['features'] = array_filter($validated['features'] ?? []);
        $plan->update($validated);

        return redirect()->route('admin.subscriptions.index')->with('success', 'Plan updated!');
    }

    public function destroyPlan(SubscriptionPlan $plan)
    {
        $plan->delete();
        return back()->with('success', 'Plan deleted.');
    }

    // -- Manage user subscriptions --

    public function updateStatus(Request $request, Subscription $subscription)
    {
        $request->validate(['status' => 'required|in:active,expired,cancelled,pending']);
        $subscription->update(['status' => $request->status]);
        return back()->with('success', 'Subscription status updated.');
    }
}
