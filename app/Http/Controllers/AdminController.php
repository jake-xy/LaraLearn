<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Hash;

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
    public function students()
    {
        $students = User::where('role', 'student')
            ->withCount('enrollments')
            ->latest()
            ->get();

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

    // Store new course
    public function storeCourse(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|unique:courses',
            'course_name' => 'required',
            'description' => 'nullable',
            'teacher_id' => 'required|exists:users,id',
            'credits' => 'required|integer|min:1'
        ]);

        Course::create($validated);

        return redirect()->route('admin.courses')->with('success', 'Course created successfully');
    }

    // Delete course
    public function deleteCourse($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Course deleted successfully');
    }
}
