<?php

namespace VertexSolutions\Ministry\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinistryMember extends Model
{
    public const ROLE_LEADER = 'leader';
    public const ROLE_COLLABORATOR = 'collaborator';
    public const ROLE_VOLUNTEER = 'volunteer';

    protected $fillable = ['ministry_id', 'user_id', 'role'];

    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function roles(): array
    {
        return [
            self::ROLE_LEADER => 'Líder',
            self::ROLE_COLLABORATOR => 'Colaborador',
            self::ROLE_VOLUNTEER => 'Voluntário',
        ];
    }
}
