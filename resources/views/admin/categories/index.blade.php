@extends('layouts.admin')

@section('title', 'Categories')
@section('topbar-title', 'Category Management')

@section('content')

{{-- Welcome/Header Banner --}}
<div class="mb-8 rounded-2xl relative overflow-hidden animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute -top-20 -left-20 w-64 h-64 bg-[#045592] rounded-full mix-blend-screen filter blur-[90px] opacity-20 pointer-events-none"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10">
        <div>
            <h1 class="text-3xl font-black mb-2 tracking-tight text-white">Categories</h1>
            <p class="text-white/60 text-sm md:text-base max-w-xl leading-relaxed">
                Organize your courses into structured learning topics to help students find exactly what they need.
            </p>
        </div>
        <div class="flex-shrink-0">
            <button onclick="document.getElementById('create-modal').classList.remove('hidden')" class="btn btn-primary" style="background: linear-gradient(135deg, #045592, #0A2463); color: #fff; border: 1px solid #045592; box-shadow: 0 4px 14px rgba(4, 85, 146, 0.3);">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Category
            </button>
        </div>
    </div>
</div>

{{-- ── Grid ─────────────────────────────────────────────────────────────── --}}
<div class="flex items-center justify-between mb-6 animate-fade-up delay-1">
    <h2 class="text-xl font-bold tracking-tight">All Categories</h2>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
    @forelse($categories as $cat)
    <div class="stat-card animate-fade-up delay-2 group flex flex-col h-full" style="background: linear-gradient(145deg, #160D50, #0F043D); border-top: 3px solid {{ $cat->color }}; min-height: 100%;">
        
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 h-24 rounded-full filter blur-[60px] opacity-10 pointer-events-none z-0 group-hover:opacity-20 transition-opacity" style="background-color: {{ $cat->color }};"></div>

        <div class="relative z-10 flex flex-col h-full">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl flex-shrink-0 shadow-lg"
                     style="background:{{ $cat->color }}20; border: 1px solid {{ $cat->color }}40;">
                    {{ $cat->icon ?? '📁' }}
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-white font-bold text-lg truncate group-hover:text-white transition-colors" style="text-shadow: 0 0 10px {{ $cat->color }}40;">{{ $cat->name }}</h3>
                    <p class="text-xs font-semibold tracking-widest uppercase" style="color: {{ $cat->color }}; opacity: 0.8;">{{ $cat->courses_count }} courses</p>
                </div>
                <div>
                    @if($cat->is_active)
                        <span class="badge" style="background: rgba(4,85,146,0.15); color: #5bb8ff; border: 1px solid rgba(4,85,146,0.3);">Active</span>
                    @else
                        <span class="badge" style="background: rgba(255,255,255,0.05); color: #94a3b8; border: 1px solid rgba(255,255,255,0.1);">Inactive</span>
                    @endif
                </div>
            </div>

            @if($cat->description)
            <p class="text-sm text-white/50 mb-5 leading-relaxed">{{ Str::limit($cat->description, 90) }}</p>
            @endif

            {{-- ── Sub-categories Section ───────────────────────────────── --}}
            <div class="mb-5 flex-grow rounded-xl overflow-hidden border border-white/[0.06]" style="background: rgba(0,0,0,0.25);">
                <div class="flex items-center justify-between px-3 border-b border-white/[0.06]" style="padding-top: 12px; padding-bottom: 12px;">
                    <span class="text-[10px] font-bold text-white/40 uppercase tracking-[0.15em]">Sub-categories</span>
                    <button onclick="openCreateSub({{ $cat->id }})"
                            class="text-xs font-semibold px-3 py-1.5 rounded-lg flex items-center gap-1.5 transition-all text-white"
                            style="background: #790150; border: 1px solid #9c0166;">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Add
                    </button>
                </div>

                @if($cat->subcategories->isEmpty())
                    <div class="flex items-center gap-2.5 px-4 text-white/25" style="padding-top: 12px; padding-bottom: 12px;">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <span class="text-xs italic">No sub-categories yet</span>
                    </div>
                @else
                    <ul class="divide-y divide-white/[0.025] max-h-[180px] overflow-y-auto [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:bg-white/10 [&::-webkit-scrollbar-thumb]:rounded-full">
                        @foreach($cat->subcategories as $sub)
                        <li class="flex items-center justify-between gap-2 px-3" style="padding-top: 12px; padding-bottom: 12px;">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $sub->is_active ? $cat->color : '#6b7280' }};"></span>
                                <div class="min-w-0">
                                    <span class="text-white/85 text-sm font-medium truncate block">{{ $sub->name }}</span>
                                    @if($sub->description)
                                        <span class="text-white/35 text-[10px] truncate block max-w-[160px]">{{ $sub->description }}</span>
                                    @endif
                                </div>
                                @if(!$sub->is_active)
                                    <span class="flex-shrink-0 text-[9px] font-bold uppercase px-1.5 py-0.5 rounded bg-red-500/10 text-red-400 border border-red-500/20">Off</span>
                                @endif
                            </div>
                            <div class="flex items-center gap-1.5 flex-shrink-0">
                                <button type="button"
                                        onclick="openEditSub({{ $cat->id }}, {{ $sub->id }}, '{{ addslashes($sub->name) }}', '{{ addslashes($sub->description ?? '') }}', {{ $sub->is_active ? 'true' : 'false' }})"
                                        class="p-2 rounded-lg bg-white/5 text-white/50 hover:text-white hover:bg-white/15 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>
                                <form method="POST" action="{{ route('admin.categories.subcategories.destroy', [$cat, $sub]) }}"
                                      id="delete-sub-form-{{ $sub->id }}" class="m-0">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmSubDelete({{ $sub->id }})" class="p-2 rounded-lg bg-red-500/10 text-red-400 hover:text-white hover:bg-red-500 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
            {{-- ── End Sub-categories ───────────────────────────────────── --}}

            <div class="mt-auto pt-4 border-t border-white/5 flex gap-2">
                <button onclick="openEdit({{ $cat->id }}, '{{ addslashes($cat->name) }}', '{{ addslashes($cat->description ?? '') }}', '{{ $cat->icon ?? '' }}', '{{ $cat->color }}', {{ $cat->is_active ? 'true' : 'false' }})"
                        class="btn btn-secondary btn-sm flex-1 bg-white/5 hover:bg-white/10" style="color: rgba(255,255,255,0.8);">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>    
                    Edit
                </button>
                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}"
                      id="delete-cat-form-{{ $cat->id }}" class="flex-1">
                    @csrf @method('DELETE')
                    <button type="button" onclick="confirmCatDelete({{ $cat->id }})" class="btn btn-danger btn-sm w-full bg-red-500/10 hover:bg-red-500/20 text-red-400 border-red-500/20 transition-colors">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="card sm:col-span-2 xl:col-span-3 bg-white/5 border border-white/10">
        <div class="card-inner text-center py-16 text-white/40 flex flex-col items-center justify-center">
            <svg class="w-12 h-12 mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
            <p>No learning categories have been created yet.</p>
        </div>
    </div>
    @endforelse
</div>

{{-- Create Category Modal --}}
<div id="create-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300"
     style="background:rgba(0,0,0,0.7);backdrop-filter:blur(8px);">
    <div class="card w-full max-w-md transform transition-all shadow-2xl" id="create-modal-content" style="background: linear-gradient(145deg, #160D50, #0F043D); border: 1px solid rgba(255,255,255,0.1); border-top: 3px solid #045592; transform: translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
        <div class="card-inner p-6 md:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#045592]/20 flex items-center justify-center text-[#5bb8ff]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">New Category</h2>
                </div>
                <button type="button" onclick="closeModal('create-modal')"
                        class="text-white/40 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="mb-5">
                    <label class="form-label text-white/70">Category Name <span class="text-[#f87171]">*</span></label>
                    <input type="text" name="name" required class="form-input bg-[#0F043D] border-white/10 text-white focus:border-[#045592]" placeholder="e.g. Web Development" />
                </div>
                
                <div class="mb-5">
                    <label class="form-label text-white/70">Description</label>
                    <textarea name="description" class="form-textarea bg-[#0F043D] border-white/10 text-white focus:border-[#045592]" rows="3" placeholder="What kind of courses go in this category?"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="form-label text-white/70">Icon (Emoji) <span class="text-[#f87171]">*</span></label>
                        <input type="text" name="icon" class="form-input bg-[#0F043D] border-white/10 text-white text-xl text-center focus:border-[#045592]" value="📁" required/>
                    </div>
                    <div>
                        <label class="form-label text-white/70">Accent Color</label>
                        <div class="relative h-[42px] rounded-lg overflow-hidden border border-white/10 focus-within:border-[#045592]">
                            <input type="color" name="color" value="#045592" class="absolute -inset-2 w-[150%] h-[150%] cursor-pointer" />
                        </div>
                    </div>
                </div>

                <div class="mb-6 flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="create-active" value="1" checked class="w-4 h-4 rounded border-white/10 bg-[#0F043D] text-[#045592] focus:ring-[#045592] cursor-pointer" />
                    <label for="create-active" class="form-label text-white/70 mb-0 cursor-pointer">Active Category</label>
                </div>
                
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeModal('create-modal')"
                            class="btn btn-secondary flex-1">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-1" style="background: linear-gradient(135deg, #045592, #0A2463); border-color:#045592;">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Category Modal --}}
<div id="edit-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300"
     style="background:rgba(0,0,0,0.7);backdrop-filter:blur(8px);">
    <div class="card w-full max-w-md transform transition-all shadow-2xl" id="edit-modal-content" style="background: linear-gradient(145deg, #160D50, #0F043D); border: 1px solid rgba(255,255,255,0.1); border-top: 3px solid #930056; transform: translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
        <div class="card-inner p-6 md:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#930056]/20 flex items-center justify-center text-[#ff80c8]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">Edit Category</h2>
                </div>
                <button type="button" onclick="closeModal('edit-modal')"
                        class="text-white/40 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form id="edit-form" method="POST">
                @csrf @method('PUT')
                <div class="mb-5">
                    <label class="form-label text-white/70">Category Name <span class="text-[#f87171]">*</span></label>
                    <input type="text" name="name" id="edit-name" required class="form-input bg-[#0F043D] border-white/10 text-white focus:border-[#930056]" />
                </div>
                
                <div class="mb-5">
                    <label class="form-label text-white/70">Description</label>
                    <textarea name="description" id="edit-desc" class="form-textarea bg-[#0F043D] border-white/10 text-white focus:border-[#930056]" rows="3"></textarea>
                </div>
                
                <div class="grid grid-cols-2 gap-5 mb-6">
                    <div>
                        <label class="form-label text-white/70">Icon (Emoji) <span class="text-[#f87171]">*</span></label>
                        <input type="text" name="icon" id="edit-icon" class="form-input bg-[#0F043D] border-white/10 text-white text-xl text-center focus:border-[#930056]" required/>
                    </div>
                    <div>
                        <label class="form-label text-white/70">Accent Color</label>
                        <div class="relative h-[42px] rounded-lg overflow-hidden border border-white/10 focus-within:border-[#930056]">
                            <input type="color" name="color" id="edit-color" class="absolute -inset-2 w-[150%] h-[150%] cursor-pointer" />
                        </div>
                    </div>
                </div>

                <div class="mb-6 flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="edit-active" value="1" class="w-4 h-4 rounded border-white/10 bg-[#0F043D] text-[#930056] focus:ring-[#930056] cursor-pointer" />
                    <label for="edit-active" class="form-label text-white/70 mb-0 cursor-pointer">Active Category</label>
                </div>
                
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeModal('edit-modal')"
                            class="btn btn-secondary flex-1">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-1">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Create Sub-category Modal --}}
<div id="create-sub-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300"
     style="background:rgba(0,0,0,0.7);backdrop-filter:blur(8px);">
    <div class="card w-full max-w-md shadow-2xl" id="create-sub-modal-content" style="background: linear-gradient(145deg, #160D50, #0F043D); border: 1px solid rgba(255,255,255,0.1); border-top: 3px solid #2d7a4f; transform: translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
        <div class="card-inner p-6 md:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#2d7a4f]/20 flex items-center justify-center text-[#4ade80]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">New Sub-category</h2>
                </div>
                <button type="button" onclick="closeModal('create-sub-modal')"
                        class="text-white/40 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="create-sub-form" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="form-label text-white/70">Sub-category Name <span class="text-[#f87171]">*</span></label>
                    <input type="text" name="name" required class="form-input bg-[#0F043D] border-white/10 text-white focus:border-[#2d7a4f]" placeholder="e.g. React JS" />
                </div>
                <div class="mb-5">
                    <label class="form-label text-white/70">Description</label>
                    <textarea name="description" class="form-textarea bg-[#0F043D] border-white/10 text-white focus:border-[#2d7a4f]" rows="2" placeholder="Optional description"></textarea>
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeModal('create-sub-modal')"
                            class="btn btn-secondary flex-1">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-1" style="background: linear-gradient(135deg, #2d7a4f, #145232); border-color:#2d7a4f;">Add Sub-category</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Sub-category Modal --}}
<div id="edit-sub-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 transition-opacity duration-300"
     style="background:rgba(0,0,0,0.7);backdrop-filter:blur(8px);">
    <div class="card w-full max-w-md shadow-2xl" id="edit-sub-modal-content" style="background: linear-gradient(145deg, #160D50, #0F043D); border: 1px solid rgba(255,255,255,0.1); border-top: 3px solid #7c3aed; transform: translateY(20px); opacity: 0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
        <div class="card-inner p-6 md:p-8">
            <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-[#7c3aed]/20 flex items-center justify-center text-[#c4b5fd]">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </div>
                    <h2 class="text-xl font-bold tracking-tight">Edit Sub-category</h2>
                </div>
                <button type="button" onclick="closeModal('edit-sub-modal')"
                        class="text-white/40 hover:text-white hover:bg-white/10 p-1.5 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form id="edit-sub-form" method="POST">
                @csrf @method('PUT')
                <div class="mb-5">
                    <label class="form-label text-white/70">Sub-category Name <span class="text-[#f87171]">*</span></label>
                    <input type="text" name="name" id="edit-sub-name" required class="form-input bg-[#0F043D] border-white/10 text-white focus:border-[#7c3aed]" />
                </div>
                <div class="mb-5">
                    <label class="form-label text-white/70">Description</label>
                    <textarea name="description" id="edit-sub-desc" class="form-textarea bg-[#0F043D] border-white/10 text-white focus:border-[#7c3aed]" rows="2"></textarea>
                </div>
                <div class="mb-6 flex items-center gap-3">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" id="edit-sub-active" value="1" class="w-4 h-4 rounded border-white/10 bg-[#0F043D] text-[#7c3aed] focus:ring-[#7c3aed] cursor-pointer" />
                    <label for="edit-sub-active" class="form-label text-white/70 mb-0 cursor-pointer">Active Sub-category</label>
                </div>
                <div class="pt-2 flex gap-3">
                    <button type="button" onclick="closeModal('edit-sub-modal')"
                            class="btn btn-secondary flex-1">Cancel</button>
                    <button type="submit" class="btn btn-primary flex-1" style="background: linear-gradient(135deg, #7c3aed, #4c1d95); border-color:#7c3aed;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Open Modal with nice animation
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalId + '-content');
    modal.classList.remove('hidden');
    // Force reflow
    void modal.offsetWidth;
    content.style.opacity = '1';
    content.style.transform = 'translateY(0)';
}

// Close Modal with nice animation
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(modalId + '-content');
    content.style.opacity = '0';
    content.style.transform = 'translateY(20px)';
    
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Override original create modal trigger
document.querySelector('[onclick="document.getElementById(\\\'create-modal\\\').classList.remove(\\\'hidden\\\')"]').onclick = function() {
    openModal('create-modal');
};

function openEdit(id, name, desc, icon, color, isActive) {
    document.getElementById('edit-name').value  = name;
    document.getElementById('edit-desc').value  = desc;
    document.getElementById('edit-icon').value  = icon || '📁';
    document.getElementById('edit-color').value = color || '#045592';
    document.getElementById('edit-active').checked = isActive === true || isActive === '1' || isActive === 1;
    document.getElementById('edit-form').action = '/admin/categories/' + id;
    openModal('edit-modal');
}

function openCreateSub(categoryId) {
    document.getElementById('create-sub-form').action = '/admin/categories/' + categoryId + '/subcategories';
    openModal('create-sub-modal');
}

function openEditSub(categoryId, subId, name, desc, isActive) {
    document.getElementById('edit-sub-name').value  = name;
    document.getElementById('edit-sub-desc').value  = desc;
    document.getElementById('edit-sub-active').checked = isActive === true || isActive === '1' || isActive === 1;
    document.getElementById('edit-sub-form').action = '/admin/categories/' + categoryId + '/subcategories/' + subId;
    openModal('edit-sub-modal');
}

function confirmSubDelete(subId) {
    Swal.fire({
        title: 'Delete this sub-category?',
        text: "You won't be able to revert this! Courses attached to it might lose their sub-category assignment.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#4b5563',
        confirmButtonText: 'Yes, delete it!',
        background: '#1A1262',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-sub-form-' + subId).submit();
        }
    });
}

function confirmCatDelete(catId) {
    Swal.fire({
        title: 'Delete this category?',
        text: "You won't be able to revert this! All associated courses and sub-categories might be lost or become unassigned.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#4b5563',
        confirmButtonText: 'Yes, delete it!',
        background: '#1A1262',
        color: '#fff'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-cat-form-' + catId).submit();
        }
    });
}
</script>
@endpush
