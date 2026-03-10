@extends('layouts.admin')

@section('title', 'Add Quiz')
@section('topbar-title', 'Add Quiz')

@section('content')

{{-- Welcome/Header Banner --}}
<div class="mb-8 rounded-2xl relative overflow-hidden animate-fade-up"
     style="background: linear-gradient(135deg, #1A1262 0%, #0F043D 100%); border: 1px solid rgba(255,255,255,0.05);">
    <div class="absolute -top-24 right-10 w-64 h-64 bg-[#ff80c8] rounded-full mix-blend-screen filter blur-[90px] opacity-20 pointer-events-none"></div>

    <div class="relative p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 z-10 w-full flex-wrap">
        <div>
            <h1 class="text-3xl font-black mb-2 tracking-tight text-white">Add Quiz</h1>
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

<form method="POST" action="{{ route('admin.courses.quizzes.store', $course) }}" id="quiz-form">
    @csrf

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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Main --}}
        <div class="xl:col-span-2 flex flex-col gap-6">

            {{-- Quiz Settings --}}
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold mb-4">Quiz Details</h2>

                    <div class="mb-4">
                        <label class="form-label">Quiz Title *</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="form-input" placeholder="e.g. HTML Basics Quiz" />
                        @error('title')<p class="text-xs mt-1" style="color:#ff80c8;">{{ $message }}</p>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-textarea" rows="2"
                                  placeholder="Optional — shown to students before they start.">{{ old('description') }}</textarea>
                    </div>

                    {{-- Quiz Type --}}
                    <div class="mb-4">
                        <label class="form-label">Quiz Type *</label>
                        <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;padding:0.75rem 1rem;border:1px solid rgba(255,255,255,0.1);border-radius:10px;flex:1;min-width:180px;transition:border-color 0.2s;" id="type-lesson-box">
                                <input type="radio" name="quiz_type" value="lesson_quiz" id="type-lesson"
                                       {{ old('quiz_type', $defaultQuizType)==='lesson_quiz'?'checked':'' }}
                                       style="accent-color:#930056;" onchange="toggleLessonPicker()" />
                                <div>
                                    <div style="color:#fff;font-size:0.85rem;font-weight:600;">📎 Lesson Quiz</div>
                                    <div style="color:rgba(255,255,255,0.4);font-size:0.75rem;">Attached to a specific lesson — student must pass to continue.</div>
                                </div>
                            </label>
                            <label style="display:flex;align-items:center;gap:0.5rem;cursor:pointer;padding:0.75rem 1rem;border:1px solid rgba(255,255,255,0.1);border-radius:10px;flex:1;min-width:180px;transition:border-color 0.2s;" id="type-between-box">
                                <input type="radio" name="quiz_type" value="between_lessons" id="type-between"
                                       {{ old('quiz_type', $defaultQuizType)==='between_lessons'?'checked':'' }}
                                       style="accent-color:#930056;" onchange="toggleLessonPicker()" />
                                <div>
                                    <div style="color:#fff;font-size:0.85rem;font-weight:600;">📋 Standalone Quiz</div>
                                    <div style="color:rgba(255,255,255,0.4);font-size:0.75rem;">Placed between lessons at a chosen position in the curriculum.</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Lesson picker (only for lesson_quiz) --}}
                    <div id="lesson-picker" class="mb-4" style="display:none;">
                        <label class="form-label">Attach to Lesson *</label>
                        <select name="lesson_id" class="form-select">
                            <option value="">— Select a lesson —</option>
                            @foreach($lessons as $lesson)
                                <option value="{{ $lesson->id }}" {{ old('lesson_id', $defaultLessonId)==$lesson->id?'selected':'' }}>
                                    {{ $lesson->order }}. {{ $lesson->title }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs mt-1" style="color:rgba(255,255,255,0.3);">Students must pass this quiz after completing the lesson.</p>
                    </div>

                    {{-- Order (only for between_lessons) --}}
                    <div id="order-picker" class="mb-4">
                        <label class="form-label">Position in Curriculum</label>
                        <p class="text-xs mb-2" style="color:rgba(255,255,255,0.3);">Set a decimal number to place it between lessons (e.g. 1.5 = after lesson 1, before lesson 2).</p>
                        <input type="number" name="order" value="{{ old('order', 0) }}" step="0.5" min="0"
                               class="form-input" />
                    </div>
                </div>
            </div>

            {{-- Questions Builder --}}
            <div class="card">
                <div class="card-inner">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;">
                        <h2 class="font-bold ">Questions</h2>
                        <button type="button" onclick="addQuestion()"
                                style="background:linear-gradient(135deg,#7c3aed,#4f46e5);color:#fff;border:none;border-radius:8px;padding:0.45rem 1rem;font-size:0.82rem;cursor:pointer;">
                            + Add Question
                        </button>
                    </div>

                    <div id="questions-container">
                        {{-- Questions injected by JS --}}
                    </div>

                    <div id="no-questions-msg" style="text-align:center;padding:2rem;color:rgba(255,255,255,0.3);font-size:0.9rem;">
                        No questions yet. Click "Add Question" to start building your quiz.
                    </div>
                </div>
            </div>

        </div>

        {{-- Sidebar: scoring --}}
        <div class="flex flex-col gap-6">
            <div class="card">
                <div class="card-inner">
                    <h2 class="font-bold mb-4">Scoring & Limits</h2>

                    <div class="mb-3">
                        <label class="form-label">Passing Score (%)</label>
                        <input type="number" name="passing_score" value="{{ old('passing_score', 70) }}" min="0" max="100"
                               class="form-input" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Time Limit (minutes)</label>
                        <p class="text-xs mb-1" style="color:rgba(255,255,255,0.3);">Leave 0 for no limit.</p>
                        <input type="number" name="time_limit_minutes" value="{{ old('time_limit_minutes', 0) }}" min="0"
                               class="form-input" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Max Attempts</label>
                        <input type="number" name="max_attempts" value="{{ old('max_attempts', 3) }}" min="1"
                               class="form-input" />
                    </div>

                    <div class="flex items-center gap-2 mb-2">
                        <input type="checkbox" name="randomize_questions" id="rand_q" value="1"
                               style="width:16px;height:16px;accent-color:#930056;" />
                        <label for="rand_q" class="text-sm" style="color:rgba(255,255,255,0.6);cursor:pointer;">Randomize question order</label>
                    </div>

                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="show_result_immediately" id="show_r" value="1" checked
                               style="width:16px;height:16px;accent-color:#930056;" />
                        <label for="show_r" class="text-sm" style="color:rgba(255,255,255,0.6);cursor:pointer;">Show result immediately</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn text-white" style="background:#120A43;width:100%;justify-content:center;padding:0.85rem;border:1px solid rgba(255,255,255,0.05);box-shadow:0 4px 14px rgba(0,0,0,0.3);">
                📝 Save Quiz
            </button>
        </div>

    </div>
</form>

@endsection

@push('scripts')
<script>
let questionCount = 0;

function toggleLessonPicker() {
    const isLesson = document.getElementById('type-lesson').checked;
    document.getElementById('lesson-picker').style.display = isLesson ? 'block' : 'none';
    document.getElementById('order-picker').style.display  = isLesson ? 'none'  : 'block';
}
toggleLessonPicker();

function addQuestion(data = {}) {
    const i = questionCount++;
    const noMsg = document.getElementById('no-questions-msg');
    if (noMsg) noMsg.style.display = 'none';

    const types = [
        { val: 'multiple_choice',   label: 'Multiple Choice (one correct)' },
        { val: 'true_false',        label: 'True / False' },
        { val: 'short_answer',      label: 'Short Answer' },
        { val: 'fill_in_the_blank', label: 'Fill in the Blank' },
    ];

    const typeOptions = types.map(t =>
        `<option value="${t.val}" ${data.type === t.val ? 'selected' : ''}>${t.label}</option>`
    ).join('');

    const div = document.createElement('div');
    div.id = `q-${i}`;
    div.style.cssText = 'background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);border-radius:12px;padding:1.25rem;margin-bottom:1rem;';
    div.innerHTML = `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <span style="color:#a78bfa;font-size:0.82rem;font-weight:600;">Question ${i + 1}</span>
            <button type="button" onclick="removeQuestion(${i})" style="color:#f87171;background:none;border:1px solid rgba(248,113,113,0.3);border-radius:6px;padding:0.2rem 0.6rem;cursor:pointer;font-size:0.78rem;">✕ Remove</button>
        </div>

        <div class="mb-3">
            <label class="form-label" style="font-size:0.8rem;">Question Text *</label>
            <textarea name="questions[${i}][question]" class="form-textarea" rows="2"
                      placeholder="Enter the question…" required style="font-size:0.875rem;">${data.question || ''}</textarea>
        </div>

        <div style="display:flex;gap:1rem;margin-bottom:1rem;flex-wrap:wrap;">
            <div style="flex:1;min-width:180px;">
                <label class="form-label" style="font-size:0.8rem;">Type</label>
                <select name="questions[${i}][type]" class="form-select" style="font-size:0.875rem;"
                        onchange="renderAnswerSection(${i}, this.value)">
                    ${typeOptions}
                </select>
            </div>
            <div style="width:100px;">
                <label class="form-label" style="font-size:0.8rem;">Points</label>
                <input type="number" name="questions[${i}][points]" value="${data.points || 1}" min="1"
                       class="form-input" style="font-size:0.875rem;" />
            </div>
        </div>

        <div id="answers-${i}" class="mb-3"></div>

        <div>
            <label class="form-label" style="font-size:0.8rem;">Explanation (shown after answer)</label>
            <input type="text" name="questions[${i}][explanation]" value="${data.explanation || ''}"
                   class="form-input" style="font-size:0.875rem;" placeholder="Optional — why is this the correct answer?" />
        </div>
    `;

    document.getElementById('questions-container').appendChild(div);

    // Render default type answers
    const typeSelect = div.querySelector(`select[name="questions[${i}][type]"]`);
    renderAnswerSection(i, typeSelect.value, data);
}

function removeQuestion(i) {
    const el = document.getElementById(`q-${i}`);
    if (el) el.remove();
    if (!document.querySelector('[id^="q-"]')) {
        document.getElementById('no-questions-msg').style.display = 'block';
    }
}

function renderAnswerSection(i, type, data = {}) {
    const container = document.getElementById(`answers-${i}`);
    if (!container) return;
    container.innerHTML = '';

    if (type === 'multiple_choice') {
        const opts = data.options || [{option_text:'',is_correct:false},{option_text:'',is_correct:false}];
        container.innerHTML = `
            <label class="form-label mb-2" style="font-size:0.8rem;">Answer Options</label>
            <div id="opts-${i}">
                ${opts.map((o, j) => renderOption(i, j, o)).join('')}
            </div>
            <button type="button" onclick="addOption(${i})"
                    style="margin-top:0.5rem;color:#a78bfa;background:none;border:1px dashed rgba(167,139,250,0.4);border-radius:6px;padding:0.3rem 0.8rem;font-size:0.78rem;cursor:pointer;">
                + Add Option
            </button>
            <p class="text-xs mt-1" style="color:rgba(255,255,255,0.3);">Check the box next to the correct answer.</p>`;

    } else if (type === 'true_false') {
        const correct = data.correct_answer || 'true';
        container.innerHTML = `
            <label class="form-label mb-2" style="font-size:0.8rem;">Correct Answer</label>
            <select name="questions[${i}][correct_answer]" class="form-select" style="font-size:0.875rem;">
                <option value="true"  ${correct==='true' ?'selected':''}>True</option>
                <option value="false" ${correct==='false'?'selected':''}>False</option>
            </select>`;

    } else {
        // short_answer / fill_in_the_blank
        container.innerHTML = `
            <label class="form-label mb-1" style="font-size:0.8rem;">Correct Answer / Keywords</label>
            <input type="text" name="questions[${i}][correct_answer]" value="${data.correct_answer || ''}"
                   class="form-input" style="font-size:0.875rem;"
                   placeholder="${type === 'fill_in_the_blank' ? 'Expected answer to fill the blank' : 'Expected answer or keywords'}" />`;
    }
}

function renderOption(qi, oi, data = {}) {
    return `<div id="opt-${qi}-${oi}" style="display:flex;align-items:center;gap:0.5rem;margin-bottom:0.4rem;">
        <input type="checkbox" name="questions[${qi}][options][${oi}][is_correct]" value="1"
               ${data.is_correct ? 'checked' : ''}
               style="width:16px;height:16px;accent-color:#930056;flex-shrink:0;" title="Mark as correct" />
        <input type="text" name="questions[${qi}][options][${oi}][option_text]" value="${data.option_text || ''}"
               class="form-input" style="flex:1;font-size:0.875rem;" placeholder="Option text" required />
        <button type="button" onclick="document.getElementById('opt-${qi}-${oi}').remove()"
                style="color:#f87171;background:none;border:none;cursor:pointer;font-size:1rem;flex-shrink:0;">✕</button>
    </div>`;
}

function addOption(qi) {
    const optsDiv = document.getElementById(`opts-${qi}`);
    const oi = optsDiv.children.length;
    optsDiv.insertAdjacentHTML('beforeend', renderOption(qi, oi));
}
</script>
@endpush
