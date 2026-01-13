<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'assignment_id',
        'student_id',
        'content',
        'file_path',
        'submitted_at',
        'status'
    ];

    protected $casts = [
        'submitted_at' => 'datetime'
    ];

    // Relationship: Submission belongs to an Assignment
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    // Relationship: Submission belongs to a Student (User)
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Relationship: Submission has one Grade
    public function grade(): HasOne
    {
        return $this->hasOne(Grade::class,'submission_id');
    }
}
