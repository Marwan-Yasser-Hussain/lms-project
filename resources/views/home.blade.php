<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LMS Project — Learn Without Limits</title>
    <meta name="description" content="Unlock your potential with world-class online courses. Expert instructors, flexible schedules, and recognised certificates." />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary:     #0F043D;
            --primary-2:   #1A1262;
            --primary-mid: #2d1f8a;
            --accent:      #5bb8ff;
            --blue:        #045592;
            --blue-2:      #033d6a;
            --bg:          #f8f9fc;
            --bg-white:    #ffffff;
            --bg-section:  #f0f4ff;
            --text:        #0f172a;
            --text-muted:  #64748b;
            --border:      #e2e8f0;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: rgba(15,4,61,0.35); border-radius: 3px; }

        /* ── NAV ── */
        .nav {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 100;
            padding: 0 2rem;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255,255,255,0.88);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.07);
            transition: all 0.3s;
        }
        .nav-logo { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .nav-logo-icon {
            width: 38px; height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #0F043D, #1A1262);
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 4px 14px rgba(15,4,61,0.35);
        }
        .nav-logo-text { font-family: 'Outfit', sans-serif; font-size: 1.1rem; font-weight: 800; color: var(--text); }
        .nav-logo-sub  { font-size: 0.63rem; color: var(--text-muted); font-weight: 400; }
        .nav-links { display: flex; align-items: center; gap: 0.15rem; }
        .nav-link {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            padding: 0.45rem 0.9rem;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .nav-link:hover { color: var(--text); background: rgba(0,0,0,0.05); }
        .nav-cta {
            background: linear-gradient(135deg, #0F043D, #1A1262) !important;
            color: #fff !important;
            padding: 0.45rem 1.2rem !important;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(15,4,61,0.3);
        }
        .nav-cta:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(15,4,61,0.45) !important; background: linear-gradient(135deg, #2d1f8a, #1A1262) !important; }

        /* ── HERO ── */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 8rem 2rem 6rem;
            overflow: hidden;
            background: linear-gradient(160deg, #fff 0%, #f0f4ff 40%, #f0f4ff 100%);
        }
        .hero-bg {
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 70% 50% at 50% -10%, rgba(15,4,61,0.10) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 90% 80%, rgba(4,85,146,0.09) 0%, transparent 50%),
                radial-gradient(ellipse 40% 35% at 5%  70%, rgba(91,184,255,0.07) 0%, transparent 50%);
        }
        .hero-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(15,4,61,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(15,4,61,0.04) 1px, transparent 1px);
            background-size: 60px 60px;
            mask-image: radial-gradient(ellipse 80% 70% at 50% 0%, black 30%, transparent 100%);
        }
        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            pointer-events: none;
            animation: float 8s ease-in-out infinite;
        }
        .orb-1 { width: 500px; height: 500px; background: rgba(15,4,61,0.09); top: -150px; left: 15%; animation-delay: 0s; }
        .orb-2 { width: 350px; height: 350px; background: rgba(4,85,146,0.09); bottom: -50px; right: 5%; animation-delay: -3s; }
        .orb-3 { width: 250px; height: 250px; background: rgba(91,184,255,0.07); top: 35%; left: -5%; animation-delay: -6s; }
        @keyframes float {
            0%,100% { transform: translateY(0) scale(1); }
            50%      { transform: translateY(-28px) scale(1.04); }
        }

        .hero-content { position: relative; max-width: 820px; }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.4rem 1rem;
            border-radius: 100px;
            background: rgba(15,4,61,0.07);
            border: 1px solid rgba(15,4,61,0.2);
            color: var(--primary);
            font-size: 0.78rem;
            font-weight: 700;
            margin-bottom: 2rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }
        .hero-badge-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--accent); animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100% { opacity: 1; } 50% { opacity: 0.35; } }

        .hero-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            font-weight: 900;
            line-height: 1.05;
            letter-spacing: -0.03em;
            color: var(--text);
            margin-bottom: 1.5rem;
        }
        .hero-title span {
            background: linear-gradient(135deg, #0F043D, #045592);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-subtitle {
            font-size: 1.1rem;
            line-height: 1.8;
            color: var(--text-muted);
            max-width: 580px;
            margin: 0 auto 2.5rem;
        }
        .hero-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; }
        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            background: linear-gradient(135deg, #0F043D, #1A1262);
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            box-shadow: 0 8px 24px rgba(15,4,61,0.35);
            transition: all 0.25s;
        }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 14px 30px rgba(15,4,61,0.45); }
        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            background: #fff;
            color: var(--text);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            border: 1.5px solid var(--border);
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.25s;
        }
        .btn-secondary:hover { border-color: rgba(15,4,61,0.3); transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.1); }

        .scroll-hint {
            position: absolute;
            bottom: 2rem; left: 50%;
            transform: translateX(-50%);
            display: flex; flex-direction: column; align-items: center; gap: 0.4rem;
            color: var(--text-muted);
            font-size: 0.72rem;
            opacity: 0.5;
            animation: bounce 2s infinite;
        }
        @keyframes bounce { 0%,100% { transform: translateX(-50%) translateY(0); } 50% { transform: translateX(-50%) translateY(8px); } }

        /* ── STATS STRIP ── */
        .stats-strip {
            padding: 3rem 2rem;
            background: #fff;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 1px 12px rgba(0,0,0,0.04);
        }
        .stats-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 2rem;
            text-align: center;
        }
        .stat-number {
            font-family: 'Outfit', sans-serif;
            font-size: 2.5rem;
            font-weight: 900;
            background: linear-gradient(135deg, #0F043D, #045592);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-label { color: var(--text-muted); font-size: 0.85rem; margin-top: 0.2rem; }

        /* ── SECTIONS ── */
        section { padding: 6rem 2rem; }
        .section-inner { max-width: 1200px; margin: 0 auto; }
        .section-tag {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 0.75rem;
            border-radius: 100px;
            background: rgba(15,4,61,0.07);
            border: 1px solid rgba(15,4,61,0.18);
            color: var(--primary);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 1rem;
        }
        .section-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            font-weight: 800;
            color: var(--text);
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 1rem;
        }
        .section-subtitle { color: var(--text-muted); font-size: 1rem; line-height: 1.7; max-width: 540px; }

        /* ── COURSE CARDS ── */
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }
        .course-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s;
            text-decoration: none;
            display: block;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .course-card:hover {
            transform: translateY(-6px);
            border-color: rgba(15,4,61,0.25);
            box-shadow: 0 20px 45px rgba(0,0,0,0.12), 0 0 0 1px rgba(15,4,61,0.12);
        }
        .course-thumb {
            position: relative;
            width: 100%; height: 180px;
            background: #f1f4fb;
            overflow: hidden;
        }
        .course-thumb img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s; }
        .course-card:hover .course-thumb img { transform: scale(1.07); }
        .course-thumb-placeholder {
            width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #f0f4ff, #f0f4ff);
        }
        .course-level-badge {
            position: absolute; top: 0.75rem; right: 0.75rem;
            padding: 0.25rem 0.65rem;
            border-radius: 100px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(8px);
            color: var(--text);
            font-size: 0.7rem; font-weight: 700;
            text-transform: capitalize;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .course-body { padding: 1.25rem; }
        .course-category {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            font-size: 0.7rem; font-weight: 700;
            margin-bottom: 0.65rem;
        }
        .course-title {
            color: var(--text);
            font-weight: 700;
            font-size: 0.95rem;
            line-height: 1.45;
            margin-bottom: 0.5rem;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .course-instructor { color: var(--text-muted); font-size: 0.8rem; margin-bottom: 1rem; }
        .course-footer {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 0.75rem;
            border-top: 1px solid var(--border);
            font-size: 0.78rem;
        }
        .course-students { color: var(--text-muted); }
        .course-arrow {
            width: 28px; height: 28px;
            border-radius: 8px;
            background: rgba(15,4,61,0.08);
            display: flex; align-items: center; justify-content: center;
            color: var(--primary);
            transition: all 0.2s;
        }
        .course-card:hover .course-arrow { background: var(--primary); color: #fff; }
        .courses-empty { text-align: center; padding: 4rem 2rem; color: var(--text-muted); }

        /* ── FEATURES ── */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
            gap: 1.5rem;
            margin-top: 3rem;
        }
        .feature-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, #0F043D, #045592);
            opacity: 0;
            transition: opacity 0.3s;
        }
        .feature-card:hover::before { opacity: 1; }
        .feature-card:hover { border-color: rgba(15,4,61,0.2); transform: translateY(-4px); box-shadow: 0 16px 40px rgba(0,0,0,0.1); }
        .feature-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
            font-size: 1.5rem;
        }
        .feature-title { color: var(--text); font-weight: 700; font-size: 1.05rem; margin-bottom: 0.6rem; }
        .feature-desc  { color: var(--text-muted); font-size: 0.875rem; line-height: 1.7; }

        /* ── CATEGORIES ── */
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 3rem;
        }
        .category-pill {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-radius: 12px;
            background: #fff;
            border: 1px solid var(--border);
            text-decoration: none;
            transition: all 0.25s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .category-pill:hover {
            border-color: rgba(15,4,61,0.25);
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.09);
        }
        .category-icon  { font-size: 1.5rem; }
        .category-name  { color: var(--text); font-size: 0.875rem; font-weight: 600; }
        .category-count { color: var(--text-muted); font-size: 0.75rem; margin-top: 0.15rem; }

        /* ── CTA BANNER ── */
        .cta-section {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #0F043D 0%, #1A1262 100%);
            border-radius: 24px;
            margin: 0 2rem 6rem;
            padding: 5rem 3rem;
            text-align: center;
        }
        .cta-section::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .cta-orb { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.08); filter: blur(60px); }
        .cta-orb-1 { width: 300px; height: 300px; top: -100px; left: -80px; }
        .cta-orb-2 { width: 250px; height: 250px; bottom: -80px; right: -60px; }
        .cta-content  { position: relative; }
        .cta-title {
            font-family: 'Outfit', sans-serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 900;
            color: #fff;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }
        .cta-sub { color: rgba(255,255,255,0.75); font-size: 1.1rem; margin-bottom: 2.5rem; max-width: 500px; margin-left: auto; margin-right: auto; }
        .btn-white {
            display: inline-flex; align-items: center; gap: 0.5rem;
            padding: 0.875rem 2rem;
            border-radius: 12px;
            background: #fff;
            color: #0F043D;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.95rem;
            transition: all 0.25s;
            box-shadow: 0 4px 18px rgba(0,0,0,0.2);
        }
        .btn-white:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,0,0,0.3); }

        /* ── FOOTER ── */
        footer {
            background: var(--text);
            padding: 3rem 2rem;
        }
        .footer-inner {
            max-width: 1200px;
            margin: 0 auto;
            display: flex; flex-wrap: wrap;
            justify-content: space-between; align-items: center;
            gap: 1.5rem;
        }
        .footer-logo  { display: flex; align-items: center; gap: 0.75rem; text-decoration: none; }
        .footer-copy  { color: rgba(255,255,255,0.25); font-size: 0.8rem; }
        .footer-links { display: flex; gap: 1.5rem; }
        .footer-link  { color: rgba(255,255,255,0.35); text-decoration: none; font-size: 0.8rem; transition: color 0.2s; }
        .footer-link:hover { color: rgba(255,255,255,0.75); }

        /* ── UTILITIES ── */
        .heading-row {
            display: flex; align-items: flex-end;
            justify-content: space-between; flex-wrap: wrap; gap: 1rem;
        }
        .see-all {
            color: var(--primary); text-decoration: none;
            font-size: 0.875rem; font-weight: 600;
            display: flex; align-items: center; gap: 0.3rem;
            transition: gap 0.2s;
        }
        .see-all:hover { gap: 0.6rem; }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .nav-links { display: none; }
            .hero { padding: 7rem 1.5rem 5rem; }
            .cta-section { margin: 0 1rem 4rem; padding: 3.5rem 2rem; }
            .categories-grid { grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); }
        }

        /* ── ANIMATIONS ── */
        .animate-up { opacity: 0; transform: translateY(28px); transition: opacity 0.65s ease, transform 0.65s ease; }
        .animate-up.visible { opacity: 1; transform: translateY(0); }
        .delay-1 { transition-delay: 0.1s !important; }
        .delay-2 { transition-delay: 0.2s !important; }
        .delay-3 { transition-delay: 0.3s !important; }
        .delay-4 { transition-delay: 0.4s !important; }
        .delay-5 { transition-delay: 0.5s !important; }
    </style>
</head>
<body>

{{-- ── NAVBAR ── --}}
<nav class="nav">
    <a href="{{ route('home') }}" class="nav-logo">
        <div class="nav-logo-icon">
            <svg width="18" height="18" fill="none" stroke="#fff" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div>
            <div class="nav-logo-text">LMS Project</div>
            <div class="nav-logo-sub">Online Learning Platform</div>
        </div>
    </a>

    <div class="nav-links">
        <a href="#courses"    class="nav-link">Courses</a>
        <a href="#features"   class="nav-link">Features</a>
        <a href="#categories" class="nav-link">Categories</a>
        @auth
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="nav-link nav-cta">Admin Panel</a>
            @else
                <a href="{{ route('home') }}" class="nav-link nav-cta">Dashboard</a>
            @endif
        @else
            <a href="{{ route('login') }}"    class="nav-link">Sign In</a>
            <a href="{{ route('register') }}" class="nav-link nav-cta">Get Started</a>
        @endauth
    </div>
</nav>

{{-- ── HERO ── --}}
<section class="hero">
    <div class="hero-bg"></div>
    <div class="hero-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>

    <div class="hero-content">
        <div class="hero-badge">
            <div class="hero-badge-dot"></div>
            {{ $stats['courses'] }} Courses Available
        </div>

        <h1 class="hero-title">
            Learn Skills That<br><span>Change Your Future</span>
        </h1>

        <p class="hero-subtitle">
            Explore expert-crafted courses, earn certificates, and advance your career — all at your own pace, on any device.
        </p>

        <div class="hero-actions">
            <a href="#courses" class="btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
                Explore Courses
            </a>
            @guest
            <a href="{{ route('register') }}" class="btn-secondary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Join for Free
            </a>
            @endguest
        </div>
    </div>

    <div class="scroll-hint">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
        Scroll to explore
    </div>
</section>

{{-- ── STATS STRIP ── --}}
<div class="stats-strip">
    <div class="stats-inner">
        <div class="stat-item animate-up">
            <div class="stat-number">{{ number_format($stats['courses']) }}+</div>
            <div class="stat-label">Published Courses</div>
        </div>
        <div class="stat-item animate-up delay-1">
            <div class="stat-number">{{ number_format($stats['students']) }}+</div>
            <div class="stat-label">Active Learners</div>
        </div>
        <div class="stat-item animate-up delay-2">
            <div class="stat-number">{{ number_format($stats['lessons']) }}+</div>
            <div class="stat-label">Expert Lessons</div>
        </div>
        <div class="stat-item animate-up delay-3">
            <div class="stat-number">100%</div>
            <div class="stat-label">Online & Flexible</div>
        </div>
    </div>
</div>

{{-- ── FEATURED COURSES ── --}}
<section id="courses">
    <div class="section-inner">
        <div class="heading-row">
            <div>
                <div class="section-tag animate-up">📚 Catalog</div>
                <h2 class="section-title animate-up delay-1">Featured Courses</h2>
                <p class="section-subtitle animate-up delay-2">Hand-picked courses by expert instructors, ready to take you to the next level.</p>
            </div>
            <a href="{{ route('home') }}" class="see-all animate-up">
                View All
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>

        @if($featuredCourses->isNotEmpty())
        <div class="courses-grid">
            @foreach($featuredCourses as $i => $course)
            <a href="{{ route('login') }}" class="course-card animate-up" style="transition-delay: {{ ($i % 3) * 0.1 }}s">
                <div class="course-thumb">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->title }}" loading="lazy" />
                    @else
                        <div class="course-thumb-placeholder">
                            <svg width="44" height="44" fill="none" stroke="rgba(15,4,61,0.25)" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    @endif
                    <div class="course-level-badge">{{ ucfirst($course->level) }}</div>
                </div>
                <div class="course-body">
                    @if($course->category)
                    <div class="course-category"
                         style="background: {{ $course->category->color ?? '#0F043D' }}20; color: {{ $course->category->color ?? '#5bb8ff' }}; border: 1px solid {{ $course->category->color ?? '#0F043D' }}40;">
                        {{ $course->category->icon ?? '' }} {{ $course->category->name }}
                    </div>
                    @endif
                    <div class="course-title">{{ $course->title }}</div>
                    <div class="course-instructor">by {{ $course->instructor_name }}</div>
                    <div class="course-footer">
                        <span class="course-students">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:inline;vertical-align:-2px;margin-right:3px;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $course->enrollments_count }} students
                        </span>
                        <div class="course-arrow">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @else
        <div class="courses-empty animate-up">
            <div style="font-size:3.5rem;margin-bottom:1rem;">📚</div>
            <p style="font-size:1.1rem;color:var(--text-muted);">Courses coming soon! Check back later.</p>
        </div>
        @endif
    </div>
</section>

{{-- ── FEATURES ── --}}
<section id="features" style="background: var(--bg-section);">
    <div class="section-inner">
        <div style="text-align:center;margin-bottom:0;">
            <div class="section-tag animate-up" style="margin-bottom:1rem;">✨ Why us</div>
            <h2 class="section-title animate-up delay-1" style="margin-bottom:1rem;">Everything You Need to Succeed</h2>
            <p class="section-subtitle animate-up delay-2" style="margin:0 auto;">From beginner to expert — our platform supports every step of your learning journey.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card animate-up delay-1">
                <div class="feature-icon" style="background:rgba(15,4,61,0.15);">🎓</div>
                <div class="feature-title">Expert Instructors</div>
                <div class="feature-desc">Learn from industry professionals who bring real-world experience directly into every lesson.</div>
            </div>
            <div class="feature-card animate-up delay-2">
                <div class="feature-icon" style="background:rgba(96,165,250,0.12);">📱</div>
                <div class="feature-title">Learn Anywhere</div>
                <div class="feature-desc">Access your courses on any device — desktop, tablet, or phone — whenever suits you best.</div>
            </div>
            <div class="feature-card animate-up delay-3">
                <div class="feature-icon" style="background:rgba(34,197,94,0.12);">🏆</div>
                <div class="feature-title">Earn Certificates</div>
                <div class="feature-desc">Showcase your achievements with beautifully designed certificates to share with employers.</div>
            </div>
            <div class="feature-card animate-up delay-1">
                <div class="feature-icon" style="background:rgba(245,158,11,0.12);">⚡</div>
                <div class="feature-title">Progress Tracking</div>
                <div class="feature-desc">Stay motivated with lesson-by-lesson progress tracking and completion milestones.</div>
            </div>
            <div class="feature-card animate-up delay-2">
                <div class="feature-icon" style="background:rgba(167,139,250,0.12);">🎯</div>
                <div class="feature-title">Quizzes & Assessments</div>
                <div class="feature-desc">Test your knowledge with quizzes built into every course to reinforce what you've learned.</div>
            </div>
            <div class="feature-card animate-up delay-3">
                <div class="feature-icon" style="background:rgba(236,72,153,0.12);">🔓</div>
                <div class="feature-title">Lifetime Access</div>
                <div class="feature-desc">Once enrolled, revisit your course content at any time — no expiry dates or hidden limits.</div>
            </div>
        </div>
    </div>
</section>

{{-- ── CATEGORIES ── --}}
@if($categories->isNotEmpty())
<section id="categories">
    <div class="section-inner">
        <div class="heading-row" style="margin-bottom:0;">
            <div>
                <div class="section-tag animate-up">🗂 Browse</div>
                <h2 class="section-title animate-up delay-1">Explore by Category</h2>
            </div>
        </div>

        <div class="categories-grid">
            @foreach($categories as $i => $cat)
            <a href="#courses" class="category-pill animate-up" style="transition-delay: {{ ($i % 4) * 0.08 }}s">
                <div class="category-icon">{{ $cat->icon ?? '📂' }}</div>
                <div>
                    <div class="category-name">{{ $cat->name }}</div>
                    <div class="category-count">{{ $cat->courses_count }} {{ Str::plural('course', $cat->courses_count) }}</div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── CTA ── --}}
<div class="cta-section animate-up">
    <div class="cta-orb cta-orb-1"></div>
    <div class="cta-orb cta-orb-2"></div>
    <div class="cta-content">
        <h2 class="cta-title">Ready to Start Learning?</h2>
        <p class="cta-sub">Join thousands of students already building their future. Sign up for free and start your first course today.</p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            @guest
            <a href="{{ route('register') }}" class="btn-white">
                🚀 Create Free Account
            </a>
            <a href="{{ route('login') }}" class="btn-secondary" style="border-color:rgba(255,255,255,0.25);color:rgba(255,255,255,0.85);">
                Sign In
            </a>
            @else
            <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('home') }}" class="btn-white">
                🎓 Go to Dashboard
            </a>
            @endguest
        </div>
    </div>
</div>

{{-- ── FOOTER ── --}}
<footer>
    <div class="footer-inner">
        <a href="{{ route('home') }}" class="footer-logo">
            <div class="nav-logo-icon">
                <svg width="16" height="16" fill="none" stroke="#fff" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="nav-logo-text" style="font-size:0.95rem;color:#fff;">LMS Project</span>
        </a>
        <div class="footer-copy">© {{ date('Y') }} LMS Project. All rights reserved.</div>
        <div class="footer-links">
            <a href="{{ route('login') }}"    class="footer-link">Sign In</a>
            <a href="{{ route('register') }}" class="footer-link">Register</a>
            @auth
                @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="footer-link">Admin</a>
                @endif
            @endauth
        </div>
    </div>
</footer>

<script>
// Animate-on-scroll using IntersectionObserver
(function () {
    const els = document.querySelectorAll('.animate-up');
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    els.forEach(el => obs.observe(el));

    // Make hero visible immediately
    document.querySelectorAll('.hero .animate-up, .hero-badge, .hero-title, .hero-subtitle, .hero-actions').forEach(el => {
        el.classList.add('visible');
    });

    // Nav scroll effect
    const nav = document.querySelector('.nav');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 50) {
            nav.style.background = 'rgba(255,255,255,0.98)';
            nav.style.boxShadow = '0 2px 16px rgba(0,0,0,0.1)';
        } else {
            nav.style.background = 'rgba(255,255,255,0.88)';
            nav.style.boxShadow = 'none';
        }
    });
})();
</script>
</body>
</html>
