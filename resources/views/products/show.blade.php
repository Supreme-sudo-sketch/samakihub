<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — AquaHub</title>
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
        .sh-nav-links a:hover { background: rgba(255,255,255,.12); color: #fff; }
        .sh-nav-right { display: flex; align-items: center; gap: 10px; }
        .sh-role-badge { background: var(--gold); color: var(--deep); font-size: .75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; }

        /* Breadcrumb */
        .breadcrumb { font-size: .85rem; color: var(--muted); padding: 14px 2rem; max-width: 1100px; margin: 0 auto; }
        .breadcrumb a { color: var(--tide); text-decoration: none; }

        /* Layout */
        .product-layout { max-width: 1100px; margin: 0 auto; padding: 0 2rem 60px; display: grid; grid-template-columns: 1fr 380px; gap: 32px; align-items: start; }
        @media(max-width:768px){ .product-layout { grid-template-columns: 1fr; } }

        /* Image */
        .product-image-box { background: linear-gradient(135deg, var(--ocean), var(--tide)); border-radius: 20px; height: 340px; display: flex; align-items: center; justify-content: center; font-size: 9rem; position: relative; overflow: hidden; }
        .product-image-box img { width: 100%; height: 100%; object-fit: cover; }

        .freshness-badge { position: absolute; top: 16px; left: 16px; font-size: .8rem; font-weight: 700; padding: 6px 14px; border-radius: 20px; letter-spacing: .5px; text-transform: uppercase; }
        .freshness-super_fresh { background: var(--fresh); color: #fff; }
        .freshness-fresh { background: #17a2b8; color: #fff; }
        .freshness-smoked { background: #e67e22; color: #fff; }
        .freshness-dried { background: #8d6748; color: #fff; }

        /* Info */
        .product-title { font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 900; color: #1a1a2e; margin-bottom: 6px; }
        .product-fisherman-row { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .fisherman-avatar { width: 42px; height: 42px; border-radius: 50%; background: linear-gradient(135deg, var(--ocean), var(--tide)); display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
        .fisherman-info { font-size: .9rem; }
        .fisherman-name { font-weight: 700; color: #1a1a2e; }
        .fisherman-loc { color: var(--muted); font-size: .8rem; }

        .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; margin-bottom: 24px; }
        .detail-cell { padding: 14px 16px; border-bottom: 1px solid var(--border); border-right: 1px solid var(--border); background: #fff; }
        .detail-cell:nth-child(even) { border-right: none; }
        .detail-cell:nth-last-child(-n+2) { border-bottom: none; }
        .detail-label { font-size: .75rem; color: var(--muted); font-weight: 600; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
        .detail-value { font-size: .95rem; font-weight: 700; color: #1a1a2e; }

        .description-box { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 18px; margin-bottom: 24px; font-size: .95rem; color: #444; line-height: 1.7; }

        /* Sidebar / Order box */
        .order-box { background: #fff; border: 1px solid var(--border); border-radius: 16px; padding: 24px; position: sticky; top: 80px; }
        .price-big { font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 900; color: var(--ocean); margin-bottom: 4px; }
        .price-unit { font-size: .9rem; color: var(--muted); font-weight: 400; }
        .stock-row { font-size: .85rem; color: var(--fresh); font-weight: 600; margin-bottom: 20px; }
        .stock-low { color: var(--coral); }

        .qty-row { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
        .qty-label { font-size: .85rem; font-weight: 600; color: var(--muted); }
        .qty-input { width: 80px; border: 2px solid var(--border); border-radius: 8px; padding: 8px 12px; font-size: 1rem; font-weight: 700; text-align: center; outline: none; font-family: 'DM Sans', sans-serif; }
        .qty-input:focus { border-color: var(--tide); }

        .cart-btn { width: 100%; background: var(--ocean); color: #fff; border: none; padding: 14px; border-radius: 10px; font-family: 'DM Sans', sans-serif; font-size: 1rem; font-weight: 700; cursor: pointer; margin-bottom: 10px; transition: background .2s; }
        .cart-btn:hover { background: var(--tide); }
        .cart-btn-disabled { background: var(--border); color: var(--muted); cursor: not-allowed; }

        /* Edit/Delete (producer actions) */
        .producer-actions { display: flex; gap: 8px; margin-top: 12px; }
        .btn-edit { flex: 1; background: var(--gold); color: var(--deep); border: none; padding: 10px; border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: .875rem; font-weight: 700; cursor: pointer; text-align: center; text-decoration: none; }
        .btn-edit:hover { background: #d4920a; }
        .btn-delete { flex: 1; background: var(--coral); color: #fff; border: none; padding: 10px; border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: .875rem; font-weight: 700; cursor: pointer; }
        .btn-delete:hover { background: #c94d2d; }

        /* Questions */
        .questions-section { margin-top: 32px; }
        .questions-title { font-family: 'Playfair Display', serif; font-size: 1.3rem; font-weight: 700; color: var(--ocean); margin-bottom: 16px; }
        .question-card { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 16px; margin-bottom: 12px; }
        .question-text { font-weight: 600; font-size: .95rem; margin-bottom: 8px; }
        .question-meta { font-size: .78rem; color: var(--muted); margin-bottom: 8px; }
        .answer-text { background: var(--foam); border-left: 3px solid var(--tide); padding: 10px 14px; border-radius: 0 8px 8px 0; font-size: .9rem; color: #333; margin-top: 8px; }
        .unanswered { font-size: .85rem; color: var(--muted); font-style: italic; }

        .question-form { background: #fff; border: 1px solid var(--border); border-radius: 12px; padding: 20px; margin-top: 12px; }
        .form-label { font-size: .85rem; font-weight: 600; color: #333; margin-bottom: 6px; display: block; }
        .form-textarea { width: 100%; border: 2px solid var(--border); border-radius: 10px; padding: 12px 14px; font-family: 'DM Sans', sans-serif; font-size: .9rem; resize: vertical; outline: none; transition: border-color .2s; }
        .form-textarea:focus { border-color: var(--tide); }
        .submit-btn { background: var(--tide); color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-family: 'DM Sans', sans-serif; font-size: .9rem; font-weight: 600; cursor: pointer; margin-top: 10px; }
        .submit-btn:hover { background: var(--ocean); }

        .alert-success { background: #d4f7e7; border: 1px solid #1a7a4a; color: #1a7a4a; padding: 12px 18px; border-radius: 10px; margin-bottom: 20px; font-weight: 500; }
        .alert-error { background: #fde8e8; border: 1px solid #a81a1a; color: #a81a1a; padding: 12px 18px; border-radius: 10px; margin-bottom: 12px; font-size: .875rem; }

        .back-link { display: inline-flex; align-items: center; gap: 6px; color: var(--tide); text-decoration: none; font-weight: 600; font-size: .875rem; margin-bottom: 8px; }
        .back-link:hover { color: var(--ocean); }
    </style>
</head>
<body>

<nav class="sh-nav">
    <a href="{{ url('/') }}" class="sh-logo">🐟 Aqua<span>Hub</span></a>
    <ul class="sh-nav-links">
        <li><a href="{{ url('/') }}">Home</a></li>
        <li><a href="{{ route('products.index') }}">Shop</a></li>
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

<div class="breadcrumb">
    <a href="{{ route('products.index') }}">← Back to Marketplace</a>
</div>

@if(session('success'))
    <div style="max-width:1100px;margin:0 auto;padding:0 2rem">
        <div class="alert-success">✅ {{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div style="max-width:1100px;margin:0 auto;padding:0 2rem">
        <div class="alert-error">⚠️ {{ session('error') }}</div>
    </div>
@endif

<div class="product-layout">
    {{-- LEFT COLUMN --}}
    <div>
        {{-- Image --}}
        <div class="product-image-box">
            @if($product->image)
                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
            @else
                @php
                    $emoji = match(true) {
                        str_contains(strtolower($product->name), 'prawn') => '🦐',
                        str_contains(strtolower($product->name), 'tuna')  => '🐟',
                        str_contains(strtolower($product->name), 'perch') => '🐟',
                        str_contains(strtolower($product->name), 'tilapia') => '🐠',
                        $product->category === 'ocean' => '🐡',
                        default => '🐟',
                    };
                @endphp
                <span>{{ $emoji }}</span>
            @endif
            <span class="freshness-badge freshness-{{ $product->freshness }}">
                {{ $product->freshness_label }}
            </span>
        </div>

        {{-- Title + fisherman --}}
        <div style="margin-top:24px">
            <h1 class="product-title">{{ $product->name }}</h1>
            <div class="product-fisherman-row">
                <div class="fisherman-avatar">🎣</div>
                <div class="fisherman-info">
                    <div class="fisherman-name">{{ $product->producer->name }}</div>
                    <div class="fisherman-loc">📍 {{ $product->producer->location ?? 'Kenya' }}</div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:10px;margin-top:12px">
                @if($product->review_count > 0)
                    <span style="color:var(--gold);font-size:1rem;letter-spacing:.5px">
                        @for($i=1;$i<=5;$i++){{ $i <= round($product->avg_rating) ? '★' : '☆' }}@endfor
                    </span>
                    <span style="font-size:.92rem;color:#334155;">
                        {{ $product->avg_rating }} · {{ $product->review_count }} review{{ $product->review_count === 1 ? '' : 's' }}
                    </span>
                @else
                    <span style="font-size:.92rem;color:var(--muted);">No reviews yet — be the first to rate this fish.</span>
                @endif
            </div>
        </div>

        {{-- Details grid --}}
        <div class="detail-grid">
            <div class="detail-cell">
                <div class="detail-label">Category</div>
                <div class="detail-value">{{ ucfirst($product->category) }}</div>
            </div>
            <div class="detail-cell">
                <div class="detail-label">Freshness</div>
                <div class="detail-value">{{ $product->freshness_label }}</div>
            </div>
            <div class="detail-cell">
                <div class="detail-label">Catch Date</div>
                <div class="detail-value">{{ $product->catch_date ? $product->catch_date->format('d M Y') : 'Not specified' }}</div>
            </div>
            <div class="detail-cell">
                <div class="detail-label">Stock Available</div>
                <div class="detail-value">{{ number_format($product->stock_kg, 1) }} kg</div>
            </div>
            <div class="detail-cell">
                <div class="detail-label">Producer Phone</div>
                <div class="detail-value">{{ $product->producer->phone ?? '—' }}</div>
            </div>
            <div class="detail-cell">
                <div class="detail-label">Listed</div>
                <div class="detail-value">{{ $product->created_at->format('d M Y') }}</div>
            </div>
        </div>

        {{-- Description --}}
        @if($product->description)
            <div class="description-box">
                <strong style="font-size:.8rem;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);display:block;margin-bottom:8px">About this product</strong>
                {{ $product->description }}
            </div>
        @endif

        {{-- Questions section --}}
        <div class="questions-section">
            <div class="questions-title">Questions & Answers ({{ $product->questions->count() }})</div>

            @forelse($product->questions as $q)
                <div class="question-card">
                    <div class="question-text">❓ {{ $q->question }}</div>
                    <div class="question-meta">Asked by {{ $q->consumer->name ?? 'Consumer' }} · {{ $q->created_at->diffForHumans() }}</div>
                    @if($q->answer)
                        <div class="answer-text">💬 <strong>Producer:</strong> {{ $q->answer }}</div>
                    @else
                        {{-- Producer answer form --}}
                        @auth
                            @if(auth()->user()->role === 'producer' && $product->user_id === auth()->id())
                                <form method="POST" action="{{ route('questions.answer', $q->id) }}" style="margin-top:10px">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="answer" rows="2" required
                                        style="width:100%;border:2px solid var(--border);border-radius:8px;padding:9px 12px;font-family:'DM Sans',sans-serif;font-size:.88rem;resize:vertical;outline:none"
                                        placeholder="Write your answer...">{{ old('answer') }}</textarea>
                                    <button type="submit"
                                        style="margin-top:6px;background:var(--tide);color:#fff;border:none;padding:8px 18px;border-radius:8px;font-size:.85rem;font-weight:600;cursor:pointer">
                                        💬 Post Answer
                                    </button>
                                </form>
                            @else
                                <div class="unanswered">Awaiting producer response...</div>
                            @endif
                        @else
                            <div class="unanswered">Awaiting producer response...</div>
                        @endauth
                    @endif
                </div>
            @empty
                <p style="color:var(--muted);font-size:.9rem">No questions yet. Be the first to ask!</p>
            @endforelse

            {{-- Ask a question (consumers only) --}}
            @auth
                @if(auth()->user()->role === 'consumer')
                    <div class="question-form">
                        <label class="form-label">Ask a Question</label>
                        @if($errors->has('question'))
                            <div class="alert-error">{{ $errors->first('question') }}</div>
                        @endif
                        <form method="POST" action="{{ route('questions.store', $product->id) }}">
                            @csrf
                            <textarea class="form-textarea" name="question" rows="3" placeholder="e.g. Is this fish deboned? Can you deliver to Nairobi?">{{ old('question') }}</textarea>
                            <button type="submit" class="submit-btn">Send Question</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        {{-- Reviews section --}}
        <div class="questions-section" style="margin-top:32px">
            @if(session('success'))
                <div class="alert-success" style="margin-bottom:18px;max-width:780px">✅ {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert-error" style="margin-bottom:18px;max-width:780px">⚠️ {{ session('error') }}</div>
            @endif
            @php
                $avgRating = $product->avg_rating;
                $reviewCount = $product->review_count;
            @endphp
            <div class="questions-title" style="display:flex;align-items:center;gap:10px">
                Reviews
                @if($reviewCount > 0)
                    <span style="display:inline-flex;align-items:center;gap:5px;font-size:.95rem;font-weight:600;color:var(--gold)">
                        @for($i=1;$i<=5;$i++)
                            {{ $i <= round($avgRating) ? '★' : '☆' }}
                        @endfor
                        <span style="color:var(--muted);font-size:.85rem;font-weight:500">{{ $avgRating }} ({{ $reviewCount }})</span>
                    </span>
                @endif
            </div>

            @forelse($product->reviews as $rev)
                <div class="question-card">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                        <div style="display:flex;align-items:center;gap:8px">
                            <span style="font-size:.88rem;font-weight:700;color:#1a1a2e">{{ $rev->consumer->name ?? 'Consumer' }}</span>
                            <span style="color:var(--gold);font-size:.9rem">
                                @for($i=1;$i<=5;$i++){{ $i <= $rev->rating ? '★' : '☆' }}@endfor
                            </span>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px">
                            <span style="font-size:.78rem;color:var(--muted)">{{ $rev->created_at->diffForHumans() }}</span>
                            @auth
                                @if(auth()->id() === $rev->consumer_id)
                                    <form method="POST" action="{{ route('reviews.destroy', $rev->id) }}" style="margin:0">
                                        @csrf @method('DELETE')
                                        <button type="submit" style="background:none;border:none;color:var(--coral);font-size:.78rem;cursor:pointer;padding:0" onclick="return confirm('Delete your review?')">Delete</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    </div>
                    @if($rev->comment)
                        <div style="font-size:.9rem;color:#374151;line-height:1.6">{{ $rev->comment }}</div>
                    @endif
                </div>
            @empty
                <p style="color:var(--muted);font-size:.9rem">No reviews yet. Be the first to review!</p>
            @endforelse

            {{-- Write a review (consumers can submit multiple reviews) --}}
            @auth
                @if(auth()->user()->role === 'consumer')
                    <div class="question-form" style="margin-top:20px">
                        <label class="form-label">Write a Review</label>
                        @if($errors->has('rating'))
                            <div class="alert-error">{{ $errors->first('rating') }}</div>
                        @endif
                        <form method="POST" action="{{ route('reviews.store', $product->id) }}">
                            @csrf
                            <div style="display:flex;gap:8px;margin-bottom:12px;align-items:center">
                                <span style="font-size:.875rem;font-weight:600;color:#374151">Rating:</span>
                                @for($i=1;$i<=5;$i++)
                                    <label style="cursor:pointer;font-size:1.5rem;color:var(--gold)">
                                        <input type="radio" name="rating" value="{{ $i }}" required style="display:none">
                                        <span class="star-label" data-val="{{ $i }}">☆</span>
                                    </label>
                                @endfor
                            </div>
                            <textarea class="form-textarea" name="comment" rows="3" placeholder="Share your experience with this product...">{{ old('comment') }}</textarea>
                            <button type="submit" class="submit-btn">Submit Review</button>
                        </form>
                        <script>
                            document.querySelectorAll('.star-label').forEach(function(star) {
                                star.addEventListener('click', function() {
                                    var val = parseInt(this.dataset.val);
                                    // Check the corresponding radio button
                                    document.querySelector('input[name="rating"][value="' + val + '"]').checked = true;
                                    // Update star display
                                    document.querySelectorAll('.star-label').forEach(function(s, i) {
                                        s.textContent = (i + 1) <= val ? '★' : '☆';
                                    });
                                });
                            });
                        </script>
                    </div>
                @endif
            @endauth
        </div>
    </div>

    {{-- RIGHT COLUMN — Order box --}}
    <div>
        <div class="order-box">
            <div class="price-big">
                KES {{ number_format($product->price_per_kg, 0) }}
                <span class="price-unit">/kg</span>
            </div>
            <div class="stock-row {{ $product->stock_kg < 5 ? 'stock-low' : '' }}">
                {{ $product->stock_kg < 5 ? '⚠️' : '✅' }}
                {{ number_format($product->stock_kg, 1) }} kg available
            </div>

            @if($product->is_available && $product->stock_kg > 0)
                <div class="qty-row">
                    <span class="qty-label">Quantity (kg):</span>
                    <input type="number" class="qty-input" id="qty-input" value="1" min="0.5" max="{{ $product->stock_kg }}" step="0.5">
                </div>
                <div style="font-size:.85rem;color:var(--muted);margin-bottom:16px">
                    Total: <strong id="total-price" style="color:var(--ocean)">KES {{ number_format($product->price_per_kg, 0) }}</strong>
                </div>
                @auth
                    @if(auth()->user()->role === 'consumer')
                        <form method="POST" action="{{ route('cart.add', $product->id) }}" id="cart-form">
                            @csrf
                            <input type="hidden" name="qty" id="cart-qty-hidden" value="1">
                            <button type="submit" class="cart-btn">🛒 Add to Cart</button>
                        </form>
                    @else
                        <button class="cart-btn cart-btn-disabled" disabled title="Only consumers can order">🛒 Add to Cart</button>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="cart-btn" style="display:block;text-align:center;text-decoration:none">🔐 Login to Order</a>
                @endauth
            @else
                <button class="cart-btn cart-btn-disabled" disabled>Out of Stock</button>
            @endif

            <div style="font-size:.78rem;color:var(--muted);text-align:center;margin-top:12px;line-height:1.6">
                📱 Pay via M-Pesa · 🚚 Delivery across Kenya
            </div>

            {{-- Producer-only actions --}}
            @auth
                @if(auth()->user()->id === $product->user_id)
                    <hr style="border:none;border-top:1px solid var(--border);margin:16px 0">
                    <div style="font-size:.8rem;color:var(--muted);margin-bottom:8px;font-weight:600;text-transform:uppercase;letter-spacing:.5px">Your Product</div>
                    <div class="producer-actions">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn-edit">✏️ Edit</a>
                        <form method="POST" action="{{ route('products.destroy', $product->id) }}" onsubmit="return confirm('Delete this product? This cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete">🗑️ Delete</button>
                        </form>
                    </div>
                @endif
            @endauth
        </div>

        {{-- Producer card --}}
        <div style="background:#fff;border:1px solid var(--border);border-radius:16px;padding:20px;margin-top:16px;text-align:center">
            <div style="width:56px;height:56px;border-radius:50%;background:linear-gradient(135deg,var(--ocean),var(--tide));display:flex;align-items:center;justify-content:center;font-size:1.6rem;margin:0 auto 12px">🎣</div>
            <div style="font-family:'Playfair Display',serif;font-weight:700;font-size:1rem;margin-bottom:4px">{{ $product->producer->name }}</div>
            <div style="font-size:.8rem;color:var(--muted);margin-bottom:12px">📍 {{ $product->producer->location ?? 'Kenya' }}</div>
            <a href="{{ route('products.index', ['search' => $product->producer->name]) }}"
               style="font-size:.85rem;color:var(--tide);font-weight:600;text-decoration:none">View all products →</a>
        </div>
    </div>
</div>

<footer style="background:#051e28;color:rgba(255,255,255,.5);text-align:center;padding:28px;font-size:.85rem;">
    <p>© 2026 <span style="color:#f0a500">AquaHub</span> — Empowering Kenya's Fishing Community 🐟</p>
</footer>

<script>
    const pricePerKg = {{ $product->price_per_kg }};
    const qtyInput = document.getElementById('qty-input');
    const totalEl = document.getElementById('total-price');

    const cartQtyHidden = document.getElementById('cart-qty-hidden');
    if (qtyInput) {
        qtyInput.addEventListener('input', () => {
            const qty = parseFloat(qtyInput.value) || 0;
            const total = Math.round(qty * pricePerKg);
            totalEl.textContent = 'KES ' + total.toLocaleString();
            if (cartQtyHidden) cartQtyHidden.value = qtyInput.value;
        });
    }
</script>

</body>
</html>
