<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login — LMS Project</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:radial-gradient(ellipse at top left,#1A1262 0%,#0F043D 40%,#080228 100%);">

    {{-- Background blobs --}}
    <div style="position:fixed;top:-100px;left:-100px;width:400px;height:400px;border-radius:50%;background:rgba(147,0,86,0.12);filter:blur(80px);pointer-events:none;"></div>
    <div style="position:fixed;bottom:-100px;right:-100px;width:400px;height:400px;border-radius:50%;background:rgba(4,85,146,0.12);filter:blur:80px;pointer-events:none;"></div>

    <div class="animate-fade-up" style="width:100%;max-width:420px;padding:1.5rem;">

        {{-- Logo --}}
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg,#930056,#6d003f);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;box-shadow:0 8px 30px rgba(147,0,86,0.4);">
                <svg style="width:28px;height:28px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0 0 0.25rem;">LMS Project</h1>
            <p style="font-size:0.85rem;color:rgba(255,255,255,0.4);margin:0;">Admin Portal — Sign in to continue</p>
        </div>

        {{-- Card --}}
        <div style="background:linear-gradient(135deg,rgba(22,13,80,0.9),rgba(18,10,66,0.9));border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:2rem;backdrop-filter:blur(20px);">

            {{-- Error --}}
            @if($errors->any())
            <div class="alert-error mb-4 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf

                {{-- Email --}}
                <div style="margin-bottom:1.25rem;">
                    <label class="form-label">Email address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="form-input" placeholder="admin@lmsproject.com" />
                </div>

                {{-- Password --}}
                <div style="margin-bottom:1.5rem;">
                    <label class="form-label">Password</label>
                    <div style="position:relative;">
                        <input type="password" name="password" id="pwd-input" required
                               class="form-input" placeholder="••••••••" style="padding-right:3rem;" />
                        <button type="button" onclick="togglePwd()"
                                style="position:absolute;right:0.75rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:rgba(255,255,255,0.3);padding:0;"
                                id="pwd-eye">
                            <svg id="eye-icon" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1.5rem;">
                    <input type="checkbox" name="remember" id="remember"
                           style="width:16px;height:16px;accent-color:#930056;" />
                    <label for="remember" style="font-size:0.85rem;color:rgba(255,255,255,0.5);cursor:pointer;">
                        Remember me
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding-top:0.75rem;padding-bottom:0.75rem;">
                    Sign In
                    <svg style="width:16px;height:16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </form>
        </div>

        <p style="text-align:center;margin-top:1.5rem;font-size:0.75rem;color:rgba(255,255,255,0.2);">
            © {{ date('Y') }} LMS Project. All rights reserved.
        </p>
    </div>

    <script>
        function togglePwd() {
            const input = document.getElementById('pwd-input');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>
</html>
