@extends('layouts.admin')

@section('title', 'Create Subscription Plan')
@section('topbar-title', 'Create Plan')

@section('content')

{{-- Welcome/Header Banner --}}
<div class="mb-8 rounded-2xl relative overflow-hidden animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-[#e0b0ff] rounded-full mix-blend-screen filter blur-[90px] opacity-20 pointer-events-none"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10">
        <div>
            <h1 class="text-3xl font-black text-white mb-2 tracking-tight">New Subscription Plan</h1>
            <p class="text-white/60 text-sm md:text-base max-w-xl leading-relaxed">
                Create a new pricing tier for your platform. Define duration, cost, and features.
            </p>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary" style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1);">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Plans
            </a>
        </div>
    </div>
</div>

<div class="card animate-fade-up delay-1" style="max-width:700px; background: linear-gradient(145deg, #160D50, #0F043D); border-top: 3px solid #e0b0ff;">
    <div class="card-inner p-6 md:p-8">
        
        <div class="flex items-center gap-3 mb-8 pb-4 border-b border-white/5">
            <div class="w-10 h-10 rounded-xl bg-[#e0b0ff]/10 flex items-center justify-center text-[#e0b0ff]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            </div>
            <h2 class="text-xl font-bold text-white tracking-tight">Plan Details</h2>
        </div>

        <form method="POST" action="{{ route('admin.subscriptions.plans.store') }}">
            @csrf

            <div class="mb-5">
                <label class="form-label text-white/70">Plan Name <span class="text-[#f87171]">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input bg-[#0F043D] border-white/10 text-white focus:border-[#e0b0ff] focus:ring-1 focus:ring-[#e0b0ff]/30 transition-all" placeholder="e.g. Pro Monthly, Premium Annual" />
            </div>

            <div class="mb-5">
                <label class="form-label text-white/70">Description</label>
                <textarea name="description" class="form-textarea bg-[#0F043D] border-white/10 text-white focus:border-[#e0b0ff] focus:ring-1 focus:ring-[#e0b0ff]/30 transition-all" rows="2" placeholder="Brief summary of who this plan is for...">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                <div>
                    <label class="form-label text-white/70">Price (USD) <span class="text-[#f87171]">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-white/40 font-medium">$</span>
                        <input type="number" name="price" value="{{ old('price') }}" required min="0" step="0.01" class="form-input pl-8 bg-[#0F043D] border-white/10 text-white focus:border-[#e0b0ff]" placeholder="29.99" />
                    </div>
                </div>
                <div>
                    <label class="form-label text-white/70">Duration (Days) <span class="text-[#f87171]">*</span></label>
                    <div class="relative">
                        <input type="number" name="duration_days" value="{{ old('duration_days') }}" required min="1" class="form-input pr-12 bg-[#0F043D] border-white/10 text-white focus:border-[#e0b0ff]" placeholder="30" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-white/40 text-sm">days</span>
                    </div>
                </div>
            </div>

            <div class="mb-6 bg-black/20 p-5 rounded-xl border border-white/5">
                <label class="form-label text-white/70 font-bold text-base mb-1">Plan Features</label>
                <p class="text-xs mb-4 text-white/40">Add the key benefits (one per line) that will appear as checkmarks on the pricing card.</p>
                
                <div id="features-list" class="flex flex-col gap-3 mb-4">
                    <div class="flex gap-2 items-center">
                        <div class="w-6 flex justify-center text-[#e0b0ff]/50"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
                        <input type="text" name="features[]" class="form-input bg-[#0F043D] border-white/10" placeholder="e.g. Access all premium courses" />
                        <button type="button" onclick="removeFeature(this)" class="btn btn-danger btn-sm flex-shrink-0 px-3 opacity-60 hover:opacity-100 transition-opacity" title="Remove Feature">✕</button>
                    </div>
                </div>
                
                <button type="button" onclick="addFeature()" class="btn text-sm font-semibold text-[#e0b0ff] bg-[#e0b0ff]/10 hover:bg-[#e0b0ff]/20 px-4 py-2 rounded-lg border border-[#e0b0ff]/20 transition-all flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Add Another Feature
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                <div>
                    <label class="form-label text-white/70">Display Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order',0) }}" class="form-input bg-[#0F043D] border-white/10 w-24" />
                    <p class="text-[11px] text-white/30 mt-1">Lower numbers appear first.</p>
                </div>
                <div class="flex flex-col gap-4 pt-1 md:pt-6">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" name="is_popular" id="popular" value="1" {{ old('is_popular')?'checked':'' }} class="peer appearance-none w-5 h-5 border border-white/20 rounded bg-[#0F043D] checked:bg-[#930056] checked:border-[#930056] transition-all cursor-pointer" />
                            <svg class="absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm text-white/80 font-medium group-hover:text-white transition-colors">Highlight as "Most Popular"</span>
                            <span class="text-[10px] text-white/40">Adds a special badge and glow effect to the card.</span>
                        </div>
                    </label>
                    
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <div class="relative flex items-center justify-center">
                            <input type="checkbox" name="is_active" id="active" value="1" checked class="peer appearance-none w-5 h-5 border border-white/20 rounded bg-[#0F043D] checked:bg-[#045592] checked:border-[#045592] transition-all cursor-pointer" />
                            <svg class="absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-sm text-white/80 font-medium group-hover:text-white transition-colors">Active Status</span>
                            <span class="text-[10px] text-white/40">Uncheck to hide this plan from students.</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="pt-5 border-t border-white/10 flex items-center justify-end gap-3">
                <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-secondary px-6">Cancel</a>
                <button type="submit" class="btn btn-primary px-8" style="background: linear-gradient(135deg, #e0b0ff, #1A1262); color: #fff; border: 1px solid #e0b0ff; box-shadow: 0 4px 14px rgba(224, 176, 255, 0.3);">
                    Create Subscription Plan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function addFeature() {
    const list = document.getElementById('features-list');
    const div  = document.createElement('div');
    div.className = 'flex gap-2 items-center animate-fade-up';
    div.innerHTML = `
        <div class="w-6 flex justify-center text-[#e0b0ff]/50"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
        <input type="text" name="features[]" class="form-input bg-[#0F043D] border-white/10" placeholder="New feature..." />
        <button type="button" onclick="removeFeature(this)" class="btn btn-danger btn-sm flex-shrink-0 px-3 opacity-60 hover:opacity-100 transition-opacity" title="Remove Feature">✕</button>
    `;
    list.appendChild(div);
}
function removeFeature(btn) {
    const list = document.getElementById('features-list');
    if (list.children.length > 1) {
        btn.parentElement.style.opacity = '0';
        setTimeout(() => btn.parentElement.remove(), 200);
    }
}
</script>
@endpush
