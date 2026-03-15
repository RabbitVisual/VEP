<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

namespace VertexSolutions\Sermons\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SermonFavorite extends Model
{
    protected $fillable = [
        'sermon_id',
        'user_id',
        'notes',
    ];

    /**
     * Get the sermon
     */
    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class, 'sermon_id');
    }

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
