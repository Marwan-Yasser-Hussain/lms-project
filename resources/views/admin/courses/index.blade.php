@extends('layouts.admin')

@section('title', 'Courses')
@section('topbar-title', 'Course Management')

@section('content')

{{-- Welcome/Header Banner --}}
<div class="mb-8 rounded-2xl relative overflow-hidden animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute -top-24 right-10 w-64 h-64 bg-[#ff80c8] rounded-full mix-blend-screen filter blur-[90px] opacity-20 pointer-events-none"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10 w-full flex-wrap">
        <div>
            <h1 class="text-3xl font-black text-white mb-2 tracking-tight">Browse Courses</h1>
            <p class="text-white/60 text-sm md:text-base max-w-xl leading-relaxed">
                Manage your complete course catalog, monitor enrollments, and coordinate learning materials.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.courses.export.pdf', request()->only(['search','status','category'])) }}"
               class="btn text-white transition-opacity hover:opacity-90 px-4" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" title="Export to PDF">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m0 0l-3-3m3 3l3-3" /></svg>
                PDF
            </a>
            <a href="{{ route('admin.courses.export.excel', request()->only(['search','status','category'])) }}"
               class="btn text-white transition-opacity hover:opacity-90 px-4" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #34d399;" title="Export to Excel">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                Excel
            </a>
            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #930056, #6d003f); color: #fff; border: 1px solid #ff80c8; box-shadow: 0 4px 14px rgba(147, 0, 86, 0.45);">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Publish Course
            </a>
        </div>
    </div>
</div>

<div class="flex items-center justify-between mb-6 animate-fade-up delay-1">
    <h2 class="text-xl font-bold text-white tracking-tight">Course Catalog</h2>
</div>

{{-- Filters Card --}}
<div class="card mb-6 animate-fade-up delay-2" style="background: linear-gradient(135deg, #160D50 0%, #120A42 100%);">
    <div class="card-inner py-4">
        <form method="GET" action="{{ route('admin.courses.index') }}" class="flex flex-wrap items-center gap-3">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by title..."
                       class="form-input w-full" style="padding-left: 2.75rem; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);" />
            </div>
            <select name="status" class="form-select flex-shrink-0" style="width: 150px; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                <option value="">All Statuses</option>
                <option value="published" {{ request('status')=='published'?'selected':'' }}>🟢 Published</option>
                <option value="draft"     {{ request('status')=='draft'?'selected':'' }}>📝 Draft</option>
            </select>
            <select name="category" class="form-select flex-shrink-0" style="width: 160px; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->icon ?? '' }} {{ $cat->name }}</option>
                @endforeach
            </select>
            <select name="per_page" class="form-select flex-shrink-0" style="width: 130px; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                <option value="10"  {{ request('per_page','15')=='10'  ? 'selected':'' }}>10 / page</option>
                <option value="25"  {{ request('per_page','15')=='25'  ? 'selected':'' }}>25 / page</option>
                <option value="50"  {{ request('per_page','15')=='50'  ? 'selected':'' }}>50 / page</option>
                <option value="100" {{ request('per_page','15')=='100' ? 'selected':'' }}>100 / page</option>
            </select>
            <button type="submit" class="btn btn-sky px-5 flex-shrink-0">Apply Filters</button>
            @if(request()->hasAny(['search', 'status', 'category', 'per_page']))
                <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary px-4 text-white/50 flex-shrink-0">Clear</a>
            @endif
        </form>
    </div>
</div>

{{-- Content Table --}}
<div class="card animate-fade-up delay-3" style="background: linear-gradient(135deg, #160D50 0%, #0F043D 100%);">
    <div class="overflow-x-auto pb-2">
        <table class="data-table">
            <thead>
                <tr>
                    <th class="pl-6" style="width:40px;">
                        <input type="checkbox" class="bulk-checkbox" id="selectAll" title="Select All">
                    </th>
                    <th class="w-2/5">Course Content</th>
                    <th>Category</th>
                    <th class="text-center">Complexity</th>
                    <th class="text-center">Enrolled</th>
                    <th>Status</th>
                    <th class="text-center">Certificate</th>
                    <th class="pr-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr class="group hover:bg-white/5 transition-colors" data-id="{{ $course->id }}">
                    <td class="pl-6" style="width:40px;">
                        <input type="checkbox" class="bulk-checkbox row-check" value="{{ $course->id }}">
                    </td>
                    <td class="pl-6">
                        <div class="flex items-center gap-4">
                            @if($course->thumbnail)
                                <img src="{{ asset('storage/'.$course->thumbnail) }}" alt=""
                                     class="w-16 h-12 object-cover rounded-lg flex-shrink-0 border border-white/10 shadow-sm" />
                            @else
                                <div class="w-16 h-12 rounded-lg flex items-center justify-center flex-shrink-0 shadow-sm"
                                     style="background:rgba(147,0,86,0.15); border:1px solid rgba(147,0,86,0.3);">
                                    <svg class="w-6 h-6" style="color:#ff80c8;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0">
                                <a href="{{ route('admin.courses.show', $course) }}" class="text-white font-bold text-sm truncate block hover:text-[#ff80c8] transition-colors">
                                    {{ Str::limit($course->title, 45) }}
                                </a>
                                <p class="text-xs mt-1 text-white/40 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    {{ $course->instructor_name }}
                                    <span class="mx-1">•</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    {{ $course->total_lessons }} Lessons
                                </p>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($course->category)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold" 
                                  style="background:{{ $course->category->color }}20; color: {{ $course->category->color }}; border: 1px solid {{ $course->category->color }}40;">
                                {{ $course->category->icon ?? '' }} {{ $course->category->name }}
                            </span>
                        @else
                            <span class="text-white/30 text-xs">—</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge" style="background: rgba(255,255,255,0.05); color: #e2e8f0; border: 1px solid rgba(255,255,255,0.1); text-transform: capitalize;">
                            {{ $course->level }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="text-white font-mono font-medium">{{ $course->enrolled_count }}</span>
                        <p class="text-[0.65rem] uppercase tracking-wider text-white/30">Students</p>
                    </td>
                    <td>
                        @if($course->status === 'published')
                            <span class="badge px-3 py-1" style="background: rgba(16, 185, 129, 0.15); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3);">Published</span>
                        @else
                            <span class="badge px-3 py-1" style="background: rgba(255, 255, 255, 0.1); color: #94a3b8; border: 1px solid rgba(255, 255, 255, 0.2);">Draft</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($course->has_certificate)
                            <div class="flex justify-center text-[#ff80c8] opacity-80" title="Offers Certificate">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            </div>
                        @else
                            <span class="text-white/20">—</span>
                        @endif
                    </td>
                    <td class="pr-6 text-right">
                        <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('admin.courses.edit', $course) }}" class="p-2 bg-white/5 hover:bg-white/10 text-white/60 hover:text-white rounded-lg transition-colors" title="Edit Course">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>    
                            </a>
                            <form method="POST" action="{{ route('admin.courses.destroy', $course) }}"
                                  onsubmit="return confirm('Delete this course entirely? This may affect active enrollments.')" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-2 bg-red-500/10 hover:bg-red-500/20 text-red-400 hover:text-red-300 rounded-lg transition-colors" title="Delete Course">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-20 text-white/40 border-b-0">
                        <div class="flex flex-col items-center justify-center">
                            <svg class="w-14 h-14 mb-4 opacity-50 text-[#ff80c8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                            <p class="text-lg font-medium text-white mb-2">Build Your Catalog</p>
                            <p class="text-sm mb-5">You haven't added any courses yet. Start creating your first one!</p>
                            <a href="{{ route('admin.courses.create') }}" class="btn btn-primary" style="background: linear-gradient(135deg, #930056, #6d003f); color: #fff;">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                Create First Course
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($courses->hasPages())
    <div class="p-6 border-t border-white/5">
        {{ $courses->withQueryString()->links('pagination::default') }}
    </div>
    @endif
</div>

{{-- Bulk Action Bar --}}
<div id="bulkBar" class="bulk-bar">
    <span class="bulk-bar-count" id="bulkCount">0</span>
    <span class="bulk-bar-label">courses selected</span>
    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.courses.bulk-delete') }}" onsubmit="return confirmBulkDelete()">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="bulkIds">
        <button type="submit" class="btn btn-danger btn-sm" style="border-color:rgba(248,113,113,0.5);">
            🗑 Delete Selected
        </button>
    </form>
    <button onclick="clearSelection()" class="btn btn-secondary btn-sm" style="font-size:0.75rem;">✕ Clear</button>
</div>

@endsection

@push('scripts')
<script>
(function () {
    const selectAll  = document.getElementById('selectAll');
    const bulkBar    = document.getElementById('bulkBar');
    const bulkCount  = document.getElementById('bulkCount');
    const bulkIds    = document.getElementById('bulkIds');

    function getChecked() {
        return [...document.querySelectorAll('.row-check:checked')];
    }
    function updateBar() {
        const checked = getChecked();
        bulkCount.textContent = checked.length;
        bulkIds.value = checked.map(c => c.value).join(',');
        bulkBar.classList.toggle('visible', checked.length > 0);
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.closest('tr').classList.toggle('row-selected', cb.checked);
        });
        const all = document.querySelectorAll('.row-check');
        selectAll.indeterminate = checked.length > 0 && checked.length < all.length;
        selectAll.checked = all.length > 0 && checked.length === all.length;
    }

    selectAll.addEventListener('change', function () {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = this.checked);
        updateBar();
    });

    document.querySelectorAll('.row-check').forEach(cb => {
        cb.addEventListener('change', updateBar);
    });

    window.clearSelection = function () {
        document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
        selectAll.checked = false;
        updateBar();
    };

    window.confirmBulkDelete = function () {
        const n = getChecked().length;
        return confirm(`⚠️ Delete ${n} selected course${n > 1 ? 's' : ''}? This action cannot be undone.`);
    };
})();
</script>
@endpush
