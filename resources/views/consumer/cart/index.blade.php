<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
        .layout{display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start}
        @media(max-width:768px){.layout{grid-template-columns:1fr}}

        /* Cart items */
        .cart-card{background:#fff;border:1px solid var(--border);border-radius:14px;overflow:hidden}
        .cart-header{padding:16px 20px;border-bottom:1px solid var(--border);font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--ocean)}
        .cart-item{display:flex;gap:14px;align-items:center;padding:16px 20px;border-bottom:1px solid var(--foam)}
        .cart-item:last-child{border-bottom:none}
        .item-emoji{width:56px;height:56px;border-radius:12px;background:linear-gradient(135deg,var(--ocean),var(--tide));display:flex;align-items:center;justify-content:center;font-size:1.6rem;flex-shrink:0}
        .item-emoji img{width:100%;height:100%;object-fit:cover;border-radius:12px}
        .item-info{flex:1;min-width:0}
        .item-name{font-weight:700;font-size:.95rem;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
        .item-sub{font-size:.8rem;color:var(--muted)}
        .item-controls{display:flex;align-items:center;gap:6px;margin-top:8px}
        .qty-btn{width:28px;height:28px;border-radius:50%;border:2px solid var(--border);background:none;cursor:pointer;font-size:.9rem;font-weight:700;color:var(--ocean);display:flex;align-items:center;justify-content:center;transition:all .15s;font-family:inherit}
        .qty-btn:hover{background:var(--ocean);color:#fff;border-color:var(--ocean)}
        .qty-display{font-weight:700;font-size:.95rem;min-width:28px;text-align:center}
        .qty-unit{font-size:.75rem;color:var(--muted)}
        .item-price{font-weight:700;font-size:1rem;color:var(--ocean);white-space:nowrap}
        .remove-btn{background:none;border:none;cursor:pointer;color:var(--coral);font-size:1.1rem;padding:4px;margin-left:4px;line-height:1}

        /* Summary box */
        .summary-box{background:#fff;border:1px solid var(--border);border-radius:16px;padding:24px;position:sticky;top:80px}
        .summary-title{font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;margin-bottom:20px;color:var(--ocean)}
        .summary-row{display:flex;justify-content:space-between;font-size:.9rem;padding:9px 0;border-bottom:1px solid var(--foam);color:var(--muted)}
        .summary-row.total{font-weight:700;color:#1a1a2e;font-size:1.1rem;border-bottom:none;padding-top:14px;margin-top:4px}
        .summary-items{font-size:.82rem;color:var(--muted);margin-bottom:16px}

        /* Delivery form */
        .delivery-form{margin-top:18px;padding-top:18px;border-top:1px solid var(--border)}
        .form-label{display:block;font-size:.82rem;font-weight:600;color:#374151;margin-bottom:5px}
        .form-input,.form-textarea{width:100%;border:2px solid var(--border);border-radius:10px;padding:10px 13px;font-family:'DM Sans',sans-serif;font-size:.9rem;outline:none;background:#fff;transition:border-color .2s;color:#1a1a2e}
        .form-input:focus,.form-textarea:focus{border-color:var(--tide)}
        .form-textarea{resize:vertical;min-height:70px}
        .form-group{margin-bottom:14px}
        .form-error{color:var(--coral);font-size:.8rem;margin-top:4px}

        .place-order-btn{width:100%;background:var(--fresh);color:#fff;border:none;padding:14px;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:1rem;font-weight:700;cursor:pointer;margin-top:6px;transition:background .2s}
        .place-order-btn:hover{background:#25a06a}
        .mpesa-note{background:var(--foam);border:1px solid var(--border);border-radius:8px;padding:10px 13px;font-size:.78rem;color:var(--muted);margin-top:10px;text-align:center;line-height:1.5}

        .alert-s{background:#d4f7e7;border:1px solid #1a7a4a;color:#1a7a4a;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-weight:500}
        .alert-e{background:#fde8e8;border:1px solid #a81a1a;color:#a81a1a;padding:12px 18px;border-radius:10px;margin-bottom:20px;font-weight:500}

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
        <a href="{{ route('products.index') }}">Shop</a>
        <a href="{{ route('orders.index') }}">My Orders</a>
        <a href="{{ route('dashboard') }}">Dashboard</a>
    </div>
    <div style="display:flex;align-items:center;gap:10px">
        @php $navCartCount = \App\Models\Cart::where('user_id', auth()->id())->count(); @endphp
        <a href="{{ route('cart.index') }}" style="position:relative;color:rgba(255,255,255,.8);text-decoration:none;padding:7px 10px;border-radius:8px;font-size:.9rem;font-weight:700;background:rgba(255,255,255,.12)" title="Cart">
            🛒 Cart
            @if($navCartCount > 0)
                <span style="background:#e85d3a;color:#fff;font-size:.65rem;font-weight:700;padding:2px 6px;border-radius:12px;margin-left:4px">{{ $navCartCount }}</span>
            @endif
        </a>
        <span class="sh-role">Consumer</span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" style="background:rgba(255,255,255,.15);border:none;color:#fff;padding:7px 14px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600">Logout</button>
        </form>
    </div>
</nav>

<div class="page-header">
    <h2>Your Cart</h2>
    <p>Review your order before checkout</p>
</div>

<div class="wrap">
    @if(session('success'))<div class="alert-s">✅ {{ session('success') }}</div>@endif
    @if(session('error'))<div class="alert-e">⚠️ {{ session('error') }}</div>@endif

    @if(empty($cart))
        <div class="empty-state">
            <div class="ei">🛒</div>
            <h3>Your cart is empty</h3>
            <p>Add some fresh fish to get started!</p>
            <a href="{{ route('products.index') }}" class="shop-btn">Browse Marketplace</a>
        </div>
    @else
        <div class="layout">
            {{-- Cart items — each update/remove is its own standalone form --}}
            <div>
                <div class="cart-card">
                    <div class="cart-header">🛒 Cart ({{ count($cart) }} {{ count($cart) === 1 ? 'item' : 'items' }})</div>
                    @foreach($cart as $productId => $item)
                        @php $lineTotal = $item['price_per_kg'] * $item['qty']; @endphp
                        <div class="cart-item">
                            <div class="item-emoji">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                @else
                                    🐟
                                @endif
                            </div>
                            <div class="item-info">
                                <div class="item-name">{{ $item['name'] }}</div>
                                <div class="item-sub">🎣 {{ $item['fisherman'] }} · KES {{ number_format($item['price_per_kg'], 0) }}/kg</div>
                                <div class="item-controls">
                                    {{-- Decrease --}}
                                    <form method="POST" action="{{ route('cart.update', $productId) }}" style="display:inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="qty" value="{{ max(0.5, $item['qty'] - 0.5) }}">
                                        <button type="submit" class="qty-btn">−</button>
                                    </form>
                                    <span class="qty-display">{{ $item['qty'] }}</span>
                                    <span class="qty-unit">kg</span>
                                    {{-- Increase --}}
                                    <form method="POST" action="{{ route('cart.update', $productId) }}" style="display:inline">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="qty" value="{{ min($item['max_stock'], $item['qty'] + 0.5) }}">
                                        <button type="submit" class="qty-btn">+</button>
                                    </form>
                                </div>
                            </div>
                            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;flex-shrink:0">
                                <div class="item-price">KES {{ number_format($lineTotal, 0) }}</div>
                                <form method="POST" action="{{ route('cart.remove', $productId) }}" style="margin:0">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="remove-btn" title="Remove">🗑️</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Order summary + checkout form (its own separate form) --}}
            <div>
                <div class="summary-box">
                    <div class="summary-title">Order Summary</div>
                    <div class="summary-items">{{ array_sum(array_column($cart, 'qty')) }} kg total</div>

                    @foreach($cart as $item)
                        <div class="summary-row">
                            <span>{{ $item['name'] }} × {{ $item['qty'] }}kg</span>
                            <span>KES {{ number_format($item['price_per_kg'] * $item['qty'], 0) }}</span>
                        </div>
                    @endforeach

                    <div class="summary-row">
                        <span>Delivery fee</span>
                        <span>KES {{ number_format($deliveryFee, 0) }}</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total</span>
                        <span>KES {{ number_format($total, 0) }}</span>
                    </div>

                    {{-- Delivery details + place order (standalone form, no cart forms inside) --}}
                    <form method="POST" action="{{ route('orders.store') }}" id="order-form">
                        @csrf
                        <div class="delivery-form">
                            <div style="font-size:.8rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:12px">Delivery Details</div>

                            <div class="form-group">
                                <label class="form-label" for="delivery_address">Delivery Address <span style="color:var(--coral)">*</span></label>
                                <textarea class="form-textarea" id="delivery_address" name="delivery_address" rows="2"
                                    placeholder="e.g. Tom Mboya St, Nairobi CBD" required>{{ old('delivery_address') }}</textarea>
                                @error('delivery_address')<div class="form-error">{{ $message }}</div>@enderror
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="delivery_notes">Notes (optional)</label>
                                <input class="form-input" type="text" id="delivery_notes" name="delivery_notes"
                                    placeholder="e.g. Call on arrival" value="{{ old('delivery_notes') }}">
                            </div>

                            <button type="submit" class="place-order-btn">
                                ✅ Place Order — KES {{ number_format($total, 0) }}
                            </button>
                            <div class="mpesa-note">📱 You will be prompted to pay via M-Pesa after placing your order.</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

<footer style="background:#051e28;color:rgba(255,255,255,.5);text-align:center;padding:28px;font-size:.85rem">
    <p>© 2026 <span style="color:#f0a500">AquaHub</span></p>
</footer>
@include('partials.samakibot')
</body>
</html>
