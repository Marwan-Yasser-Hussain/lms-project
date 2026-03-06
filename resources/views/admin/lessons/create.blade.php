@extends('layouts.admin')

@section('title', 'Add Lesson')
@section('topbar-title', 'Add Lesson')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Add Lesson</h1>
        <p class="page-subtitle">{{ $course->title }}</p>
    </div>
    <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-secondary">← Back to Course</a>
</div>

<form method="POST" action="{{ route('admin.courses.lessons.store', $course) }}">
    @csrf

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Main --}}
        <div class="xl:col-span-2 flex flex-col gap-6">

            {{-- Lesson Info --}}
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold text-white mb-4">Lesson Details</h2>

                    <div class="mb-4">
                        <label class="form-label">Lesson Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="form-input" placeholder="e.g. Introduction to HTML" />
                        @error('title')<p class="text-xs mt-1" style="color:#ff80c8;">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Video URL</label>
                        <p class="text-xs mb-2" style="color:rgba(255,255,255,0.3);">
                            Paste a YouTube, Vimeo, or Google Drive link — embedded automatically for students.
                        </p>
                        <input type="text" name="video_url" value="{{ old('video_url') }}" id="video-url-input"
                               class="form-input" placeholder="https://www.youtube.com/watch?v=..." />
                        @error('video_url')<p class="text-xs mt-1" style="color:#ff80c8;">{{ $message }}</p>@enderror

                        {{-- Live preview --}}
                        <div id="video-preview" style="display:none;margin-top:1rem;border-radius:12px;overflow:hidden;aspect-ratio:16/9;">
                            <iframe id="video-frame" src="" allow="autoplay;encrypted-media" allowfullscreen
                                    style="width:100%;height:100%;border:none;border-radius:12px;"></iframe>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Lesson Description / Content</label>
                        <textarea name="description" class="form-textarea" rows="5"
                                  placeholder="Describe what this lesson covers, or add written content…">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Resource URL</label>
                        <p class="text-xs mb-2" style="color:rgba(255,255,255,0.3);">Optional downloadable file or external resource link.</p>
                        <input type="text" name="resource_url" value="{{ old('resource_url') }}"
                               class="form-input" placeholder="https://..." />
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar --}}
        <div class="flex flex-col gap-6">

            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold text-white mb-4">Settings</h2>

                    <div class="mb-4">
                        <label class="form-label">Duration (minutes)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 0) }}" min="0"
                               class="form-input" />
                    </div>

                    <div class="flex items-center gap-2 mb-4">
                        <input type="checkbox" name="is_free_preview" id="is_free" value="1"
                               {{ old('is_free_preview') ? 'checked' : '' }}
                               style="width:16px;height:16px;accent-color:#930056;" />
                        <label for="is_free" class="text-sm" style="color:rgba(255,255,255,0.6);cursor:pointer;">
                            Free Preview (visible without enrollment)
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn text-white" style="background:#120A43;width:100%;justify-content:center;padding:0.85rem;border:1px solid rgba(255,255,255,0.05);box-shadow:0 4px 14px rgba(0,0,0,0.3);">
                📖 Save Lesson
            </button>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
// Video URL live preview
(function () {
    const input = document.getElementById('video-url-input');
    const preview = document.getElementById('video-preview');
    const frame = document.getElementById('video-frame');

    function getEmbedUrl(url) {
        if (!url) return null;
        let m;
        if (m = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/))
            return 'https://www.youtube.com/embed/' + m[1];
        if (m = url.match(/vimeo\.com\/(\d+)/))
            return 'https://player.vimeo.com/video/' + m[1];
        if (m = url.match(/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/))
            return 'https://drive.google.com/file/d/' + m[1] + '/preview';
        return null;
    }

    let timer;
    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            const embed = getEmbedUrl(input.value.trim());
            if (embed) {
                frame.src = embed;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
                frame.src = '';
            }
        }, 600);
    });
})();
</script>
@endpush
