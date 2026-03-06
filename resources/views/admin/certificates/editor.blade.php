@extends('layouts.admin')

@section('title', 'Certificate Editor — ' . $course->title)
@section('topbar-title', 'Certificate Editor')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">🏆 Certificate Editor</h1>
        <p class="page-subtitle">{{ $course->title }}</p>
    </div>
    <a href="{{ route('admin.courses.show', $course) }}" class="btn btn-secondary">← Back to Course</a>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#4ade80;font-size:0.875rem;">
        ✅ {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div style="background:rgba(248,113,113,0.1);border:1px solid rgba(248,113,113,0.3);border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;">
        <p style="color:#f87171;font-weight:600;font-size:0.85rem;margin-bottom:0.5rem;">⚠ Please fix the following errors:</p>
        <ul style="list-style:disc;padding-left:1.5rem;color:rgba(255,255,255,0.6);font-size:0.82rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.courses.certificate.update', $course) }}" enctype="multipart/form-data" id="cert-form">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- ═══ Main Area: Visual Editor ═══ --}}
        <div class="xl:col-span-2">
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold text-white mb-4">📐 Visual Editor</h2>
                    <p style="color:rgba(255,255,255,0.4);font-size:0.82rem;margin-bottom:1rem;">
                        Upload a certificate background, then <strong style="color:#a78bfa;">click or drag</strong> on the image to position where the student's name should appear.
                    </p>

                    {{-- Certificate Canvas --}}
                    <div id="cert-canvas-wrap" style="position:relative;border:2px dashed rgba(255,255,255,0.1);border-radius:14px;overflow:hidden;background:#0a0a1a;min-height:300px;cursor:crosshair;user-select:none;">

                        {{-- Background image or placeholder --}}
                        @if($course->certificate_bg_image)
                            <img id="cert-bg" src="{{ asset('storage/' . $course->certificate_bg_image) }}"
                                 alt="Certificate Background"
                                 style="width:100%;display:block;pointer-events:none;" />
                        @else
                            <div id="cert-placeholder" style="display:flex;flex-direction:column;align-items:center;justify-content:center;padding:4rem 2rem;color:rgba(255,255,255,0.2);">
                                <div style="font-size:4rem;margin-bottom:1rem;">📄</div>
                                <p style="font-size:0.95rem;">Upload a certificate background to start</p>
                                <p style="font-size:0.78rem;margin-top:0.5rem;">Recommended: landscape, 1920×1360 or similar</p>
                            </div>
                        @endif

                        {{-- Draggable name label --}}
                        <div id="name-label"
                             style="position:absolute;
                                    left:{{ $course->certificate_name_x ?? 50 }}%;
                                    top:{{ $course->certificate_name_y ?? 50 }}%;
                                    transform:translate(-50%, -50%);
                                    font-size:{{ $course->certificate_name_font_size ?? 48 }}px;
                                    color:{{ $course->certificate_name_color ?? '#1a1a2e' }};
                                    font-family:'{{ $course->certificate_name_font ?? 'Great Vibes' }}', cursive;
                                    pointer-events:none;
                                    white-space:nowrap;
                                    text-shadow: 0 1px 4px rgba(0,0,0,0.15);
                                    z-index:10;
                                    {{ $course->certificate_bg_image ? '' : 'display:none;' }}"
                        >
                            John Doe
                        </div>

                        {{-- Crosshair indicator --}}
                        <div id="crosshair" style="position:absolute;z-index:5;pointer-events:none;display:none;">
                            <div style="position:absolute;width:1px;height:20px;background:rgba(167,139,250,0.6);left:50%;transform:translateX(-50%);top:-10px;"></div>
                            <div style="position:absolute;width:20px;height:1px;background:rgba(167,139,250,0.6);top:50%;transform:translateY(-50%);left:-10px;"></div>
                        </div>
                    </div>

                    {{-- Position readout --}}
                    <div style="display:flex;gap:1rem;margin-top:0.75rem;align-items:center;flex-wrap:wrap;">
                        <span style="font-size:0.78rem;color:rgba(255,255,255,0.4);">
                            📍 Position: <strong id="pos-readout" style="color:#a78bfa;">{{ $course->certificate_name_x ?? 50 }}%, {{ $course->certificate_name_y ?? 50 }}%</strong>
                        </span>
                        <span style="font-size:0.72rem;color:rgba(255,255,255,0.25);">Click on the certificate to reposition.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ Sidebar: Settings ═══ --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            {{-- Upload --}}
            <div class="card">
                <div class="card-inner">
                    <h3 class="font-bold text-white mb-3" style="font-size:1rem;">🖼️ Certificate Background</h3>

                    @if($course->certificate_bg_image)
                        <div style="margin-bottom:0.75rem;padding:0.5rem;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);border-radius:8px;display:flex;align-items:center;gap:0.5rem;">
                            <span style="color:#4ade80;font-size:0.82rem;">✅ Image uploaded</span>
                        </div>
                    @endif

                    <label style="display:flex;flex-direction:column;align-items:center;gap:0.75rem;padding:1.25rem;border:2px dashed rgba(167,139,250,0.3);border-radius:12px;cursor:pointer;transition:border-color 0.2s;"
                           onmouseover="this.style.borderColor='rgba(167,139,250,0.6)'"
                           onmouseout="this.style.borderColor='rgba(167,139,250,0.3)'">
                        <span style="font-size:2rem;">📤</span>
                        <span style="color:rgba(255,255,255,0.5);font-size:0.82rem;text-align:center;">Click to upload background<br><span style="font-size:0.72rem;color:rgba(255,255,255,0.3);">PNG, JPG — max 5 MB</span></span>
                        <input type="file" name="certificate_bg_image" accept="image/*" style="display:none;" onchange="previewBg(this)" />
                    </label>

                    @if($course->certificate_bg_image)
                        <div style="margin-top:0.5rem;">
                            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;">
                                <input type="checkbox" name="remove_bg" value="1" style="accent-color:#f87171;width:14px;height:14px;" />
                                <span style="font-size:0.78rem;color:#f87171;">Remove current background</span>
                            </label>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Name Styling --}}
            <div class="card">
                <div class="card-inner">
                    <h3 class="font-bold text-white mb-3" style="font-size:1rem;">✍️ Name Styling</h3>

                    <div class="mb-3">
                        <label class="form-label">Font Family</label>
                        <select name="certificate_name_font" id="font-select" class="form-select" onchange="updatePreview()">
                            <option value="Great Vibes" {{ ($course->certificate_name_font ?? 'Great Vibes') === 'Great Vibes' ? 'selected' : '' }}>Great Vibes (Elegant Script)</option>
                            <option value="Playfair Display" {{ ($course->certificate_name_font ?? '') === 'Playfair Display' ? 'selected' : '' }}>Playfair Display (Serif)</option>
                            <option value="Cinzel" {{ ($course->certificate_name_font ?? '') === 'Cinzel' ? 'selected' : '' }}>Cinzel (Classic)</option>
                            <option value="Cormorant Garamond" {{ ($course->certificate_name_font ?? '') === 'Cormorant Garamond' ? 'selected' : '' }}>Cormorant Garamond (Formal)</option>
                            <option value="Dancing Script" {{ ($course->certificate_name_font ?? '') === 'Dancing Script' ? 'selected' : '' }}>Dancing Script (Casual)</option>
                            <option value="Abril Fatface" {{ ($course->certificate_name_font ?? '') === 'Abril Fatface' ? 'selected' : '' }}>Abril Fatface (Bold)</option>
                            <option value="Lora" {{ ($course->certificate_name_font ?? '') === 'Lora' ? 'selected' : '' }}>Lora (Modern Serif)</option>
                            <option value="Roboto" {{ ($course->certificate_name_font ?? '') === 'Roboto' ? 'selected' : '' }}>Roboto (Sans-Serif)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Font Size ({{ $course->certificate_name_font_size ?? 48 }}px)</label>
                        <input type="range" name="certificate_name_font_size" id="font-size-slider"
                               min="16" max="120" step="1"
                               value="{{ $course->certificate_name_font_size ?? 48 }}"
                               oninput="updatePreview()"
                               style="width:100%;accent-color:#a78bfa;" />
                        <div style="display:flex;justify-content:space-between;font-size:0.7rem;color:rgba(255,255,255,0.3);">
                            <span>16px</span><span>120px</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Text Color</label>
                        <div style="display:flex;align-items:center;gap:0.75rem;">
                            <input type="color" name="certificate_name_color" id="color-picker"
                                   value="{{ $course->certificate_name_color ?? '#1a1a2e' }}"
                                   onchange="updatePreview()"
                                   style="width:42px;height:42px;border:none;border-radius:8px;cursor:pointer;background:transparent;" />
                            <span id="color-hex" style="font-size:0.82rem;color:rgba(255,255,255,0.5);font-family:monospace;">{{ $course->certificate_name_color ?? '#1a1a2e' }}</span>
                        </div>
                    </div>

                    {{-- Quick color presets --}}
                    <div style="display:flex;gap:0.4rem;flex-wrap:wrap;margin-top:0.25rem;">
                        @foreach(['#1a1a2e' => 'Dark', '#0d1b2a' => 'Navy', '#6b21a8' => 'Purple', '#92400e' => 'Gold', '#991b1b' => 'Maroon', '#ffffff' => 'White'] as $hex => $label)
                            <button type="button"
                                    onclick="document.getElementById('color-picker').value='{{ $hex }}';updatePreview()"
                                    style="width:28px;height:28px;border-radius:6px;border:2px solid rgba(255,255,255,0.1);background:{{ $hex }};cursor:pointer;transition:transform 0.15s;"
                                    title="{{ $label }}"
                                    onmouseover="this.style.transform='scale(1.15)'"
                                    onmouseout="this.style.transform='scale(1)'"></button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Hidden fields for position --}}
            <input type="hidden" name="certificate_name_x" id="name-x" value="{{ $course->certificate_name_x ?? 50 }}" />
            <input type="hidden" name="certificate_name_y" id="name-y" value="{{ $course->certificate_name_y ?? 50 }}" />

            {{-- Save --}}
            <button type="submit" class="btn text-white"
                    style="background:linear-gradient(135deg,#7c3aed,#4f46e5);width:100%;justify-content:center;padding:0.85rem;font-size:0.95rem;border:none;box-shadow:0 4px 14px rgba(124,58,237,0.3);">
                💾 Save Certificate Template
            </button>
        </div>

    </div>
</form>

@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:wght@700&family=Cinzel:wght@700&family=Cormorant+Garamond:wght@600&family=Dancing+Script:wght@700&family=Abril+Fatface&family=Lora:wght@700&family=Roboto:wght@700&display=swap" rel="stylesheet">
@endpush

@push('scripts')
<script>
(function() {
    const canvas   = document.getElementById('cert-canvas-wrap');
    const label    = document.getElementById('name-label');
    const inputX   = document.getElementById('name-x');
    const inputY   = document.getElementById('name-y');
    const readout  = document.getElementById('pos-readout');
    const crosshair= document.getElementById('crosshair');

    // Click / drag to reposition
    let isDragging = false;

    function setPosition(e) {
        const rect = canvas.getBoundingClientRect();
        const bg = document.getElementById('cert-bg');
        if (!bg) return;

        const x = ((e.clientX - rect.left) / rect.width) * 100;
        const y = ((e.clientY - rect.top) / rect.height) * 100;

        const clampedX = Math.max(0, Math.min(100, x)).toFixed(1);
        const clampedY = Math.max(0, Math.min(100, y)).toFixed(1);

        label.style.left = clampedX + '%';
        label.style.top  = clampedY + '%';
        label.style.display = 'block';

        inputX.value = Math.round(clampedX);
        inputY.value = Math.round(clampedY);

        readout.textContent = Math.round(clampedX) + '%, ' + Math.round(clampedY) + '%';
    }

    canvas.addEventListener('mousedown', function(e) {
        isDragging = true;
        setPosition(e);
    });
    canvas.addEventListener('mousemove', function(e) {
        // Show crosshair
        const rect = canvas.getBoundingClientRect();
        crosshair.style.left = (e.clientX - rect.left) + 'px';
        crosshair.style.top  = (e.clientY - rect.top)  + 'px';
        crosshair.style.display = 'block';

        if (isDragging) setPosition(e);
    });
    canvas.addEventListener('mouseup', function() { isDragging = false; });
    canvas.addEventListener('mouseleave', function() {
        isDragging = false;
        crosshair.style.display = 'none';
    });

    // Touch support
    canvas.addEventListener('touchstart', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        setPosition(touch);
    }, { passive: false });
    canvas.addEventListener('touchmove', function(e) {
        e.preventDefault();
        const touch = e.touches[0];
        setPosition(touch);
    }, { passive: false });
})();

function updatePreview() {
    const label    = document.getElementById('name-label');
    const font     = document.getElementById('font-select').value;
    const size     = document.getElementById('font-size-slider').value;
    const color    = document.getElementById('color-picker').value;
    const sizeLabel= document.querySelector('label[for="font-size-slider"], label.form-label');

    label.style.fontFamily = "'" + font + "', cursive";
    label.style.fontSize   = size + 'px';
    label.style.color      = color;

    document.getElementById('color-hex').textContent = color;

    // Update the label next to the slider
    const sliderLabel = document.getElementById('font-size-slider').closest('.mb-3').querySelector('.form-label');
    if (sliderLabel) sliderLabel.textContent = 'Font Size (' + size + 'px)';
}

function previewBg(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function(e) {
        const canvas = document.getElementById('cert-canvas-wrap');
        const placeholder = document.getElementById('cert-placeholder');
        let bg = document.getElementById('cert-bg');

        // Remove placeholder if exists
        if (placeholder) placeholder.style.display = 'none';

        if (!bg) {
            bg = document.createElement('img');
            bg.id = 'cert-bg';
            bg.style.width = '100%';
            bg.style.display = 'block';
            bg.style.pointerEvents = 'none';
            canvas.prepend(bg);
        }
        bg.src = e.target.result;

        // Show name label
        document.getElementById('name-label').style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
