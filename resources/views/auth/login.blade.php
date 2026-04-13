<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--ocean:#0a3d4f;--tide:#0d6e8a;--foam:#e8f4f8;--coral:#e85d3a;--gold:#f0a500;--fresh:#2eb87a;--muted:#6b7d8a;--border:#dde8ed}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;display:flex;flex-direction:column;background:var(--ocean)}

        /* Split layout */
        .auth-wrap{display:grid;grid-template-columns:1fr 1fr;min-height:100vh}
        .auth-left{background:linear-gradient(160deg,var(--ocean) 0%,#0b5570 50%,#0e7a9a 100%);display:flex;flex-direction:column;justify-content:center;padding:4rem 3rem;position:relative;overflow:hidden}
        .auth-left::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 30% 70%,rgba(255,255,255,.05),transparent 60%);pointer-events:none}
        .auth-right{background:#fff;display:flex;align-items:center;justify-content:center;padding:3rem 2rem}

        /* Left panel */
        .brand{display:flex;align-items:center;gap:12px;margin-bottom:3rem}
        .brand-logo{font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:#fff;text-decoration:none}
        .brand-logo span{color:var(--gold)}
        .brand-tag{background:rgba(240,165,0,.15);border:1px solid rgba(240,165,0,.3);color:var(--gold);font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.8px;padding:4px 10px;border-radius:20px}
        .left-heading{font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:900;color:#fff;line-height:1.15;margin-bottom:1rem}
        .left-heading span{color:var(--gold)}
        .left-sub{color:rgba(255,255,255,.65);font-size:1rem;line-height:1.7;margin-bottom:2.5rem;max-width:380px}
        .feature-list{display:flex;flex-direction:column;gap:.75rem}
        .feature-item{display:flex;align-items:center;gap:10px;color:rgba(255,255,255,.8);font-size:.9rem}
        .feature-dot{width:28px;height:28px;border-radius:50%;background:rgba(255,255,255,.12);display:flex;align-items:center;justify-content:center;font-size:.85rem;flex-shrink:0}

        /* Form panel */
        .form-box{width:100%;max-width:420px}
        .form-title{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:900;color:var(--ocean);margin-bottom:.4rem}
        .form-subtitle{color:var(--muted);font-size:.9rem;margin-bottom:2rem}
        .form-group{margin-bottom:1.25rem}
        .form-group label{display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:.4rem}
        .form-input{width:100%;padding:.7rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-size:.925rem;font-family:'DM Sans',sans-serif;outline:none;transition:border .2s,box-shadow .2s;color:#1a1a2e}
        .form-input:focus{border-color:var(--tide);box-shadow:0 0 0 3px rgba(13,110,138,.1)}
        .form-error{font-size:.8rem;color:var(--coral);margin-top:.3rem}
        .form-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem}
        .remember-label{display:flex;align-items:center;gap:8px;font-size:.875rem;color:#374151;cursor:pointer}
        .remember-label input{width:16px;height:16px;accent-color:var(--tide);cursor:pointer}
        .forgot-link{font-size:.875rem;color:var(--tide);font-weight:600;text-decoration:none}
        .forgot-link:hover{color:var(--ocean);text-decoration:underline}
        .btn-login{width:100%;background:var(--ocean);color:#fff;padding:.85rem;border:none;border-radius:10px;font-size:1rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;transition:all .2s;letter-spacing:.2px}
        .btn-login:hover{background:var(--tide);transform:translateY(-1px);box-shadow:0 4px 16px rgba(10,61,79,.3)}
        .divider{display:flex;align-items:center;gap:12px;margin:1.5rem 0}
        .divider::before,.divider::after{content:'';flex:1;height:1px;background:var(--border)}
        .divider span{font-size:.78rem;color:var(--muted);white-space:nowrap}
        .register-link{text-align:center;font-size:.875rem;color:var(--muted)}
        .register-link a{color:var(--tide);font-weight:700;text-decoration:none}
        .register-link a:hover{text-decoration:underline}
        .alert-info{background:#e0f2fe;border:1px solid #bae6fd;color:#0369a1;padding:.85rem 1rem;border-radius:10px;margin-bottom:1.25rem;font-size:.875rem}

        @media(max-width:860px){
            .auth-wrap{grid-template-columns:1fr}
            .auth-left{display:none}
            .auth-right{min-height:100vh;background:linear-gradient(160deg,var(--ocean) 0%,#0b5570 100%);padding:2rem 1.25rem}
            .form-box{background:#fff;border-radius:20px;padding:2rem 1.75rem;box-shadow:0 24px 60px rgba(0,0,0,.3)}
        }
    </style>
</head>
<body>

<div class="auth-wrap">
    {{-- Left panel --}}
    <div class="auth-left">
        <div class="brand">
            <a href="{{ url('/') }}" class="brand-logo">Aqua<span>Hub</span></a>
            <span class="brand-tag">Digital Seafood System</span>
        </div>
        <h1 class="left-heading">Kenya's Freshest<br>Fish, <span>Delivered</span><br>To Your Door</h1>
        <p class="left-sub">Connect directly with producers, get farm-fresh seafood, and track your delivery in real time.</p>
        <div class="feature-list">
            <div class="feature-item"><div class="feature-dot">🐟</div> Browse 100+ fresh fish products</div>
            <div class="feature-item"><div class="feature-dot">📱</div> Pay instantly via M-Pesa</div>
            <div class="feature-item"><div class="feature-dot">📍</div> Live GPS delivery tracking</div>
            <div class="feature-item"><div class="feature-dot">⭐</div> Verified producers & reviews</div>
        </div>
    </div>

    {{-- Right panel --}}
    <div class="auth-right">
        <div class="form-box">
            <div class="form-title">Welcome back</div>
            <div class="form-subtitle">Sign in to your AquaHub account</div>

            @if(session('status'))
                <div class="alert-info">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input"
                        value="{{ old('email') }}" required autofocus autocomplete="username"
                        placeholder="you@example.com">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input"
                        required autocomplete="current-password" placeholder="Enter your password">
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                    @if(Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
                    @endif
                </div>

                <button type="submit" class="btn-login">Sign In</button>
            </form>

            <div class="divider"><span>Don't have an account?</span></div>

            <div class="register-link">
                <a href="{{ route('register') }}">Create a free account →</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
