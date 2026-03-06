@extends('layouts.admin')

@section('title', 'Users')
@section('topbar-title', 'User Management')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Students</h1>
        <p class="page-subtitle">Manage all registered students and their access.</p>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-inner">
        <form method="GET" class="flex flex-wrap gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email…"
                   class="form-input" style="max-width:280px;" />
            <select name="status" class="form-select" style="max-width:160px;">
                <option value="">All Status</option>
                <option value="active"   {{ request('status')=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactive</option>
            </select>
            <button type="submit" class="btn btn-sky">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="padding-left:1.5rem;">Student</th>
                    <th>Phone</th>
                    <th>Subscription</th>
                    <th>Enrollments</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td style="padding-left:1.5rem;">
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="" class="avatar rounded-full" />
                            <div>
                                <p class="text-white font-semibold text-sm">{{ $user->name }}</p>
                                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35);">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-sm" style="color:rgba(255,255,255,0.5);">{{ $user->phone ?? '—' }}</td>
                    <td>
                        @if($user->subscriptions->isNotEmpty())
                            <div>
                                <span class="badge badge-active">{{ $user->subscriptions->first()->plan->name ?? 'Active' }}</span>
                                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.3);">
                                    Until {{ $user->subscriptions->first()->ends_at?->format('M d, Y') }}
                                </p>
                            </div>
                        @else
                            <span class="badge badge-inactive">No Plan</span>
                        @endif
                    </td>
                    <td class="text-center text-white text-sm">{{ $user->enrollments_count }}</td>
                    <td class="text-xs" style="color:rgba(255,255,255,0.4);">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge badge-active">Active</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-1.5">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-sm">View</a>
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-sky' }}">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-16" style="color:rgba(255,255,255,0.3);">
                        No students registered yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-6 pb-4">
        {{ $users->withQueryString()->links('pagination::default') }}
    </div>
    @endif
</div>

@endsection
