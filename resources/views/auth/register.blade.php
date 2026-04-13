<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--ocean:#0a3d4f;--tide:#0d6e8a;--foam:#e8f4f8;--coral:#e85d3a;--gold:#f0a500;--fresh:#2eb87a;--muted:#6b7d8a;--border:#dde8ed}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;min-height:100vh;background:var(--ocean)}

        /* Split layout */
        .auth-wrap{display:grid;grid-template-columns:1fr 1fr;min-height:100vh}
        .auth-left{background:linear-gradient(160deg,var(--ocean) 0%,#0b5570 50%,#0e7a9a 100%);display:flex;flex-direction:column;justify-content:center;padding:4rem 3rem;position:relative;overflow:hidden}
        .auth-left::before{content:'';position:absolute;inset:0;background:radial-gradient(circle at 30% 70%,rgba(255,255,255,.05),transparent 60%);pointer-events:none}
        .auth-right{background:#fff;display:flex;align-items:center;justify-content:center;padding:3rem 2rem;overflow-y:auto}

        /* Left panel */
        .brand-logo{font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:#fff;text-decoration:none;display:block;margin-bottom:3rem}
        .brand-logo span{color:var(--gold)}
        .left-heading{font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:900;color:#fff;line-height:1.15;margin-bottom:1rem}
        .left-heading span{color:var(--gold)}
        .left-sub{color:rgba(255,255,255,.65);font-size:.95rem;line-height:1.7;margin-bottom:2.5rem;max-width:380px}

        /* Role cards */
        .role-cards{display:flex;flex-direction:column;gap:.75rem}
        .role-card{background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:12px;padding:.9rem 1.1rem;display:flex;align-items:center;gap:12px}
        .role-icon{font-size:1.4rem;width:40px;height:40px;background:rgba(255,255,255,.1);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .role-info-title{font-size:.875rem;font-weight:700;color:#fff;margin-bottom:2px}
        .role-info-sub{font-size:.75rem;color:rgba(255,255,255,.55)}

        /* Form panel */
        .form-box{width:100%;max-width:460px}
        .form-title{font-family:'Playfair Display',serif;font-size:1.9rem;font-weight:900;color:var(--ocean);margin-bottom:.4rem}
        .form-subtitle{color:var(--muted);font-size:.9rem;margin-bottom:1.75rem}
        .form-group{margin-bottom:1.1rem}
        .form-group label{display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:.4rem}
        .form-input{width:100%;padding:.7rem 1rem;border:1.5px solid var(--border);border-radius:10px;font-size:.925rem;font-family:'DM Sans',sans-serif;outline:none;transition:border .2s,box-shadow .2s;color:#1a1a2e}
        .form-input:focus{border-color:var(--tide);box-shadow:0 0 0 3px rgba(13,110,138,.1)}
        .form-error{font-size:.8rem;color:var(--coral);margin-top:.3rem}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:.85rem}

        /* Role selector */
        .role-selector{display:grid;grid-template-columns:repeat(3,1fr);gap:.6rem;margin-bottom:1.1rem}
        .role-opt{position:relative}
        .role-opt input{position:absolute;opacity:0;pointer-events:none}
        .role-opt label{display:flex;flex-direction:column;align-items:center;gap:5px;padding:.7rem .5rem;border:2px solid var(--border);border-radius:10px;cursor:pointer;transition:all .2s;text-align:center}
        .role-opt label:hover{border-color:var(--tide);background:var(--foam)}
        .role-opt input:checked + label{border-color:var(--tide);background:#e0f5f9;color:var(--ocean)}
        .role-opt .r-icon{font-size:1.3rem}
        .role-opt .r-name{font-size:.78rem;font-weight:700;color:#374151}
        .role-opt .r-desc{font-size:.68rem;color:var(--muted)}
        .role-opt input:checked + label .r-name{color:var(--ocean)}
        .role-opt-label{font-size:.875rem;font-weight:600;color:#374151;margin-bottom:.5rem;display:block}

        .pending-note{background:#fef9c3;border:1px solid #fde68a;color:#854d0e;padding:.75rem 1rem;border-radius:10px;font-size:.8rem;margin-top:.4rem;display:none}
        .pending-note.show{display:block}

        .btn-register{width:100%;background:var(--ocean);color:#fff;padding:.85rem;border:none;border-radius:10px;font-size:1rem;font-weight:700;font-family:'DM Sans',sans-serif;cursor:pointer;transition:all .2s;margin-top:.5rem}
        .btn-register:hover{background:var(--tide);transform:translateY(-1px);box-shadow:0 4px 16px rgba(10,61,79,.3)}
        .login-link{text-align:center;font-size:.875rem;color:var(--muted);margin-top:1.25rem}
        .login-link a{color:var(--tide);font-weight:700;text-decoration:none}
        .login-link a:hover{text-decoration:underline}
        .terms-note{font-size:.78rem;color:var(--muted);text-align:center;margin-top:.75rem;line-height:1.5}

        @media(max-width:860px){
            .auth-wrap{grid-template-columns:1fr}
            .auth-left{display:none}
            .auth-right{min-height:100vh;background:linear-gradient(160deg,var(--ocean) 0%,#0b5570 100%);padding:2rem 1.25rem}
            .form-box{background:#fff;border-radius:20px;padding:1.75rem 1.5rem;box-shadow:0 24px 60px rgba(0,0,0,.3)}
            .form-grid{grid-template-columns:1fr}
        }
    </style>
</head>
<body>

<div class="auth-wrap">
    {{-- Left panel --}}
    <div class="auth-left">
        <a href="{{ url('/') }}" class="brand-logo">Aqua<span>Hub</span></a>
        <h1 class="left-heading">Join Kenya's<br>Premier <span>Seafood</span><br>Marketplace</h1>
        <p class="left-sub">Whether you're buying, selling, or delivering — AquaHub connects everyone in the fish supply chain.</p>
        <div class="role-cards">
            <div class="role-card">
                <div class="role-icon">🛒</div>
                <div>
                    <div class="role-info-title">Consumer</div>
                    <div class="role-info-sub">Browse & order fresh fish, track deliveries live</div>
                </div>
            </div>
            <div class="role-card">
                <div class="role-icon">🐟</div>
                <div>
                    <div class="role-info-title">Producer</div>
                    <div class="role-info-sub">List your catch, manage orders & receive M-Pesa payouts</div>
                </div>
            </div>
            <div class="role-card">
                <div class="role-icon">🚚</div>
                <div>
                    <div class="role-info-title">Delivery Agent</div>
                    <div class="role-info-sub">Claim orders, update status & earn per delivery</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Right panel --}}
    <div class="auth-right">
        <div class="form-box">
            <div class="form-title">Create Account</div>
            <div class="form-subtitle">Join thousands of Kenyans on AquaHub</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Role selector --}}
                <span class="role-opt-label">I want to...</span>
                <div class="role-selector">
                    <div class="role-opt">
                        <input type="radio" id="role-consumer" name="role" value="consumer" {{ old('role','consumer') === 'consumer' ? 'checked' : '' }} onchange="showPendingNote(this)">
                        <label for="role-consumer">
                            <span class="r-icon">🛒</span>
                            <span class="r-name">Buy Fish</span>
                            <span class="r-desc">Consumer</span>
                        </label>
                    </div>
                    <div class="role-opt">
                        <input type="radio" id="role-producer" name="role" value="producer" {{ old('role') === 'producer' ? 'checked' : '' }} onchange="showPendingNote(this)">
                        <label for="role-producer">
                            <span class="r-icon">🐟</span>
                            <span class="r-name">Sell Fish</span>
                            <span class="r-desc">Producer</span>
                        </label>
                    </div>
                    <div class="role-opt">
                        <input type="radio" id="role-delivery" name="role" value="delivery" {{ old('role') === 'delivery' ? 'checked' : '' }} onchange="showPendingNote(this)">
                        <label for="role-delivery">
                            <span class="r-icon">🚚</span>
                            <span class="r-name">Deliver</span>
                            <span class="r-desc">Agent</span>
                        </label>
                    </div>
                </div>
                @error('role')<p class="form-error" style="margin-top:-.5rem;margin-bottom:.75rem">{{ $message }}</p>@enderror
                <div class="pending-note {{ in_array(old('role'), ['producer','delivery']) ? 'show' : '' }}" id="pending-note">
                    ⚠️ Producer and Delivery accounts require admin approval before you can log in.
                </div>

                {{-- Name + Email --}}
                <div class="form-grid" style="margin-top:1rem">
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-input"
                            value="{{ old('name') }}" required autofocus placeholder="John Mwangi">
                        @error('name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone (M-Pesa)</label>
                        <input type="tel" id="phone" name="phone" class="form-input"
                            value="{{ old('phone') }}" placeholder="0712 345 678">
                        @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input"
                        value="{{ old('email') }}" required placeholder="you@example.com">
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" class="form-input"
                            required autocomplete="new-password" placeholder="Min. 8 characters">
                        @error('password')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                            required autocomplete="new-password" placeholder="Repeat password">
                    </div>
                </div>

                <button type="submit" class="btn-register">Create My Account</button>
            </form>

            <div class="login-link">Already have an account? <a href="{{ route('login') }}">Sign in</a></div>
            <div class="terms-note">By registering, you agree to AquaHub's terms of service and privacy policy.</div>
        </div>
    </div>
</div>

<script>
function showPendingNote(el) {
    const note = document.getElementById('pending-note');
    note.classList.toggle('show', el.value === 'producer' || el.value === 'delivery');
}
</script>
</body>
</html>
