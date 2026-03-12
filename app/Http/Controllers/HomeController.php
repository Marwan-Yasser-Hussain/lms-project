<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use App\Models\Enrollment;

class HomeController extends Controller
{
    public function index()
    {
        // Published courses for showcase (latest 6)
        $featuredCourses = Course::where('status', 'published')
            ->with(['category'])
            ->withCount('enrollments')
            ->latest()
            ->take(6)
            ->get();

        // Stats
        $stats = [
            'courses'  => Course::where('status', 'published')->count(),
            'students' => User::where('role', 'user')->count(),
            'lessons'  => \App\Models\Lesson::count(),
        ];

        // Categories with course counts
        $categories = Category::withCount(['courses' => function ($q) {
            $q->where('status', 'published');
        }])->orderByDesc('courses_count')->take(6)->get();

        return view('home', compact('featuredCourses', 'stats', 'categories'));
    }
}
