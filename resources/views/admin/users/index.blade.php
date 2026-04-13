@extends('admin.layouts.sidebar')

@section('title', 'Users')
@section('topbar-title', 'User Management')

@push('styles')
<style>
    .filters{background:#fff;border:1px solid var(--border);border-radius:12px;padding:16px 20px;margin-bottom:20px;display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end}
    .filter-group{display:flex;flex-direction:column;gap:4px}
    .filter-label{font-size:.72rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.4px}
    .filter-input,.filter-select{border:2px solid var(--border);border-radius:8px;padding:7px 11px;font-family:'DM Sans',sans-serif;font-size:.875rem;outline:none;background:#fff;color:#1a1a2e;min-width:160px}
    .filter-input:focus,.filter-select:focus{border-color:var(--tide)}
    .filter-btn{background:var(--ocean);color:#fff;border:none;padding:8px 18px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:700;align-self:flex-end}
    .filter-btn:hover{background:var(--tide)}
    .filter-reset{font-size:.82rem;color:var(--tide);text-decoration:none;font-weight:600;align-self:center;white-space:nowrap}

    .card{background:#fff;border:1px solid var(--border);border-radius:16px;overflow:hidden}
    .table{width:100%;border-collapse:collapse}
    .table th{text-align:left;padding:10px 16px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px;color:var(--muted);background:#f8fbfc;white-space:nowrap}
    .table td{padding:12px 16px;font-size:.875rem;border-bottom:1px solid var(--foam);vertical-align:middle}
    .table tr:last-child td{border-bottom:none}
    .table tr:hover td{background:#f8fbfc}

    .user-cell{display:flex;align-items:center;gap:10px}
    .user-avatar{width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.85rem;font-weight:700;color:#fff;flex-shrink:0;background:linear-gradient(135deg,var(--ocean),var(--tide))}
    .user-avatar.deleted{background:#ccc}
    .user-name{font-weight:700;font-size:.88rem}
    .user-email{font-size:.77rem;color:var(--muted)}

    .pill{padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:uppercase}
    .s-active{background:#d4f7e7;color:#1a7a4a}
    .s-pending{background:#fff3d0;color:#a07000}
    .s-suspended{background:#fde8e8;color:#a81a1a}
    .s-deleted{background:#f0f0f0;color:#888}

    .role-badge{padding:3px 9px;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:capitalize}
    .r-producer{background:#e8f4f8;color:var(--ocean)}
    .r-consumer{background:#f0fff4;color:#1a7a4a}
    .r-delivery{background:#fff8e6;color:#a07000}
    .r-admin{background:#fde8e8;color:#a81a1a}

    .action-form{display:inline}
    .act-btn{border:none;padding:5px 11px;border-radius:7px;cursor:pointer;font-size:.78rem;font-weight:700;font-family:inherit;white-space:nowrap}
    .act-approve{background:var(--fresh);color:#fff}
    .act-approve:hover{background:#25a06a}
    .act-suspend{background:#fff3d0;color:#a07000;border:1px solid #f0d080}
    .act-suspend:hover{background:#ffe099}
    .act-unsuspend{background:#d4f7e7;color:#1a7a4a;border:1px solid #9de0c0}
    .act-unsuspend:hover{background:#b6f0d4}
    .act-delete{background:#fde8e8;color:#a81a1a;border:1px solid #f0b0b0}
    .act-delete:hover{background:#fdd0d0}

    .pagination-wrap{padding:16px 20px;border-top:1px solid var(--border);display:flex;justify-content:flex-end}

    /* Modal */
    .modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.5);z-index:1000;align-items:center;justify-content:center}
    .modal.active{display:flex}
    .modal-content{background:#fff;border-radius:16px;padding:28px;max-width:500px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.3)}
    .modal-header{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--ocean);margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid var(--border)}
    .modal-close{float:right;font-size:1.5rem;color:var(--muted);cursor:pointer;border:none;background:none;padding:0;width:30px;height:30px;display:flex;align-items:center;justify-content:center}
    .modal-close:hover{color:var(--ocean)}
    .form-group{margin-bottom:16px}
    .form-label{display:block;font-size:.82rem;font-weight:700;color:var(--ocean);margin-bottom:6px}
    .form-select{width:100%;border:2px solid var(--border);border-radius:8px;padding:10px 14px;font-family:'DM Sans',sans-serif;font-size:.875rem;outline:none;color:#1a1a2e}
    .form-select:focus{border-color:var(--tide)}
    .form-actions{display:flex;gap:10px;justify-content:flex-end;margin-top:20px;padding-top:16px;border-top:1px solid var(--border)}
    .btn-submit{background:var(--fresh);color:#fff;border:none;padding:10px 20px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:700;transition:.2s}
    .btn-submit:hover{opacity:.9}
    .btn-cancel{background:var(--foam);color:var(--ocean);border:1px solid var(--border);padding:10px 20px;border-radius:8px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.875rem;font-weight:700;transition:.2s}
    .btn-cancel:hover{background:var(--border)}
    .role-btn{background:var(--tide);color:#fff;border:none;padding:6px 12px;border-radius:6px;cursor:pointer;font-family:'DM Sans',sans-serif;font-size:.8rem;font-weight:700}
    .role-btn:hover{opacity:.9}
</style>
@endpush

@section('topbar-actions')
    <span style="font-size:.82rem;color:var(--muted)">{{ $users->total() }} users found</span>
@endsection

@section('content')

{{-- Filters --}}
<form method="GET" action="{{ route('admin.users') }}">
    <div class="filters">
        <div class="filter-group">
            <label class="filter-label">Search</label>
            <input type="text" name="search" class="filter-input" placeholder="Name, email, phone…" value="{{ request('search') }}">
        </div>
        <div class="filter-group">
            <label class="filter-label">Role</label>
            <select name="role" class="filter-select">
                <option value="">All Roles</option>
                @foreach(['consumer','producer','delivery','admin'] as $r)
                    <option value="{{ $r }}" {{ request('role') === $r ? 'selected' : '' }}>{{ ucfirst($r) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                @foreach(['active','pending','suspended','deleted'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="filter-btn">Filter</button>
        @if(request()->anyFilled(['search','role','status']))
            <a href="{{ route('admin.users') }}" class="filter-reset">✕ Clear</a>
        @endif
    </div>
</form>

<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>User</th>
                <th>Phone</th>
                <th>Role</th>
                <th>Status</th>
                <th>Joined</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                @php $isDeleted = $user->trashed(); @endphp
                <tr style="{{ $isDeleted ? 'opacity:.55' : '' }}">
                    <td>
                        <div class="user-cell">
                            <div class="user-avatar {{ $isDeleted ? 'deleted' : '' }}">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="user-name">{{ $user->name }}</div>
                                <div class="user-email">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="color:var(--muted);font-size:.83rem">{{ $user->phone ?? '—' }}</td>
                    <td><span class="role-badge r-{{ $user->role }}">{{ ucfirst($user->role) }}</span></td>
                    <td>
                        @if($isDeleted)
                            <span class="pill s-deleted">Deleted</span>
                        @else
                            <span class="pill s-{{ $user->status }}">{{ ucfirst($user->status) }}</span>
                        @endif
                    </td>
                    <td style="color:var(--muted);font-size:.82rem">{{ $user->created_at->format('d M Y') }}</td>
                    <td>
                        @if(! $isDeleted && $user->role !== 'admin')
                            <div style="display:flex;gap:6px;flex-wrap:wrap">
                                {{-- Change Role --}}
                                <button type="button" class="act-btn role-btn" onclick="openRoleModal({{ $user->id }}, '{{ $user->name }}', '{{ $user->role }}')">
                                    Change Role
                                </button>

                                {{-- Approve (only if pending) --}}
                                @if($user->status === 'pending')
                                    <form class="action-form" method="POST" action="{{ route('admin.users.approve', $user->id) }}">
                                        @csrf
                                        <button class="act-btn act-approve">Approve</button>
                                    </form>
                                @endif

                                {{-- Suspend / Unsuspend --}}
                                <form class="action-form" method="POST" action="{{ route('admin.users.suspend', $user->id) }}">
                                    @csrf
                                    @if($user->status === 'suspended')
                                        <button class="act-btn act-unsuspend">Unsuspend</button>
                                    @else
                                        <button class="act-btn act-suspend">Suspend</button>
                                    @endif
                                </form>

                                {{-- Delete --}}
                                <form class="action-form" method="POST" action="{{ route('admin.users.delete', $user->id) }}"
                                      onsubmit="return confirm('Delete {{ addslashes($user->name) }}? This can be undone from the database.')">
                                    @csrf @method('DELETE')
                                    <button class="act-btn act-delete">Delete</button>
                                </form>
                            </div>
                        @elseif($user->role === 'admin')
                            <span style="font-size:.77rem;color:var(--muted)">Admin — protected</span>
                        @else
                            <span style="font-size:.77rem;color:var(--muted)">Soft-deleted</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:36px;color:var(--muted)">No users match your filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($users->hasPages())
        <div class="pagination-wrap">
            {{ $users->links() }}
        </div>
    @endif
</div>

<!-- Role Change Modal -->
<div class="modal" id="roleModal">
    <div class="modal-content">
        <div style="display:flex;justify-content:space-between;align-items:center">
            <div class="modal-header">Change User Role</div>
            <button type="button" class="modal-close" onclick="closeRoleModal()">✕</button>
        </div>

        <div style="margin-bottom:16px;color:var(--muted);font-size:.85rem">
            <strong>User:</strong> <span id="userName"></span><br>
            <strong>Current Role:</strong> <span id="currentRole"></span>
        </div>

        <form method="POST" id="roleForm">
            @csrf

            <div class="form-group">
                <label class="form-label">New Role</label>
                <select name="role" class="form-select" required>
                    <option value="">Select a role...</option>
                    <option value="consumer">Consumer</option>
                    <option value="producer">Producer</option>
                    <option value="delivery">Delivery Agent</option>
                </select>
            </div>

            <div class="form-actions">
                <button type="button" class="btn-cancel" onclick="closeRoleModal()">Cancel</button>
                <button type="submit" class="btn-submit">Update Role</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openRoleModal(userId, userName, currentRole) {
        document.getElementById('userName').textContent = userName;
        document.getElementById('currentRole').textContent = currentRole.charAt(0).toUpperCase() + currentRole.slice(1);
        document.getElementById('roleForm').action = '{{ url("/admin/users") }}/' + userId + '/role';
        document.querySelector('select[name="role"]').value = '';
        document.getElementById('roleModal').classList.add('active');
    }

    function closeRoleModal() {
        document.getElementById('roleModal').classList.remove('active');
    }

    // Close modal when clicking outside
    document.getElementById('roleModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRoleModal();
        }
    });
</script>

@endsection
