<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizQuestionOption;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function create(Course $course, Request $request)
    {
        $lessons = $course->lessons()->orderBy('order')->get();
        $defaultLessonId  = $request->query('lesson_id');
        $defaultQuizType  = $request->query('quiz_type', 'between_lessons');
        return view('admin.quizzes.create', compact('course', 'lessons', 'defaultLessonId', 'defaultQuizType'));
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title'                  => 'required|string|max:255',
            'description'            => 'nullable|string',
            'quiz_type'              => 'required|in:lesson_quiz,between_lessons',
            'lesson_id'              => 'nullable|exists:lessons,id',
            'order'                  => 'nullable|integer|min:0',
            'passing_score'          => 'required|integer|min:0|max:100',
            'time_limit_minutes'     => 'nullable|integer|min:0',
            'max_attempts'           => 'required|integer|min:1',
            'randomize_questions'    => 'boolean',
            'show_result_immediately'=> 'boolean',
            // Questions
            'questions'              => 'nullable|array',
            'questions.*.question'   => 'required_with:questions|string',
            'questions.*.type'       => 'required_with:questions|in:multiple_choice,true_false,short_answer,fill_in_the_blank',
            'questions.*.points'     => 'nullable|integer|min:1',
            'questions.*.explanation'=> 'nullable|string',
            'questions.*.options'    => 'nullable|array',
            'questions.*.options.*.option_text' => 'required_with:questions|string',
            'questions.*.options.*.is_correct'  => 'nullable|boolean',
            'questions.*.correct_answer'        => 'nullable|string', // for short_answer/fill
        ]);

        // Convert 0 to null for time_limit_minutes (0 means no limit)
        if (empty($validated['time_limit_minutes'])) {
            $validated['time_limit_minutes'] = null;
        }

        // For lesson_quiz, automatically use the lesson's order
        if ($validated['quiz_type'] === 'lesson_quiz' && !empty($validated['lesson_id'])) {
            $lesson = Lesson::find($validated['lesson_id']);
            $validated['order'] = $lesson ? $lesson->order : 0;
        }

        $quiz = Quiz::create([
            'course_id'               => $course->id,
            'lesson_id'               => $validated['lesson_id'] ?? null,
            'title'                   => $validated['title'],
            'description'             => $validated['description'] ?? null,
            'quiz_type'               => $validated['quiz_type'],
            'order'                   => $validated['order'] ?? 0,
            'passing_score'           => $validated['passing_score'],
            'time_limit_minutes'      => $validated['time_limit_minutes'],
            'max_attempts'            => $validated['max_attempts'],
            'randomize_questions'     => $request->boolean('randomize_questions'),
            'show_result_immediately' => $request->boolean('show_result_immediately'),
            'is_active'               => true,
        ]);

        $this->saveQuestions($quiz, $request->input('questions', []));

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Quiz created successfully!');
    }

    public function edit(Course $course, Quiz $quiz)
    {
        $lessons = $course->lessons()->orderBy('order')->get();
        $quiz->load('questions.options');
        return view('admin.quizzes.edit', compact('course', 'quiz', 'lessons'));
    }

    public function update(Request $request, Course $course, Quiz $quiz)
    {
        $validated = $request->validate([
            'title'                  => 'required|string|max:255',
            'description'            => 'nullable|string',
            'quiz_type'              => 'required|in:lesson_quiz,between_lessons',
            'lesson_id'              => 'nullable|exists:lessons,id',
            'order'                  => 'nullable|integer|min:0',
            'passing_score'          => 'required|integer|min:0|max:100',
            'time_limit_minutes'     => 'nullable|integer|min:0',
            'max_attempts'           => 'required|integer|min:1',
            'randomize_questions'    => 'boolean',
            'show_result_immediately'=> 'boolean',
            'questions'              => 'nullable|array',
            'questions.*.question'   => 'required_with:questions|string',
            'questions.*.type'       => 'required_with:questions|in:multiple_choice,true_false,short_answer,fill_in_the_blank',
            'questions.*.points'     => 'nullable|integer|min:1',
            'questions.*.explanation'=> 'nullable|string',
            'questions.*.options'    => 'nullable|array',
            'questions.*.options.*.option_text' => 'required_with:questions|string',
            'questions.*.options.*.is_correct'  => 'nullable|boolean',
            'questions.*.correct_answer'        => 'nullable|string',
        ]);

        // Convert 0 to null for time_limit_minutes (0 means no limit)
        if (empty($validated['time_limit_minutes'])) {
            $validated['time_limit_minutes'] = null;
        }

        if ($validated['quiz_type'] === 'lesson_quiz' && !empty($validated['lesson_id'])) {
            $lesson = Lesson::find($validated['lesson_id']);
            $validated['order'] = $lesson ? $lesson->order : 0;
        }

        $quiz->update([
            'lesson_id'               => $validated['lesson_id'] ?? null,
            'title'                   => $validated['title'],
            'description'             => $validated['description'] ?? null,
            'quiz_type'               => $validated['quiz_type'],
            'order'                   => $validated['order'] ?? $quiz->order,
            'passing_score'           => $validated['passing_score'],
            'time_limit_minutes'      => $validated['time_limit_minutes'],
            'max_attempts'            => $validated['max_attempts'],
            'randomize_questions'     => $request->boolean('randomize_questions'),
            'show_result_immediately' => $request->boolean('show_result_immediately'),
        ]);

        // Replace all questions
        $quiz->questions()->each(fn($q) => $q->options()->delete());
        $quiz->questions()->delete();
        $this->saveQuestions($quiz, $request->input('questions', []));

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Quiz updated successfully!');
    }

    public function destroy(Course $course, Quiz $quiz)
    {
        $quiz->questions()->each(fn($q) => $q->options()->delete());
        $quiz->questions()->delete();
        $quiz->delete();

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Quiz deleted.');
    }

    /* ── Private helpers ─────────────────────────────────────────────────── */

    private function saveQuestions(Quiz $quiz, array $questions): void
    {
        foreach ($questions as $i => $qData) {
            $question = QuizQuestion::create([
                'quiz_id'     => $quiz->id,
                'question'    => $qData['question'],
                'type'        => $qData['type'],
                'points'      => $qData['points'] ?? 1,
                'explanation' => $qData['explanation'] ?? null,
                'order'       => $i + 1,
            ]);

            if ($qData['type'] === 'true_false') {
                // Auto-create True / False options
                $correctAnswer = $qData['correct_answer'] ?? 'true';
                QuizQuestionOption::create(['question_id' => $question->id, 'option_text' => 'True',  'is_correct' => ($correctAnswer === 'true'),  'order' => 1]);
                QuizQuestionOption::create(['question_id' => $question->id, 'option_text' => 'False', 'is_correct' => ($correctAnswer === 'false'), 'order' => 2]);

            } elseif (in_array($qData['type'], ['multiple_choice', 'multiple_select'])) {
                foreach ($qData['options'] ?? [] as $j => $opt) {
                    QuizQuestionOption::create([
                        'question_id' => $question->id,
                        'option_text' => $opt['option_text'],
                        'is_correct'  => !empty($opt['is_correct']),
                        'order'       => $j + 1,
                    ]);
                }

            } elseif (in_array($qData['type'], ['short_answer', 'fill_in_the_blank'])) {
                // Store correct answer as a single option
                QuizQuestionOption::create([
                    'question_id' => $question->id,
                    'option_text' => $qData['correct_answer'] ?? '',
                    'is_correct'  => true,
                    'order'       => 1,
                ]);
            }
        }
    }
}
