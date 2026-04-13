<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit {{ $product->name }} — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --ocean: #0a3d4f; --deep: #051e28; --tide: #0d6e8a;
            --foam: #e8f4f8; --coral: #e85d3a; --gold: #f0a500;
            --fresh: #2eb87a; --muted: #6b7d8a; --border: #dde8ed;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--foam); color: #1a1a2e; }
        .sh-nav { background: var(--ocean); padding: 0 2rem; display: flex; align-items: center; justify-content: space-between; height: 64px; position: sticky; top: 0; z-index: 100; box-shadow: 0 2px 20px rgba(0,0,0,.3); }
        .sh-logo { font-family: 'Playfair Display', serif; font-size: 1.5rem; font-weight: 900; color: #fff; text-decoration: none; }
        .sh-logo span { color: var(--gold); }
        .sh-nav-links { display: flex; gap: 6px; list-style: none; }
        .sh-nav-links a { color: rgba(255,255,255,.7); font-size: .875rem; font-weight: 500; padding: 7px 13px; border-radius: 8px; text-decoration: none; transition: all .2s; }
        .sh-nav-links a:hover { background: rgba(255,255,255,.12); color: #fff; }
        .sh-role-badge { background: var(--gold); color: var(--deep); font-size: .75rem; font-weight: 700; padding: 4px 10px; border-radius: 20px; }
        .page-header { background: #fff; border-bottom: 1px solid var(--border); padding: 24px 2rem; }
        .page-header h2 { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; color: var(--ocean); margin-bottom: 4px; }
        .page-header p { color: var(--muted); font-size: .9rem; }
        .form-wrap { max-width: 720px; margin: 40px auto; padding: 0 2rem 60px; }
        .form-card { background: #fff; border: 1px solid var(--border); border-radius: 20px; padding: 36px; }
        .form-section-title { font-family: 'Playfair Display', serif; font-size: 1.1rem; font-weight: 700; color: var(--ocean); margin: 28px 0 16px; padding-bottom: 8px; border-bottom: 2px solid var(--foam); }
        .form-section-title:first-child { margin-top: 0; }
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: .875rem; font-weight: 600; color: #374151; margin-bottom: 6px; }
        .form-label span { color: var(--coral); }
        .form-input, .form-select, .form-textarea { width: 100%; border: 2px solid var(--border); border-radius: 10px; padding: 11px 14px; font-family: 'DM Sans', sans-serif; font-size: .95rem; outline: none; background: #fff; transition: border-color .2s; color: #1a1a2e; }
        .form-input:focus, .form-select:focus, .form-textarea:focus { border-color: var(--tide); }
        .form-textarea { resize: vertical; min-height: 100px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        @media(max-width:600px){ .form-row { grid-template-columns: 1fr; } }
        .form-error { color: var(--coral); font-size: .82rem; margin-top: 5px; }
        .freshness-options { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .freshness-option { position: relative; }
        .freshness-option input[type=radio] { position: absolute; opacity: 0; width: 0; }
        .freshness-label-card { display: flex; flex-direction: column; align-items: center; padding: 14px; border: 2px solid var(--border); border-radius: 12px; cursor: pointer; transition: all .2s; text-align: center; }
        .freshness-label-card:hover { border-color: var(--tide); }
        .freshness-option input:checked + .freshness-label-card { border-color: var(--ocean); background: var(--foam); }
        .freshness-emoji { font-size: 1.8rem; margin-bottom: 4px; }
        .freshness-name { font-size: .8rem; font-weight: 700; color: #1a1a2e; }
        .freshness-desc { font-size: .72rem; color: var(--muted); margin-top: 2px; }
        .current-image { border-radius: 10px; max-height: 160px; object-fit: cover; margin-bottom: 10px; display: block; }
        .upload-area { border: 2px dashed var(--border); border-radius: 12px; padding: 24px; text-align: center; cursor: pointer; transition: border-color .2s; position: relative; }
        .upload-area:hover { border-color: var(--tide); }
        .upload-area input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; }
        .preview-img { max-width: 100%; max-height: 160px; border-radius: 10px; margin-top: 10px; display: none; }
        .submit-btn { width: 100%; background: var(--ocean); color: #fff; border: none; padding: 14px; border-radius: 12px; font-family: 'DM Sans', sans-serif; font-size: 1rem; font-weight: 700; cursor: pointer; transition: background .2s; margin-top: 8px; }
        .submit-btn:hover { background: var(--tide); }
        .cancel-link { display: block; text-align: center; margin-top: 14px; color: var(--muted); font-size: .9rem; text-decoration: none; }
        .cancel-link:hover { color: var(--ocean); }
        .alert-error-box { background: #fde8e8; border: 1px solid #a81a1a; color: #a81a1a; padding: 14px 18px; border-radius: 10px; margin-bottom: 24px; font-size: .875rem; }
        .alert-error-box ul { margin: 6px 0 0 16px; }
        .toggle-row { display: flex; align-items: center; gap: 12px; }
        .toggle-label { font-size: .9rem; color: #374151; }
    </style>
</head>
<body>

<nav class="sh-nav">
    <a href="{{ url('/') }}" class="sh-logo">🐟 Aqua<span>Hub</span></a>
    <ul class="sh-nav-links">
        <li><a href="{{ route('products.index') }}">Marketplace</a></li>
        <li><a href="{{ route('producer.dashboard') }}">Dashboard</a></li>
    </ul>
    <div style="display:flex;align-items:center;gap:10px">
        <span class="sh-role-badge">Producer</span>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" style="background:rgba(255,255,255,.15);border:none;color:#fff;padding:7px 14px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.85rem;font-weight:600;">Logout</button>
        </form>
    </div>
</nav>

<div class="page-header">
    <h2>Edit Product</h2>
    <p>Update your fish listing on the marketplace</p>
</div>

<div class="form-wrap">
    <div class="form-card">

        @if($errors->any())
            <div class="alert-error-box">
                <strong>Please fix the following errors:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="form-section-title">Basic Information</div>

            <div class="form-group">
                <label class="form-label" for="name">Fish Name <span>*</span></label>
                <input class="form-input" type="text" id="name" name="name"
                    value="{{ old('name', $product->name) }}" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="category">Category <span>*</span></label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="freshwater" {{ old('category', $product->category) === 'freshwater' ? 'selected' : '' }}>🌊 Freshwater (Lakes/Rivers)</option>
                        <option value="ocean"      {{ old('category', $product->category) === 'ocean'      ? 'selected' : '' }}>🌊 Ocean (Indian Ocean)</option>
                        <option value="shellfish"  {{ old('category', $product->category) === 'shellfish'  ? 'selected' : '' }}>🦐 Shellfish & Prawns</option>
                        <option value="smoked"     {{ old('category', $product->category) === 'smoked'     ? 'selected' : '' }}>🔥 Smoked Fish</option>
                        <option value="dried"      {{ old('category', $product->category) === 'dried'      ? 'selected' : '' }}>☀️ Dried Fish (Omena/Dagaa)</option>
                        <option value="other"      {{ old('category', $product->category) === 'other'      ? 'selected' : '' }}>🐠 Other</option>
                    </select>
                    @error('category')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="catch_date">Catch Date</label>
                    <input class="form-input" type="date" id="catch_date" name="catch_date"
                        value="{{ old('catch_date', $product->catch_date?->format('Y-m-d')) }}"
                        max="{{ now()->format('Y-m-d') }}">
                    @error('catch_date')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="description">Description</label>
                <textarea class="form-textarea" id="description" name="description">{{ old('description', $product->description) }}</textarea>
                @error('description')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-section-title">Pricing & Stock</div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label" for="price_per_kg">Price per kg (KES) <span>*</span></label>
                    <input class="form-input" type="number" id="price_per_kg" name="price_per_kg"
                        value="{{ old('price_per_kg', $product->price_per_kg) }}" min="1" step="1">
                    @error('price_per_kg')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="stock_kg">Available Stock (kg) <span>*</span></label>
                    <input class="form-input" type="number" id="stock_kg" name="stock_kg"
                        value="{{ old('stock_kg', $product->stock_kg) }}" min="0" step="0.5">
                    @error('stock_kg')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Availability</label>
                <div class="toggle-row">
                    <input type="hidden" name="is_available" value="0">
                    <input type="checkbox" id="is_available" name="is_available" value="1"
                        {{ old('is_available', $product->is_available) ? 'checked' : '' }}
                        style="width:18px;height:18px;accent-color:var(--fresh)">
                    <label class="toggle-label" for="is_available">Product is available for purchase</label>
                </div>
            </div>

            <div class="form-section-title">Freshness</div>

            <div class="form-group">
                <div class="freshness-options">
                    @php
                        $freshnessOptions = [
                            'super_fresh' => ['emoji' => '⚡', 'name' => 'Super Fresh', 'desc' => 'Caught today'],
                            'fresh'       => ['emoji' => '✅', 'name' => 'Fresh',       'desc' => 'Caught recently'],
                            'smoked'      => ['emoji' => '🔥', 'name' => 'Smoked',      'desc' => 'Traditionally smoked'],
                            'dried'       => ['emoji' => '☀️', 'name' => 'Dried',       'desc' => 'Sun-dried / Omena'],
                        ];
                    @endphp
                    @foreach($freshnessOptions as $value => $opt)
                        <div class="freshness-option">
                            <input type="radio" id="freshness_{{ $value }}" name="freshness" value="{{ $value }}"
                                {{ old('freshness', $product->freshness) === $value ? 'checked' : '' }} required>
                            <label class="freshness-label-card" for="freshness_{{ $value }}">
                                <span class="freshness-emoji">{{ $opt['emoji'] }}</span>
                                <span class="freshness-name">{{ $opt['name'] }}</span>
                                <span class="freshness-desc">{{ $opt['desc'] }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>
                @error('freshness')<div class="form-error" style="margin-top:8px">{{ $message }}</div>@enderror
            </div>

            <div class="form-section-title">Product Photo</div>

            <div class="form-group">
                @if($product->image)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="current-image">
                    <p style="font-size:.8rem;color:var(--muted);margin-bottom:10px">Current image. Upload a new one to replace it.</p>
                @endif
                <div class="upload-area">
                    <input type="file" name="image" accept="image/jpg,image/jpeg,image/png,image/webp"
                        onchange="previewImage(this)">
                    <div style="font-size:.9rem;color:var(--muted)">
                        <strong style="color:var(--tide)">Click to upload</strong> new image (optional)
                    </div>
                    <img class="preview-img" id="preview-img" alt="Preview">
                </div>
                @error('image')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="submit-btn">💾 Save Changes</button>
        </form>

        <a href="{{ route('products.show', $product->id) }}" class="cancel-link">← Cancel, back to product</a>
    </div>
</div>

<footer style="background:#051e28;color:rgba(255,255,255,.5);text-align:center;padding:28px;font-size:.85rem;">
    <p>© 2026 <span style="color:#f0a500">AquaHub</span> — Empowering Kenya's Fishing Community 🐟</p>
</footer>

<script>
    function previewImage(input) {
        const preview = document.getElementById('preview-img');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
</body>
</html>
