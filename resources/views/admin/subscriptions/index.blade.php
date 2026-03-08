@extends('layouts.admin')

@section('title', 'Subscriptions')
@section('topbar-title', 'Subscription Management')

@section('content')

{{-- Welcome/Header Banner --}}
<div class="mb-8 rounded-2xl relative overflow-hidden animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute -top-24 -right-24 w-64 h-64 bg-[#e0b0ff] rounded-full mix-blend-screen filter blur-[90px] opacity-20 pointer-events-none"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10">
        <div>
            <h1 class="text-3xl font-black text-white mb-2 tracking-tight">Subscriptions</h1>
            <p class="text-white/60 text-sm md:text-base max-w-xl leading-relaxed">
                Manage your platform's subscription tiers and monitor active student plans.
            </p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #e0b0ff, #1A1262); color: #fff; border: 1px solid #e0b0ff; box-shadow: 0 4px 14px rgba(224, 176, 255, 0.3);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create New Plan
            </a>
        </div>
    </div>
</div>

{{-- ── Plans Grid ─────────────────────────────────────────────────────────── --}}
<div class="flex items-center justify-between mb-6 animate-fade-up delay-1">
    <h2 class="text-xl font-bold text-white tracking-tight">Available Plans</h2>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">
    @forelse($plans as $plan)
    <div class="stat-card animate-fade-up delay-2 group flex flex-col" style="background: linear-gradient(145deg, #160D50, #0F043D); {{ $plan->is_popular ? 'border-top: 3px solid #930056;' : 'border-top: 3px solid #045592;' }} min-height: 100%;">
        @if($plan->is_popular)
            <div class="absolute top-0 right-0 px-3 py-1 bg-[#930056] text-white text-[0.65rem] font-bold uppercase tracking-wider rounded-bl-lg">
                Most Popular
            </div>
            <!-- Glow effect for popular plan -->
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-[#930056] rounded-full filter blur-[70px] opacity-10 pointer-events-none z-0"></div>
        @endif
        
        <div class="relative z-10 flex flex-col h-full">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h3 class="text-white font-bold text-xl tracking-tight mb-1 group-hover:text-[{{ $plan->is_popular ? '#ff80c8' : '#5bb8ff' }}] transition-colors">{{ $plan->name }}</h3>
                    <p class="text-xs font-semibold tracking-widest uppercase text-white/40">{{ $plan->duration_days }} days</p>
                </div>
            </div>

            <div class="mb-6">
                <span class="text-4xl font-black text-white group-hover:text-[{{ $plan->is_popular ? '#ff80c8' : '#5bb8ff' }}] transition-colors">${{ number_format($plan->price, 2) }}</span>
                <span class="text-sm text-white/40">/ {{ $plan->duration_days }}d</span>
            </div>

            @if($plan->features)
            <ul class="mb-8 space-y-3 flex-grow">
                @foreach($plan->features as $feature)
                <li class="flex items-start text-sm text-white/70">
                    <svg class="w-5 h-5 flex-shrink-0 mr-3 mt-0.5" style="color: {{ $plan->is_popular ? '#930056' : '#045592' }};" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ $feature }}
                </li>
                @endforeach
            </ul>
            @else
            <div class="flex-grow"></div>
            @endif

            <div class="mt-auto pt-5 border-t border-white/5 flex items-center justify-between">
                <div class="flex items-center gap-2 text-xs font-semibold">
                    <svg class="w-4 h-4 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354l3.708 2.31c.642.4 1.391.802 2.14 1.154m-8.91 3.535l-1.928 2.656m12.42-3.834L17.58 8.44M12 16c2.5 0 4-1 4-1m-4 0v4m0 0a2 2 0 100-4 2 2 0 000 4z" /></svg>
                    <span class="text-white/60">{{ $plan->subscriptions_count }} Users</span>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.subscriptions.plans.edit', $plan) }}" class="btn btn-secondary btn-sm p-2" title="Edit Plan">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </a>
                    <form method="POST" action="{{ route('admin.subscriptions.plans.destroy', $plan) }}" onsubmit="return confirm('Delete this plan?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm p-2" title="Delete Plan">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card md:col-span-2 xl:col-span-3">
        <div class="card-inner text-center py-12" style="color:rgba(255,255,255,0.3);">
            <p class="mb-4">No subscription plans configured yet.</p>
            <a href="{{ route('admin.subscriptions.plans.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #045592, #033d6a);">Create your first plan</a>
        </div>
    </div>
    @endforelse
</div>

{{-- ── User Subscriptions Table ───────────────────────────────────────────── --}}
<div class="flex items-center justify-between mb-6 animate-fade-up delay-3">
    <h2 class="text-xl font-bold text-white tracking-tight">Active & Past Subscriptions</h2>
</div>

{{-- Filter/Search card --}}
<div class="card mb-6 animate-fade-up delay-4" style="background: linear-gradient(135deg, #160D50 0%, #120A42 100%);">
    <div class="card-inner py-4">
        <form method="GET" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by student name..." class="form-input w-full" style="padding-left: 2.75rem; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);" />
            </div>
            <select name="status" class="form-select flex-shrink-0" style="width: 150px; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                <option value="">All Statuses</option>
                <option value="active"    {{ request('status')=='active'?'selected':'' }}>✅ Active</option>
                <option value="expired"   {{ request('status')=='expired'?'selected':'' }}>⏰ Expired</option>
                <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>❌ Cancelled</option>
                <option value="pending"   {{ request('status')=='pending'?'selected':'' }}>⏳ Pending</option>
            </select>
            <select name="per_page" class="form-select flex-shrink-0" style="width: 130px; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                <option value="10"  {{ request('per_page','20')=='10'  ? 'selected':'' }}>10 / page</option>
                <option value="20"  {{ request('per_page','20')=='20'  ? 'selected':'' }}>20 / page</option>
                <option value="50"  {{ request('per_page','20')=='50'  ? 'selected':'' }}>50 / page</option>
                <option value="100" {{ request('per_page','20')=='100' ? 'selected':'' }}>100 / page</option>
            </select>
            <button type="submit" class="btn btn-sky px-5 flex-shrink-0">Apply</button>
            @if(request()->hasAny(['search', 'status', 'per_page']))
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary px-4 text-white/50 flex-shrink-0">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Subscriptions Table --}}
<div class="card animate-fade-up delay-5" style="background: linear-gradient(135deg, #160D50 0%, #0F043D 100%);">
    <div class="overflow-x-auto pb-2">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="pl-6">Student Info</th>
                    <th>Plan</th>
                    <th>Date Span</th>
                    <th>Revenue</th>
                    <th>Status</th>
                    <th class="pr-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $sub)
                <tr>
                    <td class="pl-6">
                        <div class="flex items-center gap-3">
                            <img src="{{ $sub->user->avatar_url }}" alt="" class="w-9 h-9 rounded-full object-cover border border-white/10" />
                            <div>
                                <p class="text-white text-sm font-semibold hover:text-[#5bb8ff] transition-colors">
                                    <a href="{{ route('admin.users.show', $sub->user) }}">{{ $sub->user->name }}</a>
                                </p>
                                <p class="text-xs text-white/40">{{ $sub->user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="text-sm text-white font-medium">{{ $sub->plan->name ?? '—' }}</span>
                    </td>
                    <td>
                        <div class="flex flex-col text-xs">
                            <span class="text-white/60 mb-0.5"><strong class="text-white/40 font-medium">Start:</strong> {{ $sub->starts_at?->format('M d, Y') ?? '—' }}</span>
                            <span class="text-white/60"><strong class="text-white/40 font-medium">Ends:</strong> {{ $sub->ends_at?->format('M d, Y') ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="text-sm font-semibold" style="color: {{ $sub->status == 'active' ? '#5eead4' : '#5bb8ff' }};">
                        ${{ number_format($sub->amount_paid, 2) }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $sub->status }} px-3 py-1">{{ ucfirst($sub->status) }}</span>
                    </td>
                    <td class="pr-6 text-right">
                        <form method="POST" action="{{ route('admin.subscriptions.update-status', $sub) }}" class="flex items-center justify-end gap-2">
                            @csrf
                            <select name="status" class="form-select bg-[#0F043D] border-white/10 text-xs py-1.5 px-2" style="max-width:110px;">
                                @foreach(['active','expired','cancelled','pending'] as $s)
                                <option value="{{ $s }}" {{ $sub->status===$s?'selected':'' }}>Mark {{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-secondary btn-sm bg-white/5 hover:bg-white/10 text-xs px-3 py-1.5">Save</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-16">
                        <div class="flex flex-col items-center justify-center opacity-40">
                            <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            <p class="text-sm">No subscription records found.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($subscriptions->hasPages())
    <div class="p-6 border-t border-white/5">
        {{ $subscriptions->withQueryString()->links('pagination::default') }}
    </div>
    @endif
</div>

@endsection
