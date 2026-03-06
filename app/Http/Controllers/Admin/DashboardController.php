<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Course;
use App\Models\Subscription;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'          => User::where('role', 'user')->count(),
            'total_courses'        => Course::count(),
            'published_courses'    => Course::where('status', 'published')->count(),
            'active_subscriptions' => Subscription::where('status', 'active')
                                        ->where('ends_at', '>=', now())->count(),
            'total_revenue'        => Subscription::where('status', 'active')->sum('amount_paid'),
            'total_enrollments'    => Enrollment::count(),
        ];

        $recentUsers = User::where('role', 'user')
            ->latest()
            ->take(8)
            ->get();

        $recentSubscriptions = Subscription::with(['user', 'plan'])
            ->latest()
            ->take(8)
            ->get();

        $recentCourses = Course::with('category')
            ->latest()
            ->take(5)
            ->get();

        // Chart Data: Last 6 months Revenue
        $revenueData = [];
        $enrollmentData = [];
        $months = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthName = $date->format('M');
            $months[] = $monthName;

            $rev = Subscription::where('status', 'active')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount_paid');
            $revenueData[] = (float) $rev;

            $enr = Enrollment::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $enrollmentData[] = $enr;
        }

        $chartData = [
            'labels' => $months,
            'revenue' => $revenueData,
            'enrollments' => $enrollmentData,
        ];

        return view('admin.dashboard.index', compact('stats', 'recentUsers', 'recentSubscriptions', 'recentCourses', 'chartData'));
    }
}
