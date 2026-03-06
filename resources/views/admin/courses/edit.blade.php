@extends('layouts.admin')

@section('title', 'Edit Course')
@section('topbar-title', 'Edit Course')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Course</h1>
        <p class="page-subtitle">Update course details and settings.</p>
    </div>
    <a href="{{ route('admin.courses.index') }}" class="btn btn-secondary">← Back</a>
</div>

<form method="POST" action="{{ route('admin.courses.update', $course) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 flex flex-col gap-6">
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold text-white mb-4">Course Details</h2>

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
                    <h2 class="font-bold text-white mb-3">Thumbnail</h2>
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
                    <h2 class="font-bold text-white mb-4">Settings</h2>

                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">— None —</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id',$course->category_id)==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                            @endforeach
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
                        <label class="form-label">Instructor Name</label>
                        <input type="text" name="instructor_name" value="{{ old('instructor_name',$course->instructor_name) }}" required class="form-input" />
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
