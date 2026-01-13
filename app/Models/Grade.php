<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'submission_id',
        'student_id',
        'course_id',
        'points_earned',
        'max_points',
        'feedback',
        'graded_by'
    ];

    // Relationship: Grade belongs to a Submission
    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }

    // Relationship: Grade belongs to a Student (User)
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relationship: Grade belongs to a Course
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Relationship: Grade graded by a Teacher (User)
    public function gradedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'graded_by');
    }

    // Calculate percentage
    public function getPercentageAttribute(): float
    {
        return ($this->points_earned / $this->max_points) * 100;
    }
}
