<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Course;
use App\Models\Enrollment;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@elearning.com',
            'password' => Hash::make('admin'),
            'role' => 'admin',
        ]);

        // Create Teacher Users
        $teacher1 = User::create([
            'name' => 'John Teacher',
            'email' => 'teacher@elearning.com',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
        ]);

        $teacher2 = User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.teacher@elearning.com',
            'password' => Hash::make('teacher123'),
            'role' => 'teacher',
        ]);

        // Create Student Users
        $student1 = User::create([
            'name' => 'Alice Student',
            'email' => 'student@elearning.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
        ]);

        $student2 = User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob.student@elearning.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
        ]);

        $student3 = User::create([
            'name' => 'Carol Martinez',
            'email' => 'carol.student@elearning.com',
            'password' => Hash::make('student123'),
            'role' => 'student',
        ]);

        // Create Courses
        $course1 = Course::create([
            'course_code' => 'CS101',
            'title' => 'Introduction to Programming',
            'description' => 'Learn the fundamentals of programming using Python',
            'teacher_id' => $teacher1->id,
            'status' => 'active',
        ]);

        $course2 = Course::create([
            'course_code' => 'WEB201',
            'title' => 'Web Development Fundamentals',
            'description' => 'Master HTML, CSS, and JavaScript basics',
            'teacher_id' => $teacher1->id,
            'status' => 'active',
        ]);

        $course3 = Course::create([
            'course_code' => 'DB301',
            'title' => 'Database Management Systems',
            'description' => 'Learn SQL and database design principles',
            'teacher_id' => $teacher2->id,
            'status' => 'active',
        ]);

        // Enroll Students in Courses
        Enrollment::create([
            'student_id' => $student1->id,
            'course_id' => $course1->id,
            'enrollment_date' => now(),
            'status' => 'active',
        ]);

        Enrollment::create([
            'student_id' => $student1->id,
            'course_id' => $course2->id,
            'enrollment_date' => now(),
            'status' => 'active',
        ]);

        Enrollment::create([
            'student_id' => $student2->id,
            'course_id' => $course1->id,
            'enrollment_date' => now(),
            'status' => 'active',
        ]);

        Enrollment::create([
            'student_id' => $student2->id,
            'course_id' => $course3->id,
            'enrollment_date' => now(),
            'status' => 'active',
        ]);

        Enrollment::create([
            'student_id' => $student3->id,
            'course_id' => $course2->id,
            'enrollment_date' => now(),
            'status' => 'active',
        ]);
    }
}
