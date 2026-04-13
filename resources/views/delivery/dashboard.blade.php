<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Dashboard — AquaHub</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        .page-header{background:#fff;border-bottom:1px solid var(--border);padding:22px 2rem}
        .page-header h2{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--ocean);margin-bottom:4px}
        .page-header p{color:var(--muted);font-size:.9rem}
        .wrap{max-width:1100px;margin:0 auto;padding:36px 2rem 60px}

        /* Stats bar */
        .stats-row{display:flex;gap:16px;margin-bottom:32px;flex-wrap:wrap}
        .stat-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:18px 22px;flex:1;min-width:140px}
        .stat-num{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--ocean)}
        .stat-label{font-size:.78rem;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.5px}

        /* Section title */
        .section-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--ocean);margin-bottom:16px;display:flex;align-items:center;gap:10px}
        .section-badge{background:var(--coral);color:#fff;font-size:.72rem;font-weight:700;padding:3px 9px;border-radius:20px;font-family:'DM Sans',sans-serif}
        .section-badge.green{background:var(--fresh)}
        .section-badge.gold{background:var(--gold);color:#051e28}

        /* Order row */
        .order-row{background:#fff;border:1px solid var(--border);border-radius:14px;padding:18px 20px;margin-bottom:12px}
        .order-row-top{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;flex-wrap:wrap}
        .order-id-area .oid{font-family:'Playfair Display',serif;font-weight:700;color:var(--ocean);font-size:1rem}
        .order-id-area .meta{font-size:.8rem;color:var(--muted);margin-top:2px}
        .order-amount{font-size:1.1rem;font-weight:700;color:var(--ocean)}

        /* Status pill */
        .status-pill{padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:700;text-transform:uppercase}
        .s-confirmed {background:#d4eeff;color:#0057a8}
        .s-preparing  {background:#fff3d0;color:#a07000}
        .s-in_transit {background:#e8f0fe;color:#1a73e8}
        .s-delivered  {background:#d4f7e7;color:#1a7a4a}
        .s-cancelled  {background:#fde8e8;color:#a81a1a}

        /* Items chips */
        .items-row{display:flex;gap:6px;flex-wrap:wrap;margin:10px 0}
        .item-chip{background:var(--foam);border:1px solid var(--border);border-radius:7px;padding:4px 9px;font-size:.78rem;font-weight:500}

        /* Address */
        .address-row{font-size:.82rem;color:var(--muted);margin-bottom:12px;display:flex;align-items:flex-start;gap:5px}

        /* Action buttons */
        .actions{display:flex;gap:8px;flex-wrap:wrap;margin-top:2px}
        .btn{display:inline-flex;align-items:center;gap:5px;padding:8px 16px;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:700;cursor:pointer;border:none;text-decoration:none;transition:all .15s}
        .btn-advance{background:var(--fresh);color:#fff}
        .btn-advance:hover{background:#25a06a}
        .btn-claim{background:var(--ocean);color:#fff}
        .btn-claim:hover{background:var(--tide)}
        .btn-view{background:var(--foam);color:var(--tide);border:1px solid var(--border)}
        .btn-view:hover{background:var(--tide);color:#fff}
        .btn-loc{background:rgba(13,110,138,.1);color:var(--tide);border:1px solid var(--tide)}
        .btn-loc:hover{background:var(--tide);color:#fff}
        .btn-disabled{background:var(--border);color:var(--muted);cursor:not-allowed}

        /* Consumer info */
        .consumer-row{font-size:.82rem;color:var(--muted);margin-bottom:10px;display:flex;align-items:center;gap:5px}

        .alert-s{background:#d4f7e7;border:1px solid #1a7a4a;color:#1a7a4a;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-weight:500}
        .alert-e{background:#fde8e8;border:1px solid #a81a1a;color:#a81a1a;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-weight:500}

        .empty-msg{text-align:center;padding:32px;color:var(--muted);font-size:.9rem;background:#fff;border:1px dashed var(--border);border-radius:14px}

        /* GPS panel */
        .gps-panel{display:none;background:var(--foam);border:1px solid var(--border);border-radius:10px;padding:14px;margin-top:12px}
        .gps-panel.open{display:block}
        .gps-map{height:200px;border-radius:8px;margin-top:12px;border:1px solid var(--border)}
        .gps-row{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
        .gps-input{border:2px solid var(--border);border-radius:8px;padding:8px 12px;font-family:'DM Sans',sans-serif;font-size:.85rem;outline:none;width:130px;transition:border-color .2s}
        .gps-input:focus{border-color:var(--tide)}
        .gps-send{background:var(--tide);color:#fff;border:none;padding:8px 14px;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600;cursor:pointer}
        .gps-feedback{font-size:.8rem;margin-top:6px;display:none}
        .gps-feedback.ok{color:var(--fresh);display:block}
        .gps-feedback.err{color:var(--coral);display:block}
    </style>
</head>
<body>
<nav class="sh-nav">
    <a href="{{ url('/') }}" class="sh-logo">🐟 Aqua<span>Hub</span></a>
    <div style="display:flex;gap:6px">
        <a href="{{ route('delivery.dashboard') }}">Dashboard</a>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
        <span class="sh-role">Delivery</span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" style="background:rgba(255,255,255,.15);border:none;color:#fff;padding:7px 14px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600">Logout</button>
        </form>
    </div>
</nav>

<div class="page-header">
    <h2>Delivery Dashboard</h2>
    <p>Welcome back, {{ auth()->user()->name }}! Manage your deliveries below.</p>
</div>

<div class="wrap">
    @if(session('success'))<div class="alert-s">✅ {{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-e">⚠️ {{ session('error') }}</div>@endif

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-num">{{ $myOrders->count() }}</div>
            <div class="stat-label">Active Deliveries</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $available->count() }}</div>
            <div class="stat-label">Available to Claim</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $completed->count() }}</div>
            <div class="stat-label">Completed Today</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">KES {{ number_format($completed->sum('total_amount'), 0) }}</div>
            <div class="stat-label">Delivered Value</div>
        </div>
    </div>

    {{-- My Active Orders --}}
    <div class="section-title">
        🛵 My Active Deliveries
        @if($myOrders->count()) <span class="section-badge">{{ $myOrders->count() }}</span> @endif
    </div>

    @forelse($myOrders as $order)
        @php $next = \App\Models\Order::NEXT_STATUS[$order->status] ?? null; @endphp
        <div class="order-row" id="order-{{ $order->id }}">
            <div class="order-row-top">
                <div class="order-id-area">
                    <div class="oid">Order #{{ $order->id }}</div>
                    <div class="meta">{{ $order->created_at->diffForHumans() }}</div>
                </div>
                <div style="display:flex;align-items:center;gap:10px">
                    <span class="status-pill s-{{ $order->status }}">{{ $order->status_label }}</span>
                    <div class="order-amount">KES {{ number_format($order->total_amount, 0) }}</div>
                </div>
            </div>

            <div class="consumer-row">
                👤 {{ $order->consumer->name ?? '—' }}
                @if($order->consumer->phone) · 📞 {{ $order->consumer->phone }} @endif
            </div>

            <div class="address-row">📍 {{ $order->delivery_address }}</div>

            <div class="items-row">
                @foreach($order->items->take(3) as $item)
                    <span class="item-chip">🐟 {{ $item->product->name ?? 'Item' }} × {{ $item->quantity_kg }}kg</span>
                @endforeach
                @if($order->items->count() > 3)
                    <span class="item-chip" style="background:transparent;border-style:dashed">+{{ $order->items->count()-3 }} more</span>
                @endif
            </div>

            <div class="actions">
                @if($next)
                    <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}" style="margin:0">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-advance">
                            ➤ Mark as "{{ \App\Models\Order::STATUS_LABELS[$next] }}"
                        </button>
                    </form>
                @else
                    <span class="btn btn-disabled">✓ Delivered</span>
                @endif
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-view">View</a>
                @if($order->status === 'in_transit')
                    <button type="button" class="btn btn-loc" onclick="toggleGps({{ $order->id }})">📍 Send Location</button>
                @endif
            </div>

            {{-- GPS panel --}}
            @if($order->status === 'in_transit')
                <div class="gps-panel" id="gps-{{ $order->id }}">
                    <div style="font-size:.8rem;font-weight:600;color:var(--muted);margin-bottom:8px">SEND GPS PING</div>
                    <div class="gps-row">
                        <input class="gps-input" type="number" step="any" placeholder="Latitude" id="lat-{{ $order->id }}">
                        <input class="gps-input" type="number" step="any" placeholder="Longitude" id="lng-{{ $order->id }}">
                        <input class="gps-input" type="text" placeholder="Status note (opt.)" id="note-{{ $order->id }}" style="width:140px">
                        <button class="gps-send" onclick="sendLocation({{ $order->id }})">Send</button>
                    </div>
                    <div class="gps-feedback" id="gps-fb-{{ $order->id }}"></div>
                    <div class="gps-map" id="gps-map-{{ $order->id }}"></div>
                </div>
            @endif
        </div>
    @empty
        <div class="empty-msg">No active deliveries assigned to you yet. Claim one below!</div>
    @endforelse

    {{-- Available Orders --}}
    <div class="section-title" style="margin-top:36px">
        📦 Available Orders to Claim
        @if($available->count()) <span class="section-badge gold">{{ $available->count() }}</span> @endif
    </div>

    @forelse($available as $order)
        <div class="order-row">
            <div class="order-row-top">
                <div class="order-id-area">
                    <div class="oid">Order #{{ $order->id }}</div>
                    <div class="meta">{{ $order->created_at->diffForHumans() }} · {{ $order->items->count() }} item(s)</div>
                </div>
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    <span class="status-pill s-{{ $order->status }}">{{ $order->status_label }}</span>
                    @if($order->payment_status)
                        <span style="font-size:.75rem;font-weight:700;padding:4px 10px;border-radius:20px;{{ $order->payment_status === 'paid' ? 'background:#d4f7e7;color:#1a7a4a' : 'background:#fff3d0;color:#a07000' }}">
                            {{ $order->payment_status === 'paid' ? '✓ Paid' : '⏳ Pending Payment' }}
                        </span>
                    @endif
                    <div class="order-amount">KES {{ number_format($order->total_amount, 0) }}</div>
                </div>
            </div>

            <div class="consumer-row">
                👤 {{ $order->consumer->name ?? '—' }}
                @if($order->consumer->phone) · 📞 {{ $order->consumer->phone }} @endif
            </div>

            <div class="address-row">📍 {{ $order->delivery_address }}</div>

            <div class="items-row">
                @foreach($order->items->take(3) as $item)
                    <span class="item-chip">🐟 {{ $item->product->name ?? 'Item' }} × {{ $item->quantity_kg }}kg</span>
                @endforeach
                @if($order->items->count() > 3)
                    <span class="item-chip" style="background:transparent;border-style:dashed">+{{ $order->items->count()-3 }} more</span>
                @endif
            </div>

            <div class="actions">
                <form method="POST" action="{{ route('delivery.assign', $order->id) }}" style="margin:0">
                    @csrf
                    <button type="submit" class="btn btn-claim">🚀 Claim this Order</button>
                </form>
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-view">View Details</a>
            </div>
        </div>
    @empty
        <div class="empty-msg">No orders available to claim right now. Check back soon!</div>
    @endforelse

    {{-- Completed --}}
    @if($completed->count())
        <div class="section-title" style="margin-top:36px">
            ✅ Recently Delivered
            <span class="section-badge green">{{ $completed->count() }}</span>
        </div>
        @foreach($completed as $order)
            <div class="order-row" style="opacity:.75">
                <div class="order-row-top">
                    <div class="order-id-area">
                        <div class="oid">Order #{{ $order->id }}</div>
                        <div class="meta">{{ $order->updated_at->format('d M Y, H:i') }}</div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px">
                        <span class="status-pill s-delivered">Delivered</span>
                        <div class="order-amount">KES {{ number_format($order->total_amount, 0) }}</div>
                    </div>
                </div>
                <div class="address-row">📍 {{ $order->delivery_address }}</div>
                <div class="actions">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-view">View</a>
                </div>
            </div>
        @endforeach
    @endif
</div>

<footer style="background:#051e28;color:rgba(255,255,255,.5);text-align:center;padding:28px;font-size:.85rem">
    <p>© 2026 <span style="color:#f0a500">AquaHub</span></p>
</footer>

@include('partials.samakibot')

<script>
    const CSRF        = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const LOC_BASE    = '{{ url('/delivery/orders') }}';
    const agentMaps   = {};   // orderId → { map, marker }
    const agentIcon   = () => L.divIcon({
        html: '<div style="font-size:2rem;filter:drop-shadow(0 2px 4px rgba(0,0,0,.4))">🛵</div>',
        className: '', iconSize: [36,36], iconAnchor: [18,18], popupAnchor: [0,-20],
    });

    function initMap(orderId, lat, lng) {
        if (agentMaps[orderId]) return; // already initialised

        const map = L.map('gps-map-' + orderId).setView([lat, lng], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 19,
        }).addTo(map);

        const marker = L.marker([lat, lng], { icon: agentIcon() })
            .addTo(map)
            .bindPopup('Your current position')
            .openPopup();

        agentMaps[orderId] = { map, marker };
    }

    function updateMapMarker(orderId, lat, lng, label) {
        if (!agentMaps[orderId]) { initMap(orderId, lat, lng); return; }
        const { map, marker } = agentMaps[orderId];
        marker.setLatLng([lat, lng]).getPopup().setContent(label);
        map.panTo([lat, lng]);
    }

    function toggleGps(orderId) {
        const panel = document.getElementById('gps-' + orderId);
        const wasOpen = panel.classList.contains('open');
        panel.classList.toggle('open');

        // Initialise map first time the panel opens
        if (!wasOpen) {
            const lat  = parseFloat(document.getElementById('lat-'  + orderId).value);
            const lng  = parseFloat(document.getElementById('lng-'  + orderId).value);
            const hasCoords = !isNaN(lat) && !isNaN(lng);
            // Use detected coords or Nairobi default
            setTimeout(() => {
                initMap(orderId, hasCoords ? lat : -1.2921, hasCoords ? lng : 36.8219);
                if (hasCoords) agentMaps[orderId].map.invalidateSize();
            }, 50); // tiny delay so the panel is visible before Leaflet measures it
        } else {
            // Refresh map size in case layout shifted
            if (agentMaps[orderId]) agentMaps[orderId].map.invalidateSize();
        }
    }

    async function sendLocation(orderId) {
        const lat  = document.getElementById('lat-'  + orderId).value;
        const lng  = document.getElementById('lng-'  + orderId).value;
        const note = document.getElementById('note-' + orderId).value;
        const fb   = document.getElementById('gps-fb-' + orderId);

        if (!lat || !lng) {
            fb.textContent = 'Latitude and longitude are required.';
            fb.className = 'gps-feedback err';
            return;
        }

        try {
            const res = await fetch(`${LOC_BASE}/${orderId}/location`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
                body: JSON.stringify({ latitude: lat, longitude: lng, status_update: note }),
            });
            const data = await res.json();
            if (data.success) {
                fb.textContent = '✓ Location sent!';
                fb.className = 'gps-feedback ok';
                updateMapMarker(orderId, parseFloat(lat), parseFloat(lng), '✓ Location sent to customer');
            } else {
                fb.textContent = data.error || 'Failed.';
                fb.className = 'gps-feedback err';
            }
        } catch {
            fb.textContent = 'Network error. Try again.';
            fb.className = 'gps-feedback err';
        }
    }

    // Auto-detect GPS on page load for all in-transit orders
    document.querySelectorAll('[id^="lat-"]').forEach(el => {
        const orderId = el.id.replace('lat-', '');
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude.toFixed(6);
                const lng = pos.coords.longitude.toFixed(6);
                document.getElementById('lat-'  + orderId).value = lat;
                document.getElementById('lng-'  + orderId).value = lng;
                // Update map marker if panel is already open
                if (agentMaps[orderId]) {
                    updateMapMarker(orderId, parseFloat(lat), parseFloat(lng), 'Your current position (auto-detected)');
                }
            });
        }
    });
</script>
</body>
</html>
