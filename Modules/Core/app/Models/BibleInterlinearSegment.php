<?php
/**
 * Segmento (palavra) do verso interlinear: palavra original + Strong + morfologia.
 */

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BibleInterlinearSegment extends Model
{
    protected $table = 'bible_interlinear_segments';

    protected $fillable = [
        'interlinear_verse_id', 'position', 'word_original', 'strong_id', 'morph_tag', 'compound_prefix',
    ];

    public function interlinearVerse(): BelongsTo
    {
        return $this->belongsTo(BibleInterlinearVerse::class, 'interlinear_verse_id');
    }

    public function strong(): BelongsTo
    {
        return $this->belongsTo(BibleStrong::class, 'strong_id');
    }
}
