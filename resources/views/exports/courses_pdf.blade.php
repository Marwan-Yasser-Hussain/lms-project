<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; color: #0f043d; background: #fff; }

    .header {
        background: linear-gradient(135deg, #0F043D 0%, #1A1262 100%);
        color: #fff;
        padding: 18px 24px;
        border-radius: 8px 8px 0 0;
    }
    .header h1 { font-size: 20px; font-weight: bold; }
    .header p  { font-size: 9px; color: rgba(255,255,255,0.55); margin-top: 3px; }
    .header .meta { font-size: 8px; color: rgba(255,255,255,0.4); margin-top: 1px; }

    .accent-bar { height: 4px; background: linear-gradient(90deg, #045592, #5bb8ff, #930056); }

    .filter-info {
        background: #f0f6ff;
        border: 1px solid #d0e4f5;
        border-radius: 4px;
        padding: 5px 12px;
        margin: 10px 0;
        font-size: 8px;
        color: #555;
    }

    table { width: 100%; border-collapse: collapse; margin-top: 10px; }

    thead tr { background: #045592; color: #fff; }
    thead th {
        padding: 8px 10px;
        text-align: left;
        font-size: 8px;
        letter-spacing: 0.07em;
        text-transform: uppercase;
        font-weight: bold;
        border: 1px solid #033d6a;
    }

    tbody tr:nth-child(odd)  { background: #fff; }
    tbody tr:nth-child(even) { background: #f0f6ff; }

    tbody td {
        padding: 6px 10px;
        border: 1px solid #d8e8f5;
        vertical-align: middle;
        font-size: 9px;
    }

    .badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 20px;
        font-size: 7.5px;
        font-weight: bold;
    }
    .badge-published { background: #dcfce7; color: #16a34a; border: 1px solid #bbf7d0; }
    .badge-draft     { background: #f1f5f9; color: #64748b; border: 1px solid #cbd5e1; }
    .badge-cert-yes  { background: #eff6ff; color: #045592; border: 1px solid #bfdbfe; }

    .footer {
        margin-top: 16px;
        border-top: 1px solid #d0e4f5;
        padding-top: 8px;
        font-size: 7.5px;
        color: #aaa;
        display: flex;
        justify-content: space-between;
    }
    .count-info { color: #045592; font-weight: bold; }
</style>
</head>
<body>

<div class="header">
    <h1>📚 Course Catalog Report</h1>
    <p>LMS Platform — Administration Dashboard</p>
    <p class="meta">Generated on {{ now()->format('F d, Y — H:i') }}</p>
</div>
<div class="accent-bar"></div>

@if($search || $status || $category)
<div class="filter-info">
    <strong>Active Filters:</strong>
    @if($search) Search: "{{ $search }}" @endif
    @if($status) &nbsp;| Status: {{ ucfirst($status) }} @endif
    @if($category) &nbsp;| Category ID: {{ $category }} @endif
</div>
@endif

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Title</th>
            <th>Category</th>
            <th>Instructor</th>
            <th>Level</th>
            <th>Status</th>
            <th>Enrolled</th>
            <th>Cert.</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
        @forelse($courses as $i => $course)
        <tr>
            <td style="text-align:center; color:#888;">{{ $i + 1 }}</td>
            <td><strong>{{ $course->title }}</strong></td>
            <td style="color:#555;">{{ $course->category->name ?? '—' }}</td>
            <td style="color:#555;">{{ $course->instructor_name }}</td>
            <td style="text-transform:capitalize;">{{ $course->level }}</td>
            <td>
                <span class="badge badge-{{ $course->status }}">{{ ucfirst($course->status) }}</span>
            </td>
            <td style="text-align:center; font-weight:bold;">{{ $course->enrollments_count }}</td>
            <td style="text-align:center;">
                @if($course->has_certificate)
                    <span class="badge badge-cert-yes">Yes</span>
                @else
                    <span style="color:#aaa;">—</span>
                @endif
            </td>
            <td style="color:#777;">{{ $course->created_at->format('M d, Y') }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="9" style="text-align:center; padding:20px; color:#aaa;">No courses found.</td>
        </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    <span>LMS Platform &copy; {{ date('Y') }}</span>
    <span class="count-info">Total: {{ count($courses) }} course(s)</span>
    <span>Confidential — Admin Use Only</span>
</div>

</body>
</html>
