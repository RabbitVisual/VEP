<?php

namespace VertexSolutions\Ministry\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MinistrySchedule extends Model
{
    protected $fillable = ['ministry_id', 'activity_name', 'scheduled_at', 'notes'];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(MinistryScheduleAssignment::class, 'ministry_schedule_id');
    }
}
