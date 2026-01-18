<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Grade;
use App\Models\ContentUpload;


class TeacherController extends Controller
{
    // Show teacher dashboard
    public function dashboard()
    {
        $teacher = Auth::user();

        $courses = $teacher->taughtCourses()->withCount('enrollments')->get();

        $myCourses = $courses->count();
        $totalStudents = $courses->sum('enrollments_count');

        $pendingSubmissions = Submission::whereHas('assignment', function ($query) use ($teacher) {
            $query->whereIn('course_id', $teacher->taughtCourses()->pluck('id'));
        })
        ->where('status', 'submitted')
        ->count();

        $gradedThisWeek = Grade::where('graded_by', $teacher->id)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        return view('teacher.dashboard', compact(
            'courses',
            'myCourses',
            'totalStudents',
            'pendingSubmissions',
            'gradedThisWeek'
        ));
    }


    // Show teacher courses
    public function courses()
    {
        $courses = Auth::user()->taughtCourses()
            ->withCount(['enrollments', 'assignments'])
            ->latest()
            ->get();

        return view('teacher.courses', compact('courses'));
    }

    // Shows courses creation
    public function createCourse()
    {
        return view('teacher.courses-create');
    }

    // Stores courses
    public function storeCourse(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'credits' => 'nullable|integer',
        ]);

        $course = Course::create([
            'course_code' => strtoupper(substr(md5(uniqid()), 0, 6)),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'credits' => $data['credits'] ?? 0,
            'teacher_id' => Auth::id(),
            'status' => 'active',
        ]);

        return redirect()
            ->route('teacher.courses.show', $course->id)
            ->with('success', 'Course created successfully!');
    }

    // Show specific course content
    public function showCourse(Course $course)
    {
        // Security: only owner teacher can view
        abort_if($course->teacher_id !== Auth::id(), 403);

        // If your course has lessons relation:
        // $lessons = $course->lessons()->latest()->get();
        // If not sure, safe:
        $lessons = $course->contentUploads()->latest()->get();
        return view('teacher.course-content', compact('course', 'lessons'));

    }

    // Show edit course form
    public function editCourse(Course $course)
    {
        // Make sure the logged-in teacher owns the course
        abort_unless($course->teacher_id === Auth::id(), 403);

        return view('teacher.courses-edit', compact('course'));
    }

    // Shows updated courses in My Courses
    public function updateCourse(Request $request, Course $course)
        {
            abort_unless($course->teacher_id === Auth::id(), 403);

            $data = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'credits' => 'nullable|integer|min:0',
                'status' => 'required|in:active,inactive',
            ]);

            $course->update([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'credits' => $data['credits'] ?? 3,
                'status' => $data['status'],
            ]);

            return redirect()->route('teacher.courses')
                ->with('success', 'Course updated successfully!');
        }

    // Deletes a course
    public function destroyCourse(Course $course)
        {
            abort_unless($course->teacher_id === Auth::id(), 403);

            $course->delete();

            return redirect()->route('teacher.courses')
                ->with('success', 'Course deleted successfully!');
        }

    // Show content index 
    public function contentIndex()
    {
        $teacher = Auth::user();

        // Get teacher courses
        $courses = $teacher->taughtCourses()->latest()->get();

        // Get uploaded contents for teacher's courses
        $uploads = ContentUpload::whereHas('course', function ($q) use ($teacher) {
                $q->where('teacher_id', $teacher->id);
            })
            ->with('course')
            ->latest()
            ->get();

        return view('teacher.content.index', compact('courses', 'uploads'));
    }

    // Show content upload page
    public function contentUpload()
    {
        $courses = Auth::user()->taughtCourses()->get();
        $uploads = ContentUpload::whereIn('course_id', $courses->pluck('id'))
            ->with('course')
            ->latest()
            ->get();

        return view('teacher.content-upload', compact('courses', 'uploads'));
    }

    // Store content upload
    public function storeContent(Request $request)
    {
        $validated = $request->validate([
            'course_id'    => 'required|exists:courses,id',
            'content_type' => 'required|in:lecture,assignment,reading,video,other',
            'title'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'file'         => 'required|file|max:51200', // 50MB
            'due_date'     => 'nullable|date|required_if:content_type,assignment',
        ]);

        // ✅ get the course (fixes your undefined $course bug)
        $course = Course::findOrFail($validated['course_id']);

        $file = $request->file('file');
        $path = $file->store('content-uploads', 'public');

        // Store lesson/content upload
        $upload = ContentUpload::create([
            'course_id'   => $course->id,
            'uploaded_by' => Auth::id(),
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file_path'   => $path,
            'file_type'   => $file->getClientOriginalExtension(),
            'file_size'   => $file->getSize(),
        ]);

        // ✅ If assignment, ALSO create assignment record
        if ($validated['content_type'] === 'assignment') {
            Assignment::create([
                'course_id'   => $course->id,                 // best practice
                'course_code' => $course->course_code ?? null, // if column exists
                'title'       => $validated['title'],
                'description' => $validated['description'] ?? '',
                'due_date'    => $validated['due_date'] ?? now()->addDays(7),
                'max_points'  => 100,
                'status'      => 'active',
                'content_upload_id'   => $upload->id,
                'file_path'           => $upload->file_path,
                'file_original_name'  => $file->getClientOriginalName(),
                'file_type'           => $upload->file_type,
                'file_size'           => $upload->file_size,
            ]);
        }

        return redirect()
            ->route('teacher.courses.show', $validated['course_id'])
            ->with('success', 'Content uploaded successfully');
    }

    public function destroyAssignment(Assignment $assignment)
    {
        // ✅ Only the teacher who owns the course can delete
        abort_unless($assignment->course && $assignment->course->teacher_id === auth()->id(), 403);

        // ✅ Block delete if students already submitted (safer)
        if ($assignment->submissions()->exists()) {
            return back()->withErrors(['delete' => 'Cannot delete: students already submitted to this assignment.']);
        }

        // ✅ Delete assignment file (if stored in public disk)
        if ($assignment->file_path && Storage::disk('public')->exists($assignment->file_path)) {
            Storage::disk('public')->delete($assignment->file_path);
        }

        $assignment->delete();

        return back()->with('success', 'Assignment deleted successfully.');
    }


    public function editContent(ContentUpload $content)
    {
        // Only allow the teacher who owns the course
        abort_unless($content->course && $content->course->teacher_id === auth()->id(), 403);

        return view('teacher.content-edit', compact('content'));
    }

    public function updateContent(Request $request, ContentUpload $content)
    {
        abort_unless($content->course && $content->course->teacher_id === auth()->id(), 403);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        'content_type' => 'required|in:lecture,assignment,reading,video,other'
        ]);

        $content->update($validated);

        return redirect()
            ->route('teacher.courses.show', $content->course_id)
            ->with('success', 'Lesson updated successfully!');
    }

    public function deleteContent(ContentUpload $content)
    {
        abort_unless($content->course && $content->course->teacher_id === auth()->id(), 403);

        if ($content->file_path && Storage::disk('public')->exists($content->file_path)) {
            Storage::disk('public')->delete($content->file_path);
        }

        $courseId = $content->course_id;
        $content->delete();

        return redirect()
            ->route('teacher.courses.show', $courseId)
            ->with('success', 'Lesson deleted successfully!');
    }

    public function downloadContent(ContentUpload $content)
    {
        abort_unless($content->course && $content->course->teacher_id === auth()->id(), 403);

        $absolutePath = Storage::disk('public')->path($content->file_path);

        if (!file_exists($absolutePath)) {
            abort(404, 'File not found.');
        }

        $downloadName = ($content->title ?? 'lesson') . '.' . ($content->file_type ?? 'file');

        return response()->download($absolutePath, $downloadName);
    }

    public function downloadSubmission(Submission $submission)
    {
        // ✅ Make sure this submission belongs to a course owned by this teacher
        $teacherId = auth()->id();

        $course = $submission->assignment?->course;

        if (!$course || $course->teacher_id != $teacherId) {
            abort(403, 'Unauthorized access.');
        }

        if (!$submission->file_path || !Storage::disk('public')->exists($submission->file_path)) {
            abort(404, 'Submission file not found.');
        }

        $downloadName = $submission->file_original_name
            ?: basename($submission->file_path);

        return Storage::disk('public')->download($submission->file_path, $downloadName);
    }

    // Show grading dashboard
    public function grading(Request $request)
    {
        $courses = Auth::user()->taughtCourses()->get();
        $query = Submission::whereHas('assignment', function($q) use ($courses) {
            $q->whereIn('course_id', $courses->pluck('id'));
        })
        ->with(['student', 'assignment.course', 'grade'])
        ->latest();
        
        // filter by courses
        if ($request->filled('course') && $request->course !== '-1') {
            $query->whereHas('assignment', function($q) use ($request) {
                $q->where('course_id', $request->course);
            });
        }

        // filter by status
        if ($request->filled('status') && strtolower($request->status) !== 'all') {
            $query->where('status', strtolower($request->status));
        }
        
        $submissions = $query->get();

        return view('teacher.grading', compact('submissions', 'courses'));
    }
        public function showGradeForm(Submission $submission)
    {
        $teacherId = auth()->id();

        // ✅ ensure submission belongs to teacher's course
        $course = $submission->assignment?->course;
        if (!$course || $course->teacher_id != $teacherId) {
            abort(403, 'Unauthorized.');
        }

        // existing grade (if already graded)
        $existingGrade = Grade::where('student_id', $submission->student_id)
            ->where('submission_id', $submission->id)
            ->first();

        return view('teacher.grade-submission', compact('submission', 'existingGrade'));
    }

    public function storeGrade(Request $request, Submission $submission)
    {
        $teacherId = auth()->id();

        // ✅ ensure submission belongs to teacher's course
        $course = $submission->assignment?->course;
        if (!$course || $course->teacher_id != $teacherId) {
            abort(403, 'Unauthorized.');
        }

        $data = $request->validate([
            'grade' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string',
        ]);

        // ✅ Create/update grade record (reflects in student Grades/Progress)
        Grade::updateOrCreate(
            [
                'student_id' => $submission->student_id,
                'submission_id' => $submission->id,
                'course_id' => $course->id,
            ],
            [
                // if your grades table uses points_earned/max_points:
                'points_earned' => $data['grade'],
                'max_points'    => 100,
                'feedback'      => $data['feedback'] ?? null,
                'graded_by'     => auth()->id(),
            ]
        );

        // ✅ update submission status
        $submission->update([
            'status' => 'graded',
        ]);

        return redirect()
            ->route('teacher.grading')
            ->with('success', 'Grade saved successfully!');
    }

    public function submissionsIndex()
    {
        $teacher = auth()->user();

        $courses = $teacher->taughtCourses()
            ->latest()
            ->get();

        return view('teacher.submissions', compact('courses'));
    }

    public function courseSubmissions(Course $course)
{
    abort_unless($course->teacher_id === auth()->id(), 403);

    // ✅ Load assignments using course_id (correct + reliable)
    $assignments = Assignment::where('course_id', $course->id)
        ->latest()
        ->get();

    $submissions = collect();
    if ($assignments->isNotEmpty()) {
        $submissions = Submission::with(['student', 'assignment'])
            ->whereIn('assignment_id', $assignments->pluck('id'))
            ->latest()
            ->get();
    }

    return view('teacher.course-submissions', compact('course', 'assignments', 'submissions'));
}



}
