@extends('layouts.admin')

@section('title', $course->title . ' — Course Builder')
@section('topbar-title', 'Course Builder')

@section('content')

{{-- Welcome/Header Banner --}}
<div class="mb-8 rounded-2xl relative overflow-hidden animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute -top-24 right-10 w-64 h-64 bg-[#ff80c8] rounded-full mix-blend-screen filter blur-[90px] opacity-20 pointer-events-none"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10 w-full flex-wrap">
        <div>
            <h1 class="text-3xl font-black text-white mb-2 tracking-tight">{{ $course->title }}</h1>
            <p class="text-white/60 text-sm md:text-base max-w-xl leading-relaxed">
                Manage lessons and quizzes for this course.
            </p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.courses.edit', $course) }}" class="btn text-white transition-opacity hover:opacity-90 px-4"
               style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
               ✏️ Edit Course
            </a>
            <a href="{{ route('admin.courses.index') }}" class="btn text-white transition-opacity hover:opacity-90 px-4"
               style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1);">
               ← All Courses
            </a>
        </div>
    </div>
</div>

{{-- Flash messages --}}
@if(session('success'))
    <div style="background:rgba(34,197,94,0.15);border:1px solid rgba(34,197,94,0.3);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#4ade80;font-size:0.875rem;">
        ✅ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background:rgba(239,68,68,0.15);border:1px solid rgba(239,68,68,0.3);border-radius:12px;padding:1rem 1.25rem;margin-bottom:1.5rem;color:#f87171;font-size:0.875rem;">
        ❌ {{ session('error') }}
    </div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

    {{-- ── Curriculum Builder ────────────────────────────────────────────── --}}
    <div class="xl:col-span-2">
        <div class="card">
            <div class="card-inner">

                {{-- Header --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.75rem;">
                    <h2 class="font-bold text-white" style="font-size:1.1rem;">📚 Curriculum</h2>
                    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                        <a href="{{ route('admin.courses.lessons.create', $course) }}"
                           class="btn"
                           style="background:linear-gradient(135deg,#7c3aed,#4f46e5);color:#fff;font-size:0.8rem;padding:0.45rem 1rem;">
                            + Add Lesson
                        </a>
                        <a href="{{ route('admin.courses.quizzes.create', $course) }}"
                           class="btn"
                           style="background:linear-gradient(135deg,#930056,#c2185b);color:#fff;font-size:0.8rem;padding:0.45rem 1rem;">
                            + Add Standalone Quiz
                        </a>
                    </div>
                </div>

                {{-- Legend --}}
                <div style="display:flex;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
                    <span style="display:flex;align-items:center;gap:0.35rem;font-size:0.72rem;color:rgba(255,255,255,0.4);">
                        <span style="width:10px;height:10px;border-radius:3px;background:linear-gradient(135deg,#7c3aed,#4f46e5);display:inline-block;"></span> Lesson
                    </span>
                    <span style="display:flex;align-items:center;gap:0.35rem;font-size:0.72rem;color:rgba(255,255,255,0.4);">
                        <span style="width:10px;height:10px;border-radius:3px;background:linear-gradient(135deg,#930056,#c2185b);display:inline-block;"></span> Standalone Quiz
                    </span>
                    <span style="display:flex;align-items:center;gap:0.35rem;font-size:0.72rem;color:rgba(255,255,255,0.4);">
                        <span style="width:10px;height:10px;border-radius:3px;background:linear-gradient(135deg,#0ea5e9,#0284c7);display:inline-block;"></span> Lesson Quiz
                    </span>
                </div>

                @if($curriculum->isEmpty())
                    <div style="text-align:center;padding:3rem 1rem;color:rgba(255,255,255,0.3);">
                        <div style="font-size:3rem;margin-bottom:1rem;">📋</div>
                        <p style="font-size:0.95rem;">No content yet. Start by adding a lesson!</p>
                    </div>
                @else
                    {{-- ── Lesson number counter for display ── --}}
                    @php $lessonNum = 0; @endphp

                    <div id="curriculum-list" style="display:flex;flex-direction:column;gap:0.75rem;">
                        @foreach($curriculum as $entry)

                            {{-- ══ LESSON ROW ══ --}}
                            @if($entry['type'] === 'lesson')
                                @php
                                    $lesson = $entry['item'];
                                    $lessonNum++;
                                    $hasQuiz = $lesson->quiz !== null;
                                @endphp

                                <div class="curriculum-item" data-id="{{ $lesson->id }}" data-type="lesson"
                                     style="background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);border-radius:12px;padding:0;overflow:hidden;">

                                    {{-- Main lesson row --}}
                                    <div style="display:flex;align-items:center;gap:1rem;padding:0.9rem 1.25rem;cursor:grab;">

                                        {{-- Drag handle --}}
                                        <div style="color:rgba(255,255,255,0.2);font-size:1.1rem;flex-shrink:0;">⋮⋮</div>

                                        {{-- Lesson badge --}}
                                        <div style="background:linear-gradient(135deg,#7c3aed,#4f46e5);border-radius:8px;padding:0.35rem 0.6rem;font-size:0.7rem;color:#fff;font-weight:600;flex-shrink:0;min-width:62px;text-align:center;">
                                            Lesson {{ $lessonNum }}
                                        </div>

                                        {{-- Info --}}
                                        <div style="flex:1;min-width:0;">
                                            <div style="color:#fff;font-weight:600;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                                {{ $lesson->title }}
                                            </div>
                                            <div style="display:flex;gap:0.5rem;margin-top:0.3rem;flex-wrap:wrap;align-items:center;">
                                                @if($lesson->video_url)
                                                    <span style="font-size:0.7rem;color:rgba(255,255,255,0.4);">🎬 Video</span>
                                                @endif
                                                @if($lesson->duration_minutes)
                                                    <span style="font-size:0.7rem;color:rgba(255,255,255,0.4);">⏱ {{ $lesson->duration_minutes }}min</span>
                                                @endif
                                                @if($lesson->is_free_preview)
                                                    <span style="font-size:0.7rem;background:rgba(34,197,94,0.15);color:#4ade80;border-radius:4px;padding:0 0.4rem;">Free Preview</span>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Lesson actions --}}
                                        <div style="display:flex;gap:0.4rem;flex-shrink:0;flex-wrap:wrap;align-items:center;">
                                            <a href="{{ route('admin.courses.lessons.edit', [$course, $lesson]) }}"
                                               style="color:#60a5fa;font-size:0.78rem;text-decoration:none;padding:0.25rem 0.55rem;border:1px solid rgba(96,165,250,0.3);border-radius:6px;">✏️ Edit</a>
                                            <form method="POST" action="{{ route('admin.courses.lessons.destroy', [$course, $lesson]) }}"
                                                  onsubmit="return confirm('Delete lesson \'{{ addslashes($lesson->title) }}\'? This will also remove its quiz.')" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" style="color:#f87171;font-size:0.78rem;background:none;border:1px solid rgba(248,113,113,0.3);border-radius:6px;padding:0.25rem 0.55rem;cursor:pointer;">🗑 Delete</button>
                                            </form>
                                        </div>
                                    </div>

                                    {{-- ── Lesson Quiz sub-row ── --}}
                                    @if($hasQuiz)
                                        @php $lq = $lesson->quiz; @endphp
                                        <div style="background:rgba(14,165,233,0.08);border-top:1px solid rgba(14,165,233,0.2);padding:0.65rem 1.25rem;display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
                                            <div style="display:flex;align-items:center;gap:0.5rem;flex:1;min-width:0;">
                                                <span style="background:linear-gradient(135deg,#0ea5e9,#0284c7);border-radius:6px;padding:0.25rem 0.5rem;font-size:0.68rem;color:#fff;font-weight:600;flex-shrink:0;">📎 Lesson Quiz</span>
                                                <span style="color:rgba(255,255,255,0.75);font-size:0.8rem;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $lq->title }}</span>
                                                <span style="font-size:0.7rem;color:rgba(255,255,255,0.35);">·</span>
                                                <span style="font-size:0.7rem;color:rgba(255,255,255,0.4);">{{ $lq->questions->count() }} Q</span>
                                                <span style="font-size:0.7rem;color:rgba(255,255,255,0.35);">·</span>
                                                <span style="font-size:0.7rem;color:rgba(255,255,255,0.4);">Pass {{ $lq->passing_score }}%</span>
                                            </div>
                                            <div style="display:flex;gap:0.4rem;flex-shrink:0;">
                                                <a href="{{ route('admin.courses.quizzes.edit', [$course, $lq]) }}"
                                                   style="color:#38bdf8;font-size:0.75rem;text-decoration:none;padding:0.2rem 0.5rem;border:1px solid rgba(56,189,248,0.3);border-radius:6px;">✏️ Edit Quiz</a>
                                                <form method="POST" action="{{ route('admin.courses.quizzes.destroy', [$course, $lq]) }}"
                                                      onsubmit="return confirm('Remove quiz from this lesson?')" style="display:inline;">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" style="color:#f87171;font-size:0.75rem;background:none;border:1px solid rgba(248,113,113,0.3);border-radius:6px;padding:0.2rem 0.5rem;cursor:pointer;">🗑</button>
                                                </form>
                                            </div>
                                        </div>
                                    @else
                                        {{-- No quiz yet — quick-add shortcut --}}
                                        <div style="border-top:1px dashed rgba(255,255,255,0.06);padding:0.55rem 1.25rem;display:flex;align-items:center;justify-content:flex-end;">
                                            <a href="{{ route('admin.courses.quizzes.create', $course) }}?lesson_id={{ $lesson->id }}&quiz_type=lesson_quiz"
                                               style="color:#a78bfa;font-size:0.75rem;text-decoration:none;padding:0.22rem 0.65rem;border:1px dashed rgba(167,139,250,0.35);border-radius:6px;transition:border-color 0.2s;"
                                               onmouseover="this.style.borderColor='rgba(167,139,250,0.7)'"
                                               onmouseout="this.style.borderColor='rgba(167,139,250,0.35)'">
                                                📎 Add Lesson Quiz
                                            </a>
                                        </div>
                                    @endif

                                </div>

                            {{-- ══ STANDALONE QUIZ ROW ══ --}}
                            @else
                                @php $quiz = $entry['item']; @endphp
                                <div class="curriculum-item" data-id="{{ $quiz->id }}" data-type="quiz"
                                     style="background:rgba(147,0,86,0.07);border:1px solid rgba(147,0,86,0.2);border-radius:12px;padding:1rem 1.25rem;display:flex;align-items:center;gap:1rem;">

                                    <div style="color:rgba(255,255,255,0.2);font-size:1.1rem;flex-shrink:0;">⋮⋮</div>

                                    <div style="background:linear-gradient(135deg,#930056,#c2185b);border-radius:8px;padding:0.35rem 0.6rem;font-size:0.7rem;color:#fff;font-weight:600;flex-shrink:0;min-width:62px;text-align:center;">
                                        📋 Quiz
                                    </div>

                                    <div style="flex:1;min-width:0;">
                                        <div style="color:#fff;font-weight:600;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                            {{ $quiz->title }}
                                        </div>
                                        <div style="display:flex;gap:0.5rem;margin-top:0.3rem;flex-wrap:wrap;">
                                            <span style="font-size:0.7rem;color:rgba(255,255,255,0.4);">🎯 Pass: {{ $quiz->passing_score }}%</span>
                                            <span style="font-size:0.7rem;color:rgba(255,255,255,0.4);">❓ {{ $quiz->questions_count }} questions</span>
                                            @if($quiz->time_limit_minutes)
                                                <span style="font-size:0.7rem;color:rgba(255,255,255,0.4);">⏱ {{ $quiz->time_limit_minutes }}min</span>
                                            @endif
                                            <span style="font-size:0.7rem;color:rgba(255,255,255,0.3);">pos: {{ $quiz->order }}</span>
                                        </div>
                                    </div>

                                    <div style="display:flex;gap:0.4rem;flex-shrink:0;">
                                        <a href="{{ route('admin.courses.quizzes.edit', [$course, $quiz]) }}"
                                           style="color:#60a5fa;font-size:0.78rem;text-decoration:none;padding:0.25rem 0.55rem;border:1px solid rgba(96,165,250,0.3);border-radius:6px;">✏️ Edit</a>
                                        <form method="POST" action="{{ route('admin.courses.quizzes.destroy', [$course, $quiz]) }}"
                                              onsubmit="return confirm('Delete this quiz?')" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" style="color:#f87171;font-size:0.78rem;background:none;border:1px solid rgba(248,113,113,0.3);border-radius:6px;padding:0.25rem 0.55rem;cursor:pointer;">🗑 Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endif

                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>

    {{-- ── Sidebar ─────────────────────────────────────────────────────────── --}}
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        {{-- Course Info --}}
        <div class="card">
            <div class="card-inner">
                <h3 class="font-bold text-white mb-3" style="font-size:1rem;">📋 Course Info</h3>
                @if($course->thumbnail)
                    <img src="{{ asset('storage/'.$course->thumbnail) }}" alt=""
                         style="width:100%;height:140px;object-fit:cover;border-radius:10px;margin-bottom:1rem;" />
                @endif
                <div style="display:flex;flex-direction:column;gap:0.6rem;">
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                        <span style="color:rgba(255,255,255,0.4);">Status</span>
                        <span style="color:{{ $course->status==='published'?'#4ade80':'#fbbf24' }};font-weight:600;text-transform:capitalize;">{{ $course->status }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                        <span style="color:rgba(255,255,255,0.4);">Level</span>
                        <span style="color:#fff;text-transform:capitalize;">{{ $course->level }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                        <span style="color:rgba(255,255,255,0.4);">Certificate</span>
                        <span style="color:{{ $course->has_certificate ? '#4ade80' : 'rgba(255,255,255,0.5)' }};">{{ $course->has_certificate ? '✅ Yes' : 'No' }}</span>
                    </div>
                    @if($course->category)
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                        <span style="color:rgba(255,255,255,0.4);">Category</span>
                        <span style="color:#fff;">{{ $course->category->name }}</span>
                    </div>
                    @endif
                    @if($course->language)
                    <div style="display:flex;justify-content:space-between;font-size:0.82rem;">
                        <span style="color:rgba(255,255,255,0.4);">Language</span>
                        <span style="color:#fff;">{{ $course->language }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Curriculum Stats --}}
        <div class="card">
            <div class="card-inner">
                <h3 class="font-bold text-white mb-3" style="font-size:1rem;">📊 Curriculum Stats</h3>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.75rem;">
                    <div style="background:rgba(124,58,237,0.1);border:1px solid rgba(124,58,237,0.2);border-radius:10px;padding:0.75rem;text-align:center;">
                        <div style="font-size:1.6rem;font-weight:700;color:#a78bfa;">{{ $stats['lessons'] }}</div>
                        <div style="font-size:0.72rem;color:rgba(255,255,255,0.4);margin-top:0.15rem;">Lessons</div>
                    </div>
                    <div style="background:rgba(14,165,233,0.1);border:1px solid rgba(14,165,233,0.2);border-radius:10px;padding:0.75rem;text-align:center;">
                        <div style="font-size:1.6rem;font-weight:700;color:#38bdf8;">{{ $stats['lesson_quizzes'] }}</div>
                        <div style="font-size:0.72rem;color:rgba(255,255,255,0.4);margin-top:0.15rem;">Lesson Quizzes</div>
                    </div>
                    <div style="background:rgba(147,0,86,0.1);border:1px solid rgba(147,0,86,0.2);border-radius:10px;padding:0.75rem;text-align:center;">
                        <div style="font-size:1.6rem;font-weight:700;color:#f472b6;">{{ $stats['standalone_quizzes'] }}</div>
                        <div style="font-size:0.72rem;color:rgba(255,255,255,0.4);margin-top:0.15rem;">Standalone Quizzes</div>
                    </div>
                    <div style="background:rgba(245,158,11,0.1);border:1px solid rgba(245,158,11,0.2);border-radius:10px;padding:0.75rem;text-align:center;">
                        <div style="font-size:1.6rem;font-weight:700;color:#fbbf24;">{{ $stats['total_questions'] }}</div>
                        <div style="font-size:0.72rem;color:rgba(255,255,255,0.4);margin-top:0.15rem;">Total Questions</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card">
            <div class="card-inner">
                <h3 class="font-bold text-white mb-3" style="font-size:1rem;">⚡ Quick Actions</h3>
                <div style="display:flex;flex-direction:column;gap:0.6rem;">
                    <a href="{{ route('admin.courses.lessons.create', $course) }}"
                       class="btn" style="background:linear-gradient(135deg,#7c3aed,#4f46e5);color:#fff;justify-content:center;font-size:0.85rem;">
                        📖 Add Lesson
                    </a>
                    <a href="{{ route('admin.courses.quizzes.create', $course) }}"
                       class="btn" style="background:linear-gradient(135deg,#930056,#c2185b);color:#fff;justify-content:center;font-size:0.85rem;">
                        📋 Add Standalone Quiz
                    </a>
                    <a href="{{ route('admin.courses.certificate.edit', $course) }}"
                       class="btn" style="background:linear-gradient(135deg,#d97706,#b45309);color:#fff;justify-content:center;font-size:0.85rem;">
                        🏆 Certificate Editor
                    </a>
                    <a href="{{ route('admin.courses.edit', $course) }}"
                       class="btn btn-secondary" style="justify-content:center;font-size:0.85rem;">
                        ✏️ Edit Course Details
                    </a>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@push('scripts')
<script>
// Drag-to-reorder (HTML5 Drag API — lessons + standalone quizzes)
(function () {
    const list = document.getElementById('curriculum-list');
    if (!list) return;
    let dragged = null;

    list.querySelectorAll('.curriculum-item').forEach(item => {
        item.setAttribute('draggable', 'true');
        item.style.cursor = 'grab';

        item.addEventListener('dragstart', () => {
            dragged = item;
            item.style.opacity = '0.45';
        });
        item.addEventListener('dragend', () => {
            dragged = null;
            item.style.opacity = '1';
        });
        item.addEventListener('dragover', e => {
            e.preventDefault();
            item.style.outline = '2px dashed rgba(255,255,255,0.25)';
        });
        item.addEventListener('dragleave', () => {
            item.style.outline = 'none';
        });
        item.addEventListener('drop', e => {
            e.preventDefault();
            item.style.outline = 'none';
            if (dragged && dragged !== item) {
                const items = [...list.querySelectorAll('.curriculum-item')];
                const dragIdx = items.indexOf(dragged);
                const dropIdx = items.indexOf(item);
                if (dragIdx < dropIdx) item.after(dragged);
                else item.before(dragged);
                saveOrder();
            }
        });
    });

    function saveOrder() {
        const items = [...list.querySelectorAll('.curriculum-item')];
        const order = items.map((el, idx) => ({
            type: el.dataset.type,
            id:   el.dataset.id,
            position: idx + 1
        }));
        fetch('{{ route('admin.courses.curriculum.reorder', $course) }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ order: order })
        });
    }
})();
</script>
@endpush
