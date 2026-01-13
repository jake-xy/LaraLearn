<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'description',
        'due_date',
        'max_points',
        'status',
        'content_upload_id',
        'file_path',
        'file_original_name',
        'file_type',
        'file_size',
    ];

    protected $casts = [
        'due_date' => 'datetime'
    ];

    // Relationship: Assignment belongs to a Course
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // Relationship: Assignment has many Submissions
    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
public function contentUpload(): BelongsTo
    {
        return $this->belongsTo(ContentUpload::class, 'content_upload_id');
    }

}
