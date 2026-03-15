<?php
/**
 * Metadados do léxico Strong (ex.: strongs.json "metadados").
 */

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BibleInterlinearLexiconMetadata extends Model
{
    protected $table = 'bible_interlinear_lexicon_metadata';

    protected $fillable = [
        'slug', 'title', 'version', 'year', 'author', 'license', 'note', 'extra',
    ];

    protected $casts = [
        'extra' => 'array',
    ];

    public function strongs(): HasMany
    {
        return $this->hasMany(BibleStrong::class, 'lexicon_metadata_id');
    }
}
