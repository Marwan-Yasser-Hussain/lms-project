@extends('layouts.admin')

@section('title', 'Create Course')
@section('topbar-title', 'Create New Course')

@section('content')

{{-- Welcome/Header Banner --}}
<div class="mb-8 rounded-2xl relative overflow-hidden animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute -top-24 right-10 w-64 h-64 bg-[#ff80c8] rounded-full mix-blend-screen filter blur-[90px] opacity-20 pointer-events-none"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10 w-full flex-wrap">
        <div>
            <h1 class="text-3xl font-black mb-2 tracking-tight text-white">Create Course</h1>
            <p class="text-white/60 text-sm md:text-base max-w-xl leading-relaxed">
                Add a new course to the platform.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.courses.index') }}" class="btn text-white transition-opacity hover:opacity-90 px-4"
               style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
               ← Back to Courses
            </a>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.courses.store') }}" enctype="multipart/form-data">
    @csrf

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Main Column --}}
        <div class="xl:col-span-2 flex flex-col gap-6">

            {{-- Basic Info --}}
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold mb-4">Course Details</h2>

                    <div class="mb-4">
                        <label class="form-label">Course Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="form-input" placeholder="e.g. Complete Web Development Bootcamp" />
                        @error('title')<p class="text-xs mt-1" style="color:#ff80c8;">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="4"
                                  placeholder="Describe what this course is about…">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">What Students Will Learn</label>
                        <p class="text-xs mb-2" style="color:rgba(255,255,255,0.3);">One point per line — shown as a bullet list on the course page.</p>
                        <textarea name="whatyoulearn" class="form-textarea" rows="5"
                                  placeholder="Build responsive websites&#10;Master HTML, CSS & JavaScript&#10;Deploy apps to the web">{{ old('whatyoulearn') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Preview Video URL</label>
                        <p class="text-xs mb-2" style="color:rgba(255,255,255,0.3);">
                            Paste a YouTube, Vimeo, or Google Drive link. The video will be embedded automatically.
                        </p>
                        <input type="url" name="preview_video_url" value="{{ old('preview_video_url') }}"
                               class="form-input" placeholder="https://www.youtube.com/watch?v=..." />
                        @error('preview_video_url')<p class="text-xs mt-1" style="color:#ff80c8;">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar Column --}}
        <div class="flex flex-col gap-6">

            {{-- Thumbnail --}}
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold mb-4">Thumbnail</h2>
                    <div id="thumb-drop" onclick="document.getElementById('thumbnail-input').click()"
                         style="border:2px dashed rgba(255,255,255,0.1);border-radius:12px;padding:2rem;text-align:center;cursor:pointer;transition:border-color 0.2s;"
                         onmouseover="this.style.borderColor='rgba(147,0,86,0.5)'"
                         onmouseout="this.style.borderColor='rgba(255,255,255,0.1)'">
                        <div id="thumb-preview" class="hidden mb-3">
                            <img id="thumb-img" src="" alt="" style="max-height:150px;border-radius:8px;margin:0 auto;" />
                        </div>
                        <svg id="thumb-icon" class="w-8 h-8 mx-auto mb-2" style="color:rgba(255,255,255,0.2);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-xs" style="color:rgba(255,255,255,0.3);">Click to upload thumbnail</p>
                        <p class="text-xs mt-1" style="color:rgba(255,255,255,0.2);">JPG, PNG — max 2MB</p>
                    </div>
                    <input type="file" name="thumbnail" id="thumbnail-input" accept="image/*" class="hidden"
                           onchange="previewThumb(this)" />
                </div>
            </div>

            {{-- Settings --}}
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold mb-4">Settings</h2>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select" onchange="toggleSubcategories()">
                            <option value="">— Select Category —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-subs="{{ $cat->subcategories->toJson() }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3 hidden" id="sub_category_container">
                        <label class="form-label">Sub-category</label>
                        <select name="sub_category_id" id="sub_category_id" class="form-select">
                            <option value="">— Select Sub-category —</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Level *</label>
                        <select name="level" class="form-select" required>
                            <option value="beginner"     {{ old('level','beginner')=='beginner'?'selected':'' }}>Beginner</option>
                            <option value="intermediate" {{ old('level')=='intermediate'?'selected':'' }}>Intermediate</option>
                            <option value="advanced"     {{ old('level')=='advanced'?'selected':'' }}>Advanced</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Language</label>
                        <input type="text" name="language" value="{{ old('language','English') }}"
                               class="form-input" placeholder="English" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Duration (hours)</label>
                        <input type="number" name="duration_hours" value="{{ old('duration_hours',0) }}" min="0"
                               class="form-input" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="draft"     {{ old('status','draft')=='draft'?'selected':'' }}>Draft</option>
                            <option value="published" {{ old('status')=='published'?'selected':'' }}>Published</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="has_certificate" id="has_cert" value="1"
                               {{ old('has_certificate',1)?'checked':'' }}
                               style="width:16px;height:16px;accent-color:#930056;" />
                        <label for="has_cert" class="text-sm" style="color:rgba(255,255,255,0.6);cursor:pointer;">
                            Issue completion certificate
                        </label>
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn text-white transition-opacity hover:opacity-90" style="background:#120A43; width:100%; justify-content:center; padding-top:0.85rem; padding-bottom:0.85rem; border:1px solid rgba(255,255,255,0.05); box-shadow:0 4px 14px rgba(0,0,0,0.3);">
                🚀 Create Course
            </button>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
function previewThumb(input) {
    const file = input.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('thumb-img').src = e.target.result;
        document.getElementById('thumb-preview').classList.remove('hidden');
        document.getElementById('thumb-icon').classList.add('hidden');
    };
    reader.readAsDataURL(file);
}

const oldSubCategory = "{{ old('sub_category_id') }}";

function toggleSubcategories() {
    const catSelect = document.getElementById('category_id');
    const subContainer = document.getElementById('sub_category_container');
    const subSelect = document.getElementById('sub_category_id');
    
    if (!catSelect || !subContainer || !subSelect) return;
    
    const selectedOption = catSelect.options[catSelect.selectedIndex];
    
    if (!selectedOption.value) {
        subContainer.classList.add('hidden');
        subSelect.innerHTML = '<option value="">— Select Sub-category —</option>';
        return;
    }

    const subsData = selectedOption.getAttribute('data-subs');
    if (subsData) {
        const subs = JSON.parse(subsData);
        if (subs.length > 0) {
            let optionsHtml = '<option value="">— Select Sub-category —</option>';
            subs.forEach(sub => {
                const selected = (oldSubCategory == sub.id) ? 'selected' : '';
                optionsHtml += `<option value="${sub.id}" ${selected}>${sub.name}</option>`;
            });
            subSelect.innerHTML = optionsHtml;
            subContainer.classList.remove('hidden');
            return;
        }
    }
    
    subContainer.classList.add('hidden');
    subSelect.innerHTML = '<option value="">— Select Sub-category —</option>';
}

document.addEventListener('DOMContentLoaded', function() {
    toggleSubcategories();
});
</script>
@endpush
