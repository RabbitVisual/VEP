<?php

namespace VertexSolutions\Ministry\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Ministry extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'leader_id',
        'icon',
        'color',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Ministry $ministry) {
            if (empty($ministry->slug)) {
                $ministry->slug = Str::slug($ministry->name) . '-' . Str::random(6);
            }
        });
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(MinistryMember::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(MinistrySchedule::class)->orderBy('scheduled_at');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(MinistryMaterial::class)->orderBy('created_at', 'desc');
    }

    public function isLeader(User $user): bool
    {
        return $this->leader_id === $user->id
            || $this->members()->where('user_id', $user->id)->where('role', MinistryMember::ROLE_LEADER)->exists();
    }

    public function isCollaboratorOrLeader(User $user): bool
    {
        if ($this->isLeader($user)) {
            return true;
        }
        return $this->members()->where('user_id', $user->id)->whereIn('role', [MinistryMember::ROLE_LEADER, MinistryMember::ROLE_COLLABORATOR])->exists();
    }
}
