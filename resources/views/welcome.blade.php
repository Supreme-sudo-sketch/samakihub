<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaHub — Fresh Fish Marketplace Kenya</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <style>
        :root{--ocean:#0a3d4f;--tide:#0d6e8a;--foam:#e8f4f8;--coral:#e85d3a;--gold:#f0a500;--fresh:#2eb87a;--muted:#6b7d8a;--border:#dde8ed}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:#fff;color:#1a1a2e;overflow-x:hidden}

        /* NAV */
        .nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:0 2rem;height:68px;display:flex;align-items:center;justify-content:space-between;transition:background .3s,box-shadow .3s}
        .nav.scrolled{background:var(--ocean);box-shadow:0 2px 20px rgba(0,0,0,.3)}
        .nav-logo{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:#fff;text-decoration:none}
        .nav-logo span{color:var(--gold)}
        .nav-links{display:flex;align-items:center;gap:6px}
        .nav-links a{color:rgba(255,255,255,.8);font-size:.875rem;font-weight:500;padding:8px 14px;border-radius:8px;text-decoration:none;transition:all .2s}
        .nav-links a:hover{background:rgba(255,255,255,.12);color:#fff}
        .nav-cta{background:var(--gold);color:#051e28 !important;font-weight:700 !important;border-radius:8px}
        .nav-cta:hover{background:#e09600 !important}

        /* HERO */
        .hero{min-height:100vh;background:linear-gradient(160deg,var(--ocean) 0%,#0b5570 50%,#0e7a9a 100%);display:flex;align-items:center;position:relative;overflow:hidden;padding:80px 2rem 0}
        .hero-bg-pattern{position:absolute;inset:0;background-image:radial-gradient(circle at 20% 50%,rgba(255,255,255,.04) 0%,transparent 50%),radial-gradient(circle at 80% 20%,rgba(240,165,0,.08) 0%,transparent 40%);pointer-events:none}
        .hero-wave{position:absolute;bottom:-1px;left:0;right:0}
        .hero-inner{max-width:1200px;margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;width:100%}
        @media(max-width:900px){.hero-inner{grid-template-columns:1fr}.hero-visual{display:none}}
        .hero-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(240,165,0,.15);border:1px solid rgba(240,165,0,.3);color:var(--gold);padding:6px 14px;border-radius:20px;font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:20px}
        .hero h1{font-family:'Playfair Display',serif;font-size:3.4rem;font-weight:900;color:#fff;line-height:1.1;margin-bottom:20px}
        .hero h1 span{color:var(--gold)}
        .hero p{font-size:1.1rem;color:rgba(255,255,255,.75);line-height:1.7;margin-bottom:36px;max-width:480px}
        .hero-actions{display:flex;gap:14px;flex-wrap:wrap}
        .btn-primary{display:inline-flex;align-items:center;gap:8px;background:var(--gold);color:#051e28;padding:14px 28px;border-radius:12px;font-weight:700;font-size:1rem;text-decoration:none;transition:all .2s}
        .btn-primary:hover{background:#e09600;transform:translateY(-2px)}
        .btn-secondary{display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);color:#fff;padding:14px 28px;border-radius:12px;font-weight:600;font-size:1rem;text-decoration:none;border:1px solid rgba(255,255,255,.2);transition:all .2s}
        .btn-secondary:hover{background:rgba(255,255,255,.2);transform:translateY(-2px)}
        .hero-stats{display:flex;gap:32px;margin-top:44px;flex-wrap:wrap}
        .stat{text-align:center}
        .stat-num{font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:#fff}
        .stat-label{font-size:.78rem;color:rgba(255,255,255,.6);font-weight:500;margin-top:2px}

        /* Hero visual */
        .hero-visual{display:flex;flex-direction:column;gap:14px;align-items:flex-end}
        .fish-card{background:rgba(255,255,255,.1);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15);border-radius:16px;padding:16px 20px;display:flex;align-items:center;gap:14px;width:280px;transition:transform .3s}
        .fish-card:hover{transform:translateX(-4px)}
        .fish-card:nth-child(2){margin-right:30px}
        .fish-card:nth-child(3){margin-right:15px}
        .fish-icon{font-size:2rem;width:52px;height:52px;background:rgba(255,255,255,.12);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0}
        .fish-info{flex:1}
        .fish-name{font-weight:700;color:#fff;font-size:.9rem}
        .fish-price{color:var(--gold);font-weight:700;font-size:.95rem}
        .fish-tag{font-size:.72rem;color:rgba(255,255,255,.6)}
        .fish-fresh{background:rgba(46,184,122,.2);color:#5de8a0;padding:2px 8px;border-radius:6px;font-size:.7rem;font-weight:700}

        /* HOW IT WORKS */
        .section{padding:80px 2rem}
        .section-inner{max-width:1100px;margin:0 auto}
        .section-label{font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:var(--tide);margin-bottom:10px}
        .section-title{font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:900;color:var(--ocean);margin-bottom:14px}
        .section-sub{font-size:1rem;color:var(--muted);max-width:520px;line-height:1.7}
        .steps-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:28px;margin-top:52px}
        .step-card{background:#fff;border:1px solid var(--border);border-radius:20px;padding:28px;transition:box-shadow .2s,transform .2s}
        .step-card:hover{box-shadow:0 12px 32px rgba(10,61,79,.1);transform:translateY(-4px)}
        .step-num{font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:900;color:var(--foam);line-height:1;margin-bottom:12px}
        .step-icon{font-size:2rem;margin-bottom:12px}
        .step-title{font-weight:700;font-size:1.05rem;color:var(--ocean);margin-bottom:8px}
        .step-desc{font-size:.875rem;color:var(--muted);line-height:1.6}

        /* CATEGORIES */
        .categories-bg{background:var(--foam)}
        .cats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:14px;margin-top:44px}
        .cat-card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:20px;text-align:center;text-decoration:none;color:inherit;transition:all .2s}
        .cat-card:hover{border-color:var(--tide);transform:translateY(-3px);box-shadow:0 8px 20px rgba(10,61,79,.1)}
        .cat-emoji{font-size:2.4rem;margin-bottom:10px}
        .cat-name{font-weight:700;font-size:.9rem;color:var(--ocean)}
        .cat-count{font-size:.75rem;color:var(--muted);margin-top:3px}

        /* ROLES */
        .roles-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;margin-top:52px}
        .role-card{border-radius:20px;padding:32px;position:relative;overflow:hidden}
        .role-card.producer{background:linear-gradient(135deg,var(--ocean),#0b5570)}
        .role-card.consumer{background:linear-gradient(135deg,#1a6b3a,var(--fresh))}
        .role-card.delivery{background:linear-gradient(135deg,#7a5200,var(--gold))}
        .role-card::after{content:'';position:absolute;right:-20px;bottom:-20px;width:100px;height:100px;border-radius:50%;background:rgba(255,255,255,.06)}
        .role-icon{font-size:2.5rem;margin-bottom:16px}
        .role-title{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:900;color:#fff;margin-bottom:8px}
        .role-desc{font-size:.875rem;color:rgba(255,255,255,.8);line-height:1.6;margin-bottom:20px}
        .role-link{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,.2);color:#fff;padding:9px 18px;border-radius:8px;text-decoration:none;font-size:.85rem;font-weight:700;transition:background .2s}
        .role-link:hover{background:rgba(255,255,255,.3)}

        /* TRUST */
        .trust-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:24px;margin-top:48px}
        .trust-card{display:flex;align-items:flex-start;gap:14px}
        .trust-icon{width:48px;height:48px;border-radius:12px;background:var(--foam);display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0}
        .trust-title{font-weight:700;font-size:.95rem;color:var(--ocean);margin-bottom:4px}
        .trust-desc{font-size:.83rem;color:var(--muted);line-height:1.5}

        /* CTA BANNER */
        .cta-section{background:linear-gradient(135deg,var(--ocean),var(--tide));padding:80px 2rem;text-align:center}
        .cta-section h2{font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:900;color:#fff;margin-bottom:14px}
        .cta-section p{color:rgba(255,255,255,.75);font-size:1.05rem;margin-bottom:36px;max-width:500px;margin-left:auto;margin-right:auto;line-height:1.7}
        .cta-actions{display:flex;justify-content:center;gap:14px;flex-wrap:wrap}

        /* FOOTER */
        footer{background:#051e28;padding:48px 2rem 28px}
        .footer-inner{max-width:1100px;margin:0 auto}
        .footer-top{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:40px;padding-bottom:40px;border-bottom:1px solid rgba(255,255,255,.08)}
        @media(max-width:768px){.footer-top{grid-template-columns:1fr}}
        .footer-logo{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:900;color:#fff;text-decoration:none;display:inline-block;margin-bottom:12px}
        .footer-logo span{color:var(--gold)}
        .footer-desc{font-size:.85rem;color:rgba(255,255,255,.5);line-height:1.7;max-width:260px}
        .footer-col-title{font-weight:700;font-size:.82rem;text-transform:uppercase;letter-spacing:.6px;color:rgba(255,255,255,.4);margin-bottom:14px}
        .footer-col a{display:block;font-size:.875rem;color:rgba(255,255,255,.55);text-decoration:none;margin-bottom:8px;transition:color .15s}
        .footer-col a:hover{color:#fff}
        .footer-bottom{padding-top:24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px}
        .footer-copy{font-size:.82rem;color:rgba(255,255,255,.35)}
        .footer-copy span{color:var(--gold)}
    </style>
</head>
<body>

{{-- NAVBAR --}}
<nav class="nav" id="mainNav">
    <a href="{{ url('/') }}" class="nav-logo">🐟 Aqua<span>Hub</span></a>
    <div class="nav-links">
        <a href="{{ route('products.index') }}">Browse Fish</a>
        @auth
            <a href="{{ url('/dashboard') }}">Dashboard</a>
            <a href="{{ route('products.index') }}" class="nav-cta">Shop Now</a>
        @else
            <a href="{{ route('login') }}">Log in</a>
            <a href="{{ route('register') }}" class="nav-cta">Get Started</a>
        @endauth
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div class="hero-bg-pattern"></div>
    <div class="hero-inner">
        <div>
            <div class="hero-badge">🇰🇪 Kenya's Fish Marketplace</div>
            <h1>Fresh Fish,<br>Straight from the <span>Lake</span></h1>
            <p>Connect directly with local fishermen across Kenya. Order fresh tilapia, salmon, tuna and more — delivered to your door within hours.</p>
            <div class="hero-actions">
                <a href="{{ route('products.index') }}" class="btn-primary">🛒 Shop Fresh Fish</a>
                @guest
                    <a href="{{ route('register') }}" class="btn-secondary">Join as Producer →</a>
                @endguest
            </div>
            <div class="hero-stats">
                <div class="stat">
                    <div class="stat-num">500+</div>
                    <div class="stat-label">Fish Varieties</div>
                </div>
                <div class="stat">
                    <div class="stat-num">1,200+</div>
                    <div class="stat-label">Happy Customers</div>
                </div>
                <div class="stat">
                    <div class="stat-num">48hr</div>
                    <div class="stat-label">Freshness Guarantee</div>
                </div>
            </div>
        </div>

        <div class="hero-visual">
            <div class="fish-card">
                <div class="fish-icon">🐟</div>
                <div class="fish-info">
                    <div class="fish-name">Nile Tilapia</div>
                    <div class="fish-price">KES 450/kg</div>
                    <div class="fish-tag"><span class="fish-fresh">Super Fresh</span></div>
                </div>
            </div>
            <div class="fish-card">
                <div class="fish-icon">🐠</div>
                <div class="fish-info">
                    <div class="fish-name">Atlantic Salmon</div>
                    <div class="fish-price">KES 1,200/kg</div>
                    <div class="fish-tag"><span class="fish-fresh">Fresh</span></div>
                </div>
            </div>
            <div class="fish-card">
                <div class="fish-icon">🦈</div>
                <div class="fish-info">
                    <div class="fish-name">Smoked Omena</div>
                    <div class="fish-price">KES 280/kg</div>
                    <div class="fish-tag"><span class="fish-fresh">Smoked</span></div>
                </div>
            </div>
        </div>
    </div>

    <svg class="hero-wave" viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
        <path d="M0 80L48 72C96 64 192 48 288 45.3C384 43 480 53 576 58.7C672 64 768 64 864 56C960 48 1056 32 1152 26.7C1248 21 1344 27 1392 29.3L1440 32V80H0Z" fill="#ffffff"/>
    </svg>
</section>

{{-- HOW IT WORKS --}}
<section class="section">
    <div class="section-inner">
        <div class="section-label">How It Works</div>
        <div class="section-title">From Lake to Your Table</div>
        <div class="section-sub">Simple steps to get the freshest fish delivered to your doorstep anywhere in Kenya.</div>

        <div class="steps-grid">
            <div class="step-card">
                <div class="step-num">01</div>
                <div class="step-icon">🔍</div>
                <div class="step-title">Browse the Marketplace</div>
                <div class="step-desc">Explore hundreds of fresh fish listings from verified local producers across Kenya's lakes and coastlines.</div>
            </div>
            <div class="step-card">
                <div class="step-num">02</div>
                <div class="step-icon">🛒</div>
                <div class="step-title">Add to Cart</div>
                <div class="step-desc">Select your fish, choose the quantity in kilograms, and add multiple varieties to your cart in one order.</div>
            </div>
            <div class="step-card">
                <div class="step-num">03</div>
                <div class="step-icon">📱</div>
                <div class="step-title">Pay via M-Pesa</div>
                <div class="step-desc">Secure checkout with Safaricom M-Pesa STK Push — pay instantly from your phone, no card needed.</div>
            </div>
            <div class="step-card">
                <div class="step-num">04</div>
                <div class="step-icon">🛵</div>
                <div class="step-title">Track Your Delivery</div>
                <div class="step-desc">A verified delivery agent picks up your order. Track it live from confirmed all the way to your doorstep.</div>
            </div>
        </div>
    </div>
</section>

{{-- CATEGORIES --}}
<section class="section categories-bg">
    <div class="section-inner">
        <div class="section-label">Fish Categories</div>
        <div class="section-title">Something for Everyone</div>
        <div class="section-sub">From everyday tilapia to premium salmon — browse by category or freshness level.</div>

        <div class="cats-grid">
            @foreach([
                ['🐟','Tilapia','Most popular'],
                ['🐠','Salmon','Premium catch'],
                ['🦈','Tuna','High protein'],
                ['🐡','Catfish','Kenyan favourite'],
                ['🐟','Sardines','Budget-friendly'],
                ['🐠','Trout','Highland rivers'],
                ['🦐','Bass','Lake Victoria'],
                ['🐟','Omena','Smoked & dried'],
            ] as [$emoji, $name, $sub])
                <a href="{{ route('products.index', ['category' => strtolower($name)]) }}" class="cat-card">
                    <div class="cat-emoji">{{ $emoji }}</div>
                    <div class="cat-name">{{ $name }}</div>
                    <div class="cat-count">{{ $sub }}</div>
                </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ROLES --}}
<section class="section">
    <div class="section-inner">
        <div class="section-label">Join AquaHub</div>
        <div class="section-title">Built for the Whole Ecosystem</div>
        <div class="section-sub">Whether you're a fisherman, a buyer, or a delivery rider — AquaHub has a role for you.</div>

        <div class="roles-grid">
            <div class="role-card producer">
                <div class="role-icon">🎣</div>
                <div class="role-title">Fish Producers</div>
                <div class="role-desc">List your catch, set your prices, and reach thousands of customers across Kenya — directly, no middlemen.</div>
                <a href="{{ route('register') }}" class="role-link">Join as Producer →</a>
            </div>
            <div class="role-card consumer">
                <div class="role-icon">🛒</div>
                <div class="role-title">Consumers</div>
                <div class="role-desc">Buy fresh fish directly from verified local producers. Track your order in real-time and pay securely via M-Pesa.</div>
                <a href="{{ route('register') }}" class="role-link">Start Shopping →</a>
            </div>
            <div class="role-card delivery">
                <div class="role-icon">🛵</div>
                <div class="role-title">Delivery Riders</div>
                <div class="role-desc">Pick up orders in your area, earn per delivery, and use our GPS tracking to keep customers updated every step.</div>
                <a href="{{ route('register') }}" class="role-link">Deliver with Us →</a>
            </div>
        </div>
    </div>
</section>

{{-- TRUST --}}
<section class="section categories-bg">
    <div class="section-inner">
        <div style="text-align:center;margin-bottom:0">
            <div class="section-label" style="text-align:center">Why AquaHub</div>
            <div class="section-title" style="text-align:center;max-width:480px;margin-left:auto;margin-right:auto">Trusted by Fishermen & Families Across Kenya</div>
        </div>
        <div class="trust-grid">
            <div class="trust-card">
                <div class="trust-icon">✅</div>
                <div>
                    <div class="trust-title">Verified Producers</div>
                    <div class="trust-desc">Every producer goes through admin verification before listing. Quality you can trust.</div>
                </div>
            </div>
            <div class="trust-card">
                <div class="trust-icon">📍</div>
                <div>
                    <div class="trust-title">Live Order Tracking</div>
                    <div class="trust-desc">Real-time GPS updates from your delivery agent — see exactly where your fish is.</div>
                </div>
            </div>
            <div class="trust-card">
                <div class="trust-icon">📱</div>
                <div>
                    <div class="trust-title">M-Pesa Payments</div>
                    <div class="trust-desc">Secure STK Push checkout. Pay instantly from your phone — no cards, no hassle.</div>
                </div>
            </div>
            <div class="trust-card">
                <div class="trust-icon">❄️</div>
                <div>
                    <div class="trust-title">Freshness Guarantee</div>
                    <div class="trust-desc">Catch dates displayed on every listing. Super Fresh, Fresh, Smoked or Dried — clearly labelled.</div>
                </div>
            </div>
            <div class="trust-card">
                <div class="trust-icon">💬</div>
                <div>
                    <div class="trust-title">Ask the Producer</div>
                    <div class="trust-desc">Have a question about the catch? Message the fisherman directly before you buy.</div>
                </div>
            </div>
            <div class="trust-card">
                <div class="trust-icon">🤖</div>
                <div>
                    <div class="trust-title">AI Fish Assistant</div>
                    <div class="trust-desc">AquaBot answers your questions about fish — nutrition, recipes, cooking tips — 24/7.</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta-section">
    <h2>Ready for Fresher Fish?</h2>
    <p>Join thousands of Kenyans buying direct from local fishermen. Sign up free in under a minute.</p>
    <div class="cta-actions">
        <a href="{{ route('register') }}" class="btn-primary" style="font-size:1.05rem;padding:15px 32px">🚀 Create Free Account</a>
        <a href="{{ route('products.index') }}" class="btn-secondary" style="font-size:1.05rem;padding:15px 32px">Browse Marketplace</a>
    </div>
</section>

{{-- FOOTER --}}
<footer>
    <div class="footer-inner">
        <div class="footer-top">
            <div>
                <a href="{{ url('/') }}" class="footer-logo">🐟 Aqua<span>Hub</span></a>
                <p class="footer-desc">Kenya's premier fresh fish marketplace — connecting fishermen, consumers and delivery riders across the country.</p>
            </div>
            <div class="footer-col">
                <div class="footer-col-title">Marketplace</div>
                <a href="{{ route('products.index') }}">Browse Fish</a>
                <a href="{{ route('products.index', ['category' => 'tilapia']) }}">Tilapia</a>
                <a href="{{ route('products.index', ['category' => 'salmon']) }}">Salmon</a>
                <a href="{{ route('products.index', ['freshness' => 'smoked']) }}">Smoked Fish</a>
            </div>
            <div class="footer-col">
                <div class="footer-col-title">Account</div>
                <a href="{{ route('login') }}">Log In</a>
                <a href="{{ route('register') }}">Register</a>
                @auth
                    <a href="{{ url('/dashboard') }}">Dashboard</a>
                @endauth
            </div>
            <div class="footer-col">
                <div class="footer-col-title">Join As</div>
                <a href="{{ route('register') }}">Consumer</a>
                <a href="{{ route('register') }}">Fish Producer</a>
                <a href="{{ route('register') }}">Delivery Rider</a>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="footer-copy">© 2026 <span>AquaHub</span> — Fresh Fish Marketplace Kenya</div>
            <div class="footer-copy">Built with ❤️ for Kenyan fishermen</div>
        </div>
    </div>
</footer>

<script>
    const nav = document.getElementById('mainNav');
    window.addEventListener('scroll', () => {
        nav.classList.toggle('scrolled', window.scrollY > 40);
    });
    // Trigger on load in case page is already scrolled
    if (window.scrollY > 40) nav.classList.add('scrolled');
</script>

@include('partials.samakibot')
</body>
</html>
