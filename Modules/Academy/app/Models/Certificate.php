<?php

namespace VertexSolutions\Academy\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $table = 'academy_certificates';

    protected $fillable = ['user_id', 'course_id', 'validation_code', 'issued_at'];

    protected $casts = [
        'issued_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Certificate $cert) {
            if (empty($cert->validation_code)) {
                $cert->validation_code = (string) Str::uuid();
            }
            if (empty($cert->issued_at)) {
                $cert->issued_at = now();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
