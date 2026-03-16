<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrayerRequestPray extends Model
{
    public $timestamps = false;

    protected $table = 'prayer_request_prays';

    protected $fillable = ['user_id', 'prayer_request_id'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function prayerRequest(): BelongsTo
    {
        return $this->belongsTo(PrayerRequest::class, 'prayer_request_id');
    }
}
