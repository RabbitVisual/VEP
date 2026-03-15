<?php

namespace VertexSolutions\Ministry\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinistryScheduleAssignment extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';

    protected $fillable = ['ministry_schedule_id', 'user_id', 'status'];

    public function ministrySchedule(): BelongsTo
    {
        return $this->belongsTo(MinistrySchedule::class, 'ministry_schedule_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
