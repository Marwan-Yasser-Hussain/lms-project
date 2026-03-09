<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register — LMS Project</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body style="min-height:100vh;display:flex;align-items:center;justify-content:center;background:radial-gradient(ellipse at top left,#1A1262 0%,#0F043D 40%,#080228 100%);">

    {{-- Background blobs --}}
    <div style="position:fixed;top:-100px;left:-100px;width:400px;height:400px;border-radius:50%;background:rgba(147,0,86,0.12);filter:blur(80px);pointer-events:none;"></div>
    <div style="position:fixed;bottom:-100px;right:-100px;width:400px;height:400px;border-radius:50%;background:rgba(4,85,146,0.12);filter:blur:80px;pointer-events:none;"></div>

    <div class="animate-fade-up" style="width:100%;max-width:500px;padding:1.5rem;margin:2rem 0;">

        {{-- Logo --}}
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg,#930056,#6d003f);display:inline-flex;align-items:center;justify-content:center;margin-bottom:1rem;box-shadow:0 8px 30px rgba(147,0,86,0.4);">
                <svg style="width:28px;height:28px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h1 style="font-size:1.5rem;font-weight:800;color:#fff;margin:0 0 0.25rem;">LMS Project</h1>
            <p style="font-size:0.85rem;color:rgba(255,255,255,0.4);margin:0;">Create a new account</p>
        </div>

        {{-- Card --}}
        <div style="background:linear-gradient(135deg,rgba(22,13,80,0.9),rgba(18,10,66,0.9));border:1px solid rgba(255,255,255,0.08);border-radius:20px;padding:2rem;backdrop-filter:blur(20px);">

            {{-- Error --}}
            @if($errors->any())
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1rem;font-size:.85rem;">
                <ul style="margin:0;padding-left:1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}">
                @csrf

                <div style="display:flex;flex-direction:column;gap:1.25rem;">
                    <div>
                        <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Full Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required autofocus
                               class="form-input" style="width:100%;" placeholder="John Doe" />
                    </div>

                    <div>
                        <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Email Address *</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="form-input" style="width:100%;" placeholder="john@example.com" />
                    </div>

                    <div>
                        <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Phone (optional)</label>
                        <input type="text" name="phone" value="{{ old('phone') }}"
                               class="form-input" style="width:100%;" placeholder="+1 234 567 890" />
                    </div>

                    <div>
                        <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Role *</label>
                        <select name="role" required class="form-select" style="width:100%;">
                            <option value="user"  {{ old('role','user')=='user'  ? 'selected':'' }}>User</option>
                            <option value="admin" {{ old('role')=='admin' ? 'selected':'' }}>Admin</option>
                        </select>
                    </div>

                    <div style="display:flex;gap:1rem;">
                        <div style="flex:1;">
                            <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Password *</label>
                            <input type="password" name="password" required
                                   class="form-input" style="width:100%;" placeholder="Min. 8 chars" />
                        </div>

                        <div style="flex:1;">
                            <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Confirm Password *</label>
                            <input type="password" name="password_confirmation" required
                                   class="form-input" style="width:100%;" placeholder="Repeat password" />
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding-top:0.75rem;padding-bottom:0.75rem;margin-top:1.5rem;">
                    Sign Up
                    <svg style="width:16px;height:16px;margin-left:.5rem" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
                
                <div style="text-align:center;margin-top:1rem;">
                    <a href="{{ route('login') }}" style="color:#f472b6;font-size:0.85rem;text-decoration:none;">Already have an account? Sign in</a>
                </div>
            </form>
        </div>

        <p style="text-align:center;margin-top:1.5rem;font-size:0.75rem;color:rgba(255,255,255,0.2);">
            © {{ date('Y') }} LMS Project. All rights reserved.
        </p>
    </div>
</body>
</html>
