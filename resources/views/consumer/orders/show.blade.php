<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order #{{ $order->id }} — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        :root{--ocean:#0a3d4f;--tide:#0d6e8a;--foam:#e8f4f8;--coral:#e85d3a;--gold:#f0a500;--fresh:#2eb87a;--muted:#6b7d8a;--border:#dde8ed}
        body{font-family:'DM Sans',sans-serif;background:var(--foam);color:#1a1a2e}
        .sh-nav{background:var(--ocean);padding:0 2rem;display:flex;align-items:center;justify-content:space-between;height:64px;position:sticky;top:0;z-index:100;box-shadow:0 2px 20px rgba(0,0,0,.3)}
        .sh-logo{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:#fff;text-decoration:none}
        .sh-logo span{color:var(--gold)}
        .sh-nav a{color:rgba(255,255,255,.75);font-size:.875rem;font-weight:500;padding:7px 13px;border-radius:8px;text-decoration:none;transition:all .2s}
        .sh-nav a:hover{background:rgba(255,255,255,.12);color:#fff}
        .sh-role{background:var(--gold);color:#051e28;font-size:.75rem;font-weight:700;padding:4px 10px;border-radius:20px}

        .breadcrumb{font-size:.85rem;color:var(--muted);padding:14px 2rem;max-width:1000px;margin:0 auto}
        .breadcrumb a{color:var(--tide);text-decoration:none}

        .wrap{max-width:1000px;margin:0 auto;padding:0 2rem 60px}
        .layout{display:grid;grid-template-columns:1fr 300px;gap:24px;align-items:start}
        @media(max-width:768px){.layout{grid-template-columns:1fr}}

        .card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:20px}
        .card-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--ocean);margin-bottom:18px;padding-bottom:10px;border-bottom:2px solid var(--foam)}

        /* Status pill */
        .status-pill{padding:5px 14px;border-radius:20px;font-size:.8rem;font-weight:700;text-transform:uppercase;display:inline-block}
        .s-confirmed {background:#d4eeff;color:#0057a8}
        .s-preparing  {background:#fff3d0;color:#a07000}
        .s-in_transit {background:#e0f0ff;color:#0057a8}
        .s-delivered  {background:#d4f7e7;color:#1a7a4a}
        .s-cancelled  {background:#fde8e8;color:#a81a1a}

        /* Step tracker — large */
        .tracker-lg{display:flex;align-items:flex-start;gap:0;margin:8px 0 4px}
        .step-lg{display:flex;flex-direction:column;align-items:center;flex:1;position:relative}
        .step-lg::after{content:'';position:absolute;top:18px;left:50%;width:100%;height:3px;background:var(--border);z-index:0}
        .step-lg:last-child::after{display:none}
        .step-lg.done::after{background:var(--fresh)}
        .step-dot-lg{width:36px;height:36px;border-radius:50%;z-index:1;position:relative;display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;border:3px solid #fff;flex-shrink:0;box-shadow:0 2px 8px rgba(0,0,0,.12)}
        .step-dot-lg.done{background:var(--fresh);color:#fff}
        .step-dot-lg.active{background:var(--gold);color:#fff;animation:pulse 1.5s infinite}
        .step-dot-lg.idle{background:#e5e7eb;color:var(--muted)}
        .step-label-lg{font-size:.78rem;color:var(--muted);margin-top:8px;text-align:center;font-weight:600}
        .step-label-lg.done{color:var(--fresh)}
        .step-label-lg.active{color:var(--gold)}
        @keyframes pulse{0%,100%{box-shadow:0 0 0 0 rgba(240,165,0,.5)}50%{box-shadow:0 0 0 8px rgba(240,165,0,0)}}

        /* Map */
        #order-map{height:240px;border-radius:14px;margin-bottom:20px;z-index:1;border:1px solid var(--border)}
        .map-inactive{background:linear-gradient(135deg,var(--ocean) 0%,var(--tide) 100%);border-radius:14px;height:200px;display:flex;flex-direction:column;align-items:center;justify-content:center;color:rgba(255,255,255,.8);gap:10px;margin-bottom:20px}
        .map-inactive .icon{font-size:2.5rem}
        .map-inactive p{font-size:.85rem;text-align:center;max-width:220px;line-height:1.5}
        .map-badge{font-size:.75rem;font-weight:700;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.3);border-radius:20px;padding:4px 12px;margin-top:4px}

        /* Order items table */
        .items-table{width:100%;border-collapse:collapse}
        .items-table th{text-align:left;padding:8px 12px;font-size:.75rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:.4px;background:var(--foam)}
        .items-table td{padding:12px;font-size:.9rem;border-bottom:1px solid var(--foam)}
        .items-table tr:last-child td{border-bottom:none}
        .item-emoji-sm{width:36px;height:36px;background:linear-gradient(135deg,var(--ocean),var(--tide));border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:1.1rem}

        /* Details */
        .detail-row{display:flex;justify-content:space-between;padding:9px 0;border-bottom:1px solid var(--foam);font-size:.9rem}
        .detail-row:last-child{border-bottom:none}
        .detail-label{color:var(--muted)}
        .detail-value{font-weight:600;text-align:right;max-width:60%}
        .total-row{font-weight:700;font-size:1rem;color:var(--ocean);padding-top:12px;margin-top:4px}

        /* Agent card */
        .agent-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:20px;text-align:center;margin-top:20px}
        .agent-avatar{width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--ocean),var(--tide));display:flex;align-items:center;justify-content:center;font-size:1.6rem;margin:0 auto 12px}
        .agent-name{font-family:'Playfair Display',serif;font-weight:700;font-size:1rem;margin-bottom:4px}
        .agent-phone{font-size:.85rem;color:var(--tide);font-weight:600}

        .alert-s{background:#d4f7e7;border:1px solid #1a7a4a;color:#1a7a4a;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-weight:500}
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
        <a href="{{ route('orders.index') }}">My Orders</a>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
        <span class="sh-role">{{ ucfirst(auth()->user()->role) }}</span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" style="background:rgba(255,255,255,.15);border:none;color:#fff;padding:7px 14px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600">Logout</button>
        </form>
    </div>
</nav>

<div class="breadcrumb">
    <a href="{{ route('orders.index') }}">← My Orders</a> / Order #{{ $order->id }}
</div>

<div class="wrap">
    @if(session('success'))<div class="alert-s">✅ {{ session('success') }}</div>@endif

    <div class="layout">
        {{-- LEFT --}}
        <div>
            {{-- Tracking card --}}
            <div class="card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px">
                    <div>
                        <div class="card-title" style="margin-bottom:4px;border:none;padding:0">Order #{{ $order->id }} Tracking</div>
                        <div style="font-size:.8rem;color:var(--muted)">Placed {{ $order->created_at->format('d M Y, H:i') }}</div>
                    </div>
                    <span class="status-pill s-{{ $order->status }}">{{ $order->status_label }}</span>
                </div>

                @php
                    $steps    = \App\Models\Order::STEPS;
                    $stepIdx  = $order->step_index;
                    $cancelled = $order->status === 'cancelled';
                @endphp

                @if(! $cancelled)
                    <div class="tracker-lg">
                        @foreach($steps as $i => $step)
                            @php
                                $state = $i < $stepIdx ? 'done' : ($i === $stepIdx ? 'active' : 'idle');
                            @endphp
                            <div class="step-lg {{ $state }}">
                                <div class="step-dot-lg {{ $state }}">
                                    {{ $i < $stepIdx ? '✓' : ($i + 1) }}
                                </div>
                                <div class="step-label-lg {{ $state }}">{{ \App\Models\Order::STATUS_LABELS[$step] }}</div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="background:#fde8e8;border-radius:10px;padding:14px 16px;color:#a81a1a;font-size:.9rem;font-weight:600">
                        ❌ This order was cancelled.
                    </div>
                @endif
            </div>

            {{-- Live Map (Always visible) --}}
            <div style="position:relative">
                <div id="order-map"></div>
                <div id="map-live-badge" style="position:absolute;top:10px;left:50%;transform:translateX(-50%);z-index:999;background:#0a3d4f;color:#fff;font-size:.72rem;font-weight:700;padding:4px 12px;border-radius:20px;letter-spacing:.4px;pointer-events:none;display:flex;align-items:center;gap:6px">
                    <span style="display:inline-flex;align-items:center;gap:3px"><span style="width:6px;height:6px;background:#ff4444;border-radius:50%;animation:pulse-red 1.5s infinite"></span>LIVE</span>
                </div>
            </div>

            {{-- Order items --}}
            <div class="card">
                <div class="card-title">Order Items ({{ $order->items->count() }})</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th style="width:40px"></th>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Unit Price</th>
                            <th style="text-align:right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td><div class="item-emoji-sm">🐟</div></td>
                                <td>
                                    <div style="font-weight:700;font-size:.9rem">{{ $item->product->name ?? 'Product' }}</div>
                                    @if($item->product)
                                        <div style="font-size:.77rem;color:var(--muted)">🎣 {{ $item->product->producer->name ?? '' }}</div>
                                    @endif
                                </td>
                                <td style="font-weight:600">{{ $item->quantity_kg }} kg</td>
                                <td style="color:var(--muted)">KES {{ number_format($item->unit_price, 0) }}</td>
                                <td style="text-align:right;font-weight:700;color:var(--ocean)">KES {{ number_format($item->subtotal, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- RIGHT --}}
        <div>
            {{-- Order summary --}}
            <div class="card">
                <div class="card-title">Summary</div>
                <div class="detail-row">
                    <span class="detail-label">Subtotal</span>
                    <span class="detail-value">KES {{ number_format($order->total_amount - $order->delivery_fee, 0) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Delivery fee</span>
                    <span class="detail-value">KES {{ number_format($order->delivery_fee, 0) }}</span>
                </div>
                <div class="detail-row total-row">
                    <span>Total Paid</span>
                    <span>KES {{ number_format($order->total_amount, 0) }}</span>
                </div>
            </div>

            {{-- Delivery details --}}
            <div class="card">
                <div class="card-title">Delivery Details</div>
                <div class="detail-row">
                    <span class="detail-label">Address</span>
                    <span class="detail-value">{{ $order->delivery_address }}</span>
                </div>
                @if($order->delivery_notes)
                    <div class="detail-row">
                        <span class="detail-label">Notes</span>
                        <span class="detail-value">{{ $order->delivery_notes }}</span>
                    </div>
                @endif
                <div class="detail-row">
                    <span class="detail-label">Ordered</span>
                    <span class="detail-value">{{ $order->created_at->format('d M Y') }}</span>
                </div>
            </div>

            {{-- Delivery agent --}}
            @if($order->deliveryAgent)
                <div class="agent-card">
                    <div style="font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:12px">Your Delivery Agent</div>
                    <div class="agent-avatar">🛵</div>
                    <div class="agent-name">{{ $order->deliveryAgent->name }}</div>
                    @if($order->deliveryAgent->phone)
                        <div class="agent-phone">📞 {{ $order->deliveryAgent->phone }}</div>
                    @endif
                    <div style="font-size:.78rem;color:var(--muted);margin-top:6px">📍 {{ $order->deliveryAgent->location ?? 'Kenya' }}</div>
                </div>
            @else
                <div class="agent-card" style="background:var(--foam)">
                    <div style="font-size:2rem;margin-bottom:8px">🔍</div>
                    <div style="font-size:.9rem;color:var(--muted);font-weight:600">Finding a delivery agent…</div>
                    <div style="font-size:.8rem;color:var(--muted);margin-top:4px">You'll be notified once one is assigned.</div>
                </div>
            @endif
        </div>
    </div>
</div>

<footer style="background:#051e28;color:rgba(255,255,255,.5);text-align:center;padding:28px;font-size:.85rem">
    <p>© 2026 <span style="color:#f0a500">AquaHub</span></p>
</footer>
@include('partials.samakibot')

<style>
@keyframes pulse-red {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.6; }
}
</style>
<script>
(function () {
    // Default centre: Nairobi CBD
    const DEFAULT_LAT = {{ $order->latestLocation ? $order->latestLocation->latitude : -1.2921 }};
    const DEFAULT_LNG = {{ $order->latestLocation ? $order->latestLocation->longitude : 36.8219 }};
    const ORDER_ID    = {{ $order->id }};
    const LOCATION_URL = '{{ route('orders.location.latest', $order->id) }}';

    const map = L.map('order-map').setView([DEFAULT_LAT, DEFAULT_LNG], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        maxZoom: 19,
    }).addTo(map);

    // Custom delivery agent icon
    const agentIcon = L.divIcon({
        html: '<div style="font-size:2rem;filter:drop-shadow(0 2px 4px rgba(0,0,0,.4))">🛵</div>',
        className: '',
        iconSize: [36, 36],
        iconAnchor: [18, 18],
        popupAnchor: [0, -20],
    });

    @if($order->latestLocation)
    let agentMarker = L.marker([DEFAULT_LAT, DEFAULT_LNG], { icon: agentIcon })
        .addTo(map)
        .bindPopup('<strong>Delivery Agent</strong><br>{{ $order->deliveryAgent->name ?? "Agent" }}<br><small>Updated {{ $order->latestLocation->created_at->diffForHumans() }}</small>')
        .openPopup();
    @else
    let agentMarker = null;
    @endif

    // Poll for live updates every 30 seconds (always active)
    setInterval(async () => {
        try {
            const res  = await fetch(LOCATION_URL, { credentials: 'same-origin', headers: { Accept: 'application/json' } });
            const data = await res.json();
            if (!data.found) return;

            const latlng = [data.latitude, data.longitude];
            const popup  = `<strong>Delivery Agent</strong><br>{{ $order->deliveryAgent->name ?? "Agent" }}<br><small>Updated ${data.updated_at}</small>`;

            if (agentMarker) {
                agentMarker.setLatLng(latlng).getPopup().setContent(popup);
            } else {
                agentMarker = L.marker(latlng, { icon: agentIcon }).addTo(map).bindPopup(popup).openPopup();
            }
            map.panTo(latlng);
        } catch (_) {}
    }, 30000);
})();
</script>

</body>
</html>
