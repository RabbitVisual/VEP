<?php

namespace VertexSolutions\Academy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $table = 'academy_lessons';

    protected $fillable = [
        'module_id',
        'title',
        'video_url',
        'content',
        'duration_in_minutes',
        'order',
    ];

    protected $casts = [
        'duration_in_minutes' => 'integer',
        'order' => 'integer',
    ];

    public function module(): BelongsTo
    {
        return $this->belongsTo(CourseModule::class, 'module_id');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(LessonAttachment::class, 'lesson_id');
    }
}
