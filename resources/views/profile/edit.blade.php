<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile — AquaHub</title>
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
        .sh-role{font-size:.75rem;font-weight:700;padding:4px 10px;border-radius:20px;color:#fff}
        .role-admin{background:var(--coral)}
        .role-producer{background:var(--fresh)}
        .role-consumer{background:var(--tide)}
        .role-delivery{background:var(--gold);color:#051e28}
        .sh-avatar{width:36px;height:36px;border-radius:50%;background:var(--tide);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.9rem;border:2px solid rgba(255,255,255,.25)}
        .page-header{background:#fff;border-bottom:1px solid var(--border);padding:22px 2rem}
        .page-header h2{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--ocean);margin-bottom:4px}
        .page-header p{color:var(--muted);font-size:.9rem}
        .main{max-width:760px;margin:2rem auto;padding:0 1.5rem}
        .card{background:#fff;border:1px solid var(--border);border-radius:16px;padding:1.75rem;margin-bottom:1.5rem}
        .card-title{font-size:1.05rem;font-weight:700;color:var(--ocean);margin-bottom:.35rem}
        .card-sub{font-size:.875rem;color:var(--muted);margin-bottom:1.5rem;padding-bottom:1.25rem;border-bottom:1px solid var(--border)}
        .form-group{margin-bottom:1.25rem}
        .form-group label{display:block;font-size:.875rem;font-weight:600;color:#374151;margin-bottom:.4rem}
        .form-input{width:100%;padding:.65rem .9rem;border:1.5px solid var(--border);border-radius:10px;font-size:.9rem;font-family:'DM Sans',sans-serif;outline:none;transition:border .2s}
        .form-input:focus{border-color:var(--tide)}
        .form-grid{display:grid;grid-template-columns:1fr 1fr;gap:1rem}
        .form-error{font-size:.8rem;color:var(--coral);margin-top:.3rem}
        .btn-save{background:var(--ocean);color:#fff;padding:.7rem 1.75rem;border:none;border-radius:10px;font-size:.9rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s}
        .btn-save:hover{background:var(--tide)}
        .btn-danger{background:#fee2e2;color:#991b1b;border:1px solid #fecaca;padding:.7rem 1.75rem;border-radius:10px;font-size:.9rem;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .2s}
        .btn-danger:hover{background:#fecaca}
        .alert-success{background:#dcfce7;border:1px solid #bbf7d0;color:#166534;padding:.85rem 1.25rem;border-radius:10px;margin-bottom:1.25rem;font-size:.875rem}
        .danger-zone{border:1px solid #fecaca;border-radius:16px;padding:1.75rem;margin-bottom:1.5rem;background:#fff}
        .danger-title{font-size:1.05rem;font-weight:700;color:#991b1b;margin-bottom:.35rem}
        .danger-sub{font-size:.875rem;color:var(--muted);margin-bottom:1.5rem;padding-bottom:1.25rem;border-bottom:1px solid #fecaca}
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:999;align-items:center;justify-content:center}
        .modal-overlay.open{display:flex}
        .modal{background:#fff;border-radius:16px;padding:1.75rem;width:420px;max-width:90vw}
        .modal h3{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:#991b1b;margin-bottom:.5rem}
        .modal p{font-size:.875rem;color:var(--muted);margin-bottom:1.25rem}
        .modal-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:1rem}
        .modal-cancel{background:var(--foam);border:none;padding:.6rem 1.25rem;border-radius:8px;font-family:'DM Sans',sans-serif;font-weight:600;cursor:pointer;color:var(--muted)}
        footer{text-align:center;padding:2rem;color:var(--muted);font-size:.85rem}
        @media(max-width:600px){.form-grid{grid-template-columns:1fr}}
    </style>
</head>
<body>

<nav class="sh-nav">
    <a href="{{ route('dashboard') }}" class="sh-logo">Aqua<span>Hub</span></a>
    <div class="sh-nav-links">
        @php $role = auth()->user()->role; @endphp
        @if($role === 'admin')
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        @elseif($role === 'producer')
            <a href="{{ route('producer.dashboard') }}">Dashboard</a>
            <a href="{{ route('products.create') }}">+ List Fish</a>
            <a href="{{ route('producer.payouts') }}">Payouts</a>
        @elseif($role === 'delivery')
            <a href="{{ route('delivery.dashboard') }}">Dashboard</a>
        @else
            <a href="{{ route('products.index') }}">Shop</a>
            <a href="{{ route('cart.index') }}">Cart</a>
            <a href="{{ route('orders.index') }}">My Orders</a>
        @endif
        <a href="{{ route('profile.edit') }}" class="active">Profile</a>
    </div>
    <div class="sh-nav-right">
        <span class="sh-role role-{{ $role }}">{{ ucfirst($role) }}</span>
        <div class="sh-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <form method="POST" action="{{ route('logout') }}" style="margin:0">
            @csrf
            <button type="submit" style="background:none;border:none;color:rgba(255,255,255,.7);cursor:pointer;font-size:.875rem;font-weight:500;padding:7px 13px;border-radius:8px;transition:all .2s" onmouseover="this.style.background='rgba(255,255,255,.12)';this.style.color='#fff'" onmouseout="this.style.background='none';this.style.color='rgba(255,255,255,.7)'">Logout</button>
        </form>
    </div>
</nav>

<div class="page-header">
    <h2>My Profile</h2>
    <p>Manage your account information and security settings</p>
</div>

<div class="main">

    @if(session('status') === 'profile-updated')
        <div class="alert-success">Profile updated successfully.</div>
    @endif
    @if(session('status') === 'password-updated')
        <div class="alert-success">Password updated successfully.</div>
    @endif

    {{-- Profile Info --}}
    <div class="card">
        <div class="card-title">Profile Information</div>
        <div class="card-sub">Update your name, email, phone, and location.</div>
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PATCH')
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input"
                        value="{{ old('name', $user->name) }}" required autofocus>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input"
                        value="{{ old('email', $user->email) }}" required>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label for="phone">Phone (M-Pesa)</label>
                    <input type="tel" id="phone" name="phone" class="form-input"
                        value="{{ old('phone', $user->phone) }}" placeholder="e.g. 0712 345 678">
                    @error('phone')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label for="location">Location</label>
                    <input type="text" id="location" name="location" class="form-input"
                        value="{{ old('location', $user->location) }}" placeholder="e.g. Nairobi, Kenya">
                    @error('location')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
            <button type="submit" class="btn-save">Save Changes</button>
        </form>
    </div>

    {{-- Change Password --}}
    <div class="card">
        <div class="card-title">Change Password</div>
        <div class="card-sub">Use a strong password with at least 8 characters.</div>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" class="form-input" autocomplete="current-password">
                @error('current_password', 'updatePassword')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="form-grid">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" id="password" name="password" class="form-input" autocomplete="new-password">
                    @error('password', 'updatePassword')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" autocomplete="new-password">
                </div>
            </div>
            <button type="submit" class="btn-save">Update Password</button>
        </form>
    </div>

    {{-- Account Info --}}
    <div class="card">
        <div class="card-title">Account Details</div>
        <div class="card-sub" style="margin-bottom:1rem">Your account role and status on AquaHub.</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div style="background:var(--foam);border-radius:10px;padding:1rem">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:4px">Role</div>
                <div style="font-weight:700;color:var(--ocean);font-size:.95rem">{{ ucfirst($user->role) }}</div>
            </div>
            <div style="background:var(--foam);border-radius:10px;padding:1rem">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:4px">Status</div>
                <div style="font-weight:700;color:{{ $user->status === 'active' ? 'var(--fresh)' : 'var(--coral)' }};font-size:.95rem">{{ ucfirst($user->status) }}</div>
            </div>
            <div style="background:var(--foam);border-radius:10px;padding:1rem">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:4px">Member Since</div>
                <div style="font-weight:600;color:#374151;font-size:.9rem">{{ $user->created_at->format('d M Y') }}</div>
            </div>
            <div style="background:var(--foam);border-radius:10px;padding:1rem">
                <div style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:4px">User ID</div>
                <div style="font-weight:600;color:#374151;font-size:.9rem">#{{ $user->id }}</div>
            </div>
        </div>
    </div>

    {{-- Delete Account --}}
    <div class="danger-zone">
        <div class="danger-title">Delete Account</div>
        <div class="danger-sub">Once deleted, all data will be permanently removed. This cannot be undone.</div>
        <button class="btn-danger" onclick="document.getElementById('delete-modal').classList.add('open')">Delete My Account</button>
    </div>

</div>

<footer>© {{ date('Y') }} AquaHub</footer>

{{-- Delete Confirmation Modal --}}
<div class="modal-overlay" id="delete-modal">
    <div class="modal">
        <h3>Delete Account?</h3>
        <p>This will permanently delete your account and all your data. Please enter your password to confirm.</p>
        <form method="POST" action="{{ route('profile.destroy') }}">
            @csrf
            @method('DELETE')
            <div class="form-group">
                <label for="del-password">Your Password</label>
                <input type="password" id="del-password" name="password" class="form-input" required placeholder="Enter your password">
                @error('password', 'userDeletion')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div class="modal-actions">
                <button type="button" class="modal-cancel" onclick="document.getElementById('delete-modal').classList.remove('open')">Cancel</button>
                <button type="submit" class="btn-danger">Yes, Delete Account</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('delete-modal').addEventListener('click', function(e) {
    if (e.target === this) this.classList.remove('open');
});
</script>

@include('partials.samakibot')
</body>
</html>
