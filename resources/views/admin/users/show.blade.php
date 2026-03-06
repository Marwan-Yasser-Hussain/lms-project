@extends('layouts.admin')

@section('title', $user->name . ' — User Detail')
@section('topbar-title', 'User Profile')

@section('content')

<div class="page-header">
    <div class="flex items-center gap-4">
        <img src="{{ $user->avatar_url }}" alt="" class="avatar-lg rounded-full" />
        <div>
            <h1 class="page-title">{{ $user->name }}</h1>
            <p class="page-subtitle">{{ $user->email }}</p>
        </div>
    </div>
    <div class="flex gap-2">
        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
            @csrf
            <button type="submit" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-sky' }}">
                {{ $user->is_active ? 'Deactivate Account' : 'Activate Account' }}
            </button>
        </form>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">← Back</a>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- Profile card --}}
    <div class="flex flex-col gap-5">
        <div class="card">
            <div class="card-inner">
                <h2 class="font-bold text-white mb-4">Profile</h2>
                <div class="flex flex-col gap-3 text-sm">
                    <div class="flex justify-between">
                        <span style="color:rgba(255,255,255,0.4);">Phone</span>
                        <span class="text-white">{{ $user->phone ?? '—' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:rgba(255,255,255,0.4);">Role</span>
                        <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:rgba(255,255,255,0.4);">Status</span>
                        <span class="badge badge-{{ $user->is_active ? 'active' : 'inactive' }}">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:rgba(255,255,255,0.4);">Joined</span>
                        <span class="text-white">{{ $user->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:rgba(255,255,255,0.4);">Enrollments</span>
                        <span class="text-white">{{ $user->enrollments->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:rgba(255,255,255,0.4);">Certificates</span>
                        <span class="text-white">{{ $user->certificates->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Subscriptions --}}
        <div class="card">
            <div class="card-inner">
                <h2 class="font-bold text-white mb-4">Subscriptions</h2>
                @forelse($user->subscriptions as $sub)
                <div class="mb-3 p-3 rounded-xl" style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);">
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-white font-semibold text-sm">{{ $sub->plan->name ?? '—' }}</p>
                        <span class="badge badge-{{ $sub->status }}">{{ ucfirst($sub->status) }}</span>
                    </div>
                    <p class="text-xs" style="color:rgba(255,255,255,0.35);">
                        {{ $sub->starts_at?->format('M d') }} – {{ $sub->ends_at?->format('M d, Y') }}
                    </p>
                    <p class="text-xs mt-1" style="color:#5bb8ff;">${{ number_format($sub->amount_paid, 2) }}</p>
                </div>
                @empty
                <p class="text-sm" style="color:rgba(255,255,255,0.3);">No subscriptions.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Enrollments + Certificates --}}
    <div class="xl:col-span-2 flex flex-col gap-6">

        {{-- Enrollments --}}
        <div class="card">
            <div class="card-inner">
                <h2 class="font-bold text-white mb-4">Enrolled Courses</h2>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Course</th>
                            <th>Progress</th>
                            <th>Enrolled</th>
                            <th>Completed</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->enrollments as $en)
                        <tr>
                            <td>
                                <p class="text-white text-sm font-medium">{{ $en->course->title ?? '—' }}</p>
                            </td>
                            <td>
                                <div style="background:rgba(255,255,255,0.07);border-radius:4px;height:6px;width:100px;overflow:hidden;">
                                    <div style="height:100%;width:{{ $en->progress_percentage }}%;background:linear-gradient(90deg,#045592,#930056);border-radius:4px;"></div>
                                </div>
                                <span class="text-xs" style="color:rgba(255,255,255,0.4);">{{ $en->progress_percentage }}%</span>
                            </td>
                            <td class="text-xs" style="color:rgba(255,255,255,0.4);">{{ $en->enrolled_at?->format('M d, Y') }}</td>
                            <td class="text-xs" style="color:rgba(255,255,255,0.4);">{{ $en->completed_at?->format('M d, Y') ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-8" style="color:rgba(255,255,255,0.3);">No enrollments.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Certificates --}}
        <div class="card">
            <div class="card-inner">
                <h2 class="font-bold text-white mb-4">Certificates</h2>
                @forelse($user->certificates as $cert)
                <div class="flex items-center justify-between p-3 rounded-xl mb-3"
                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);">
                    <div>
                        <p class="text-white text-sm font-semibold">{{ $cert->course_title }}</p>
                        <p class="text-xs" style="color:rgba(255,255,255,0.35);">
                            ID: <span style="font-family:monospace;">{{ $cert->certificate_uid }}</span>
                        </p>
                        <p class="text-xs" style="color:rgba(255,255,255,0.35);">Issued {{ $cert->issued_at?->format('M d, Y') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                         style="background:rgba(147,0,86,0.2);border:1px solid rgba(147,0,86,0.3);">
                        🏆
                    </div>
                </div>
                @empty
                <p class="text-sm" style="color:rgba(255,255,255,0.3);">No certificates issued yet.</p>
                @endforelse
            </div>
        </div>

    </div>
</div>

@endsection
