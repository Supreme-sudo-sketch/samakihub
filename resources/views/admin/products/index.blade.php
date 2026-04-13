@extends('admin.layouts.sidebar')

@section('title', 'Products')
@section('topbar-title', 'Products Overview')

@push('styles')
<style>
    .filters{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end}
    .filter-group{display:flex;flex-direction:column;gap:4px}
    .filter-label{font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.4px}
    .filter-input,.filter-select{border:2px solid var(--border);border-radius:8px;padding:7px 11px;font-family:'DM Sans',sans-serif;font-size:.875rem;outline:none;background:#fff;color:#1a1a2e;min-width:160px}
    .filter-input:focus,.filter-select:focus{border-color:var(--tide)}
    .filter-btn{background:var(--ocean);color:#fff;border:none;padding:8px 18px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:700}
    .filter-reset{font-size:.82rem;color:var(--tide);text-decoration:none;font-weight:600;align-self:center}

    .card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden}
    .table{width:100%;border-collapse:collapse}
    .table th{text-align:left;padding:10px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--muted);background:#f8fbfc}
    .table td{padding:11px 16px;font-size:.875rem;border-bottom:1px solid var(--foam);vertical-align:middle}
    .table tr:last-child td{border-bottom:none}
    .table tr:hover td{background:#f8fbfc}

    .product-cell{display:flex;align-items:center;gap:10px}
    .product-thumb{width:40px;height:40px;border-radius:10px;object-fit:cover;background:linear-gradient(135deg,var(--ocean),var(--tide));display:flex;align-items:center;justify-content:center;font-size:1.1rem;flex-shrink:0;overflow:hidden}
    .product-thumb img{width:100%;height:100%;object-fit:cover}

    .pill{padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:capitalize}
    .p-available{background:#d4f7e7;color:#1a7a4a}
    .p-unavailable{background:#fde8e8;color:#a81a1a}

    .freshness-badge{padding:2px 8px;border-radius:6px;font-size:.7rem;font-weight:700}
    .f-super_fresh{background:#d0f4ff;color:#0057a8}
    .f-fresh{background:#d4f7e7;color:#1a7a4a}
    .f-smoked{background:#fff3d0;color:#a07000}
    .f-dried{background:#fde8e8;color:#a81a1a}

    .pagination-wrap{padding:16px 20px;border-top:1px solid var(--border)}
</style>
@endpush

@section('content')

<form method="GET" action="{{ route('admin.products') }}">
    <div class="filters">
        <div class="filter-group">
            <label class="filter-label">Search</label>
            <input type="text" name="search" class="filter-input" placeholder="Product name…" value="{{ request('search') }}">
        </div>
        <div class="filter-group">
            <label class="filter-label">Category</label>
            <select name="category" class="filter-select">
                <option value="">All Categories</option>
                @foreach(['tilapia','salmon','tuna','catfish','sardines','bass','trout','other'] as $cat)
                    <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="filter-btn">Filter</button>
        @if(request()->anyFilled(['search','category']))
            <a href="{{ route('admin.products') }}" class="filter-reset">✕ Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Producer</th>
                <th>Category</th>
                <th>Freshness</th>
                <th>Reviews</th>
                <th>Price/kg</th>
                <th>Stock (kg)</th>
                <th>Status</th>
                <th>Listed</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
                <tr>
                    <td>
                        <div class="product-cell">
                            <div class="product-thumb">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="">
                                @else
                                    🐟
                                @endif
                            </div>
                            <div style="font-weight:700;font-size:.88rem">{{ $product->name }}</div>
                        </div>
                    </td>
                    <td style="font-size:.83rem">{{ $product->producer->name ?? '—' }}</td>
                    <td style="font-size:.82rem;color:var(--muted);text-transform:capitalize">{{ $product->category }}</td>
                    <td>
                        <span class="freshness-badge f-{{ $product->freshness }}">{{ $product->freshness_label }}</span>
                    </td>
                    <td style="font-weight:700">KES {{ number_format($product->price_per_kg, 0) }}</td>
                    <td>{{ number_format($product->stock_kg, 1) }}</td>
                    <td style="font-size:.84rem;white-space:nowrap">
                        @if($product->reviews_count > 0)
                            <span style="color:var(--gold);font-weight:700;">
                                @for($i=1; $i<=5; $i++){{ $i <= round($product->reviews_avg_rating ?? 0) ? '★' : '☆' }}@endfor
                            </span>
                            <div style="color:var(--muted);font-size:.78rem;margin-top:2px">
                                {{ number_format($product->reviews_avg_rating ?? 0, 1) }} · {{ $product->reviews_count }}
                            </div>
                        @else
                            <span style="color:var(--muted);font-size:.84rem">No reviews</span>
                        @endif
                    </td>
                    <td style="font-weight:700">KES {{ number_format($product->price_per_kg, 0) }}</td>
                    <td>{{ number_format($product->stock_kg, 1) }}</td>
                    <td>
                        <span class="pill {{ $product->is_available ? 'p-available' : 'p-unavailable' }}">
                            {{ $product->is_available ? 'Available' : 'Hidden' }}
                        </span>
                    </td>
                    <td style="font-size:.8rem;color:var(--muted)">{{ $product->created_at->format('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:36px;color:var(--muted)">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($products->hasPages())
        <div class="pagination-wrap">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection
