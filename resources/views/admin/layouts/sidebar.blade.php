<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — AquaHub Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--ocean:#0a3d4f;--tide:#0d6e8a;--foam:#e8f4f8;--coral:#e85d3a;--gold:#f0a500;--fresh:#2eb87a;--muted:#6b7d8a;--border:#dde8ed;--sidebar-w:240px}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:#f0f4f7;color:#1a1a2e;display:flex;min-height:100vh}

        /* Sidebar */
        .sidebar{width:var(--sidebar-w);background:var(--ocean);display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:200;overflow-y:auto}
        .sidebar-logo{padding:22px 20px 16px;border-bottom:1px solid rgba(255,255,255,.1)}
        .sidebar-logo a{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:900;color:#fff;text-decoration:none}
        .sidebar-logo a span{color:var(--gold)}
        .sidebar-logo .badge{display:block;font-family:'DM Sans',sans-serif;font-size:.7rem;font-weight:700;color:rgba(255,255,255,.5);letter-spacing:.8px;text-transform:uppercase;margin-top:2px}

        .sidebar-nav{flex:1;padding:12px 0}
        .nav-section{padding:14px 20px 6px;font-size:.65rem;font-weight:700;color:rgba(255,255,255,.35);text-transform:uppercase;letter-spacing:1px}
        .nav-link{display:flex;align-items:center;gap:10px;padding:10px 20px;color:rgba(255,255,255,.7);text-decoration:none;font-size:.875rem;font-weight:500;transition:all .18s;border-left:3px solid transparent}
        .nav-link:hover{background:rgba(255,255,255,.08);color:#fff}
        .nav-link.active{background:rgba(255,255,255,.12);color:#fff;border-left-color:var(--gold)}
        .nav-icon{font-size:1rem;width:20px;text-align:center;flex-shrink:0}
        .nav-badge{margin-left:auto;background:var(--coral);color:#fff;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px}
        .nav-badge.green{background:var(--fresh)}

        .sidebar-bottom{padding:16px 20px;border-top:1px solid rgba(255,255,255,.1)}
        .admin-user{display:flex;align-items:center;gap:10px;margin-bottom:12px}
        .admin-avatar{width:36px;height:36px;border-radius:50%;background:var(--gold);display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:700;color:#051e28;flex-shrink:0}
        .admin-info{min-width:0}
        .admin-name{font-size:.85rem;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .admin-role{font-size:.72rem;color:rgba(255,255,255,.5)}
        .logout-btn{width:100%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);color:rgba(255,255,255,.8);padding:8px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.82rem;font-weight:600;transition:all .18s}
        .logout-btn:hover{background:rgba(255,255,255,.18);color:#fff}

        /* Main content */
        .main{margin-left:var(--sidebar-w);flex:1;display:flex;flex-direction:column;min-height:100vh}
        .topbar{background:#fff;border-bottom:1px solid var(--border);padding:0 28px;height:60px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100}
        .topbar-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--ocean)}
        .topbar-actions{display:flex;align-items:center;gap:12px}
        .content{padding:28px;flex:1}

        /* Responsive */
        @media(max-width:900px){
            .sidebar{transform:translateX(-100%)}
            .main{margin-left:0}
        }

        /* Alerts */
        .alert-s{background:#d4f7e7;border:1px solid #1a7a4a;color:#1a7a4a;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-weight:500}
        .alert-e{background:#fde8e8;border:1px solid #a81a1a;color:#a81a1a;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-weight:500}
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar">
    <div class="sidebar-logo">
        <a href="{{ route('admin.dashboard') }}">🐟 Aqua<span>Hub</span></a>
        <span class="badge">Admin Panel</span>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">Overview</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="nav-icon">📊</span> Dashboard
        </a>
        <a href="{{ route('admin.statistics') }}" class="nav-link {{ request()->routeIs('admin.statistics') ? 'active' : '' }}">
            <span class="nav-icon">📈</span> Statistics
        </a>

        <div class="nav-section">Management</div>
        <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">
            <span class="nav-icon">👥</span> Users
            @php $pending = \App\Models\User::where('status','pending')->count(); @endphp
            @if($pending > 0)
                <span class="nav-badge">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">
            <span class="nav-icon">🐟</span> Products
        </a>
        <a href="{{ route('admin.reviews') }}" class="nav-link {{ request()->routeIs('admin.reviews') ? 'active' : '' }}">
            <span class="nav-icon">⭐</span> Reviews
        </a>
        <a href="{{ route('admin.questions') }}" class="nav-link {{ request()->routeIs('admin.questions') ? 'active' : '' }}">
            <span class="nav-icon">❓</span> Questions
        </a>
        <a href="{{ route('admin.orders') }}" class="nav-link {{ request()->routeIs('admin.orders') ? 'active' : '' }}">
            <span class="nav-icon">📦</span> Orders
        </a>

        <a href="{{ route('admin.withdrawals') }}" class="nav-link {{ request()->routeIs('admin.withdrawals') ? 'active' : '' }}">
            <span class="nav-icon">💸</span> Withdrawals
            @php $pendingW = \App\Models\Withdrawal::where('status','pending')->count(); @endphp
            @if($pendingW > 0)
                <span class="nav-badge green">{{ $pendingW }}</span>
            @endif
        </a>

        <div class="nav-section">Platform</div>
        <a href="{{ route('products.index') }}" class="nav-link" target="_blank">
            <span class="nav-icon">🛍️</span> View Store
        </a>
    </nav>

    <div class="sidebar-bottom">
        <div class="admin-user">
            <div class="admin-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="admin-info">
                <div class="admin-name">{{ auth()->user()->name }}</div>
                <div class="admin-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">← Logout</button>
        </form>
    </div>
</aside>

<main class="main">
    <div class="topbar">
        <div class="topbar-title">@yield('topbar-title', 'Admin Panel')</div>
        <div class="topbar-actions">@yield('topbar-actions')</div>
    </div>

    <div class="content">
        @if(session('success'))<div class="alert-s">✅ {{ session('success') }}</div>@endif
        @if(session('error'))<div class="alert-e">⚠️ {{ session('error') }}</div>@endif

        @yield('content')
    </div>
</main>

@stack('scripts')
</body>
</html>
