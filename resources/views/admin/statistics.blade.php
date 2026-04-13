@extends('admin.layouts.sidebar')

@section('title', 'Statistics')
@section('topbar-title', 'Platform Statistics')

@push('styles')
<style>
    .stats-row{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:28px}
    .stat-tile{background:#fff;border:1px solid var(--border);border-radius:14px;padding:18px 20px;text-align:center}
    .stat-num{font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:var(--ocean)}
    .stat-lbl{font-size:.8rem;color:var(--muted);margin-top:4px;font-weight:500}

    .charts-grid{display:grid;grid-template-columns:2fr 1fr;gap:20px;margin-bottom:24px}
    .charts-grid-3{display:grid;grid-template-columns:1fr 1fr;gap:20px}
    @media(max-width:1100px){.charts-grid{grid-template-columns:1fr}.charts-grid-3{grid-template-columns:1fr}}

    .card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:20px 22px}
    .card-title{font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:var(--ocean);margin-bottom:16px}
    .chart-wrap{position:relative;height:260px}

    /* Top products table */
    .top-table{width:100%;border-collapse:collapse}
    .top-table tr:hover td{background:#f8fbfc}
    .top-table td{padding:9px 0;font-size:.875rem;border-bottom:1px solid var(--foam)}
    .top-table tr:last-child td{border-bottom:none}
    .rank{font-weight:900;color:var(--ocean);min-width:24px}
    .bar-cell{width:100px}
    .mini-bar{height:8px;background:var(--tide);border-radius:4px;min-width:4px}
</style>
@endpush

@section('content')

{{-- Summary tiles --}}
<div class="stats-row">
    <div class="stat-tile">
        <div class="stat-num">{{ number_format($ordersByStatus->sum()) }}</div>
        <div class="stat-lbl">Total Orders</div>
    </div>
    <div class="stat-tile">
        <div class="stat-num" style="color:var(--fresh)">{{ number_format($ordersByStatus->get('delivered', 0)) }}</div>
        <div class="stat-lbl">Delivered</div>
    </div>
    <div class="stat-tile">
        <div class="stat-num" style="color:var(--gold)">{{ number_format($ordersByStatus->get('in_transit', 0) + $ordersByStatus->get('preparing', 0) + $ordersByStatus->get('confirmed', 0)) }}</div>
        <div class="stat-lbl">In Progress</div>
    </div>
    <div class="stat-tile">
        <div class="stat-num" style="color:var(--coral)">{{ number_format($ordersByStatus->get('cancelled', 0)) }}</div>
        <div class="stat-lbl">Cancelled</div>
    </div>
    <div class="stat-tile">
        <div class="stat-num">{{ number_format($usersByRole->sum()) }}</div>
        <div class="stat-lbl">Total Users</div>
    </div>
    <div class="stat-tile">
        <div class="stat-num">{{ number_format($topProducts->sum('total_kg'), 0) }} kg</div>
        <div class="stat-lbl">Fish Sold</div>
    </div>
</div>

<div class="charts-grid">
    {{-- Monthly Revenue Bar Chart --}}
    <div class="card">
        <div class="card-title">Monthly Revenue (KES) — Last 12 Months</div>
        <div class="chart-wrap">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    {{-- Orders by Status Doughnut --}}
    <div class="card">
        <div class="card-title">Orders by Status</div>
        <div class="chart-wrap">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<div class="charts-grid-3">
    {{-- Orders count line chart --}}
    <div class="card">
        <div class="card-title">Order Volume — Last 12 Months</div>
        <div class="chart-wrap">
            <canvas id="volumeChart"></canvas>
        </div>
    </div>

    {{-- Users by role doughnut --}}
    <div class="card">
        <div class="card-title">Users by Role</div>
        <div class="chart-wrap">
            <canvas id="usersChart"></canvas>
        </div>
    </div>
</div>

{{-- Top products table --}}
<div class="card" style="margin-top:20px">
    <div class="card-title">Top Products by Revenue</div>
    @php $maxRev = $topProducts->max('total_revenue') ?: 1; @endphp
    <table class="top-table">
        <tbody>
            @forelse($topProducts as $i => $item)
                <tr>
                    <td class="rank" style="width:28px">{{ $i + 1 }}</td>
                    <td style="font-weight:700">{{ $item->product->name ?? 'Unknown' }}</td>
                    <td style="color:var(--muted);font-size:.8rem">{{ number_format($item->total_kg, 1) }} kg sold</td>
                    <td class="bar-cell">
                        <div class="mini-bar" style="width:{{ ($item->total_revenue / $maxRev) * 100 }}%"></div>
                    </td>
                    <td style="font-weight:700;color:var(--ocean);text-align:right">KES {{ number_format($item->total_revenue, 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" style="color:var(--muted);padding:16px 0">No order data yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js"></script>
<script>
// ── Data from PHP ──────────────────────────────────────────────────────────
const monthlyData = @json($monthlyRevenue);
const labels      = monthlyData.map(r => r.month);
const revenues    = monthlyData.map(r => parseFloat(r.revenue));
const volumes     = monthlyData.map(r => parseInt(r.count));

const statusData = @json($ordersByStatus);
const userRoles  = @json($usersByRole);

// ── Shared defaults ────────────────────────────────────────────────────────
Chart.defaults.font.family = "'DM Sans', sans-serif";
Chart.defaults.color = '#6b7d8a';

const OCEAN  = '#0a3d4f';
const TIDE   = '#0d6e8a';
const FRESH  = '#2eb87a';
const GOLD   = '#f0a500';
const CORAL  = '#e85d3a';
const FOAM   = '#e8f4f8';

// ── Monthly Revenue Bar ────────────────────────────────────────────────────
new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels,
        datasets: [{
            label: 'Revenue (KES)',
            data: revenues,
            backgroundColor: TIDE,
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: '#f0f4f7' },
                ticks: { callback: v => 'KES ' + (v >= 1000 ? (v/1000).toFixed(0)+'K' : v) }
            },
            x: { grid: { display: false } }
        }
    }
});

// ── Orders by Status Doughnut ──────────────────────────────────────────────
const statusLabels = { confirmed:'Confirmed', preparing:'Preparing', in_transit:'In Transit', delivered:'Delivered', cancelled:'Cancelled' };
const statusColors = { confirmed:TIDE, preparing:GOLD, in_transit:'#4a9cc7', delivered:FRESH, cancelled:CORAL };
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(statusData).map(k => statusLabels[k] || k),
        datasets: [{
            data: Object.values(statusData),
            backgroundColor: Object.keys(statusData).map(k => statusColors[k] || '#ccc'),
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 14, usePointStyle: true } }
        },
        cutout: '65%'
    }
});

// ── Order Volume Line ──────────────────────────────────────────────────────
new Chart(document.getElementById('volumeChart'), {
    type: 'line',
    data: {
        labels,
        datasets: [{
            label: 'Orders',
            data: volumes,
            borderColor: FRESH,
            backgroundColor: 'rgba(46,184,122,.1)',
            fill: true,
            tension: 0.4,
            pointBackgroundColor: FRESH,
            pointRadius: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f0f4f7' } },
            x: { grid: { display: false } }
        }
    }
});

// ── Users by Role Doughnut ─────────────────────────────────────────────────
const roleColors = { consumer: FRESH, producer: TIDE, delivery: GOLD, admin: CORAL };
new Chart(document.getElementById('usersChart'), {
    type: 'doughnut',
    data: {
        labels: Object.keys(userRoles).map(r => r.charAt(0).toUpperCase() + r.slice(1)),
        datasets: [{
            data: Object.values(userRoles),
            backgroundColor: Object.keys(userRoles).map(r => roleColors[r] || '#ccc'),
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 14, usePointStyle: true } }
        },
        cutout: '65%'
    }
});
</script>
@endpush
