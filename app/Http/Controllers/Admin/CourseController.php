<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Exports\CoursesExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $perPage = in_array((int) $request->per_page, [10, 25, 50, 100]) ? (int) $request->per_page : 15;
        $courses = Course::with(['category', 'creator'])
            ->when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->latest()
            ->paginate($perPage)->withQueryString();

        $categories = Category::where('is_active', true)->get();

        return view('admin.courses.index', compact('courses', 'categories'));
    }

    public function exportExcel(Request $request)
    {
        $filename = 'courses_' . now()->format('Y-m-d_His') . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(
            new CoursesExport(
                $request->search   ?? '',
                $request->status   ?? '',
                $request->category ?? ''
            ),
            $filename
        );
    }

    public function exportPdf(Request $request)
    {
        $courses = Course::with(['category'])
            ->when($request->search,   fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->when($request->status,   fn($q) => $q->where('status', $request->status))
            ->when($request->category, fn($q) => $q->where('category_id', $request->category))
            ->withCount('enrollments')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('exports.courses_pdf', [
            'courses'  => $courses,
            'search'   => $request->search   ?? '',
            'status'   => $request->status   ?? '',
            'category' => $request->category ?? '',
        ])->setPaper('a4', 'landscape');

        return $pdf->download('courses_' . now()->format('Y-m-d_His') . '.pdf');
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.courses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'category_id'       => 'nullable|exists:categories,id',
            'description'       => 'nullable|string',
            'whatyoulearn'      => 'nullable|string',
            'preview_video_url' => 'nullable|url',
            'level'             => 'required|in:beginner,intermediate,advanced',
            'duration_hours'    => 'nullable|integer|min:0',
            'language'          => 'nullable|string|max:50',
            'status'            => 'required|in:draft,published',
            'has_certificate'   => 'boolean',
            'thumbnail'         => 'nullable|image|max:2048',
        ]);

        // Auto-set instructor name from the admin creating the course
        $validated['instructor_name'] = auth()->user()->name;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $validated['slug']       = Str::slug($validated['title']) . '-' . Str::random(5);
        $validated['created_by'] = auth()->id();

        $course = Course::create($validated);

        return redirect()->route('admin.courses.show', $course)->with('success', 'Course created! Now add lessons and quizzes.');
    }

    public function show(Course $course)
    {
        $course->load(['category', 'creator']);

        // Lessons ordered by their order column — eager-load quiz with question count
        $lessons = $course->lessons()
            ->with(['quiz.questions'])
            ->orderBy('order')
            ->get();

        // Standalone quizzes (between lessons) with question count
        $standaloneQuizzes = $course->quizzes()
            ->where('quiz_type', 'between_lessons')
            ->withCount('questions')
            ->orderBy('order')
            ->get();

        // Merge lessons + standalone quizzes into a single curriculum array
        $curriculum = collect();
        foreach ($lessons as $lesson) {
            $curriculum->push(['type' => 'lesson', 'item' => $lesson, 'order' => $lesson->order]);
        }
        foreach ($standaloneQuizzes as $quiz) {
            $curriculum->push(['type' => 'quiz', 'item' => $quiz, 'order' => $quiz->order]);
        }
        $curriculum = $curriculum->sortBy('order')->values();

        // Stats for sidebar
        $totalLessonQuizzes  = $lessons->filter(fn($l) => $l->quiz)->count();
        $totalStandaloneQuizzes = $standaloneQuizzes->count();
        $totalQuestions = $lessons->sum(fn($l) => $l->quiz ? $l->quiz->questions->count() : 0)
                        + $standaloneQuizzes->sum('questions_count');

        $stats = [
            'lessons'           => $lessons->count(),
            'lesson_quizzes'    => $totalLessonQuizzes,
            'standalone_quizzes'=> $totalStandaloneQuizzes,
            'total_questions'   => $totalQuestions,
        ];

        return view('admin.courses.show', compact('course', 'curriculum', 'lessons', 'stats'));
    }

    public function edit(Course $course)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.courses.edit', compact('course', 'categories'));
    }

    public function update(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'category_id'       => 'nullable|exists:categories,id',
            'description'       => 'nullable|string',
            'whatyoulearn'      => 'nullable|string',
            'preview_video_url' => 'nullable|url',
            'level'             => 'required|in:beginner,intermediate,advanced',
            'duration_hours'    => 'nullable|integer|min:0',
            'language'          => 'nullable|string|max:50',
            'status'            => 'required|in:draft,published',
            'has_certificate'   => 'boolean',
            'thumbnail'         => 'nullable|image|max:2048',
        ]);

        // Keep instructor_name up-to-date with the admin editing the course
        $validated['instructor_name'] = auth()->user()->name;

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated successfully!');
    }

    public function reorderCurriculum(Request $request, Course $course)
    {
        $request->validate([
            'order'          => 'required|array',
            'order.*.type'   => 'required|in:lesson,quiz',
            'order.*.id'     => 'required|integer',
            'order.*.position' => 'required|integer',
        ]);

        foreach ($request->order as $entry) {
            $position = (int) $entry['position'];
            $id       = (int) $entry['id'];

            if ($entry['type'] === 'lesson') {
                Lesson::where('id', $id)
                      ->where('course_id', $course->id)
                      ->update(['order' => $position]);
            } else {
                Quiz::where('id', $id)
                    ->where('course_id', $course->id)
                    ->update(['order' => $position]);
            }
        }

        return response()->json(['success' => true]);
    }

    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->route('admin.courses.index')->with('success', 'Course deleted.');
    }

    public function bulkDelete(Request $request)
    {
        $ids = array_filter(explode(',', $request->input('ids', '')), 'is_numeric');

        if (empty($ids)) {
            return back()->with('error', 'No courses selected.');
        }

        $count = Course::whereIn('id', $ids)->delete();

        return back()->with('success', "{$count} course(s) deleted successfully.");
    }
}
