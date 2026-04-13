@extends('admin.layouts.sidebar')

@section('title', 'Dashboard')
@section('topbar-title', 'Dashboard')

@push('styles')
<style>
    /* Stat cards */
    .stats-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:28px}
    .stat-card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:20px 22px;display:flex;align-items:flex-start;gap:14px}
    .stat-icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0}
    .stat-icon.ocean{background:rgba(10,61,79,.1)}
    .stat-icon.gold{background:rgba(240,165,0,.12)}
    .stat-icon.fresh{background:rgba(46,184,122,.12)}
    .stat-icon.coral{background:rgba(232,93,58,.1)}
    .stat-value{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:900;color:var(--ocean);line-height:1}
    .stat-label{font-size:.8rem;color:var(--muted);font-weight:500;margin-top:4px}
    .stat-sub{font-size:.75rem;font-weight:700;margin-top:6px}
    .stat-sub.up{color:var(--fresh)}
    .stat-sub.warn{color:var(--gold)}

    /* Two-column layout */
    .dash-grid{display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start}
    @media(max-width:1100px){.dash-grid{grid-template-columns:1fr}}

    /* Card */
    .card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden;margin-bottom:20px}
    .card-head{padding:16px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between}
    .card-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--ocean)}
    .card-link{font-size:.82rem;color:var(--tide);text-decoration:none;font-weight:600}
    .card-link:hover{color:var(--ocean)}

    /* Orders table */
    .table{width:100%;border-collapse:collapse}
    .table th{text-align:left;padding:8px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--muted);background:#f8fbfc}
    .table td{padding:11px 16px;font-size:.875rem;border-bottom:1px solid var(--foam)}
    .table tr:last-child td{border-bottom:none}
    .table tr:hover td{background:#f8fbfc}

    /* Status pills */
    .pill{padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:uppercase;white-space:nowrap}
    .p-confirmed{background:#d4eeff;color:#0057a8}
    .p-preparing{background:#fff3d0;color:#a07000}
    .p-in_transit{background:#e0f0ff;color:#0057a8}
    .p-delivered{background:#d4f7e7;color:#1a7a4a}
    .p-cancelled{background:#fde8e8;color:#a81a1a}

    /* Pending users list */
    .user-row{display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--foam)}
    .user-row:last-child{border-bottom:none}
    .user-avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,var(--ocean),var(--tide));display:flex;align-items:center;justify-content:center;font-size:.9rem;font-weight:700;color:#fff;flex-shrink:0}
    .user-meta{flex:1;min-width:0}
    .user-name{font-size:.88rem;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
    .user-role{font-size:.75rem;color:var(--muted)}
    .approve-btn{background:var(--fresh);color:#fff;border:none;padding:6px 12px;border-radius:8px;cursor:pointer;font-size:.78rem;font-weight:700;font-family:inherit;white-space:nowrap}
    .approve-btn:hover{background:#25a06a}

    /* Role badge */
    .role-badge{padding:3px 9px;border-radius:20px;font-size:.7rem;font-weight:700;text-transform:capitalize}
    .r-producer{background:#e8f4f8;color:var(--ocean)}
    .r-consumer{background:#f0fff4;color:#1a7a4a}
    .r-delivery{background:#fff8e6;color:#a07000}
    .r-admin{background:#fde8e8;color:#a81a1a}
</style>
@endpush

@section('topbar-actions')
    <a href="{{ route('admin.users', ['status' => 'pending']) }}" style="background:var(--ocean);color:#fff;padding:8px 16px;border-radius:8px;text-decoration:none;font-size:.82rem;font-weight:700">
        Pending Users ({{ $stats['pending_users'] }})
    </a>
@endsection

@section('content')

{{-- Stats row --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon ocean">👥</div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_users']) }}</div>
            <div class="stat-label">Total Users</div>
            @if($stats['pending_users'] > 0)
                <div class="stat-sub warn">{{ $stats['pending_users'] }} pending approval</div>
            @endif
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon ocean">🐟</div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_products']) }}</div>
            <div class="stat-label">Products Listed</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon fresh">📦</div>
        <div>
            <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
            <div class="stat-label">Total Orders</div>
            @if($stats['active_orders'] > 0)
                <div class="stat-sub up">{{ $stats['active_orders'] }} active now</div>
            @endif
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon gold">💰</div>
        <div>
            <div class="stat-value">{{ number_format($stats['revenue'] / 1000, 1) }}K</div>
            <div class="stat-label">Revenue (KES)</div>
        </div>
    </div>
</div>

<div class="dash-grid">
    {{-- Recent orders --}}
    <div>
        <div class="card">
            <div class="card-head">
                <span class="card-title">Recent Orders</span>
                <a href="{{ route('admin.orders') }}" class="card-link">View all →</a>
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr>
                            <td style="font-weight:700;color:var(--ocean)">#{{ $order->id }}</td>
                            <td>{{ $order->consumer->name ?? '—' }}</td>
                            <td style="color:var(--muted)">{{ $order->items->count() }} items</td>
                            <td style="font-weight:700">KES {{ number_format($order->total_amount, 0) }}</td>
                            <td><span class="pill p-{{ $order->status }}">{{ $order->status_label }}</span></td>
                            <td style="color:var(--muted);font-size:.8rem">{{ $order->created_at->format('d M') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" style="text-align:center;color:var(--muted);padding:24px">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Pending approvals --}}
    <div>
        <div class="card">
            <div class="card-head">
                <span class="card-title">Pending Approvals</span>
                <a href="{{ route('admin.users', ['status' => 'pending']) }}" class="card-link">All →</a>
            </div>
            @forelse($pendingUsers as $user)
                <div class="user-row">
                    <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                    <div class="user-meta">
                        <div class="user-name">{{ $user->name }}</div>
                        <div class="user-role">
                            <span class="role-badge r-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
                            · {{ $user->created_at->diffForHumans() }}
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.users.approve', $user->id) }}">
                        @csrf
                        <button type="submit" class="approve-btn">Approve</button>
                    </form>
                </div>
            @empty
                <div style="padding:28px;text-align:center;color:var(--muted);font-size:.88rem">
                    🎉 No pending approvals!
                </div>
            @endforelse
        </div>

        {{-- Quick stats by role --}}
        <div class="card">
            <div class="card-head">
                <span class="card-title">Users by Role</span>
            </div>
            @foreach(['consumer','producer','delivery','admin'] as $role)
                @php $cnt = \App\Models\User::where('role', $role)->count(); @endphp
                <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 20px;border-bottom:1px solid var(--foam)">
                    <span style="font-size:.875rem;font-weight:600">{{ ucfirst($role) }}</span>
                    <div style="display:flex;align-items:center;gap:10px">
                        <div style="width:80px;height:6px;background:var(--foam);border-radius:3px;overflow:hidden">
                            @php $max = max(1, \App\Models\User::count()); @endphp
                            <div style="width:{{ min(100, $cnt / $max * 100) }}%;height:100%;background:var(--tide);border-radius:3px"></div>
                        </div>
                        <span style="font-size:.82rem;font-weight:700;color:var(--ocean);min-width:24px;text-align:right">{{ $cnt }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@endsection
