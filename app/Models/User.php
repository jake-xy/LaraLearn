<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Role check methods
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // Relationship: User teaches many Courses (for Teachers)
    public function taughtCourses(): HasMany
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Relationship: User enrolled in many Courses (for Students)
    public function enrolledCourses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments', 'student_id', 'course_id')
                    ->withTimestamps();
    }

    // Relationship: User has many Enrollments
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class, 'student_id');
    }

    // Relationship: User has many Submissions
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class, 'student_id');
    }

    // Relationship: User has many Grades
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class, 'student_id');
    }

    // Relationship: User uploaded many Content
    public function contentUploads(): HasMany
    {
        return $this->hasMany(ContentUpload::class, 'uploaded_by');
    }
}
