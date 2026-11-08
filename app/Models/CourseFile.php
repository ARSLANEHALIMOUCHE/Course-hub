<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseFile extends Model
{
    protected $fillable = ['course_id', 'path', 'original_name'];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
