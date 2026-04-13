<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producer Dashboard — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--ocean:#0a3d4f;--tide:#0d6e8a;--foam:#e8f4f8;--coral:#e85d3a;--gold:#f0a500;--fresh:#2eb87a;--muted:#6b7d8a;--border:#dde8ed}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:var(--foam);color:#1a1a2e}

        /* Nav */
        .sh-nav{background:var(--ocean);padding:0 2rem;display:flex;align-items:center;justify-content:space-between;height:64px;position:sticky;top:0;z-index:100;box-shadow:0 2px 20px rgba(0,0,0,.3)}
        .sh-logo{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:#fff;text-decoration:none}
        .sh-logo span{color:var(--gold)}
        .sh-nav-links{display:flex;align-items:center;gap:4px}
        .sh-nav-links a{color:rgba(255,255,255,.75);font-size:.875rem;font-weight:500;padding:7px 13px;border-radius:8px;text-decoration:none;transition:all .2s}
        .sh-nav-links a:hover,.sh-nav-links a.active{background:rgba(255,255,255,.12);color:#fff}
        .sh-nav-right{display:flex;align-items:center;gap:12px}
        .sh-role{background:var(--fresh);color:#fff;font-size:.75rem;font-weight:700;padding:4px 10px;border-radius:20px}
        .sh-avatar{width:36px;height:36px;border-radius:50%;background:var(--tide);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;border:2px solid rgba(255,255,255,.25)}

        /* Page header */
        .page-header{background:#fff;border-bottom:1px solid var(--border);padding:22px 2rem}
        .page-header h2{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--ocean);margin-bottom:4px}
        .page-header p{color:var(--muted);font-size:.9rem}

        /* Layout */
        .wrap{max-width:1200px;margin:0 auto;padding:36px 2rem 80px}
        .main-grid{display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:start}
        @media(max-width:960px){.main-grid{grid-template-columns:1fr}}

        /* Stat cards */
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

        /* Cards */
        .card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:20px}
        .card-header{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
        .card-title{font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--ocean)}
        .card-link{font-size:.82rem;color:var(--tide);font-weight:600;text-decoration:none}
        .card-link:hover{color:var(--ocean)}

        /* Recent orders table */
        .order-row{display:flex;align-items:center;gap:12px;padding:14px 20px;border-bottom:1px solid var(--foam);transition:background .15s}
        .order-row:last-child{border-bottom:none}
        .order-row:hover{background:var(--foam)}
        .order-product{font-weight:600;font-size:.9rem;color:#1a1a2e;margin-bottom:2px}
        .order-meta{font-size:.78rem;color:var(--muted)}
        .order-amount{font-weight:700;font-size:.95rem;color:var(--ocean);white-space:nowrap}

        /* Status badges */
        .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px}
        .badge-confirmed{background:#dbeafe;color:#1e40af}
        .badge-preparing{background:#fef3c7;color:#92400e}
        .badge-in_transit{background:#ede9fe;color:#5b21b6}
        .badge-delivered{background:#d1fae5;color:#065f46}
        .badge-pending_payment{background:#fee2e2;color:#991b1b}
        .badge-cancelled{background:#f3f4f6;color:#374151}

        /* Products list */
        .product-row{display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--foam)}
        .product-row:last-child{border-bottom:none}
        .product-thumb{width:44px;height:44px;border-radius:10px;background:linear-gradient(135deg,var(--ocean),var(--tide));display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0;overflow:hidden}
        .product-thumb img{width:100%;height:100%;object-fit:cover}
        .product-name{font-weight:600;font-size:.88rem;color:#1a1a2e;margin-bottom:2px}
        .product-detail{font-size:.76rem;color:var(--muted)}
        .stock-ok{color:var(--fresh);font-weight:700}
        .stock-low{color:var(--coral);font-weight:700}
        .avail-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0}
        .avail-dot.on{background:var(--fresh)}
        .avail-dot.off{background:var(--coral)}

        /* Sidebar */
        .sidebar-card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:20px;margin-bottom:18px}
        .sidebar-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--ocean);margin-bottom:16px}
        .action-btn{display:flex;align-items:center;gap:10px;padding:11px 14px;border-radius:10px;text-decoration:none;font-size:.875rem;font-weight:600;transition:all .2s;margin-bottom:8px;border:none;cursor:pointer;width:100%;font-family:'DM Sans',sans-serif}
        .action-btn:last-child{margin-bottom:0}
        .action-btn.primary{background:var(--ocean);color:#fff}
        .action-btn.primary:hover{background:var(--tide)}
        .action-btn.success{background:var(--fresh);color:#fff}
        .action-btn.success:hover{background:#25a06a}
        .action-btn.warning{background:#fff7e6;color:#92400e;border:1px solid #fde68a}
        .action-btn.warning:hover{background:#fef3c7}
        .action-btn.outline{background:#fff;color:var(--ocean);border:1px solid var(--border)}
        .action-btn.outline:hover{background:var(--foam)}
        .action-icon{width:32px;height:32px;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1rem;flex-shrink:0}

        /* Profile card */
        .profile-avatar{width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,var(--ocean),var(--tide));display:flex;align-items:center;justify-content:center;font-size:1.8rem;margin:0 auto 12px;border:3px solid var(--foam)}
        .divider{border:none;border-top:1px solid var(--border);margin:14px 0}
        .info-row{display:flex;justify-content:space-between;font-size:.82rem;padding:5px 0}
        .info-label{color:var(--muted)}
        .info-value{font-weight:600;color:#1a1a2e}

        /* Question alert */
        .question-alert{background:#fff7e6;border:1px solid #fde68a;border-radius:12px;padding:14px 16px;display:flex;align-items:center;gap:12px;margin-bottom:20px;text-decoration:none}
        .question-alert:hover{background:#fef3c7}
        .question-alert-text{font-size:.875rem;color:#92400e;font-weight:600}
        .question-alert-sub{font-size:.78rem;color:#b45309;margin-top:2px}

        .empty-state{text-align:center;padding:36px 20px;color:var(--muted);font-size:.9rem}
    </style>
</head>
<body>

<nav class="sh-nav">
    <a href="{{ url('/') }}" class="sh-logo">🐟 Aqua<span>Hub</span></a>
    <div class="sh-nav-links">
        <a href="{{ route('producer.dashboard') }}" class="active">Dashboard</a>
        <a href="{{ route('products.index') }}">Marketplace</a>
        <a href="{{ route('products.create') }}">+ List Fish</a>
    </div>
    <div class="sh-nav-right">
        <span class="sh-role">Producer</span>
        <div class="sh-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" style="background:rgba(255,255,255,.15);border:none;color:#fff;padding:7px 14px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600">Logout</button>
        </form>
    </div>
</nav>

<div class="page-header">
    <h2>Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }} 👋</h2>
    <p>Here's how your fish business is doing today.</p>
</div>

<div class="wrap">

    {{-- Unanswered questions alert --}}
    @if($pendingQuestions > 0)
        <a href="{{ route('products.show', $firstUnansweredProductId) }}" class="question-alert">
            <span style="font-size:1.5rem">❓</span>
            <div>
                <div class="question-alert-text">{{ $pendingQuestions }} unanswered {{ $pendingQuestions === 1 ? 'question' : 'questions' }} from customers</div>
                <div class="question-alert-sub">Respond quickly to increase your sales</div>
            </div>
            <span style="margin-left:auto;color:#b45309;font-size:1.1rem">→</span>
        </a>
    @endif

    {{-- Stat cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon ocean">🐟</div>
            <div>
                <div class="stat-label">Total Listings</div>
                <div class="stat-value">{{ $totalProducts }}</div>
                <div class="stat-sub">{{ $activeProducts }} active</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon fresh">✅</div>
            <div>
                <div class="stat-label">Active Listings</div>
                <div class="stat-value">{{ $activeProducts }}</div>
                <div class="stat-sub">Available to buyers</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon gold">💰</div>
            <div>
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value" style="font-size:1.4rem">KES {{ number_format($totalRevenue, 0) }}</div>
                <div class="stat-sub">From delivered orders</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon coral">❓</div>
            <div>
                <div class="stat-label">Pending Q&A</div>
                <div class="stat-value">{{ $pendingQuestions }}</div>
                <div class="stat-sub">Need your response</div>
            </div>
        </div>
    </div>

    <div class="main-grid">
        {{-- LEFT --}}
        <div>
            {{-- Recent orders --}}
            <div class="card">
                <div class="card-header">
                    <span class="card-title">🛒 Recent Orders</span>
                </div>
                @forelse($recentOrders as $item)
                    <div class="order-row">
                        <div style="flex:1;min-width:0">
                            <div class="order-product">{{ $item->product->name ?? 'Product' }}</div>
                            <div class="order-meta">
                                👤 {{ $item->order->consumer->name ?? 'Consumer' }}
                                · {{ number_format($item->quantity_kg, 1) }} kg
                                · {{ $item->order->created_at->diffForHumans() }}
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:5px">
                            <div class="order-amount">KES {{ number_format($item->subtotal, 0) }}</div>
                            <span class="badge badge-{{ $item->order->status }}">
                                {{ ucfirst(str_replace('_', ' ', $item->order->status)) }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">No orders yet. List your fish to start selling!</div>
                @endforelse
            </div>

            {{-- My products --}}
            @php
                $myProducts = \App\Models\Product::where('user_id', auth()->id())->latest()->get();
            @endphp
            <div class="card">
                <div class="card-header">
                    <span class="card-title">🐟 My Listings ({{ $myProducts->count() }})</span>
                    <a href="{{ route('products.create') }}" class="card-link">+ Add New</a>
                </div>
                @forelse($myProducts as $product)
                    <div class="product-row">
                        <div class="product-thumb">
                            @if($product->image)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            @else
                                🐟
                            @endif
                        </div>
                        <div style="flex:1;min-width:0">
                            <div class="product-name">{{ $product->name }}</div>
                            <div class="product-detail">
                                KES {{ number_format($product->price_per_kg, 0) }}/kg
                                · <span class="{{ $product->stock_kg < 5 ? 'stock-low' : 'stock-ok' }}">{{ number_format($product->stock_kg, 1) }} kg</span>
                                · {{ $product->freshness_label }}
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px">
                            <div class="avail-dot {{ $product->is_available ? 'on' : 'off' }}" title="{{ $product->is_available ? 'Available' : 'Unavailable' }}"></div>
                            <a href="{{ route('products.edit', $product->id) }}" style="font-size:.8rem;color:var(--tide);font-weight:600;text-decoration:none;padding:4px 10px;border-radius:6px;border:1px solid var(--border)">Edit</a>
                            <a href="{{ route('products.show', $product->id) }}" style="font-size:.8rem;color:var(--muted);font-weight:600;text-decoration:none;padding:4px 10px;border-radius:6px;border:1px solid var(--border)">View</a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        No listings yet.
                        <a href="{{ route('products.create') }}" style="color:var(--tide);font-weight:600">List your first fish →</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- RIGHT SIDEBAR --}}
        <div>
            {{-- Quick actions --}}
            <div class="sidebar-card">
                <div class="sidebar-title">Quick Actions</div>
                <a href="{{ route('products.create') }}" class="action-btn success">
                    <div class="action-icon" style="background:rgba(255,255,255,.25)">🐟</div>
                    List New Fish
                </a>
                <a href="{{ route('products.index') }}" class="action-btn outline">
                    <div class="action-icon" style="background:var(--foam)">🛒</div>
                    View Marketplace
                </a>
                @if($pendingQuestions > 0)
                    <a href="{{ route('products.show', $firstUnansweredProductId) }}" class="action-btn warning">
                        <div class="action-icon" style="background:rgba(254,243,199,.6)">❓</div>
                        Answer Questions ({{ $pendingQuestions }})
                    </a>
                @endif
                <a href="{{ route('producer.payouts') }}" class="action-btn outline">
                    <div class="action-icon" style="background:var(--foam)">💸</div>
                    Request Payout
                </a>
                <a href="{{ route('profile.edit') }}" class="action-btn outline">
                    <div class="action-icon" style="background:var(--foam)">⚙️</div>
                    Edit Profile
                </a>
            </div>

            {{-- Profile summary --}}
            <div class="sidebar-card">
                <div class="profile-avatar">🎣</div>
                <div style="text-align:center;margin-bottom:14px">
                    <div style="font-family:'Playfair Display',serif;font-weight:700;font-size:1rem;margin-bottom:2px">{{ auth()->user()->name }}</div>
                    <div style="font-size:.8rem;color:var(--muted)">{{ auth()->user()->email }}</div>
                </div>
                <hr class="divider">
                <div class="info-row">
                    <span class="info-label">Phone</span>
                    <span class="info-value">{{ auth()->user()->phone ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Location</span>
                    <span class="info-value">{{ auth()->user()->location ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Member since</span>
                    <span class="info-value">{{ auth()->user()->created_at->format('M Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value" style="color:var(--fresh)">● Active</span>
                </div>
            </div>

            {{-- Revenue tip --}}
            <div style="background:linear-gradient(135deg,var(--ocean),var(--tide));border-radius:14px;padding:18px;color:#fff">
                <div style="font-size:1.1rem;margin-bottom:6px">💡 Tip</div>
                <div style="font-size:.82rem;line-height:1.6;opacity:.9">Add photos and detailed descriptions to your listings — they get <strong>3x more orders</strong> than listings without images.</div>
                <a href="{{ route('products.create') }}" style="display:inline-block;margin-top:10px;background:rgba(255,255,255,.2);color:#fff;font-size:.8rem;font-weight:700;padding:6px 14px;border-radius:8px;text-decoration:none">Add a listing →</a>
            </div>
        </div>
    </div>
</div>

<footer style="background:#051e28;color:rgba(255,255,255,.5);text-align:center;padding:28px;font-size:.85rem">
    <p>© 2026 <span style="color:#f0a500">AquaHub</span> — Empowering Kenya's Fishing Community 🐟</p>
</footer>

@include('partials.samakibot')
</body>
</html>
