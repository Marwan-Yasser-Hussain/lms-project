@extends('layouts.admin')

@section('title', 'Users')
@section('topbar-title', 'User Management')

@section('content')

{{-- Welcome/Header Banner --}}
<div class="mb-8 rounded-2xl relative animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute -top-24 right-10 w-64 h-64 bg-[#ff80c8] rounded-full mix-blend-screen filter blur-[90px] opacity-20 pointer-events-none"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10 w-full flex-wrap">
        <div>
            <h1 class="text-3xl font-black mb-2 tracking-tight text-white">User Management</h1>
            <p class="text-white/60 text-sm md:text-base max-w-xl leading-relaxed">
                Manage all registered users and admins, control access, and monitor activity.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3 relative z-50">
            <div class="relative group" id="export-dropdown-container">
                <button onclick="toggleExportDropdown()" type="button" class="btn text-white transition-opacity hover:opacity-90 px-4 flex items-center gap-2"
                   style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);" title="Export Options">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    Export
                    <svg id="export-chevron" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                
                <div id="export-dropdown-menu"
                     class="absolute left-0 mt-2 w-48 rounded-xl shadow-2xl overflow-hidden transition-all duration-200 pointer-events-none opacity-0 translate-y-2" 
                     style="background: #160D50; border: 1px solid rgba(255,255,255,0.1); z-index: 9999;">
                    <div class="py-1">
                        <a href="{{ route('admin.users.export.pdf', request()->only(['search','role','status'])) }}"
                           class="flex items-center px-4 py-2.5 text-sm text-white/80 hover:text-white hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11v6m0 0l-3-3m3 3l3-3" /></svg>
                            Export as PDF
                        </a>
                        <a href="{{ route('admin.users.export.excel', request()->only(['search','role','status'])) }}"
                           class="flex items-center px-4 py-2.5 text-sm text-white/80 hover:text-white hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 mr-3 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                            Export as Excel
                        </a>
                    </div>
                </div>
            </div>
            <button type="button" class="btn text-white transition-opacity hover:opacity-90 px-4"
               style="background: rgba(59, 130, 246, 0.15); border: 1px solid rgba(59, 130, 246, 0.3); color: #60a5fa;" title="Import from Excel">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                Import
            </button>
            <button onclick="openAddUserModal()" class="btn btn-primary"
                    style="background: linear-gradient(135deg, #930056, #6d003f); color: #fff; border: 1px solid #ff80c8; box-shadow: 0 4px 14px rgba(147, 0, 86, 0.45);">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add User
            </button>
        </div>
    </div>
</div>


{{-- Flash message --}}
@if(session('success'))
<div class="alert alert-success mb-4" style="background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.4);color:#6ee7b7;padding:.75rem 1.25rem;border-radius:.5rem;">
    {{ session('success') }}
</div>
@endif

{{-- Filters --}}
<div class="card mb-6">
    <div class="card-inner">
        <form method="GET" class="flex flex-wrap gap-3 items-center">
            <div class="relative flex-1 min-w-[200px]">
                <svg class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email…"
                       class="form-input w-full" style="padding-left: 2.75rem; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);" />
            </div>
            <select name="role" class="form-select flex-shrink-0" style="width:140px; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                <option value="">All Roles</option>
                <option value="admin"   {{ request('role')=='admin'  ? 'selected' : '' }}>Admin</option>
                <option value="user"    {{ request('role')=='user'   ? 'selected' : '' }}>User</option>
            </select>
            <select name="status" class="form-select flex-shrink-0" style="width:140px; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                <option value="">All Status</option>
                <option value="active"   {{ request('status')=='active'   ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <select name="per_page" class="form-select flex-shrink-0" style="width:130px; background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.08);">
                <option value="10"  {{ request('per_page','20')=='10'  ? 'selected':'' }}>10 / page</option>
                <option value="20"  {{ request('per_page','20')=='20'  ? 'selected':'' }}>20 / page</option>
                <option value="50"  {{ request('per_page','20')=='50'  ? 'selected':'' }}>50 / page</option>
                <option value="100" {{ request('per_page','20')=='100' ? 'selected':'' }}>100 / page</option>
            </select>
            <button type="submit" class="btn btn-sky flex-shrink-0">Apply</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary flex-shrink-0">Reset</a>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="padding-left:1.5rem;width:40px;">
                        <input type="checkbox" class="bulk-checkbox" id="selectAll" title="Select All">
                    </th>
                    <th>User</th>
                    <th>Role</th>
                    <th>Phone</th>
                    <th>Subscription</th>
                    <th>Enrollments</th>
                    <th>Joined</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr data-id="{{ $user->id }}">
                    <td style="padding-left:1.5rem;width:40px;">
                        <input type="checkbox" class="bulk-checkbox row-check" value="{{ $user->id }}">
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <img src="{{ $user->avatar_url }}" alt="" class="avatar rounded-full" />
                            <div>
                                <p class="text-white font-semibold text-sm">{{ $user->name }}</p>
                                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.35);">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($user->role === 'admin')
                            <span class="badge" style="background:rgba(147,0,86,0.25);color:#f472b6;border:1px solid rgba(244,114,182,0.3);">Admin</span>
                        @else
                            <span class="badge" style="background:rgba(56,189,248,0.15);color:#7dd3fc;border:1px solid rgba(125,211,252,0.3);">User</span>
                        @endif
                    </td>
                    <td class="text-sm" style="color:rgba(255,255,255,0.5);">{{ $user->phone ?? '—' }}</td>
                    <td>
                        @if($user->subscriptions->isNotEmpty())
                            <div>
                                <span class="badge badge-active">{{ $user->subscriptions->first()->plan->name ?? 'Active' }}</span>
                                <p class="text-xs mt-0.5" style="color:rgba(255,255,255,0.3);">
                                    Until {{ $user->subscriptions->first()->ends_at?->format('M d, Y') }}
                                </p>
                            </div>
                        @else
                            <span class="badge badge-inactive">No Plan</span>
                        @endif
                    </td>
                    <td class="text-center text-white text-sm">{{ $user->enrollments_count }}</td>
                    <td class="text-xs" style="color:rgba(255,255,255,0.4);">{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($user->is_active)
                            <span class="badge badge-active">Active</span>
                        @else
                            <span class="badge badge-inactive">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div class="flex gap-1.5">
                            <button type="button"
                                    onclick="openEditUserModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ addslashes($user->phone ?? '') }}', '{{ $user->role }}', {{ $user->is_active ? 1 : 0 }})"
                                    class="btn btn-secondary btn-sm">✏️ Edit</button>
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary btn-sm">View</a>
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-sky' }}">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-16" style="color:rgba(255,255,255,0.3);">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="px-6 pb-4">
        {{ $users->withQueryString()->links('pagination::default') }}
    </div>
    @endif
</div>

{{-- Bulk Action Bar --}}
<div id="bulkBar" class="bulk-bar">
    <span class="bulk-bar-count" id="bulkCount">0</span>
    <span class="bulk-bar-label">users selected</span>
    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.users.bulk-delete') }}" onsubmit="return confirmBulkDelete()">
        @csrf
        @method('DELETE')
        <input type="hidden" name="ids" id="bulkIds">
        <button type="submit" class="btn btn-danger btn-sm" style="border-color:rgba(248,113,113,0.5);">
            🗑 Delete Selected
        </button>
    </form>
    <button onclick="clearSelection()" class="btn btn-secondary btn-sm" style="font-size:0.75rem;">✕ Clear</button>
</div>

{{-- ─── Add User Modal ─────────────────────────────────────────────── --}}
<div id="addUserModal"
     style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);"
     onclick="if(event.target===this) closeAddUserModal()">
    <div style="background:#1a1a2e;border:1px solid rgba(255,255,255,0.1);border-radius:1rem;padding:2rem;width:100%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,0,0.5);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:#fff;font-size:1.2rem;font-weight:600;">Add New User</h2>
            <button onclick="closeAddUserModal()"
                    style="background:none;border:none;color:rgba(255,255,255,0.4);font-size:1.5rem;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            {{-- Validation errors --}}
            @if($errors->any())
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1rem;font-size:.85rem;">
                <ul style="margin:0;padding-left:1.2rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div style="display:flex;flex-direction:column;gap:1rem;">

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Full Name *</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                           class="form-input" style="width:100%;" placeholder="John Doe" />
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                           class="form-input" style="width:100%;" placeholder="john@example.com" />
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Phone (optional)</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                           class="form-input" style="width:100%;" placeholder="+1 234 567 890" />
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Role *</label>
                    <select name="role" required class="form-select" style="width:100%;">
                        <option value="user"  {{ old('role','user')=='user'  ? 'selected':'' }}>User</option>
                        <option value="admin" {{ old('role')=='admin' ? 'selected':'' }}>Admin</option>
                    </select>
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Password *</label>
                    <input type="password" name="password" required
                           class="form-input" style="width:100%;" placeholder="Min. 8 characters" />
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                           class="form-input" style="width:100%;" placeholder="Repeat password" />
                </div>

            </div>

            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-sky" style="flex:1;">Create User</button>
                <button type="button"
                        onclick="closeAddUserModal()"
                        class="btn btn-secondary" style="flex:1;">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- ─── Edit User Modal ─────────────────────────────────────────────── --}}
<div id="editUserModal"
     style="display:none;position:fixed;inset:0;z-index:9999;align-items:center;justify-content:center;background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);"
     onclick="if(event.target===this) closeEditUserModal()">
    <div style="background:#1a1a2e;border:1px solid rgba(255,255,255,0.1);border-radius:1rem;padding:2rem;width:100%;max-width:480px;box-shadow:0 20px 60px rgba(0,0,0,0.5);max-height:90vh;overflow-y:auto;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h2 style="color:#fff;font-size:1.2rem;font-weight:600;">✏️ Edit User</h2>
            <button onclick="closeEditUserModal()"
                    style="background:none;border:none;color:rgba(255,255,255,0.4);font-size:1.5rem;cursor:pointer;line-height:1;">&times;</button>
        </div>

        <form id="editUserForm" method="POST" action="">
            @csrf
            @method('PUT')
            <input type="hidden" name="_edit_user_id" id="editUserId" />

            {{-- Edit errors --}}
            @if(session('edit_errors'))
            <div style="background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.3);color:#fca5a5;padding:.75rem 1rem;border-radius:.5rem;margin-bottom:1rem;font-size:.85rem;">
                <ul style="margin:0;padding-left:1.2rem;">
                    @foreach(session('edit_errors') as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div style="display:flex;flex-direction:column;gap:1rem;">

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Full Name *</label>
                    <input type="text" name="name" id="editName" required
                           class="form-input" style="width:100%;" placeholder="John Doe" />
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Email Address *</label>
                    <input type="email" name="email" id="editEmail" required
                           class="form-input" style="width:100%;" placeholder="john@example.com" />
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Phone (optional)</label>
                    <input type="text" name="phone" id="editPhone"
                           class="form-input" style="width:100%;" placeholder="+1 234 567 890" />
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Role *</label>
                    <select name="role" id="editRole" required class="form-select" style="width:100%;">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Status</label>
                    <select name="is_active" id="editIsActive" class="form-select" style="width:100%;">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">
                        New Password <span style="color:rgba(255,255,255,0.3);font-size:.75rem;">(leave blank to keep current)</span>
                    </label>
                    <input type="password" name="password"
                           class="form-input" style="width:100%;" placeholder="Min. 8 characters" />
                </div>

                <div>
                    <label style="color:rgba(255,255,255,0.6);font-size:.8rem;display:block;margin-bottom:.35rem;">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="form-input" style="width:100%;" placeholder="Repeat new password" />
                </div>

            </div>

            <div style="display:flex;gap:.75rem;margin-top:1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Changes</button>
                <button type="button" onclick="closeEditUserModal()" class="btn btn-secondary" style="flex:1;">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── Add User Modal ──────────────────────────────────────────
    function openAddUserModal() {
        document.getElementById('addUserModal').style.display = 'flex';
    }
    function closeAddUserModal() {
        document.getElementById('addUserModal').style.display = 'none';
    }

    // ── Edit User Modal ─────────────────────────────────────────
    function openEditUserModal(id, name, email, phone, role, isActive) {
        document.getElementById('editUserId').value    = id;
        document.getElementById('editName').value      = name;
        document.getElementById('editEmail').value     = email;
        document.getElementById('editPhone').value     = phone;
        document.getElementById('editRole').value      = role;
        document.getElementById('editIsActive').value  = isActive;
        document.getElementById('editUserForm').action = '/admin/users/' + id;
        document.getElementById('editUserModal').style.display = 'flex';
    }
    function closeEditUserModal() {
        document.getElementById('editUserModal').style.display = 'none';
    }

    // ── Export Dropdown Toggle ──────────────────────────────────
    function toggleExportDropdown() {
        const menu = document.getElementById('export-dropdown-menu');
        const chevron = document.getElementById('export-chevron');
        const isOpen = !menu.classList.contains('pointer-events-none');
        
        if (isOpen) {
            menu.classList.add('pointer-events-none', 'opacity-0', 'translate-y-2');
            menu.classList.remove('opacity-100', 'translate-y-0');
            chevron.classList.remove('rotate-180');
        } else {
            menu.classList.remove('pointer-events-none', 'opacity-0', 'translate-y-2');
            menu.classList.add('opacity-100', 'translate-y-0');
            chevron.classList.add('rotate-180');
        }
    }

    // Close options dropdown if clicked outside
    document.addEventListener('click', function(event) {
        const container = document.getElementById('export-dropdown-container');
        const menu = document.getElementById('export-dropdown-menu');
        const chevron = document.getElementById('export-chevron');

        if (container && !container.contains(event.target) && !menu.classList.contains('pointer-events-none')) {
            menu.classList.add('pointer-events-none', 'opacity-0', 'translate-y-2');
            menu.classList.remove('opacity-100', 'translate-y-0');
            chevron.classList.remove('rotate-180');
        }
    });

</script>

{{-- Re-open Add modal when add-validation errors exist --}}
@if($errors->any() && !session('edit_errors'))
<script>
    document.addEventListener('DOMContentLoaded', function() { openAddUserModal(); });
</script>
@endif

{{-- Re-open Edit modal when edit-validation errors exist --}}
@if(session('edit_errors'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        openEditUserModal(
            '{{ session("edit_user_id") }}',
            '{{ addslashes(session("edit_name", "")) }}',
            '{{ addslashes(session("edit_email", "")) }}',
            '{{ addslashes(session("edit_phone", "")) }}',
            '{{ session("edit_role", "user") }}',
            '{{ session("edit_is_active", 1) }}'
        );
    });
</script>
@endif

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
        // highlight rows
        document.querySelectorAll('.row-check').forEach(cb => {
            cb.closest('tr').classList.toggle('row-selected', cb.checked);
        });
        // update select-all state
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
        return confirm(`⚠️ Delete ${n} selected user${n > 1 ? 's' : ''}? This action cannot be undone.`);
    };
})();
</script>
@endpush
