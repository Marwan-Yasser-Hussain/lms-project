<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\QuizController;
use App\Http\Controllers\Admin\CertificateController;
use App\Http\Controllers\Admin\SubCategoryController;

// ── Auth Routes ─────────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

// ── Home (placeholder until student dashboard is built) ──────────────────────
Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// ── Admin Routes ─────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Courses — full resource + custom show
    Route::get('/courses/export/excel', [CourseController::class, 'exportExcel'])->name('courses.export.excel');
    Route::get('/courses/export/pdf',   [CourseController::class, 'exportPdf'])->name('courses.export.pdf');
    Route::delete('/courses/bulk-delete', [CourseController::class, 'bulkDelete'])->name('courses.bulk-delete');
    Route::resource('courses', CourseController::class);

    // Lessons (nested under a course)
    Route::resource('courses.lessons', LessonController::class)->except(['index', 'show']);
    Route::post('/courses/{course}/lessons/reorder', [LessonController::class, 'reorder'])
         ->name('courses.lessons.reorder');
    Route::post('/courses/{course}/curriculum/reorder', [CourseController::class, 'reorderCurriculum'])
         ->name('courses.curriculum.reorder');

    // Quizzes (nested under a course)
    Route::resource('courses.quizzes', QuizController::class)->except(['index', 'show']);

    // Certificate Editor (per course)
    Route::get('/courses/{course}/certificate', [CertificateController::class, 'edit'])->name('courses.certificate.edit');
    Route::put('/courses/{course}/certificate', [CertificateController::class, 'update'])->name('courses.certificate.update');

    // Categories
    Route::resource('categories', CategoryController::class)->except(['show', 'create', 'edit']);

    // Sub-categories (nested under a category)
    Route::post('/categories/{category}/subcategories', [SubCategoryController::class, 'store'])->name('categories.subcategories.store');
    Route::put('/categories/{category}/subcategories/{subcategory}', [SubCategoryController::class, 'update'])->name('categories.subcategories.update');
    Route::delete('/categories/{category}/subcategories/{subcategory}', [SubCategoryController::class, 'destroy'])->name('categories.subcategories.destroy');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::delete('/users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    Route::get('/users/export/excel', [UserController::class, 'exportExcel'])->name('users.export.excel');
    Route::get('/users/export/pdf',   [UserController::class, 'exportPdf'])->name('users.export.pdf');
    Route::post('/users/import/preview',  [UserController::class, 'importPreview'])->name('users.import.preview');
    Route::post('/users/import/confirm',  [UserController::class, 'importConfirm'])->name('users.import.confirm');
    Route::get('/users/import/template',  [UserController::class, 'importTemplate'])->name('users.import.template');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Subscriptions — Plans CRUD
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::get('/subscriptions/plans/create', [SubscriptionController::class, 'createPlan'])->name('subscriptions.plans.create');
    Route::post('/subscriptions/plans', [SubscriptionController::class, 'storePlan'])->name('subscriptions.plans.store');
    Route::get('/subscriptions/plans/{plan}/edit', [SubscriptionController::class, 'editPlan'])->name('subscriptions.plans.edit');
    Route::put('/subscriptions/plans/{plan}', [SubscriptionController::class, 'updatePlan'])->name('subscriptions.plans.update');
    Route::delete('/subscriptions/plans/{plan}', [SubscriptionController::class, 'destroyPlan'])->name('subscriptions.plans.destroy');
    Route::post('/subscriptions/{subscription}/status', [SubscriptionController::class, 'updateStatus'])->name('subscriptions.update-status');
});


