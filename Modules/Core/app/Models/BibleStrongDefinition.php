<?php
/**
 * Definição hierárquica de um termo Strong (estilo NEPE).
 */

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BibleStrongDefinition extends Model
{
    protected $table = 'bible_strong_definitions';

    protected $fillable = ['bible_strong_id', 'parent_id', 'level', 'sort_order', 'definition_text'];

    public function bibleStrong(): BelongsTo
    {
        return $this->belongsTo(BibleStrong::class, 'bible_strong_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->orderBy('sort_order');
    }
}
