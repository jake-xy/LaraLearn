<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_code',
        'title',
        'description',
        'teacher_id',
        'credits',
        'status'
    ];

    // Relationship: Course belongs to a Teacher (User)
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Relationship: Course has many Enrollments
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    // Relationship: Course has many Students through Enrollments
    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'enrollments', 'course_id', 'student_id')
                    ->withTimestamps();
    }

    // Relationship: Course has many Assignments
    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    // Relationship: Course has many Content Uploads
    public function contentUploads(): HasMany
    {
        return $this->hasMany(ContentUpload::class);
    }

    // Relationship: Course has many Grades
    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
