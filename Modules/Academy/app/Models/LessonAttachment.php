<?php

declare(strict_types=1);

namespace VertexSolutions\Academy\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonAttachment extends Model
{
    protected $table = 'academy_lesson_attachments';

    protected $fillable = [
        'lesson_id',
        'file_name',
        'file_path',
        'type',
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class, 'lesson_id');
    }
}

