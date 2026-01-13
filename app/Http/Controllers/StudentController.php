<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Grade;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ContentUpload;

class StudentController extends Controller
{
    // Show student dashboard
    public function dashboard()
    {
        $student = Auth::user();

        // ✅ enrolled courses (keep this if your dashboard uses it)
        $enrolledCourses = $student->enrolledCourses()
            ->withCount('assignments')
            ->get();

        // ✅ All assignments for the student's enrolled courses
        $totalAssignments = Assignment::whereHas('course.enrollments', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->count();

        // ✅ Assignments that have a submission from THIS student
        $completedAssignments = Submission::where('student_id', $student->id)
        ->whereHas('assignment', function($q) {
            $q->whereNotNull('content_upload_id');
        })
        ->count();


        // ✅ Pending 
        $pendingAssignmentsCount = Assignment::whereHas('course.enrollments', function($query) use ($student) {
            $query->where('student_id', $student->id);
        })
        ->whereNotNull('content_upload_id') // ✅ only assignments coming from teacher uploads
        ->whereDoesntHave('submissions', function($q) use ($student) {
            $q->where('student_id', $student->id);
        })
        ->count();

        // ✅ progress per course = (submitted in that course / total in that course) * 100
        foreach ($enrolledCourses as $course) {
            $courseTotal = Assignment::where('course_id', $course->id)->count();

            $courseSubmitted = Assignment::where('course_id', $course->id)
                ->whereHas('submissions', function ($q) use ($student) {
                    $q->where('student_id', $student->id);
                })->count();

                $course->progress = $courseTotal > 0 ? round(($courseSubmitted / $courseTotal) * 100) : 0;
            }

        return view('student.dashboard', compact(
            'enrolledCourses',
            'pendingAssignmentsCount',
            'completedAssignments'
        ));
    }


    // Show available courses
    public function courses()
    {
        $enrolledCourseIds = Auth::user()->enrollments()->pluck('course_id');
        $availableCourses = Course::whereNotIn('id', $enrolledCourseIds)
            ->where('status', 'active')
            ->with('teacher')
            ->get();

        $enrolledCourses = Auth::user()->enrolledCourses()
            ->with('teacher')
            ->get();

        return view('student.courses', compact('availableCourses', 'enrolledCourses'));
    }

    // Enroll in course
    public function enroll(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);

        Enrollment::create([
            'student_id' => Auth::id(),
            'course_id' => $courseId,
            'enrollment_date' => now(),
            'status' => 'active'
        ]);

        return redirect()->route('student.courses')->with('success', 'Enrolled successfully');
    }

    public function joinCourseByCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string'
        ]);

        $course = Course::where('course_code', $request->code)->first();

        if (!$course) {
            return back()->withErrors(['code' => 'Invalid course code.'])->withInput();
        }

        auth()->user()->enrolledCourses()->syncWithoutDetaching([
            $course->id => [
                'enrollment_date' => now(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        return redirect()->route('student.courses')->with('success', 'You joined the course!');
    }

    public function enrollCourse(Course $course)
    {
        auth()->user()->enrolledCourses()->syncWithoutDetaching([
            $course->id => [
                'enrollment_date' => now(),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        return redirect()->route('student.courses')->with('success', 'You joined the course!');
    }

    // Show assignments
    public function assignments()
    {
        $student = Auth::user();
        $assignments = Assignment::whereHas('course.enrollments', function($query) use ($student) {
            $query->where('student_id', $student->id);
        })
        ->with(['course', 'submissions' => function($query) use ($student) {
            $query->where('student_id', $student->id);
        }])
        ->latest()
        ->get();

        return view('student.assignments', compact('assignments'));
    }

    // Submit assignment
    public function submitAssignment(Request $request, $assignmentId)
{
    $assignment = Assignment::findOrFail($assignmentId);
    $student = auth()->user();

    // ✅ Enrollment check (users table)
    $isEnrolled = $assignment->course
        ->students()
        ->where('users.id', $student->id)
        ->exists();

    if (!$isEnrolled) {
        abort(403, 'You are not enrolled in this course.');
    }

    // ✅ Validate (content OPTIONAL)
    $validated = $request->validate([
        'content' => 'nullable|string',
        'file'    => 'required|file|max:10240', // 10MB
    ]);

    // ✅ Store file
    $file = $request->file('file');
    $filePath = $file->store('submissions', 'public');

    // ✅ Resubmit-safe: updateOrCreate (because you have unique(assignment_id, student_id))
    $submission = Submission::where('assignment_id', $assignment->id)
        ->where('student_id', $student->id)
        ->first();

    // delete old file if exists (optional but good)
    if ($submission && $submission->file_path && Storage::disk('public')->exists($submission->file_path)) {
        Storage::disk('public')->delete($submission->file_path);
    }

    Submission::updateOrCreate(
        [
            'assignment_id' => $assignment->id,
            'student_id'    => $student->id,
        ],
        [
            'content'            => $validated['content'] ?? null, // ✅ safe now
            'file_path'          => $filePath,
            'file_original_name' => $file->getClientOriginalName(),
            'submitted_at'       => now(),
            'status'             => 'submitted',
        ]
    );

    return redirect()
        ->route('student.assignments.show', $assignment->id)
        ->with('success', 'Submission uploaded successfully!');
}


    public function viewAssignmentFile(Assignment $assignment)
        {
            $student = auth()->user();

            // ✅ Make sure student is enrolled in this course
            $isEnrolled = $assignment->course
                ->students()
                ->where('users.id', $student->id)
                ->exists();

            if (!$isEnrolled) {
                abort(403, 'You are not enrolled in this course.');
            }

            if (!$assignment->file_path || !Storage::disk('public')->exists($assignment->file_path)) {
                abort(404, 'Assignment file not found.');
            }

            // View inline (PDF/image will open in browser; others may download depending on browser)
            $mime = Storage::disk('public')->mimeType($assignment->file_path) ?? 'application/octet-stream';

            return response()->file(
                Storage::disk('public')->path($assignment->file_path),
                ['Content-Type' => $mime]
            );
        }

    public function downloadAssignmentFile(Assignment $assignment)
        {
            $student = auth()->user();

            // ✅ Make sure student is enrolled in this course
            $isEnrolled = $assignment->course
                ->students()
                ->where('users.id', $student->id)
                ->exists();

            if (!$isEnrolled) {
                abort(403, 'You are not enrolled in this course.');
            }

            if (!$assignment->file_path || !Storage::disk('public')->exists($assignment->file_path)) {
                abort(404, 'Assignment file not found.');
            }

            $downloadName = $assignment->file_original_name
                ?: basename($assignment->file_path);

            $absolutePath = Storage::disk('public')->path($assignment->file_path);
            return response()->download($absolutePath, $downloadName);
        }
    public function showAssignment(Assignment $assignment)
{
    $student = auth()->user();

    // ✅ check enrollment (users table)
    $isEnrolled = $assignment->course
        ->students()
        ->where('users.id', $student->id)
        ->exists();

    if (!$isEnrolled) {
        abort(403, 'You are not enrolled in this course.');
    }

    // ✅ fetch student's existing submission (if any)
    $submission = Submission::where('assignment_id', $assignment->id)
        ->where('student_id', $student->id)
        ->latest()
        ->first();

    return view('student.assignment-show', compact('assignment', 'submission'));
}
    public function grades()
{
    $student = Auth::user();

    // enrolled courses 
    $enrolledCourses = $student->enrolledCourses()->get();

    // if no courses enrolled, don't query grades heavily
    if ($enrolledCourses->isEmpty()) {
        $grades = collect();
        $courseGrades = collect();

        return view('student.grades', compact('enrolledCourses', 'grades', 'courseGrades'));
    }

    // Get grades for this student
    $grades = Grade::where('student_id', $student->id)
        ->with(['course', 'submission.assignment.course'])
        ->latest()
        ->get();

    // Summary per course
    $courseGrades = $grades->groupBy('course_id')->map(function($grades) {
        $totalEarned = $grades->sum('points_earned');
        $totalMax = $grades->sum('max_points');
        $percentage = $totalMax > 0 ? ($totalEarned / $totalMax) * 100 : 0;

        return [
            'course' => $grades->first()->course,
            'total_earned' => $totalEarned,
            'total_max' => $totalMax,
            'percentage' => round($percentage, 2)
        ];
    });

    return view('student.grades', compact('enrolledCourses', 'grades', 'courseGrades'));
}


    public function showCourse(Course $course)
    {
        $student = auth()->user();

        // ✅ enrollment check
        $isEnrolled = $course->students()
            ->where('users.id', $student->id)
            ->exists();

        if (!$isEnrolled) {
            abort(403, 'You are not enrolled in this course.');
        }

        // ✅ get all teacher uploads for this course (lectures, assignments, posts/other)
        $contents = ContentUpload::where('course_id', $course->id)
            ->latest()
            ->get();

        // ✅ assignments for this course (so you can show submit/view easily)
        $assignments = Assignment::where('course_id', $course->id)
            ->with(['submissions' => function ($q) use ($student) {
                $q->where('student_id', $student->id);
            }])
            ->latest()
            ->get();

        return view('student.course-show', compact('course', 'contents', 'assignments'));
    }

    // Show progress/grades
    public function progress()
    {
        $student = Auth::user();

        // Enrolled courses
        $courseProgress = $student->enrolledCourses()->get();

        // Total assignments across enrolled courses
        $totalAssignments = Assignment::whereHas('course.enrollments', function ($q) use ($student) {
            $q->where('student_id', $student->id);
        })->count();

        // Submitted assignments across enrolled courses
        $submittedAssignments = Submission::where('student_id', $student->id)
            ->whereHas('assignment.course.enrollments', function ($q) use ($student) {
                $q->where('student_id', $student->id);
            })
            ->count();

        // ✅ Blade expects this name:
        $completionRate = $totalAssignments > 0
            ? round(($submittedAssignments / $totalAssignments) * 100)
            : 0;

        // ✅ Attach ->progress to each course (Blade uses $course->progress)
        foreach ($courseProgress as $course) {
            $courseTotal = Assignment::where('course_id', $course->id)->count();

            $courseSubmitted = Submission::where('student_id', $student->id)
                ->whereHas('assignment', function ($q) use ($course) {
                    $q->where('course_id', $course->id);
                })
                ->count();

            $course->progress = $courseTotal > 0
                ? round(($courseSubmitted / $courseTotal) * 100)
                : 0;
        }

        // Recent grades (latest 5) + compute percent for Blade ($grade->grade)
        $recentGrades = Grade::where('student_id', $student->id)
            ->with(['submission.assignment.course'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($g) {
                $max = (float)($g->max_points ?? 0);
                $earned = (float)($g->points_earned ?? 0);
                $g->grade = $max > 0 ? round(($earned / $max) * 100) : 0;
                return $g;
            });

        return view('student.progress', compact('completionRate', 'courseProgress', 'recentGrades'));
    }
}
