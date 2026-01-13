<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContentUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'uploaded_by',
        'title',
        'description',
        'file_path',
        'file_type',
        'file_size'
    ];

    // Relationship: Content Upload belongs to a Course
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Relationship: Content Upload uploaded by a User (Teacher)
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
