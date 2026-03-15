<?php
/**
 * Verso interlinear (referência livro/capítulo/versículo por fonte).
 */

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BibleInterlinearVerse extends Model
{
    protected $table = 'bible_interlinear_verses';

    protected $fillable = [
        'interlinear_source_id', 'book_number', 'chapter_number', 'verse_number', 'raw_text',
    ];

    public function source(): BelongsTo
    {
        return $this->belongsTo(BibleInterlinearSource::class, 'interlinear_source_id');
    }

    public function segments(): HasMany
    {
        return $this->hasMany(BibleInterlinearSegment::class, 'interlinear_verse_id')->orderBy('position');
    }
}
