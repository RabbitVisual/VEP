<?php

declare(strict_types=1);

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BibleUserProgress extends Model
{
    protected $table = 'bible_user_progress';

    public $timestamps = true;

    protected $fillable = [
        'subscription_id',
        'plan_day_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(BiblePlanSubscription::class, 'subscription_id');
    }

    public function planDay(): BelongsTo
    {
        return $this->belongsTo(BiblePlanDay::class, 'plan_day_id');
    }
}
