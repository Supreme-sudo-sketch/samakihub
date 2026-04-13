@extends('admin.layouts.sidebar')

@section('title', 'Withdrawals')
@section('topbar-title', 'Withdrawal Requests')

@push('styles')
<style>
    .filters{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end}
    .filter-group{display:flex;flex-direction:column;gap:4px}
    .filter-label{font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.4px}
    .filter-select{border:2px solid var(--border);border-radius:8px;padding:7px 11px;font-family:'DM Sans',sans-serif;font-size:.875rem;outline:none;background:#fff;color:#1a1a2e;min-width:160px}
    .filter-select:focus{border-color:var(--tide)}
    .filter-btn{background:var(--ocean);color:#fff;border:none;padding:8px 18px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:700;align-self:flex-end}
    .filter-btn:hover{background:var(--tide)}
    .filter-reset{font-size:.82rem;color:var(--tide);text-decoration:none;font-weight:600;align-self:center}
    .card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden}
    .table{width:100%;border-collapse:collapse}
    .table th{text-align:left;padding:10px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--muted);background:#f8fbfc;white-space:nowrap}
    .table td{padding:12px 16px;font-size:.875rem;border-bottom:1px solid var(--foam);vertical-align:middle}
    .table tr:last-child td{border-bottom:none}
    .table tr:hover td{background:#f8fbfc}
    .pill{padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:uppercase}
    .pill-yellow{background:#fef9c3;color:#854d0e}
    .pill-green{background:#dcfce7;color:#166534}
    .pill-red{background:#fee2e2;color:#991b1b}
    .action-btn{border:none;padding:5px 12px;border-radius:7px;font-size:.78rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .15s}
    .btn-approve{background:#dcfce7;color:#166534}
    .btn-approve:hover{background:#bbf7d0}
    .btn-reject{background:#fee2e2;color:#991b1b}
    .btn-reject:hover{background:#fecaca}
    .empty{text-align:center;padding:3rem 1rem;color:var(--muted)}
    .empty-icon{font-size:2.5rem;margin-bottom:.75rem}
    .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999;align-items:center;justify-content:center}
    .modal-overlay.open{display:flex}
    .modal{background:#fff;border-radius:16px;padding:1.75rem;width:420px;max-width:90vw;box-shadow:0 24px 60px rgba(0,0,0,.2)}
    .modal h3{font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:var(--ocean);margin-bottom:1rem}
    .modal-input{width:100%;border:2px solid var(--border);border-radius:8px;padding:.65rem .9rem;font-family:'DM Sans',sans-serif;font-size:.875rem;outline:none;margin-bottom:1rem}
    .modal-input:focus{border-color:var(--tide)}
    .modal-actions{display:flex;gap:10px;justify-content:flex-end}
    .modal-cancel{background:var(--foam);color:var(--muted);border:none;padding:.6rem 1.25rem;border-radius:8px;font-family:'DM Sans',sans-serif;font-weight:600;cursor:pointer}
    .modal-submit{border:none;padding:.6rem 1.25rem;border-radius:8px;font-family:'DM Sans',sans-serif;font-weight:700;cursor:pointer;color:#fff}
    .modal-submit.approve{background:var(--fresh)}
    .modal-submit.reject{background:var(--coral)}
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert-s">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert-e">{{ session('error') }}</div>
@endif

{{-- Filter --}}
<form method="GET" class="filters">
    <div class="filter-group">
        <span class="filter-label">Status</span>
        <select name="status" class="filter-select">
            <option value="">All</option>
            <option value="pending"  {{ request('status') === 'pending'  ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </div>
    <button type="submit" class="filter-btn">Filter</button>
    @if(request('status'))
        <a href="{{ route('admin.withdrawals') }}" class="filter-reset">Clear</a>
    @endif
    @if($pendingCount > 0)
        <span style="margin-left:auto;font-size:.875rem;color:var(--coral);font-weight:700">
            ⚠️ {{ $pendingCount }} pending request{{ $pendingCount > 1 ? 's' : '' }} need attention
        </span>
    @endif
</form>

<div class="card">
    @if($withdrawals->isEmpty())
        <div class="empty">
            <div class="empty-icon">💸</div>
            <p>No withdrawal requests found.</p>
        </div>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Producer</th>
                    <th>Amount</th>
                    <th>M-Pesa No.</th>
                    <th>Status</th>
                    <th>Notes</th>
                    <th>Requested</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withdrawals as $w)
                <tr>
                    <td style="color:var(--muted)">#{{ $w->id }}</td>
                    <td>
                        <div style="font-weight:700;font-size:.88rem">{{ $w->producer->name ?? '—' }}</div>
                        <div style="font-size:.77rem;color:var(--muted)">{{ $w->producer->email ?? '' }}</div>
                    </td>
                    <td style="font-weight:700;color:var(--ocean)">KES {{ number_format($w->amount, 0) }}</td>
                    <td>{{ $w->phone }}</td>
                    <td>
                        @if($w->status === 'pending')
                            <span class="pill pill-yellow">Pending</span>
                        @elseif($w->status === 'approved')
                            <span class="pill pill-green">Approved</span>
                        @else
                            <span class="pill pill-red">Rejected</span>
                        @endif
                    </td>
                    <td style="color:var(--muted);font-size:.82rem">{{ $w->notes ?? '—' }}</td>
                    <td style="color:var(--muted);font-size:.82rem;white-space:nowrap">{{ $w->created_at->format('d M Y') }}</td>
                    <td>
                        @if($w->status === 'pending')
                            <div style="display:flex;gap:6px">
                                <button onclick="openModal('approve', {{ $w->id }}, '{{ addslashes($w->producer->name) }}', {{ $w->amount }})" class="action-btn btn-approve">Approve</button>
                                <button onclick="openModal('reject',  {{ $w->id }}, '{{ addslashes($w->producer->name) }}', {{ $w->amount }})" class="action-btn btn-reject">Reject</button>
                            </div>
                        @else
                            <span style="font-size:.78rem;color:var(--muted)">
                                {{ $w->admin_note ? '"'.$w->admin_note.'"' : 'No note' }}
                                @if($w->processed_at)
                                    <br>{{ $w->processed_at->format('d M Y') }}
                                @endif
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div style="padding:1rem 1.25rem">{{ $withdrawals->links() }}</div>
    @endif
</div>

{{-- Approve Modal --}}
<div class="modal-overlay" id="modal-overlay">
    <div class="modal">
        <h3 id="modal-title">Process Withdrawal</h3>
        <p id="modal-desc" style="font-size:.9rem;color:var(--muted);margin-bottom:1rem"></p>
        <input type="text" class="modal-input" id="modal-note" placeholder="Admin note (optional)">
        <div class="modal-actions">
            <button class="modal-cancel" onclick="closeModal()">Cancel</button>
            <form id="modal-form" method="POST" style="margin:0">
                @csrf
                <input type="hidden" name="admin_note" id="modal-note-hidden">
                <button type="submit" id="modal-submit" class="modal-submit">Confirm</button>
            </form>
        </div>
    </div>
</div>

<script>
function openModal(action, id, name, amount) {
    const overlay = document.getElementById('modal-overlay');
    const form    = document.getElementById('modal-form');
    const submit  = document.getElementById('modal-submit');
    const title   = document.getElementById('modal-title');
    const desc    = document.getElementById('modal-desc');

    if (action === 'approve') {
        form.action = `/admin/withdrawals/${id}/approve`;
        submit.textContent = 'Approve';
        submit.className = 'modal-submit approve';
        title.textContent = 'Approve Withdrawal';
        desc.textContent  = `Approve KES ${amount.toLocaleString()} for ${name}?`;
    } else {
        form.action = `/admin/withdrawals/${id}/reject`;
        submit.textContent = 'Reject';
        submit.className = 'modal-submit reject';
        title.textContent = 'Reject Withdrawal';
        desc.textContent  = `Reject KES ${amount.toLocaleString()} for ${name}?`;
    }

    document.getElementById('modal-note').value = '';
    overlay.classList.add('open');

    document.getElementById('modal-form').addEventListener('submit', function() {
        document.getElementById('modal-note-hidden').value = document.getElementById('modal-note').value;
    });
}
function closeModal() {
    document.getElementById('modal-overlay').classList.remove('open');
}
document.getElementById('modal-overlay').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});
</script>
@endsection
