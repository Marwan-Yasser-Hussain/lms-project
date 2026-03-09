@extends('layouts.admin')

@section('title', 'Dashboard')
@section('topbar-title', 'Overview')

@section('content')

{{-- Welcome Banner --}}
<div class="mb-8 rounded-2xl relative overflow-hidden animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute top-0 right-0 w-64 h-64 bg-[#930056] rounded-full mix-blend-screen filter blur-[80px] opacity-30 transform translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-[#045592] rounded-full mix-blend-screen filter blur-[80px] opacity-20 transform -translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

    <div class="relative p-8 md:p-10 flex flex-col md:flex-row items-center justify-between gap-6 z-10">
        <div>
            <h1 class="text-3xl font-black text-white mb-2 tracking-tight">Welcome back, {{ auth()->user()->name }} 👋</h1>
            <p class="text-white/60 text-sm md:text-base max-w-xl leading-relaxed">
                Here's what's happening with your platform today. Check out the latest enrollments, and student activities below.
            </p>
        </div>
        <div class="text-right flex-shrink-0">
            <p class="text-sm font-semibold text-[#045592] uppercase tracking-widest mb-1">{{ now()->format('l') }}</p>
            <p class="text-xl font-bold text-white">{{ now()->format('F j, Y') }}</p>
        </div>
    </div>
</div>
<!-- Dont forget to remove this comment before deployment -->
{{-- ── Refined Stat Cards ────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

    {{-- Students --}}
    <div class="stat-card animate-fade-up delay-1 group" style="background: linear-gradient(145deg, #160D50, #0F043D); border-top: 2px solid #045592;">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-white/40 mb-1">Total Students</p>
                <p class="text-4xl font-black text-white group-hover:text-[#045592] transition-colors">{{ number_format($stats['total_users']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#045592]/10 border border-[#045592]/20 text-[#045592]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-between text-xs">
            <span class="text-white/40">Registered learners</span>
            <a href="{{ route('admin.users.index') }}" class="text-[#045592] font-semibold hover:underline">View All →</a>
        </div>
    </div>

    {{-- Courses --}}
    <div class="stat-card animate-fade-up delay-2 group" style="background: linear-gradient(145deg, #160D50, #0F043D); border-top: 2px solid #930056;">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-white/40 mb-1">Total Courses</p>
                <p class="text-4xl font-black text-white group-hover:text-[#930056] transition-colors">{{ number_format($stats['total_courses']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#930056]/10 border border-[#930056]/20 text-[#930056]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-between text-xs">
            <span class="text-white/40">{{ $stats['published_courses'] }} Published</span>
            <a href="{{ route('admin.courses.index') }}" class="text-[#930056] font-semibold hover:underline">Manage →</a>
        </div>
    </div>

    {{-- Subscriptions --}}
    <div class="stat-card animate-fade-up delay-3 group" style="background: linear-gradient(145deg, #160D50, #0F043D); border-top: 2px solid #e0b0ff;">
        {{-- I'm using a lighter purple hex for variation --}}
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-white/40 mb-1">Active Subs</p>
                <p class="text-4xl font-black text-white group-hover:text-[#e0b0ff] transition-colors">{{ number_format($stats['active_subscriptions']) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#1A1262]/50 border border-[#1A1262] text-[#e0b0ff]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-between text-xs">
            <span class="text-white/40">Currently active plans</span>
            <a href="{{ route('admin.subscriptions.index') }}" class="text-[#e0b0ff] font-semibold hover:underline">View Subs →</a>
        </div>
    </div>

    {{-- Revenue --}}
    <div class="stat-card animate-fade-up delay-4 group" style="background: linear-gradient(145deg, #160D50, #0F043D); border-top: 2px solid #5eead4;">
        {{-- Mint/Teal accent for revenue --}}
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-semibold tracking-widest uppercase text-white/40 mb-1">Total Revenue</p>
                <p class="text-4xl font-black text-white group-hover:text-[#5eead4] transition-colors">${{ number_format($stats['total_revenue'], 2) }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-[#5eead4]/10 border border-[#5eead4]/20 text-[#5eead4]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-between text-xs">
            <span class="text-white/40">Lifetime earnings</span>
        </div>
    </div>

</div>

{{-- ── Charts Section ────────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 animate-fade-up delay-5">
    
    {{-- Revenue Chart --}}
    <div class="card relative overflow-hidden" style="background: linear-gradient(135deg, #160D50 0%, #0F043D 100%);">
        <div class="card-inner">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-bold text-white text-lg">Revenue Overview</h2>
                    <p class="text-xs text-white/40">Income over the last 6 months</p>
                </div>
            </div>
            <div style="height: 300px; width: 100%;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Enrollments Chart --}}
    <div class="card relative overflow-hidden" style="background: linear-gradient(135deg, #160D50 0%, #0F043D 100%);">
        <div class="card-inner">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="font-bold text-white text-lg">Student Enrollments</h2>
                    <p class="text-xs text-white/40">New course enrollments over time</p>
                </div>
            </div>
            <div style="height: 300px; width: 100%;">
                <canvas id="enrollmentsChart"></canvas>
            </div>
        </div>
    </div>

</div>

{{-- ── Data Tables Section ───────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 animate-fade-up delay-6">

    {{-- Recent Users --}}
    <div class="card">
        <div class="card-inner pb-0">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-white text-lg">Latest Students</h2>
            </div>
        </div>
        <div class="overflow-x-auto pb-4">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="pl-6">Student Info</th>
                        <th>Joined</th>
                        <th class="pr-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentUsers->take(5) as $user)
                    <tr>
                        <td class="pl-6">
                            <div class="flex items-center gap-3">
                                <img src="{{ $user->avatar_url }}" alt="" class="w-9 h-9 rounded-full object-cover border border-white/10" />
                                <div>
                                    <p class="text-white text-sm font-semibold">{{ $user->name }}</p>
                                    <p class="text-xs text-white/40">{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-xs text-white/50">{{ $user->created_at->diffForHumans() }}</td>
                        <td class="pr-6 text-right">
                            @if($user->is_active)
                                <span class="badge" style="background:rgba(4,85,146,0.15); color:#5bb8ff; border:1px solid rgba(4,85,146,0.3);">Active</span>
                            @else
                                <span class="badge" style="background:rgba(147,0,86,0.15); color:#ff80c8; border:1px solid rgba(147,0,86,0.3);">Inactive</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-8 text-white/30">No students yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Courses --}}
    <div class="card">
        <div class="card-inner pb-0">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-white text-lg">Newly Added Courses</h2>
            </div>
        </div>
        <div class="overflow-x-auto pb-4">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="pl-6">Course</th>
                        <th>Category</th>
                        <th class="pr-6 text-right">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentCourses as $course)
                    <tr>
                        <td class="pl-6">
                            <div class="flex items-center gap-3">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="" class="w-10 h-10 rounded-lg object-cover" />
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-[#930056]/20 border border-[#930056]/30 flex items-center justify-center text-[#ff80c8]">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-white text-sm font-semibold">{{ Str::limit($course->title, 35) }}</p>
                                    <p class="text-xs text-white/40">{{ $course->instructor_name }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm text-white/50">{{ $course->category?->name ?? '—' }}</td>
                        <td class="pr-6 text-right">
                            <span class="badge {{ $course->status == 'published' ? 'badge-published' : 'badge-draft' }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center py-8 text-white/30">No courses yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const chartData = @json($chartData);
        
        // Common Chart Defaults
        Chart.defaults.color = 'rgba(255, 255, 255, 0.4)';
        Chart.defaults.font.family = "'Inter', sans-serif";
        const gridOptions = {
            color: 'rgba(255, 255, 255, 0.05)',
            drawBorder: false,
        };

        // 1. Revenue Chart (Line Chart)
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        
        // Create Gradient for Revenue
        const gradientRev = ctxRev.createLinearGradient(0, 0, 0, 300);
        gradientRev.addColorStop(0, 'rgba(4, 85, 146, 0.5)'); // Sky Blue
        gradientRev.addColorStop(1, 'rgba(4, 85, 146, 0.0)');

        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Revenue ($)',
                    data: chartData.revenue,
                    borderColor: '#045592',
                    backgroundColor: gradientRev,
                    borderWidth: 3,
                    pointBackgroundColor: '#045592',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4 // Smooth curves
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 4, 61, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: gridOptions, beginAtZero: true }
                }
            }
        });

        // 2. Enrollments Chart (Bar Chart)
        const ctxEnr = document.getElementById('enrollmentsChart').getContext('2d');
        
        // Create Gradient for Enrollments
        const gradientEnr = ctxEnr.createLinearGradient(0, 0, 0, 300);
        gradientEnr.addColorStop(0, '#930056'); // Medium Red
        gradientEnr.addColorStop(1, 'rgba(147, 0, 86, 0.2)');

        new Chart(ctxEnr, {
            type: 'bar',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Enrollments',
                    data: chartData.enrollments,
                    backgroundColor: gradientEnr,
                    borderRadius: 6,
                    borderSkipped: false,
                    barPercentage: 0.6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15, 4, 61, 0.9)',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: { grid: gridOptions, beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    });
</script>
@endpush
