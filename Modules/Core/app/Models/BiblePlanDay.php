<?php

declare(strict_types=1);

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiblePlanDay extends Model
{
    protected $table = 'bible_plan_days';

    protected $fillable = [
        'plan_id',
        'day_number',
        'title',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(BiblePlan::class, 'plan_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(BiblePlanContent::class, 'plan_day_id')->orderBy('order_index');
    }
}
