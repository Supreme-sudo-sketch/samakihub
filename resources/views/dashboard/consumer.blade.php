<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--ocean:#0a3d4f;--tide:#0d6e8a;--foam:#e8f4f8;--coral:#e85d3a;--gold:#f0a500;--fresh:#2eb87a;--muted:#6b7d8a;--border:#dde8ed}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:var(--foam);color:#1a1a2e}

        /* ── Nav ── */
        .sh-nav{background:var(--ocean);padding:0 2rem;display:flex;align-items:center;justify-content:space-between;height:64px;position:sticky;top:0;z-index:100;box-shadow:0 2px 20px rgba(0,0,0,.3)}
        .sh-logo{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:#fff;text-decoration:none}
        .sh-logo span{color:var(--gold)}
        .sh-nav-links{display:flex;align-items:center;gap:4px}
        .sh-nav-links a{color:rgba(255,255,255,.75);font-size:.875rem;font-weight:500;padding:7px 13px;border-radius:8px;text-decoration:none;transition:all .2s}
        .sh-nav-links a:hover,.sh-nav-links a.active{background:rgba(255,255,255,.12);color:#fff}
        .sh-nav-right{display:flex;align-items:center;gap:12px}
        .sh-role{background:var(--gold);color:#051e28;font-size:.75rem;font-weight:700;padding:4px 10px;border-radius:20px}
        .sh-avatar{width:36px;height:36px;border-radius:50%;background:var(--tide);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;cursor:pointer;border:2px solid rgba(255,255,255,.25)}
        .sh-logout{color:rgba(255,255,255,.6);font-size:.8rem;text-decoration:none;padding:6px 10px;border-radius:8px;transition:.2s}
        .sh-logout:hover{background:rgba(255,255,255,.1);color:#fff}

        /* ── Page header ── */
        .page-header{background:#fff;border-bottom:1px solid var(--border);padding:22px 2rem}
        .page-header h2{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--ocean);margin-bottom:4px}
        .page-header p{color:var(--muted);font-size:.9rem}

        /* ── Layout ── */
        .wrap{max-width:1200px;margin:0 auto;padding:36px 2rem 80px}

        /* ── Stat cards ── */
        .stats-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:32px}
        @media(max-width:900px){.stats-grid{grid-template-columns:repeat(2,1fr)}}
        @media(max-width:480px){.stats-grid{grid-template-columns:1fr}}
        .stat-card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:22px 20px;display:flex;align-items:flex-start;gap:14px;transition:transform .2s,box-shadow .2s}
        .stat-card:hover{transform:translateY(-2px);box-shadow:0 6px 24px rgba(10,61,79,.1)}
        .stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0}
        .stat-icon.ocean{background:linear-gradient(135deg,var(--ocean),var(--tide))}
        .stat-icon.gold{background:linear-gradient(135deg,#f0a500,#f5c842)}
        .stat-icon.fresh{background:linear-gradient(135deg,var(--fresh),#5dd49a)}
        .stat-icon.coral{background:linear-gradient(135deg,var(--coral),#f0896e)}
        .stat-label{font-size:.8rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.05em;margin-bottom:4px}
        .stat-value{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:var(--ocean);line-height:1}
        .stat-sub{font-size:.78rem;color:var(--muted);margin-top:4px}

        /* ── Section title ── */
        .section-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--ocean);margin-bottom:16px;display:flex;align-items:center;gap:8px}
        .section-title a{font-family:'DM Sans',sans-serif;font-size:.82rem;font-weight:500;color:var(--tide);text-decoration:none;margin-left:auto}
        .section-title a:hover{text-decoration:underline}

        /* ── Two-col layout ── */
        .main-grid{display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start}
        @media(max-width:900px){.main-grid{grid-template-columns:1fr}}

        /* ── Orders card ── */
        .card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden}
        .card-body{padding:20px}

        .order-row{padding:16px 20px;border-bottom:1px solid var(--foam);display:flex;align-items:center;gap:14px;transition:background .15s}
        .order-row:last-child{border-bottom:none}
        .order-row:hover{background:#fafcfd}
        .order-icon{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--ocean),var(--tide));display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0}
        .order-meta{flex:1;min-width:0}
        .order-id{font-weight:700;font-size:.9rem;color:#1a1a2e;margin-bottom:3px}
        .order-items-chips{display:flex;gap:5px;flex-wrap:wrap;margin-top:4px}
        .chip{background:var(--foam);color:var(--ocean);font-size:.72rem;font-weight:600;padding:3px 8px;border-radius:20px;border:1px solid var(--border);white-space:nowrap}
        .order-right{text-align:right;flex-shrink:0}
        .order-amount{font-weight:700;font-size:.95rem;color:var(--ocean)}
        .order-date{font-size:.76rem;color:var(--muted);margin-top:2px}
        .order-link{font-size:.78rem;color:var(--tide);text-decoration:none;margin-top:6px;display:block}
        .order-link:hover{text-decoration:underline}

        /* Status badges */
        .badge{display:inline-flex;align-items:center;gap:5px;font-size:.72rem;font-weight:700;padding:4px 10px;border-radius:20px;white-space:nowrap}
        .badge-dot{width:6px;height:6px;border-radius:50%;display:inline-block}
        .badge.confirmed{background:#dbeafe;color:#1d4ed8}.badge.confirmed .badge-dot{background:#1d4ed8}
        .badge.preparing{background:#fef9c3;color:#92400e}.badge.preparing .badge-dot{background:#f59e0b}
        .badge.in_transit{background:#e0f2fe;color:#0369a1}.badge.in_transit .badge-dot{background:#0ea5e9;animation:pulse 1.2s infinite}
        .badge.delivered{background:#dcfce7;color:#166534}.badge.delivered .badge-dot{background:#22c55e}
        .badge.cancelled{background:#fee2e2;color:#991b1b}.badge.cancelled .badge-dot{background:#ef4444}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:.4}}

        /* ── Step tracker (mini) ── */
        .mini-steps{display:flex;align-items:center;gap:0;margin-top:8px}
        .mini-step{width:8px;height:8px;border-radius:50%;background:var(--border);flex-shrink:0}
        .mini-step.done{background:var(--fresh)}
        .mini-step.active{background:var(--gold);animation:pulse 1.2s infinite}
        .mini-connector{flex:1;height:2px;background:var(--border)}
        .mini-connector.done{background:var(--fresh)}

        /* ── Empty state ── */
        .empty{text-align:center;padding:48px 20px;color:var(--muted)}
        .empty-icon{font-size:3rem;margin-bottom:12px;opacity:.5}
        .empty p{font-size:.9rem;margin-bottom:16px}

        /* ── Quick actions sidebar ── */
        .quick-card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:20px;margin-bottom:20px}
        .quick-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--ocean);margin-bottom:14px}
        .quick-btn{display:flex;align-items:center;gap:12px;padding:12px 14px;border-radius:12px;text-decoration:none;font-weight:600;font-size:.88rem;color:#1a1a2e;transition:all .2s;border:1px solid var(--border);margin-bottom:8px}
        .quick-btn:last-child{margin-bottom:0}
        .quick-btn:hover{border-color:var(--tide);background:var(--foam);color:var(--ocean)}
        .quick-btn-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0}
        .quick-btn-icon.ocean{background:linear-gradient(135deg,var(--ocean),var(--tide))}
        .quick-btn-icon.gold{background:linear-gradient(135deg,#f0a500,#f5c842)}
        .quick-btn-icon.fresh{background:linear-gradient(135deg,var(--fresh),#5dd49a)}
        .quick-btn-badge{margin-left:auto;background:var(--coral);color:#fff;font-size:.7rem;font-weight:700;padding:2px 7px;border-radius:20px;min-width:22px;text-align:center}
        .quick-btn-badge.gold{background:var(--gold);color:#051e28}

        /* ── Tip card ── */
        .tip-card{background:linear-gradient(135deg,var(--ocean),var(--tide));border-radius:16px;padding:22px;color:#fff}
        .tip-card h4{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;margin-bottom:8px}
        .tip-card p{font-size:.82rem;opacity:.85;line-height:1.5;margin-bottom:14px}
        .tip-btn{display:inline-block;background:var(--gold);color:#051e28;font-size:.82rem;font-weight:700;padding:8px 16px;border-radius:8px;text-decoration:none;transition:opacity .2s}
        .tip-btn:hover{opacity:.85}
    </style>
</head>
<body>

{{-- Navigation --}}
<nav class="sh-nav">
    <a href="{{ url('/') }}" class="sh-logo">Aqua<span>Hub</span></a>
    <div class="sh-nav-links">
        <a href="{{ route('products.index') }}">🐟 Browse Fish</a>
        <a href="{{ route('cart.index') }}">🛒 Cart
            @if($cartCount > 0)
                <span style="background:var(--coral);color:#fff;font-size:.65rem;font-weight:700;padding:1px 6px;border-radius:20px;margin-left:2px">{{ $cartCount }}</span>
            @endif
        </a>
        <a href="{{ route('orders.index') }}">📦 My Orders</a>
        <a href="{{ route('consumer.dashboard') }}" class="active">Dashboard</a>
    </div>
    <div class="sh-nav-right">
        <span class="sh-role">Consumer</span>
        <div class="sh-avatar" title="{{ auth()->user()->name }}">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
        <form method="POST" action="{{ route('logout') }}" style="display:inline">
            @csrf
            <button type="submit" style="background:none;border:none;cursor:pointer;" class="sh-logout">Logout</button>
        </form>
    </div>
</nav>

{{-- Page Header --}}
<div class="page-header">
    <h2>Welcome back, {{ auth()->user()->first_name ?? explode(' ', auth()->user()->name)[0] }}! 👋</h2>
    <p>Here's what's happening with your orders and cart today.</p>
</div>

<div class="wrap">

    {{-- Session alerts --}}
    @if(session('success'))
        <div style="background:#dcfce7;border:1px solid #86efac;color:#166534;padding:12px 18px;border-radius:10px;font-size:.875rem;margin-bottom:24px;display:flex;align-items:center;gap:8px">
            ✅ {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div style="background:#fee2e2;border:1px solid #fca5a5;color:#991b1b;padding:12px 18px;border-radius:10px;font-size:.875rem;margin-bottom:24px;display:flex;align-items:center;gap:8px">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon ocean">📦</div>
            <div>
                <div class="stat-label">Total Orders</div>
                <div class="stat-value">{{ $totalOrders }}</div>
                <div class="stat-sub">All time</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon gold">🚚</div>
            <div>
                <div class="stat-label">Active Orders</div>
                <div class="stat-value">{{ $activeOrders }}</div>
                <div class="stat-sub">In progress</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon fresh">🛒</div>
            <div>
                <div class="stat-label">Cart Items</div>
                <div class="stat-value">{{ $cartCount }}</div>
                <div class="stat-sub">Waiting to order</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon coral">💰</div>
            <div>
                <div class="stat-label">Total Spent</div>
                <div class="stat-value" style="font-size:1.35rem">KES {{ number_format($totalSpent) }}</div>
                <div class="stat-sub">On delivered orders</div>
            </div>
        </div>
    </div>

    {{-- Main grid --}}
    <div class="main-grid">

        {{-- Left: Recent Orders --}}
        <div>
            <div class="section-title">
                📋 Recent Orders
                @if($totalOrders > 0)
                    <a href="{{ route('orders.index') }}">View all →</a>
                @endif
            </div>

            <div class="card">
                @forelse($recentOrders as $order)
                    @php
                        $steps = \App\Models\Order::STEPS;
                        $stepIndex = $order->step_index ?? array_search($order->status, $steps);
                    @endphp
                    <div class="order-row">
                        <div class="order-icon">🐟</div>
                        <div class="order-meta">
                            <div class="order-id">
                                Order #{{ $order->id }}
                                <span class="badge {{ $order->status }}" style="margin-left:6px">
                                    <span class="badge-dot"></span>
                                    {{ \App\Models\Order::STATUS_LABELS[$order->status] ?? ucfirst($order->status) }}
                                </span>
                            </div>
                            <div class="order-items-chips">
                                @foreach($order->items->take(3) as $item)
                                    <span class="chip">{{ $item->product->name ?? 'Product' }}</span>
                                @endforeach
                                @if($order->items->count() > 3)
                                    <span class="chip" style="background:var(--ocean);color:#fff;border-color:var(--ocean)">+{{ $order->items->count() - 3 }} more</span>
                                @endif
                            </div>
                            {{-- Mini step tracker --}}
                            @if(!in_array($order->status, ['cancelled']))
                            <div class="mini-steps" style="max-width:160px;margin-top:8px">
                                @foreach($steps as $i => $step)
                                    <div class="mini-step {{ $stepIndex > $i ? 'done' : ($stepIndex === $i ? 'active' : '') }}"></div>
                                    @if(!$loop->last)
                                        <div class="mini-connector {{ $stepIndex > $i ? 'done' : '' }}"></div>
                                    @endif
                                @endforeach
                            </div>
                            @endif
                        </div>
                        <div class="order-right">
                            <div class="order-amount">KES {{ number_format($order->total_amount) }}</div>
                            <div class="order-date">{{ $order->created_at->format('d M Y') }}</div>
                            <a href="{{ route('orders.show', $order->id) }}" class="order-link">View details →</a>
                        </div>
                    </div>
                @empty
                    <div class="empty">
                        <div class="empty-icon">📦</div>
                        <p>You haven't placed any orders yet.</p>
                        <a href="{{ route('products.index') }}" style="display:inline-block;background:var(--fresh);color:#fff;padding:10px 22px;border-radius:10px;font-weight:700;font-size:.875rem;text-decoration:none">Browse Fresh Fish</a>
                    </div>
                @endforelse
            </div>

            {{-- Cart reminder if items waiting --}}
            @if($cartCount > 0)
            <div style="margin-top:24px">
                <div class="section-title">🛒 You have items in your cart</div>
                <div class="card" style="padding:20px;display:flex;align-items:center;justify-content:space-between;gap:16px">
                    <div>
                        <div style="font-weight:700;font-size:.95rem;color:var(--ocean);margin-bottom:4px">{{ $cartCount }} item{{ $cartCount > 1 ? 's' : '' }} waiting</div>
                        <div style="font-size:.82rem;color:var(--muted)">Complete your purchase before stock runs out!</div>
                    </div>
                    <a href="{{ route('cart.index') }}" style="background:var(--fresh);color:#fff;padding:10px 20px;border-radius:10px;font-weight:700;font-size:.875rem;text-decoration:none;white-space:nowrap;flex-shrink:0">Go to Cart →</a>
                </div>
            </div>
            @endif
        </div>

        {{-- Right sidebar --}}
        <div>
            {{-- Quick actions --}}
            <div class="quick-card">
                <div class="quick-title">Quick Actions</div>
                <a href="{{ route('products.index') }}" class="quick-btn">
                    <div class="quick-btn-icon ocean">🐟</div>
                    <div>
                        <div>Browse Marketplace</div>
                        <div style="font-size:.75rem;font-weight:400;color:var(--muted)">Find fresh fish nearby</div>
                    </div>
                </a>
                <a href="{{ route('cart.index') }}" class="quick-btn">
                    <div class="quick-btn-icon gold">🛒</div>
                    <div>
                        <div>View Cart</div>
                        <div style="font-size:.75rem;font-weight:400;color:var(--muted)">{{ $cartCount }} item{{ $cartCount != 1 ? 's' : '' }} saved</div>
                    </div>
                    @if($cartCount > 0)
                        <span class="quick-btn-badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <a href="{{ route('orders.index') }}" class="quick-btn">
                    <div class="quick-btn-icon fresh">📦</div>
                    <div>
                        <div>My Orders</div>
                        <div style="font-size:.75rem;font-weight:400;color:var(--muted)">{{ $activeOrders }} active now</div>
                    </div>
                    @if($activeOrders > 0)
                        <span class="quick-btn-badge gold">{{ $activeOrders }}</span>
                    @endif
                </a>
                <a href="{{ route('profile.edit') }}" class="quick-btn">
                    <div class="quick-btn-icon" style="background:linear-gradient(135deg,var(--muted),#9aa8b3)">👤</div>
                    <div>
                        <div>My Profile</div>
                        <div style="font-size:.75rem;font-weight:400;color:var(--muted)">Update your details</div>
                    </div>
                </a>
            </div>

            {{-- Account summary --}}
            <div class="quick-card">
                <div class="quick-title">Account Summary</div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                    <div style="width:48px;height:48px;border-radius:50%;background:linear-gradient(135deg,var(--ocean),var(--tide));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.2rem">
                        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
                    </div>
                    <div>
                        <div style="font-weight:700;font-size:.95rem">{{ auth()->user()->name }}</div>
                        <div style="font-size:.78rem;color:var(--muted)">{{ auth()->user()->email }}</div>
                    </div>
                </div>
                <div style="display:flex;flex-direction:column;gap:8px">
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:8px 0;border-bottom:1px solid var(--foam)">
                        <span style="color:var(--muted)">Phone</span>
                        <span style="font-weight:600">{{ auth()->user()->phone ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:8px 0;border-bottom:1px solid var(--foam)">
                        <span style="color:var(--muted)">Location</span>
                        <span style="font-weight:600">{{ auth()->user()->location ?? '—' }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:.82rem;padding:8px 0">
                        <span style="color:var(--muted)">Member since</span>
                        <span style="font-weight:600">{{ auth()->user()->created_at->format('M Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- AquaBot tip --}}
            <div class="tip-card">
                <h4>🤖 Ask AquaBot</h4>
                <p>Not sure what's fresh today? Ask AquaBot about fish availability, prices, or track your delivery in real time.</p>
                <a href="#" class="tip-btn" onclick="document.getElementById('samaki-fab')?.click();return false">Open AquaBot</a>
            </div>
        </div>
    </div>
</div>


<footer style="background:#051e28;color:rgba(255,255,255,.5);text-align:center;padding:28px;font-size:.85rem">
    <p>© 2026 <span style="color:#f0a500">AquaHub</span> — Fresh fish, delivered fresh.</p>
</footer>

@include('partials.samakibot')
</body>
</html>
