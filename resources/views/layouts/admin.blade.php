<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>@yield('title', 'Dashboard') — LMS Project Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    
    <!-- Plyr CSS -->
    <link rel="stylesheet" href="https://cdn.plyr.io/3.7.8/plyr.css" />
    
    <style>
        /* CSS Hack to crop out forced YouTube UI elements */
        .yt-embed-holder {
            position: relative;
            width: 100%;
            overflow: hidden;
            aspect-ratio: 16/9;
            border-radius: 12px;
        }
        
        .yt-embed-holder iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 300%;
            height: 100%;
            margin-left: -100%; /* Centers the 300% wide video back in the middle */
            border: none;
        }
    </style>
</head>
<body class="h-full">

    {{-- ──────────────────── SIDEBAR BACKDROP (mobile) ──────────────────── --}}
    <div id="sidebar-backdrop" class="sidebar-backdrop" onclick="closeSidebar()"></div>

    {{-- ──────────────────── SIDEBAR ──────────────────────────────────────── --}}
    <aside id="sidebar" class="sidebar">

        {{-- Logo --}}
        <div class="sidebar-logo">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 no-underline">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: linear-gradient(135deg,#930056,#6d003f);">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-none">LMS Project</p>
                    <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35);">Admin Panel</p>
                </div>
            </a>
        </div>

        {{-- Navigation --}}
        <nav class="sidebar-nav">

            {{-- Main --}}
            <p class="nav-section-label">Main</p>

            <a href="{{ route('admin.dashboard') }}"
               class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>

            {{-- Courses --}}
            <p class="nav-section-label" style="margin-top:0.75rem;">Learning</p>

            <a href="{{ route('admin.courses.index') }}"
               class="nav-link {{ request()->routeIs('admin.courses*') ? 'active' : '' }}">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Courses
            </a>

            <a href="{{ route('admin.categories.index') }}"
               class="nav-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                Categories
            </a>

            {{-- People --}}
            <p class="nav-section-label" style="margin-top:0.75rem;">People</p>

            <a href="{{ route('admin.users.index') }}"
               class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Users
            </a>

            {{-- Revenue --}}
            <p class="nav-section-label" style="margin-top:0.75rem;">Revenue</p>

            <a href="{{ route('admin.subscriptions.index') }}"
               class="nav-link {{ request()->routeIs('admin.subscriptions*') ? 'active' : '' }}">
                <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Subscriptions
            </a>

        </nav>

        {{-- User profile footer --}}
        <div class="p-4 border-t" style="border-color:var(--color-border);">
            <div class="flex items-center gap-3">
                <img src="{{ auth()->user()->avatar_url }}" alt="" class="avatar" />
                <div class="flex-1 overflow-hidden">
                    <p class="text-white text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs truncate" style="color:rgba(255,255,255,0.35);">Administrator</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout"
                            class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors"
                            style="background:rgba(255,255,255,0.05);color:rgba(255,255,255,0.4);"
                            onmouseover="this.style.background='rgba(147,0,86,0.2)';this.style.color='#ff80c8'"
                            onmouseout="this.style.background='rgba(255,255,255,0.05)';this.style.color='rgba(255,255,255,0.4)'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ──────────────────── MAIN WRAPPER ──────────────────────────────────── --}}
    <div class="main-wrapper">

        {{-- TOP BAR --}}
        <header class="topbar">
            {{-- Mobile menu toggle --}}
            <button id="menu-toggle" onclick="toggleSidebar()"
                    class="lg:hidden w-9 h-9 rounded-lg flex items-center justify-center"
                    style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.7);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page breadcrumb --}}
            <div class="flex-1">
                <p class="text-white font-semibold text-sm">@yield('topbar-title', 'Dashboard')</p>
            </div>

            {{-- Right area --}}
            <div class="flex items-center gap-4">
                {{-- Notification bell (UI only) --}}
                <button class="w-10 h-10 rounded-xl flex items-center justify-center relative hover:bg-white/10 transition-colors"
                        style="background:rgba(255,255,255,0.06);color:rgba(255,255,255,0.8);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full border border-[#0F043D]"
                          style="background:#930056;"></span>
                </button>

                {{-- Avatar & Username --}}
                <div class="flex items-center gap-3 pl-2 sm:pl-4 sm:border-l border-white/10 cursor-pointer hover:opacity-80 transition-opacity">
                    <img src="{{ auth()->user()->avatar_url }}" alt="" class="w-10 h-10 rounded-full object-cover border-2 border-white/10 shadow-sm" />
                    <div class="hidden sm:block">
                        <p class="text-sm font-bold text-white leading-tight">{{ auth()->user()->name }}</p>
                        <p class="text-[0.65rem] text-white/50 font-medium uppercase tracking-wider mt-0.5">Admin</p>
                    </div>
                </div>
            </div>
        </header>

        {{-- FLASH MESSAGES --}}
        @if (session('success'))
            <div class="mx-6 mt-4 alert-success flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mx-6 mt-4 alert-error flex items-center gap-2">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- PAGE CONTENT --}}
        <main class="main-content">
            @yield('content')
        </main>

        {{-- FOOTER --}}
        <footer class="px-6 py-4 text-center" style="color:rgba(15,4,61,0.3);font-size:0.75rem;border-top:1px solid rgba(15,4,61,0.08);">
            © {{ date('Y') }} LMS Project. All rights reserved.
        </footer>
    </div>

    {{-- ──────────────────── Scripts ─────────────────────────────────────── --}}
    <script>
        function toggleSidebar() {
            const sidebar  = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            sidebar.classList.toggle('open');
            backdrop.classList.toggle('show');
        }
        function closeSidebar() {
            document.getElementById('sidebar').classList.remove('open');
            document.getElementById('sidebar-backdrop').classList.remove('show');
        }
    </script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Plyr JS -->
    <script src="https://cdn.plyr.io/3.7.8/plyr.js"></script>

    @stack('scripts')
</body>
</html>
