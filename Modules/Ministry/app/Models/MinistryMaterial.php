<?php

namespace VertexSolutions\Ministry\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MinistryMaterial extends Model
{
    public const TYPE_SETLIST = 'setlist';
    public const TYPE_CHORD = 'chord';
    public const TYPE_CURRICULUM = 'curriculum';
    public const TYPE_LESSON = 'lesson';
    public const TYPE_ACTIVITY = 'activity';
    public const TYPE_REPORT = 'report';
    public const TYPE_PRAYER_REQUEST = 'prayer_request';
    public const TYPE_MAP = 'map';

    protected $fillable = [
        'ministry_id',
        'type',
        'title',
        'content',
        'file_path',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function ministry(): BelongsTo
    {
        return $this->belongsTo(Ministry::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function types(): array
    {
        return [
            self::TYPE_SETLIST => 'Setlist',
            self::TYPE_CHORD => 'Cifra',
            self::TYPE_CURRICULUM => 'Currículo',
            self::TYPE_LESSON => 'Lição EBD',
            self::TYPE_ACTIVITY => 'Atividade',
            self::TYPE_REPORT => 'Relatório',
            self::TYPE_PRAYER_REQUEST => 'Pedido de oração',
            self::TYPE_MAP => 'Mapa',
        ];
    }
}
