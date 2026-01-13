<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Hash;

use function Illuminate\Support\now;

class AdminController extends Controller
{
    // Show admin dashboard
    public function dashboard()
    {
        $totalStudents = User::where('role', 'student')->count();
        $totalTeachers = User::where('role', 'teacher')->count();
        $totalCourses = Course::count();
        $activeEnrollments = Enrollment::where('status', 'active')->count();

        return view('admin.dashboard', compact('totalStudents', 'totalTeachers', 'totalCourses', 'activeEnrollments'));
    }

    // Show all students
    public function students(Request $request)
    {
        $query = User::where('role', 'student');

        if ($request->has('search') && $request->filled('search')) {
            $searchTerm = $request->get('search');
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $students = $query->get();

        return view('admin.students', compact('students'));
    }

    // Show all courses
    public function courses()
    {
        $courses = Course::with('teacher')
            ->withCount('enrollments')
            ->latest()
            ->get();

        return view('admin.courses', compact('courses'));
    }


    public function createCourse() {
        $teachers = User::all()->where('role', '=', 'teacher');

        return view('admin.courses-edit-create', compact('teachers'));
    }


    public function editCourse(Course $course) {
        $teachers = User::all()->where('role', '=', 'teacher');
        $selectedTeacherId = $course->teacher_id;

        return view('admin.courses-edit-create', compact('course', 'teachers', 'selectedTeacherId'));
    }


    // Store new student
    public function storeStudent(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8'
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'student'
        ]);

        return redirect()->route('admin.students')->with('success', 'Student added successfully');
    }

    // Delete student
    public function deleteStudent($id)
    {
        $student = User::where('role', 'student')->findOrFail($id);
        $student->delete();

        return redirect()->route('admin.students')->with('success', 'Student deleted successfully');
    }

    // edit student
    public function editStudent(User $student) {
        $courses = Course::all();

        return view('admin.students-edit', compact('student', 'courses'));
    }

    public function updateStudent(Request $request, User $student) {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email'
        ]);

        $student->name = $validated['name'];
        $student->email = $validated['email'];
        $student->save();

        return redirect()->route('admin.students.edit', $student)->with('success update', 'Updated student info successfully');
    }

    public function addStudentCourse(Request $request, User $student) {
        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $request['addedItemId'],
            'enrollment_date' => now(),
            'status' => 'active'
        ]
        );

        return redirect()->route('admin.students.edit', $student)->with('success', 'Course added to student successfully');
    }

    public function deleteStudentCourse(User $student, Course $course) {
        $enrollment = Enrollment::where('student_id', $student->id)
                                    ->where('course_id', $course->id)
                                    ->firstOrFail();

        $enrollment->delete();

        return redirect()->route('admin.students.edit', $student)->with('success', 'Course removed from student successfully');
    }

    // Store new course
    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'credits' => 'required|integer|min:1',
            'teacher_id' => 'required|exists:users,id'
        ]);

        Course::create([
            'course_code' => strtoupper(substr(md5(uniqid()), 0, 6)),
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'teacher_id' => $validated['teacher_id'],
            'credits' => $validated['credits'] ?? 0,
            'status' => 'active',
        ]);

        return redirect()->route('admin.courses')->with('success', 'Course created successfully');
    }

    // update new course
    public function updateCourse(Request $request, Course $course) {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'course_code' => "required|unique:courses,course_code,{$course->id}|min:6|max:6",
            'credits' => 'required|integer|min:1',
            'teacher_id' => 'required|exists:users,id'
        ]);

        $course->title = $validated['title'];
        $course->description = $validated['description'];
        $course->course_code = $validated['course_code'];
        $course->teacher_id = $validated['teacher_id'];
        $course->save();

        return redirect()->route('admin.courses')->with('success', 'Course updated successfully');
    }

    // Delete course
    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully');
    }


    // teachers endpoint
    public function teachers(Request $request) {
        $query = User::where('role', 'teacher');

        if ($request->has('search') && $request->filled('search')) {
            $searchTerm = $request->get('search');
            
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('email', 'like', "%{$searchTerm}%");
            });
        }

        $teachers = $query->get();


        return view('admin.teachers', compact('teachers'));
    }
}
