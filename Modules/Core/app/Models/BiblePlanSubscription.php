<?php

declare(strict_types=1);

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BiblePlanSubscription extends Model
{
    protected $table = 'bible_plan_subscriptions';

    protected $fillable = [
        'user_id',
        'plan_id',
        'start_date',
        'current_day_number',
        'projected_end_date',
        'is_completed',
        'prayer_request_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'projected_end_date' => 'date',
        'is_completed' => 'boolean',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(BiblePlan::class, 'plan_id');
    }

    public function progress(): HasMany
    {
        return $this->hasMany(BibleUserProgress::class, 'subscription_id');
    }
}
