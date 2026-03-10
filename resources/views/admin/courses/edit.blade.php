@extends('layouts.admin')

@section('title', 'Edit Course')
@section('topbar-title', 'Edit Course')

@section('content')

{{-- Welcome/Header Banner --}}
<div class="mb-8 rounded-2xl relative overflow-hidden animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute -top-24 right-10 w-64 h-64 bg-[#ff80c8] rounded-full mix-blend-screen filter blur-[90px] opacity-20 pointer-events-none"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10 w-full flex-wrap">
        <div>
            <h1 class="text-3xl font-black mb-2 tracking-tight text-white">Edit Course</h1>
            <p class="text-white/60 text-sm md:text-base max-w-xl leading-relaxed">
                Update course details and settings.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.courses.index') }}" class="btn text-white transition-opacity hover:opacity-90 px-4"
               style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
               ← Back
            </a>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 flex flex-col gap-6">
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold mb-4">Course Details</h2>

                    <div class="mb-4">
                        <label class="form-label">Course Title *</label>
                        <input type="text" name="title" value="{{ old('title', $course->title) }}" required class="form-input" />
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="4">{{ old('description', $course->description) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">What Students Will Learn</label>
                        <p class="text-xs mb-2" style="color:rgba(255,255,255,0.3);">One point per line.</p>
                        <textarea name="whatyoulearn" class="form-textarea" rows="5">{{ old('whatyoulearn', $course->whatyoulearn) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Preview Video URL</label>
                        <p class="text-xs mb-2" style="color:rgba(255,255,255,0.3);">YouTube, Vimeo, or Google Drive link — embedded automatically.</p>
                        <input type="url" name="preview_video_url" value="{{ old('preview_video_url', $course->preview_video_url) }}" class="form-input" placeholder="https://www.youtube.com/watch?v=..." />
                        @if($course->preview_video_url)
                        <p class="text-xs mt-1" style="color:#5bb8ff;">
                            Current: <a href="{{ $course->preview_video_url }}" target="_blank" style="color:#5bb8ff;">{{ Str::limit($course->preview_video_url, 50) }}</a>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold mb-3">Thumbnail</h2>
                    @if($course->thumbnail)
                    <img src="{{ asset('storage/'.$course->thumbnail) }}" alt=""
                         class="w-full h-36 object-cover rounded-xl mb-3" />
                    @endif
                    <input type="file" name="thumbnail" accept="image/*" class="form-input" style="padding:0.4rem;" />
                    <p class="text-xs mt-1" style="color:rgba(255,255,255,0.3);">Leave empty to keep current thumbnail.</p>
                </div>
            </div>

            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold mb-4">Settings</h2>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" id="category_id" class="form-select" onchange="toggleSubcategories()">
                            <option value="">— None —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" data-subs="{{ $cat->subcategories->toJson() }}" {{ old('category_id',$course->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
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
                        <label class="form-label">Level</label>
                        <select name="level" class="form-select" required>
                            @foreach(['beginner','intermediate','advanced'] as $lv)
                            <option value="{{ $lv }}" {{ old('level',$course->level)===$lv?'selected':'' }}>{{ ucfirst($lv) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Language</label>
                        <input type="text" name="language" value="{{ old('language',$course->language) }}" class="form-input" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Duration (hours)</label>
                        <input type="number" name="duration_hours" value="{{ old('duration_hours',$course->duration_hours) }}" min="0" class="form-input" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select" required>
                            <option value="draft"     {{ old('status',$course->status)==='draft'?'selected':'' }}>Draft</option>
                            <option value="published" {{ old('status',$course->status)==='published'?'selected':'' }}>Published</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="has_certificate" id="has_cert" value="1"
                               {{ old('has_certificate',$course->has_certificate)?'checked':'' }}
                               style="width:16px;height:16px;accent-color:#930056;" />
                        <label for="has_cert" class="text-sm" style="color:rgba(255,255,255,0.6);cursor:pointer;">Issue completion certificate</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn text-white transition-opacity hover:opacity-90" style="background:#120A43; width:100%; justify-content:center; padding-top:0.85rem; padding-bottom:0.85rem; border:1px solid rgba(255,255,255,0.05); box-shadow:0 4px 14px rgba(0,0,0,0.3);">
                Save Changes
            </button>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
const oldSubCategory = "{{ old('sub_category_id', $course->sub_category_id) }}";

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
