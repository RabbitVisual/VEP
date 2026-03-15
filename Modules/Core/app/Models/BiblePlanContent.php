<?php

declare(strict_types=1);

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BiblePlanContent extends Model
{
    protected $table = 'bible_plan_contents';

    protected $fillable = [
        'plan_day_id',
        'order_index',
        'type',
        'book_id',
        'chapter_start',
        'chapter_end',
        'verse_start',
        'verse_end',
    ];

    protected $casts = [
        'chapter_start' => 'integer',
        'chapter_end' => 'integer',
        'verse_start' => 'integer',
        'verse_end' => 'integer',
    ];

    public function planDay(): BelongsTo
    {
        return $this->belongsTo(BiblePlanDay::class, 'plan_day_id');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}
