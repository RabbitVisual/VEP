<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

namespace VertexSolutions\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BibleVerse extends Model
{
    protected $table = 'bible_verses';
    protected $fillable = [
        'chapter_id',
        'verse_number',
        'text',
        'original_verse_id',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(BibleChapter::class, 'chapter_id');
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(
            \App\Models\User::class,
            'bible_favorites',
            'verse_id',
            'user_id'
        )->withPivot('color')->withTimestamps();
    }

    public function getFullReferenceAttribute(): string
    {
        $chapter = $this->chapter;
        $book = $chapter->book;

        return $book->name.' '.$chapter->chapter_number.':'.$this->verse_number;
    }

    public function scopeSearch($query, string $search)
    {
        return $query->where('text', 'like', "%{$search}%");
    }

    /**
     * Get the Strong's numbers associated with this verse.
     */
    public function strongs(): BelongsToMany
    {
        return $this->belongsToMany(BibleStrong::class, 'bible_verse_strongs', 'verse_id', 'strong_id');
    }
}
