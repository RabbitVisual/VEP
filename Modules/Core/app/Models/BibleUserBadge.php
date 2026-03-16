<?php

declare(strict_types=1);

namespace VertexSolutions\Core\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BibleUserBadge extends Model
{
    protected $table = 'bible_user_badges';

    public const BADGE_BEREANO_SEMANA = 'bereano_semana';

    public const BADGE_FIEL_AO_PACTO = 'fiel_ao_pacto';

    public const BADGE_LEITOR_DO_CORPO = 'leitor_do_corpo';

    protected $fillable = ['user_id', 'badge_key', 'subscription_id', 'awarded_at'];

    protected function casts(): array
    {
        return [
            'awarded_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getLabel(string $badgeKey): string
    {
        return match ($badgeKey) {
            self::BADGE_BEREANO_SEMANA => 'Bereano da Semana',
            self::BADGE_FIEL_AO_PACTO => 'Fiel ao Pacto',
            self::BADGE_LEITOR_DO_CORPO => 'Leitor do Corpo',
            default => $badgeKey,
        };
    }
}
