<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - require authentication
Route::middleware(['auth'])->group(function () {
    
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/students', [AdminController::class, 'students'])->name('students');
        Route::post('/students', [AdminController::class, 'storeStudent'])->name('students.store');
        Route::delete('/students/{id}', [AdminController::class, 'deleteStudent'])->name('students.delete');

        Route::get('/courses', [AdminController::class, 'courses'])->name('courses');
        Route::post('/courses', [AdminController::class, 'storeCourse'])->name('courses.store');
        Route::delete('/courses/{id}', [AdminController::class, 'deleteCourse'])->name('courses.delete');
    });

    // Teacher routes
    Route::middleware(['auth','role:teacher'])->prefix('teacher')->name('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherController::class, 'dashboard'])->name('dashboard');
        Route::get('/courses', [TeacherController::class, 'courses'])->name('courses');
        Route::get('/courses/create', [TeacherController::class, 'createCourse'])->name('courses.create');
        Route::post('/courses', [TeacherController::class, 'storeCourse'])->name('courses.store');
        Route::get('/courses/{course}', [TeacherController::class, 'showCourse'])->name('courses.show');
        Route::get('/courses/{course}/edit', [TeacherController::class, 'editCourse'])->name('courses.edit');
        Route::put('/courses/{course}', [TeacherController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{course}', [TeacherController::class, 'destroyCourse'])->name('courses.destroy');
        Route::get('/courses/{course}', [TeacherController::class, 'showCourse'])->name('courses.show');
        Route::get('/content', [TeacherController::class, 'contentIndex'])->name('content.index');
        Route::get('/content-upload', [TeacherController::class, 'contentUpload'])->name('content-upload');
        Route::post('/content-upload', [TeacherController::class, 'storeContent'])->name('content.store');
        Route::get('/content/{content}/download', [TeacherController::class, 'downloadContent'])->name('content.download');
        Route::get('/content/{content}/edit', [TeacherController::class, 'editContent'])->name('content.edit');
        Route::put('/content/{content}', [TeacherController::class, 'updateContent'])->name('content.update');
        Route::delete('/content/{content}', [TeacherController::class, 'deleteContent'])->name('content.delete');
        Route::get('/submissions', [TeacherController::class, 'submissionsIndex'])->name('submissions');
        Route::get('/submissions/{submission}/download', [TeacherController::class, 'downloadSubmission'])->name('submissions.download');
        Route::delete('/assignments/{assignment}', [TeacherController::class, 'destroyAssignment'])
    ->name('assignments.destroy');
        Route::get('/courses/{course}/submissions', [TeacherController::class, 'courseSubmissions'])->name('courses.submissions');
        Route::get('/grading', [TeacherController::class, 'grading'])->name('grading');
        Route::get('/grading/{submission}', [TeacherController::class, 'showGradeForm'])->name('grading.show');
        Route::post('/grading/{submission}', [TeacherController::class, 'storeGrade'])->name('grading.store');

    });

    // Student routes
        Route::middleware(['auth', 'role:student'])
            ->prefix('student')
            ->name('student.')
            ->group(function () {

        Route::get('/dashboard', [StudentController::class, 'dashboard'])->name('dashboard');

        Route::get('/courses', [StudentController::class, 'courses'])->name('courses');

        // ✅ FIXED: only ONE enroll route, correct name becomes "student.courses.enroll"
        Route::post('/courses/{course}/enroll', [StudentController::class, 'enrollCourse'])
            ->name('courses.enroll');
        Route::post('/courses/join', [StudentController::class, 'joinCourseByCode'])->name('courses.join');
        Route::get('/courses/{course}', [StudentController::class, 'showCourse'])->name('courses.show');

        Route::get('/assignments/{assignment}', [StudentController::class, 'showAssignment'])
        ->name('assignments.show');
        Route::get('/assignments', [StudentController::class, 'assignments'])->name('assignments');
        Route::post('/assignments/{assignment}/submit', [StudentController::class, 'submitAssignment'])->name('assignments.submit');
         // ✅ View assignment file (inline in browser)
        Route::get('/assignments/{assignment}/file', [StudentController::class, 'viewAssignmentFile'])
            ->name('assignments.file');
        // ✅ Download assignment file
        Route::get('/assignments/{assignment}/download', [StudentController::class, 'downloadAssignmentFile'])
            ->name('assignments.download');
       
        Route::get('/progress', [StudentController::class, 'progress'])->name('progress');
        // ✅ FIXED: keep ONLY one grades route
        Route::get('/grades', [StudentController::class, 'grades'])->name('grades');
    });

});
