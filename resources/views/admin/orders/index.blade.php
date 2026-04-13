@extends('admin.layouts.sidebar')

@section('title', 'Orders')
@section('topbar-title', 'Orders Overview')

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
    .table th{text-align:left;padding:10px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--muted);background:#f8fbfc;white-space:nowrap}
    .table td{padding:11px 16px;font-size:.875rem;border-bottom:1px solid var(--foam);vertical-align:middle}
    .table tr:last-child td{border-bottom:none}
    .table tr:hover td{background:#f8fbfc}

    .pill{padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:uppercase}
    .p-confirmed{background:#d4eeff;color:#0057a8}
    .p-preparing{background:#fff3d0;color:#a07000}
    .p-in_transit{background:#e0f0ff;color:#0057a8}
    .p-delivered{background:#d4f7e7;color:#1a7a4a}
    .p-cancelled{background:#fde8e8;color:#a81a1a}

    .view-link{color:var(--tide);text-decoration:none;font-size:.82rem;font-weight:700}
    .view-link:hover{color:var(--ocean)}
    .pagination-wrap{padding:20px;border-top:1px solid var(--border);display:flex;flex-direction:column;gap:16px;align-items:center}
    .pagination-info{font-size:.83rem;color:var(--muted);text-align:center}
    .pagination-nav{display:flex;align-items:center;justify-content:center;gap:8px;flex-wrap:wrap}
    .pagination-nav a,.pagination-nav span{padding:6px 10px;border:1px solid var(--border);border-radius:6px;font-size:.82rem;text-decoration:none;color:var(--ocean);background:#fff;transition:.2s}
    .pagination-nav a:hover{background:var(--foam);border-color:var(--tide)}
    .pagination-nav .active{background:var(--ocean);color:#fff;border-color:var(--ocean);font-weight:700}
    .pagination-nav .disabled{color:var(--muted);cursor:not-allowed;opacity:.5}
    .pagination-nav .disabled:hover{background:#fff;border-color:var(--border)}
</style>
@endpush

@section('content')

<form method="GET" action="{{ route('admin.orders') }}">
    <div class="filters">
        <div class="filter-group">
            <label class="filter-label">Search</label>
            <input type="text" name="search" class="filter-input" placeholder="Order # or customer name…" value="{{ request('search') }}">
        </div>
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                @foreach(\App\Models\Order::STATUS_LABELS as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="filter-btn">Filter</button>
        @if(request()->anyFilled(['search','status']))
            <a href="{{ route('admin.orders') }}" class="filter-reset">✕ Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Delivery Agent</th>
                <th>Items</th>
                <th>Total</th>
                <th>Status</th>
                <th>Placed</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td style="font-weight:700;color:var(--ocean)">#{{ $order->id }}</td>
                    <td>
                        <div style="font-weight:700;font-size:.88rem">{{ $order->consumer->name ?? '—' }}</div>
                        <div style="font-size:.77rem;color:var(--muted)">{{ $order->consumer->phone ?? '' }}</div>
                    </td>
                    <td style="font-size:.83rem;color:var(--muted)">
                        {{ $order->deliveryAgent->name ?? '—' }}
                    </td>
                    <td style="color:var(--muted);font-size:.83rem">{{ $order->items->count() }} item(s)</td>
                    <td style="font-weight:700">KES {{ number_format($order->total_amount, 0) }}</td>
                    <td><span class="pill p-{{ $order->status }}">{{ $order->status_label }}</span></td>
                    <td style="font-size:.8rem;color:var(--muted)">
                        {{ $order->created_at->format('d M Y') }}<br>
                        <span style="font-size:.75rem">{{ $order->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="view-link" target="_blank">View →</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align:center;padding:36px;color:var(--muted)">No orders found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($orders->hasPages())
        <div class="pagination-wrap">
            <div class="pagination-info">
                Showing <strong>{{ $orders->firstItem() }}</strong> to <strong>{{ $orders->lastItem() }}</strong> of <strong>{{ $orders->total() }}</strong> orders
            </div>
            <div class="pagination-nav">
                {{ $orders->links('vendor.pagination.custom') }}
            </div>
        </div>
    @else
        <div class="pagination-wrap">
            <div class="pagination-info">
                Showing <strong>{{ $orders->count() }}</strong> order{{ $orders->count() !== 1 ? 's' : '' }}
            </div>
        </div>
    @endif
</div>

@endsection
