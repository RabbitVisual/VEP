<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PrayerRequest extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_ANSWERED = 'answered';

    protected $fillable = ['user_id', 'title', 'content', 'status', 'prays_count'];

    protected function casts(): array
    {
        return [
            'prays_count' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prays(): HasMany
    {
        return $this->hasMany(PrayerRequestPray::class, 'prayer_request_id');
    }
}
