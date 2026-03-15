<?php

namespace VertexSolutions\Academy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonProgress extends Model
{
    protected $table = 'academy_lesson_progress';

    protected $fillable = ['enrollment_id', 'lesson_id', 'is_completed', 'watched_seconds'];

    protected $casts = [
        'is_completed' => 'boolean',
        'watched_seconds' => 'integer',
    ];

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
