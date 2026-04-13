<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--ocean:#0a3d4f;--tide:#0d6e8a;--foam:#e8f4f8;--coral:#e85d3a;--gold:#f0a500;--fresh:#2eb87a;--muted:#6b7d8a;--border:#dde8ed}
        body{font-family:'DM Sans',sans-serif;background:var(--foam);color:#1a1a2e}
        .sh-nav{background:var(--ocean);padding:0 2rem;display:flex;align-items:center;justify-content:space-between;height:64px;position:sticky;top:0;z-index:100;box-shadow:0 2px 20px rgba(0,0,0,.3)}
        .sh-logo{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:#fff;text-decoration:none}
        .sh-logo span{color:var(--gold)}
        .sh-nav a{color:rgba(255,255,255,.75);font-size:.875rem;font-weight:500;padding:7px 13px;border-radius:8px;text-decoration:none;transition:all .2s}
        .sh-nav a:hover,.sh-nav a.active{background:rgba(255,255,255,.12);color:#fff}
        .sh-role{background:var(--gold);color:#051e28;font-size:.75rem;font-weight:700;padding:4px 10px;border-radius:20px}
        .page-header{background:#fff;border-bottom:1px solid var(--border);padding:22px 2rem}
        .page-header h2{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--ocean);margin-bottom:4px}
        .page-header p{color:var(--muted);font-size:.9rem}
        .wrap{max-width:1000px;margin:0 auto;padding:36px 2rem 60px}

        /* Order card */
        .order-card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:20px 24px;margin-bottom:16px;display:flex;gap:18px;align-items:flex-start;flex-wrap:wrap;text-decoration:none;color:inherit;transition:transform .2s,box-shadow .2s;display:block}
        .order-card:hover{transform:translateY(-2px);box-shadow:0 8px 24px rgba(10,61,79,.1)}
        .order-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px}
        .order-id{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--ocean)}
        .order-date{font-size:.8rem;color:var(--muted)}
        .order-amount{font-weight:700;font-size:1.1rem;color:var(--ocean)}

        /* Status pill */
        .status-pill{padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.3px}
        .s-confirmed {background:#d4eeff;color:#0057a8}
        .s-preparing  {background:#fff3d0;color:#a07000}
        .s-in_transit {background:#e0f0ff;color:#0057a8}
        .s-delivered  {background:#d4f7e7;color:#1a7a4a}
        .s-cancelled  {background:#fde8e8;color:#a81a1a}

        /* Items summary */
        .items-row{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:16px}
        .item-chip{background:var(--foam);border:1px solid var(--border);border-radius:8px;padding:5px 10px;font-size:.8rem;font-weight:500;color:#1a1a2e}

        /* Compact step tracker */
        .tracker{display:flex;align-items:center;gap:0}
        .step{display:flex;flex-direction:column;align-items:center;flex:1;position:relative}
        .step::after{content:'';position:absolute;top:11px;left:50%;width:100%;height:2px;background:var(--border);z-index:0}
        .step:last-child::after{display:none}
        .step-dot{width:22px;height:22px;border-radius:50%;z-index:1;position:relative;display:flex;align-items:center;justify-content:center;font-size:.65rem;font-weight:700;border:2px solid #fff;flex-shrink:0}
        .step-dot.done{background:var(--fresh);color:#fff}
        .step-dot.active{background:var(--gold);color:#fff;animation:pulse 1.5s infinite}
        .step-dot.idle{background:var(--border);color:var(--muted)}
        .step-dot.cancelled{background:var(--coral);color:#fff}
        .step-label{font-size:.65rem;color:var(--muted);margin-top:5px;text-align:center;line-height:1.2}
        @keyframes pulse{0%,100%{box-shadow:0 0 0 0 rgba(240,165,0,.5)}50%{box-shadow:0 0 0 6px rgba(240,165,0,0)}}

        .view-link{font-size:.85rem;color:var(--tide);font-weight:600;text-decoration:none}
        .view-link:hover{color:var(--ocean)}

        .alert-s{background:#d4f7e7;border:1px solid #1a7a4a;color:#1a7a4a;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-weight:500}
        .empty-state{text-align:center;padding:60px 20px;color:var(--muted)}
        .empty-state .ei{font-size:4rem;margin-bottom:16px}
        .empty-state h3{font-family:'Playfair Display',serif;color:var(--ocean);font-size:1.4rem;margin-bottom:8px}
        .shop-btn{display:inline-block;background:var(--ocean);color:#fff;padding:12px 24px;border-radius:10px;text-decoration:none;font-weight:700;margin-top:16px}
    </style>
</head>
<body>
<nav class="sh-nav">
    <a href="{{ url('/') }}" class="sh-logo">🐟 Aqua<span>Hub</span></a>
    <div style="display:flex;gap:6px">
        @php $navCartCount = \App\Models\Cart::where('user_id', auth()->id())->count(); @endphp
        <a href="{{ route('products.index') }}">Shop</a>
        <a href="{{ route('cart.index') }}" style="position:relative">
            🛒 Cart
            @if($navCartCount > 0)
                <span style="background:#e85d3a;color:#fff;font-size:.6rem;font-weight:700;padding:1px 5px;border-radius:10px;margin-left:3px">{{ $navCartCount }}</span>
            @endif
        </a>
        <a href="{{ route('orders.index') }}" class="active">My Orders</a>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
        <span class="sh-role">Consumer</span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" style="background:rgba(255,255,255,.15);border:none;color:#fff;padding:7px 14px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600">Logout</button>
        </form>
    </div>
</nav>

<div class="page-header">
    <h2>My Orders</h2>
    <p>Track your deliveries in real-time</p>
</div>

<div class="wrap">
    @if(session('success'))<div class="alert-s">✅ {{ session('success') }}</div>@endif

    @forelse($orders as $order)
        @php
            $steps    = \App\Models\Order::STEPS;
            $stepIdx  = $order->step_index;
            $cancelled = $order->status === 'cancelled';
        @endphp
        <div class="order-card">
            <div class="order-top">
                <div>
                    <div class="order-id">Order #{{ $order->id }}</div>
                    <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div style="display:flex;align-items:center;gap:12px">
                    <span class="status-pill s-{{ $order->status }}">{{ $order->status_label }}</span>
                    <div class="order-amount">KES {{ number_format($order->total_amount, 0) }}</div>
                </div>
            </div>

            {{-- Items --}}
            <div class="items-row">
                @foreach($order->items->take(4) as $item)
                    <span class="item-chip">
                        🐟 {{ $item->product->name ?? 'Product' }} × {{ $item->quantity_kg }}kg
                    </span>
                @endforeach
                @if($order->items->count() > 4)
                    <span class="item-chip" style="background:transparent;border-style:dashed">+{{ $order->items->count() - 4 }} more</span>
                @endif
            </div>

            {{-- Step tracker --}}
            @if(! $cancelled)
                <div class="tracker" style="margin-bottom:14px">
                    @foreach($steps as $i => $step)
                        <div class="step">
                            <div class="step-dot {{ $i < $stepIdx ? 'done' : ($i === $stepIdx ? 'active' : 'idle') }}">
                                {{ $i < $stepIdx ? '✓' : ($i + 1) }}
                            </div>
                            <div class="step-label">{{ \App\Models\Order::STATUS_LABELS[$step] }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="font-size:.85rem;color:var(--coral);font-weight:600;margin-bottom:14px">❌ This order was cancelled.</div>
            @endif

            <a href="{{ route('orders.show', $order->id) }}" class="view-link">View details →</a>
        </div>
    @empty
        <div class="empty-state">
            <div class="ei">📦</div>
            <h3>No orders yet</h3>
            <p>Place your first order from the marketplace.</p>
            <a href="{{ route('products.index') }}" class="shop-btn">Shop Fresh Fish</a>
        </div>
    @endforelse

    {{ $orders->links() }}
</div>

<footer style="background:#051e28;color:rgba(255,255,255,.5);text-align:center;padding:28px;font-size:.85rem">
    <p>© 2026 <span style="color:#f0a500">AquaHub</span></p>
</footer>
@include('partials.samakibot')
</body>
</html>
