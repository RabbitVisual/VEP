<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFollow extends Model
{
    public $timestamps = false;

    protected $table = 'user_follows';

    protected $fillable = ['follower_id', 'following_id'];

    protected static function booted(): void
    {
        static::creating(function (UserFollow $follow) {
            if (empty($follow->created_at)) {
                $follow->created_at = now();
            }
        });
    }

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function follower(): BelongsTo
    {
        return $this->belongsTo(User::class, 'follower_id');
    }

    public function following(): BelongsTo
    {
        return $this->belongsTo(User::class, 'following_id');
    }
}
