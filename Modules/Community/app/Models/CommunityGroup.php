<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityGroup extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'cover_image',
        'is_private',
    ];

    protected function casts(): array
    {
        return [
            'is_private' => 'bool',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(CommunityGroupMember::class, 'group_id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'group_id');
    }
}

