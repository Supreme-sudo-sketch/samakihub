@extends('admin.layouts.sidebar')

@section('title', 'Reviews')
@section('topbar-title', 'Product Reviews')

@push('styles')
<style>
    .filters{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end}
    .filter-group{display:flex;flex-direction:column;gap:4px}
    .filter-label{font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.4px}
    .filter-input,.filter-select{border:2px solid var(--border);border-radius:8px;padding:7px 11px;font-family:'DM Sans',sans-serif;font-size:.875rem;outline:none;background:#fff;color:#1a1a2e;min-width:220px}
    .filter-btn{background:var(--ocean);color:#fff;border:none;padding:8px 18px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:700}
    .filter-reset{font-size:.82rem;color:var(--tide);text-decoration:none;font-weight:600;align-self:center}
    .card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden}
    .table{width:100%;border-collapse:collapse}
    .table th{text-align:left;padding:12px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--muted);background:#f8fbfc}
    .table td{padding:14px 16px;font-size:.88rem;border-bottom:1px solid var(--foam);vertical-align:top}
    .table tr:last-child td{border-bottom:none}
    .review-product{font-weight:700;font-size:.95rem;}
    .review-comment{color:#334155;line-height:1.6;max-width:460px;}
    .review-meta{display:flex;flex-wrap:wrap;gap:10px;color:var(--muted);font-size:.82rem}
    .rating-stars{color:var(--gold);font-size:1rem;letter-spacing:.5px}
</style>
@endpush

@section('content')
<form method="GET" action="{{ route('admin.reviews') }}">
    <div class="filters">
        <div class="filter-group">
            <label class="filter-label">Search</label>
            <input type="text" name="search" class="filter-input" placeholder="Product, consumer, comment…" value="{{ request('search') }}">
        </div>
        <button type="submit" class="filter-btn">Filter</button>
        @if(request()->filled('search'))
            <a href="{{ route('admin.reviews') }}" class="filter-reset">✕ Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Consumer</th>
                <th>Rating</th>
                <th>Comment</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td>
                        <div class="review-product">{{ $review->product->name ?? 'Deleted product' }}</div>
                        <div class="review-meta">Producer: {{ $review->product->producer->name ?? 'Unknown' }}</div>
                    </td>
                    <td>
                        <div>{{ $review->consumer->name ?? 'Unknown' }}</div>
                        <div class="review-meta">{{ $review->consumer->email ?? '—' }}</div>
                    </td>
                    <td>
                        <div class="rating-stars">
                            @for($i = 1; $i <= 5; $i++){{ $i <= $review->rating ? '★' : '☆' }}@endfor
                        </div>
                        <div class="review-meta">{{ $review->rating }} / 5</div>
                    </td>
                    <td>
                        <div class="review-comment">{{ $review->comment ?: 'No comment provided.' }}</div>
                    </td>
                    <td style="white-space:nowrap;color:var(--muted);font-size:.82rem">{{ $review->created_at->format('d M Y, H:i') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:32px;color:var(--muted)">No reviews found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($reviews->hasPages())
        <div class="pagination-wrap" style="padding:16px 20px;border-top:1px solid var(--border)">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
