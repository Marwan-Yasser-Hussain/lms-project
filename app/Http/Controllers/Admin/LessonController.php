<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function create(Course $course)
    {
        return view('admin.lessons.create', compact('course'));
    }

    public function store(Request $request, Course $course)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'video_url'         => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'resource_url'      => 'nullable|string|max:500',
            'duration_minutes'  => 'nullable|integer|min:0',
            'is_free_preview'   => 'boolean',
        ]);

        // Auto-assign next order
        $maxOrder = $course->lessons()->max('order') ?? 0;
        $validated['course_id']         = $course->id;
        $validated['order']             = $maxOrder + 1;
        $validated['is_free_preview']   = $request->boolean('is_free_preview');

        Lesson::create($validated);

        // Update total_lessons count
        $course->update(['total_lessons' => $course->lessons()->count()]);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Lesson added successfully!');
    }

    public function edit(Course $course, Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('course', 'lesson'));
    }

    public function update(Request $request, Course $course, Lesson $lesson)
    {
        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'video_url'         => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'resource_url'      => 'nullable|string|max:500',
            'duration_minutes'  => 'nullable|integer|min:0',
            'is_free_preview'   => 'boolean',
        ]);

        $validated['is_free_preview'] = $request->boolean('is_free_preview');
        $lesson->update($validated);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Lesson updated successfully!');
    }

    public function destroy(Course $course, Lesson $lesson)
    {
        $lesson->delete();
        $course->update(['total_lessons' => $course->lessons()->count()]);

        return redirect()
            ->route('admin.courses.show', $course)
            ->with('success', 'Lesson deleted.');
    }

    public function reorder(Request $request, Course $course)
    {
        $request->validate(['order' => 'required|array']);

        foreach ($request->order as $position => $lessonId) {
            Lesson::where('id', $lessonId)
                  ->where('course_id', $course->id)
                  ->update(['order' => $position + 1]);
        }

        return response()->json(['success' => true]);
    }
}
