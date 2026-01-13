<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'enrollment_date',
        'status'
    ];

    protected $casts = [
        'enrollment_date' => 'date'
    ];

    // Relationship: Enrollment belongs to a Student (User)
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relationship: Enrollment belongs to a Course
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
