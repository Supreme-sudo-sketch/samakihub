<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payouts — AquaHub</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--ocean:#0a3d4f;--tide:#0d6e8a;--foam:#e8f4f8;--coral:#e85d3a;--gold:#f0a500;--fresh:#2eb87a;--muted:#6b7d8a;--border:#dde8ed}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'DM Sans',sans-serif;background:var(--foam);color:#1a1a2e}
        .sh-nav{background:var(--ocean);padding:0 2rem;display:flex;align-items:center;justify-content:space-between;height:64px;position:sticky;top:0;z-index:100;box-shadow:0 2px 20px rgba(0,0,0,.3)}
        .sh-logo{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:#fff;text-decoration:none}
        .sh-logo span{color:var(--gold)}
        .sh-nav-links{display:flex;align-items:center;gap:4px}
        .sh-nav-links a{color:rgba(255,255,255,.75);font-size:.875rem;font-weight:500;padding:7px 13px;border-radius:8px;text-decoration:none;transition:all .2s}
        .sh-nav-links a:hover,.sh-nav-links a.active{background:rgba(255,255,255,.12);color:#fff}
        .sh-nav-right{display:flex;align-items:center;gap:12px}
        .sh-role{background:var(--fresh);color:#fff;font-size:.75rem;font-weight:700;padding:4px 10px;border-radius:20px}
        .sh-avatar{width:36px;height:36px;border-radius:50%;background:var(--tide);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;border:2px solid rgba(255,255,255,.25)}
        .page-header{background:#fff;border-bottom:1px solid var(--border);padding:22px 2rem}
        .page-header h2{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--ocean);margin-bottom:4px}
        .page-header p{color:var(--muted);font-size:.9rem}
        .main{max-width:1000px;margin:0 auto;padding:2rem}
        .balance-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-bottom:2rem}
        .bal-card{background:#fff;border-radius:16px;padding:1.5rem;border:1px solid var(--border)}
        .bal-card.highlight{background:var(--ocean);color:#fff;border-color:var(--ocean)}
        .bal-label{font-size:.78rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:8px}
        .bal-card.highlight .bal-label{color:rgba(255,255,255,.65)}
        .bal-amount{font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:var(--ocean)}
        .bal-card.highlight .bal-amount{color:#fff}
        .bal-sub{font-size:.8rem;color:var(--muted);margin-top:4px}
        .bal-card.highlight .bal-sub{color:rgba(255,255,255,.6)}
        .section-card{background:#fff;border-radius:16px;border:1px solid var(--border);padding:1.5rem;margin-bottom:1.5rem}
        .section-card h3{font-size:1.1rem;font-weight:700;color:var(--ocean);margin-bottom:1.25rem;padding-bottom:.75rem;border-bottom:1px solid var(--border)}
        .form-group{margin-bottom:1.25rem}
        .form-group label{display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:.4rem}
        .form-input{width:100%;padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:10px;font-size:.9rem;outline:none;transition:border .2s}
        .form-input:focus{border-color:var(--tide)}
        .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
        .btn-payout{background:var(--fresh);color:#fff;padding:.75rem 2rem;border:none;border-radius:10px;font-size:.95rem;font-weight:700;cursor:pointer;transition:all .2s}
        .btn-payout:hover{background:#25a368;transform:translateY(-1px)}
        .btn-payout:disabled{opacity:.5;cursor:not-allowed;transform:none}
        .table{width:100%;border-collapse:collapse;font-size:.875rem}
        .table th{text-align:left;padding:.75rem 1rem;background:var(--foam);color:var(--muted);font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;border-bottom:1px solid var(--border)}
        .table td{padding:.85rem 1rem;border-bottom:1px solid var(--border);color:#374151}
        .table tr:last-child td{border-bottom:none}
        .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700}
        .badge-yellow{background:#fef9c3;color:#854d0e}
        .badge-green{background:#dcfce7;color:#166534}
        .badge-red{background:#fee2e2;color:#991b1b}
        .alert{padding:1rem 1.25rem;border-radius:10px;margin-bottom:1.5rem;font-size:.9rem}
        .alert-success{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
        .alert-error{background:#fee2e2;color:#991b1b;border:1px solid #fecaca}
        .empty{text-align:center;padding:3rem;color:var(--muted)}
        .empty-icon{font-size:2.5rem;margin-bottom:.75rem}
        footer{text-align:center;padding:2rem;color:var(--muted);font-size:.85rem}
        @media(max-width:700px){.balance-grid{grid-template-columns:1fr}.form-row{grid-template-columns:1fr}}
    </style>
</head>
<body>

<nav class="sh-nav">
    <a href="{{ route('producer.dashboard') }}" class="sh-logo">Aqua<span>Hub</span></a>
    <div class="sh-nav-links">
        <a href="{{ route('producer.dashboard') }}">Dashboard</a>
        <a href="{{ route('products.index') }}">Marketplace</a>
        <a href="{{ route('products.create') }}">+ List Fish</a>
        <a href="{{ route('producer.payouts') }}" class="active">Payouts</a>
    </div>
    <div class="sh-nav-right">
        <span class="sh-role">Producer</span>
        <div class="sh-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.7);cursor:pointer;font-size:.875rem;font-weight:500;padding:7px 13px;border-radius:8px;transition:all .2s" onmouseover="this.style.background='rgba(255,255,255,.12)';this.style.color='#fff'" onmouseout="this.style.background='none';this.style.color='rgba(255,255,255,.7)'">Logout</button>
        </form>
    </div>
</nav>

<div class="page-header">
    <h2>Payouts</h2>
    <p>Request withdrawals of your earnings to your M-Pesa account</p>
</div>

<div class="main">

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    {{-- Balance cards --}}
    <div class="balance-grid">
        <div class="bal-card">
            <div class="bal-label">Total Earned</div>
            <div class="bal-amount">KES {{ number_format($totalEarned, 0) }}</div>
            <div class="bal-sub">From delivered orders</div>
        </div>
        <div class="bal-card">
            <div class="bal-label">Total Withdrawn</div>
            <div class="bal-amount">KES {{ number_format($totalWithdrawn, 0) }}</div>
            <div class="bal-sub">Approved + pending requests</div>
        </div>
        <div class="bal-card highlight">
            <div class="bal-label">Available Balance</div>
            <div class="bal-amount">KES {{ number_format($availableBalance, 0) }}</div>
            <div class="bal-sub">Ready to withdraw</div>
        </div>
    </div>

    {{-- Request form --}}
    <div class="section-card">
        <h3>Request Withdrawal</h3>
        @if($availableBalance < 100)
            <div class="alert alert-error" style="margin-bottom:0">
                You need at least <strong>KES 100</strong> available balance to request a withdrawal.
            </div>
        @else
        <form method="POST" action="{{ route('producer.payouts.store') }}">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label for="amount">Amount (KES)</label>
                    <input type="number" id="amount" name="amount" class="form-input"
                        min="100" max="{{ $availableBalance }}" step="1"
                        value="{{ old('amount') }}" placeholder="e.g. 5000" required>
                    @error('amount')<p style="color:var(--coral);font-size:.8rem;margin-top:.3rem">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label for="phone">M-Pesa Number</label>
                    <input type="tel" id="phone" name="phone" class="form-input"
                        value="{{ old('phone', auth()->user()->phone) }}" placeholder="e.g. 0712 345 678" required>
                    @error('phone')<p style="color:var(--coral);font-size:.8rem;margin-top:.3rem">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="form-group">
                <label for="notes">Notes (optional)</label>
                <input type="text" id="notes" name="notes" class="form-input"
                    value="{{ old('notes') }}" placeholder="e.g. Weekly payout">
            </div>
            <button type="submit" class="btn-payout">Request Withdrawal</button>
        </form>
        @endif
    </div>

    {{-- History table --}}
    <div class="section-card">
        <h3>Withdrawal History</h3>
        @if($withdrawals->isEmpty())
            <div class="empty">
                <div class="empty-icon">💸</div>
                <p>No withdrawal requests yet.</p>
            </div>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Amount</th>
                        <th>M-Pesa No.</th>
                        <th>Status</th>
                        <th>Notes</th>
                        <th>Admin Reply</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($withdrawals as $w)
                    <tr>
                        <td>#{{ $w->id }}</td>
                        <td style="font-weight:700">KES {{ number_format($w->amount, 0) }}</td>
                        <td>{{ $w->phone }}</td>
                        <td>
                            <span class="badge {{ $w->statusBadge() }}">{{ ucfirst($w->status) }}</span>
                        </td>
                        <td style="color:var(--muted)">{{ $w->notes ?? '—' }}</td>
                        <td style="color:var(--muted)">{{ $w->admin_note ?? '—' }}</td>
                        <td style="color:var(--muted)">{{ $w->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:1rem">{{ $withdrawals->links() }}</div>
        @endif
    </div>

</div>

<footer>© {{ date('Y') }} AquaHub</footer>

@include('partials.samakibot')
</body>
</html>
