<?php
/**
 * Fonte do texto interlinear (hebrew_tagged, grc_trparsed).
 */

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BibleInterlinearSource extends Model
{
    protected $table = 'bible_interlinear_sources';

    protected $fillable = ['slug', 'name', 'language', 'testament', 'metadata'];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function interlinearVerses(): HasMany
    {
        return $this->hasMany(BibleInterlinearVerse::class, 'interlinear_source_id');
    }
}
