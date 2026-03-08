<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #1a1a2e; background: #fff; }

    .header {
        background: linear-gradient(135deg, #0F043D 0%, #1A1262 100%);
        color: #fff;
        padding: 18px 24px;
        margin-bottom: 0;
        border-radius: 8px 8px 0 0;
    }
    .header h1 { font-size: 20px; font-weight: bold; letter-spacing: -0.5px; }
    .header p  { font-size: 9px; color: rgba(255,255,255,0.55); margin-top: 3px; }
    .header .meta { font-size: 8px; color: rgba(255,255,255,0.4); margin-top: 1px; }

    .accent-bar { height: 4px; background: linear-gradient(90deg, #930056, #ff80c8, #045592); }

    .filter-info {
        background: #f8f5ff;
        border: 1px solid #e0d8f5;
        border-radius: 4px;
        padding: 5px 12px;
        margin: 10px 0;
        font-size: 8px;
        color: #555;
    }

    table { width: 100%; border-collapse: collapse; margin-top: 10px; }

    thead tr {
        background: #930056;
        color: #fff;
    }
    thead th {
        padding: 8px 10px;
        text-align: left;
        font-size: 8px;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        font-weight: bold;
        border: 1px solid #6d003f;
    }

    tbody tr:nth-child(odd)  { background: #fff; }
    tbody tr:nth-child(even) { background: #f8f4ff; }
    tbody tr:hover           { background: #f0ebff; }

    tbody td {
        padding: 6px 10px;
        border: 1px solid #ede8f8;
        vertical-align: middle;
        font-size: 9px;
    }

    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 7.5px;
        font-weight: bold;
        letter-spacing: 0.04em;
    }
    .badge-active   { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
    .badge-inactive { background: #fce7f3; color: #930056; border: 1px solid #fbcfe8; }
    .badge-admin    { background: #fce7f3; color: #930056; border: 1px solid #fbcfe8; }
    .badge-user     { background: #eff6ff; color: #045592; border: 1px solid #bfdbfe; }

    .footer {
        margin-top: 16px;
        border-top: 1px solid #e0d8f5;
        padding-top: 8px;
        display: flex;
        justify-content: space-between;
        font-size: 7.5px;
        color: #aaa;
    }

    .count-info { color: #930056; font-weight: bold; }
</style>
</head>
<body>

<div class="header">
    <h1>👥 User Management Report</h1>
    <p>LMS Platform — Administration Dashboard</p>
    <p class="meta">Generated on {{ now()->format('F d, Y — H:i') }}</p>
</div>
<div class="accent-bar"></div>

@if($search || $role || $status)
<div class="filter-info">
    <strong>Active Filters:</strong>
    @if($search) Search: "{{ $search }}" @endif
    @if($role) &nbsp;| Role: {{ ucfirst($role) }} @endif
    @if($status) &nbsp;| Status: {{ ucfirst($status) }} @endif
</div>
@endif

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Phone</th>
            <th>Enrollments</th>
            <th>Status</th>
            <th>Joined</th>
        </tr>
    </thead>
    <tbody>
        @forelse($users as $i => $user)
        <tr>
            <td style="text-align:center; color:#888;">{{ $i + 1 }}</td>
            <td><strong>{{ $user->name }}</strong></td>
            <td style="color:#555;">{{ $user->email }}</td>
            <td>
                <span class="badge badge-{{ $user->role }}">{{ ucfirst($user->role) }}</span>
            </td>
            <td style="color:#777;">{{ $user->phone ?? '—' }}</td>
            <td style="text-align:center; font-weight:bold;">{{ $user->enrollments_count }}</td>
            <td>
                <span class="badge badge-{{ $user->is_active ? 'active' : 'inactive' }}">
                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                </span>
            </td>
            <td style="color:#777;">{{ $user->created_at->format('M d, Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="8" style="text-align:center; padding:20px; color:#aaa;">No users found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    <span>LMS Platform &copy; {{ date('Y') }}</span>
    <span class="count-info">Total: {{ count($users) }} user(s)</span>
    <span>Confidential — Admin Use Only</span>
</div>

</body>
</html>
