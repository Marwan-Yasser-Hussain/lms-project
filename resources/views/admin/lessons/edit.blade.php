@extends('layouts.admin')

@section('title', 'Edit Lesson')
@section('topbar-title', 'Edit Lesson')

@section('content')

{{-- Welcome/Header Banner --}}
<div class="mb-8 rounded-2xl relative overflow-hidden animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute -top-24 right-10 w-64 h-64 bg-[#ff80c8] rounded-full mix-blend-screen filter blur-[90px] opacity-20 pointer-events-none"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10 w-full flex-wrap">
        <div>
            <h1 class="text-3xl font-black text-white mb-2 tracking-tight">Edit Lesson</h1>
            <p class="text-white/60 text-sm md:text-base max-w-xl leading-relaxed">
                {{ $course->title }}
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.courses.show', $course) }}" class="btn text-white transition-opacity hover:opacity-90 px-4"
               style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
               ← Back to Course
            </a>
        </div>
    </div>
</div>

<form method="POST" action="{{ route('admin.courses.lessons.update', [$course, $lesson]) }}">
    @csrf @method('PUT')

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <div class="xl:col-span-2 flex flex-col gap-6">
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold text-white mb-4">Lesson Details</h2>

                    <div class="mb-4">
                        <label class="form-label">Lesson Title *</label>
                        <input type="text" name="title" value="{{ old('title', $lesson->title) }}" required
                               class="form-input" />
                        @error('title')<p class="text-xs mt-1" style="color:#ff80c8;">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Video URL</label>
                        <p class="text-xs mb-2" style="color:rgba(255,255,255,0.3);">
                            YouTube, Vimeo, or Google Drive link — embedded automatically.
                        </p>
                        <input type="text" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" id="video-url-input"
                               class="form-input" placeholder="https://www.youtube.com/watch?v=..." />
                        <div id="video-preview" style="display:{{ $lesson->video_url ? 'block' : 'none' }};margin-top:1rem;border-radius:12px;overflow:hidden;aspect-ratio:16/9;">
                            @if($lesson->video_provider)
                                <div id="player" data-plyr-provider="{{ $lesson->video_provider }}" data-plyr-embed-id="{{ $lesson->video_id }}"></div>
                            @elseif($lesson->video_url)
                                <div class="plyr__video-embed" id="player">
                                    <iframe id="video-frame" src="{{ $lesson->embed_url ?? '' }}" allow="autoplay;encrypted-media" allowfullscreen
                                            style="width:100%;height:100%;border:none;border-radius:12px;"></iframe>
                                </div>
                            @else
                                <div id="player"></div>
                            @endif
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Lesson Description / Content</label>
                        <textarea name="description" class="form-textarea" rows="5">{{ old('description', $lesson->description) }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Resource URL</label>
                        <input type="text" name="resource_url" value="{{ old('resource_url', $lesson->resource_url) }}"
                               class="form-input" placeholder="https://..." />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold text-white mb-4">Settings</h2>

                    <div class="mb-4">
                        <label class="form-label">Duration (minutes)</label>
                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $lesson->duration_minutes) }}" min="0"
                               class="form-input" />
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_free_preview" id="is_free" value="1"
                               {{ old('is_free_preview', $lesson->is_free_preview) ? 'checked' : '' }}
                               style="width:16px;height:16px;accent-color:#930056;" />
                        <label for="is_free" class="text-sm" style="color:rgba(255,255,255,0.6);cursor:pointer;">
                            Free Preview
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn text-white" style="background:#120A43;width:100%;justify-content:center;padding:0.85rem;border:1px solid rgba(255,255,255,0.05);box-shadow:0 4px 14px rgba(0,0,0,0.3);">
                💾 Save Changes
            </button>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
(function () {
    const input = document.getElementById('video-url-input');
    const preview = document.getElementById('video-preview');
    const frame = document.getElementById('video-frame');

    function parseVideoUrl(url) {
        if (!url) return null;
        let m;
        if (m = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/))
            return { provider: 'youtube', id: m[1] };
        if (m = url.match(/vimeo\.com\/(\d+)/))
            return { provider: 'vimeo', id: m[1] };
        if (m = url.match(/dailymotion\.com\/video\/([a-zA-Z0-9]+)/))
            return { provider: 'dailymotion', id: m[1] };
        return null;
    }

    let timer;
    let player = new Plyr('#player', {
        controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'fullscreen'],
        settings: ['quality', 'speed'],
        youtube: { noCookie: true, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1, controls: 0, disablekb: 1 },
        vimeo: { byline: false, portrait: false, title: false, transparent: false }
    });

    input.addEventListener('input', () => {
        clearTimeout(timer);
        timer = setTimeout(() => {
            const url = input.value.trim();
            const videoData = parseVideoUrl(url);
            
            if (videoData) { 
                preview.style.display = 'block'; 
                
                if (videoData.provider === 'dailymotion') {
                    // Plyr does not support Dailymotion, so we fallback to a standard iframe
                    if (player) { player.destroy(); player = null; }
                    preview.innerHTML = `<iframe src="https://www.dailymotion.com/embed/video/${videoData.id}?ui-logo=0&ui-start-screen-info=0&sharing-enable=0" allow="autoplay;fullscreen" style="width:100%;height:100%;border:none;border-radius:12px;"></iframe>`;
                } else {
                    // Ensure player container is intact
                    if (!player) {
                        preview.innerHTML = `<div id="player"></div>`;
                        player = new Plyr('#player', {
                            controls: ['play-large', 'play', 'progress', 'current-time', 'mute', 'volume', 'settings', 'fullscreen'],
                            settings: ['quality', 'speed'],
                            youtube: { noCookie: true, rel: 0, showinfo: 0, iv_load_policy: 3, modestbranding: 1, controls: 0, disablekb: 1 },
                            vimeo: { byline: false, portrait: false, title: false, transparent: false }
                        });
                    }
                    // Use Plyr's source setter which natively handles poster thumbnails and hiding UI
                    player.source = {
                        type: 'video',
                        sources: [
                            {
                                src: videoData.id,
                                provider: videoData.provider,
                            },
                        ],
                    };
                }
            }
            else { 
                // Fallback or hide
                preview.style.display = 'none'; 
                preview.innerHTML = `<div id="player"></div>`;
                if (player) { player.destroy(); player = null; }
            }
        }, 600);
    });
})();
</script>
@endpush
