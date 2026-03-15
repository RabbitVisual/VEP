<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensitiveFieldChangeRequest extends Model
{
    protected $fillable = [
        'user_id',
        'field_name',
        'requested_value',
        'previous_value',
        'status',
        'reviewed_by',
        'reviewed_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
        ];
    }

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /** Campos sensíveis que só o admin pode alterar diretamente. */
    public const SENSITIVE_FIELDS = ['cpf', 'email', 'phone'];

    public static function getFieldLabel(string $field): string
    {
        return match ($field) {
            'cpf' => 'CPF',
            'email' => 'E-mail',
            'phone' => 'Telefone',
            default => $field,
        };
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
}
