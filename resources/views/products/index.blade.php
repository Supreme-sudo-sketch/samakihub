<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fish Marketplace — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ocean: #0a3d4f; --deep: #051e28; --tide: #0d6e8a;
            --foam: #e8f4f8; --coral: #e85d3a; --gold: #f0a500;
            --fresh: #2eb87a; --muted: #6b7d8a; --border: #dde8ed;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--foam); color: #1a1a2e; }

        /* Nav */
        .sh-nav { background: var(--ocean); padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 64px; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 20px rgba(0,0,0,.3); }
        .sh-logo { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 900; color: #fff; text-decoration: none; }
        .sh-logo span { color: var(--gold); }
        .sh-nav-links { display: flex; gap: 6px; list-style: none; }
        .sh-nav-links a { color: rgba(255,255,255,.7); font-size: .875rem; font-weight: 500; padding: 7px 13px; border-radius: 8px; text-decoration: none; transition: all .2s; }
        .sh-nav-links a:hover, .sh-nav-links a.active { background: rgba(255,255,255,.12); color: #fff; }
        .sh-nav-right { display: flex; align-items: center; gap: 10px; }
        .sh-role-badge { background: var(--gold); color: var(--deep); font-size: .75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; }

        /* Page header */
        .page-header { background: #fff; border-bottom: 1px solid var(--border); padding: 24px 2rem; }
        .page-header h2 { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; color: var(--ocean); margin-bottom: 4px; }
        .page-header p { color: var(--muted); font-size: .9rem; }

        /* Filters */
        .filters-bar { display: flex; gap: 12px; flex-wrap: wrap; align-items: center; margin-bottom: 24px; }
        .filter-input { border: 2px solid var(--border); border-radius: 10px; padding: 9px 14px; font-family: 'DM Sans', sans-serif; font-size: .9rem; outline: none; background: #fff; transition: border-color .2s; min-width: 180px; }
        .filter-input:focus { border-color: var(--tide); }
        .filter-btn { background: var(--ocean); color: #fff; border: none; padding: 9px 20px; border-radius: 10px; cursor: pointer; font-size: .9rem; font-weight: 600; font-family: 'DM Sans', sans-serif; }
        .filter-btn-outline { background: #fff; color: var(--ocean); border: 2px solid var(--border); padding: 8px 16px; border-radius: 10px; cursor: pointer; font-size: .85rem; font-weight: 600; font-family: 'DM Sans', sans-serif; text-decoration: none; }

        /* Category chips */
        .cat-chips { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 24px; }
        .cat-chip { background: #fff; border: 2px solid var(--border); color: var(--muted); padding: 7px 16px; border-radius: 30px; font-size: .85rem; font-weight: 500; cursor: pointer; text-decoration: none; transition: all .2s; }
        .cat-chip:hover, .cat-chip.active { background: var(--ocean); border-color: var(--ocean); color: #fff; }

        /* Product grid */
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px; }
        .product-card { background: #fff; border-radius: 16px; overflow: hidden; border: 1px solid var(--border); transition: transform .2s, box-shadow .2s; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(10,61,79,.12); }

        .product-img { width: 100%; height: 180px; object-fit: cover; background: linear-gradient(135deg, var(--ocean), var(--tide)); display: flex; align-items: center; justify-content: center; font-size: 5rem; position: relative; }
        .product-img img { width: 100%; height: 100%; object-fit: cover; }

        .freshness-badge { position: absolute; top: 10px; left: 10px; font-size: .7rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; letter-spacing: .5px; text-transform: uppercase; }
        .freshness-super_fresh { background: var(--fresh); color: #fff; }
        .freshness-fresh { background: #17a2b8; color: #fff; }
        .freshness-smoked { background: #e67e22; color: #fff; }
        .freshness-dried { background: #8d6748; color: #fff; }

        .stock-badge { position: absolute; top: 10px; right: 10px; background: rgba(5,30,40,.7); color: #fff; font-size: .68rem; font-weight: 600; padding: 3px 8px; border-radius: 20px; }

        .product-body { padding: 16px; }
        .product-name { font-family: 'Playfair Display', serif; font-size: 1.05rem; font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
        .product-fisherman { font-size: .8rem; color: var(--muted); margin-bottom: 10px; }
        .product-meta { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .product-price { font-size: 1.15rem; font-weight: 700; color: var(--ocean); }
        .product-price span { font-size: .75rem; color: var(--muted); font-weight: 400; }
        .product-category { background: var(--foam); color: var(--tide); font-size: .72rem; font-weight: 600; padding: 3px 9px; border-radius: 10px; }

        .view-btn { flex: 1; background: var(--ocean); color: #fff; border: none; padding: 10px; border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: .9rem; font-weight: 600; cursor: pointer; text-align: center; text-decoration: none; transition: background .2s; display: block; }
        .view-btn:hover { background: var(--tide); }
        .card-actions { display: flex; gap: 8px; margin-top: 10px; }
        .quick-add-btn { background: var(--fresh); color: #fff; border: none; padding: 10px 14px; border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: .9rem; font-weight: 600; cursor: pointer; white-space: nowrap; transition: background .2s; }
        .quick-add-btn:hover { background: #25a06a; }

        /* Producer add button */
        .add-product-btn { background: var(--fresh); color: #fff; border: none; padding: 10px 20px; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: .9rem; font-weight: 700; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; }
        .add-product-btn:hover { background: #25a06a; }

        /* Pagination */
        .pagination-wrap { margin-top: 40px; display: flex; justify-content: center; }

        /* Alert */
        .alert-success { background: #d4f7e7; border: 1px solid #1a7a4a; color: #1a7a4a; padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-weight: 500; }

        /* Empty */
        .empty-state { text-align: center; padding: 60px 20px; color: var(--muted); grid-column: 1/-1; }
        .empty-state .empty-icon { font-size: 4rem; margin-bottom: 16px; }
        .empty-state h3 { font-family: 'Playfair Display', serif; color: var(--ocean); font-size: 1.4rem; margin-bottom: 8px; }

        section { max-width: 1200px; margin: 0 auto; padding: 36px 2rem; }
    </style>
</head>
<body>

<nav class="sh-nav">
    <a href="{{ url('/') }}" class="sh-logo">🐟 Aqua<span>Hub</span></a>
    <ul class="sh-nav-links">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ route('products.index') }}" class="active">Shop</a></li>
        @auth
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
        @endauth
    </ul>
    <div class="sh-nav-right">
        @auth
            @if(auth()->user()->role === 'consumer')
                @php $navCartCount = \App\Models\Cart::where('user_id', auth()->id())->count(); @endphp
                <a href="{{ route('cart.index') }}" style="position:relative;color:rgba(255,255,255,.8);text-decoration:none;padding:7px 10px;border-radius:8px;font-size:.9rem;font-weight:600" title="Cart">
                    🛒
                    @if($navCartCount > 0)
                        <span style="position:absolute;top:2px;right:2px;background:#e85d3a;color:#fff;font-size:.6rem;font-weight:700;width:16px;height:16px;border-radius:50%;display:flex;align-items:center;justify-content:center;line-height:1">{{ $navCartCount }}</span>
                    @endif
                </a>
            @endif
            <span class="sh-role-badge">{{ ucfirst(auth()->user()->role) }}</span>
            <form method="POST" action="{{ route('logout') }}" style="margin:0">
                @csrf
                <button type="submit" style="background:rgba(255,255,255,.15);border:none;color:#fff;padding:7px 14px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600;">Logout</button>
            </form>
        @else
            <a href="{{ route('login') }}" style="color:rgba(255,255,255,.8);font-size:.875rem;font-weight:500;text-decoration:none;padding:7px 13px;border-radius:8px;border:1px solid rgba(255,255,255,.3);">Login</a>
        @endauth
    </div>
</nav>

<div class="page-header">
    <h2>Fish Marketplace</h2>
    <p>Fresh catch from verified fishermen around Kenya</p>
</div>

<section>
    @if(session('success'))
        <div class="alert-success">✅ {{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" action="{{ route('products.index') }}">
        <div class="filters-bar">
            <input class="filter-input" type="text" name="search" placeholder="Search fish, fisherman..." value="{{ request('search') }}">
            <select class="filter-input" name="freshness" style="min-width:150px">
                <option value="">All Freshness</option>
                @foreach(['super_fresh' => 'Super Fresh', 'fresh' => 'Fresh', 'smoked' => 'Smoked', 'dried' => 'Dried'] as $val => $label)
                    <option value="{{ $val }}" {{ request('freshness') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="filter-btn">🔍 Search</button>
            @if(request()->hasAny(['search', 'freshness', 'category']))
                <a href="{{ route('products.index') }}" class="filter-btn-outline">✕ Clear</a>
            @endif
            @auth
                @if(auth()->user()->role === 'producer')
                    <a href="{{ route('products.create') }}" class="add-product-btn" style="margin-left:auto">+ List New Fish</a>
                @endif
            @endauth
        </div>

        {{-- Category chips --}}
        <div class="cat-chips">
            <a href="{{ route('products.index', array_merge(request()->except('category', 'page'), [])) }}"
               class="cat-chip {{ !request('category') ? 'active' : '' }}">All</a>
            <a href="{{ route('products.index', array_merge(request()->except('category', 'page'), ['category' => 'freshwater'])) }}"
               class="cat-chip {{ request('category') === 'freshwater' ? 'active' : '' }}">🌊 Freshwater</a>
            <a href="{{ route('products.index', array_merge(request()->except('category', 'page'), ['category' => 'ocean'])) }}"
               class="cat-chip {{ request('category') === 'ocean' ? 'active' : '' }}">🌊 Ocean</a>
        </div>
    </form>

    {{-- Product grid --}}
    <div class="product-grid">
        @forelse($products as $product)
            @php
                $emoji = match(true) {
                    str_contains(strtolower($product->name), 'prawn') || str_contains(strtolower($product->name), 'kamba') => '🦐',
                    str_contains(strtolower($product->name), 'tuna') => '🐟',
                    str_contains(strtolower($product->name), 'perch') || str_contains(strtolower($product->name), 'nile') => '🐟',
                    str_contains(strtolower($product->name), 'tilapia') => '🐠',
                    str_contains(strtolower($product->name), 'catfish') => '🐡',
                    $product->category === 'ocean' => '🐡',
                    default => '🐟',
                };
            @endphp
            <div class="product-card">
                <div class="product-img">
                    @if($product->image)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                    @else
                        <span>{{ $emoji }}</span>
                    @endif
                    <span class="freshness-badge freshness-{{ $product->freshness }}">
                        {{ $product->freshness_label }}
                    </span>
                    <span class="stock-badge">{{ number_format($product->stock_kg, 1) }} kg</span>
                </div>
                <div class="product-body">
                    <div class="product-name">{{ $product->name }}</div>
                    <div class="product-fisherman">🎣 {{ $product->producer->name }} · {{ $product->producer->location ?? 'Kenya' }}</div>
                    <div class="product-meta">
                        <div class="product-price">
                            KES {{ number_format($product->price_per_kg, 0) }}<span>/kg</span>
                        </div>
                        <span class="product-category">{{ ucfirst($product->category) }}</span>
                    </div>
                    <div class="product-rating">
                        @if($product->review_count > 0)
                            <span style="color:var(--gold);font-size:.92rem;">
                                @for($i=1;$i<=5;$i++){{ $i <= round($product->avg_rating) ? '★' : '☆' }}@endfor
                            </span>
                            <span style="font-size:.82rem;color:var(--muted);margin-left:6px">{{ $product->avg_rating }} · {{ $product->review_count }} reviews</span>
                        @else
                            <span style="font-size:.82rem;color:var(--muted);">No reviews yet</span>
                        @endif
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('products.show', $product->id) }}" class="view-btn">View Details</a>
                        @auth
                            @if(auth()->user()->role === 'consumer')
                                <form method="POST" action="{{ route('cart.add', $product->id) }}" style="margin:0">
                                    @csrf
                                    <input type="hidden" name="qty" value="1">
                                    <button type="submit" class="quick-add-btn" title="Add 1kg to cart">🛒 +1kg</button>
                                </form>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <h3>No products found</h3>
                <p>Try adjusting your search or filters.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($products->hasPages())
        <div class="pagination-wrap">
            {{ $products->links() }}
        </div>
    @endif
</section>

<footer style="background:#051e28;color:rgba(255,255,255,.5);text-align:center;padding:28px;font-size:.85rem;margin-top:60px;">
    <p>© 2026 <span style="color:#f0a500">AquaHub</span> — Empowering Kenya's Fishing Community 🐟</p>
</footer>

</body>
</html>
