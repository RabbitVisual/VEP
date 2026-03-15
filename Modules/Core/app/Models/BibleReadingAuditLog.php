<?php

declare(strict_types=1);

namespace Modules\Bible\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BibleReadingAuditLog extends Model
{
    protected $table = 'bible_reading_audit_logs';

    public const ACTION_RECALCULATE_ROUTE = 'recalculate_route';

    protected $fillable = [
        'user_id',
        'subscription_id',
        'action',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(BiblePlanSubscription::class, 'subscription_id');
    }
}
