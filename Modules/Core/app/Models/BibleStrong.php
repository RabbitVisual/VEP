<?php

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BibleStrong extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bible_strongs';

    protected $fillable = [
        'number',
        'lemma',
        'lemma_br',
        'transliteration',
        'pronunciation',
        'description',
        'part_of_speech',
        'twot_ref',
        'ditat_ref',
        'language',
        'gematria_hechrachi',
        'gematria_gadol',
        'gematria_siduri',
        'gematria_katan',
        'gematria_perati',
        'lexicon_metadata_id',
    ];

    public function lexiconMetadata(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(BibleInterlinearLexiconMetadata::class, 'lexicon_metadata_id');
    }

    public function definitions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BibleStrongDefinition::class, 'bible_strong_id')->whereNull('parent_id')->orderBy('sort_order');
    }

    /**
     * Get the verses associated with this Strong number.
     */
    public function verses(): BelongsToMany
    {
        return $this->belongsToMany(BibleVerse::class, 'bible_verse_strongs', 'strong_id', 'verse_id');
    }
}
