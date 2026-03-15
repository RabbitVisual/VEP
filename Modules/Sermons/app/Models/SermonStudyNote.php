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
use VertexSolutions\Core\Models\BibleBook;
use VertexSolutions\Core\Models\BibleChapter;
use VertexSolutions\Core\Models\BibleVerse;

class SermonStudyNote extends Model
{
    protected $table = 'sermon_study_notes';

    protected $fillable = [
        'user_id',
        'sermon_id',
        'reference_text',
        'book_id',
        'chapter_id',
        'verse_id',
        'content',
        'is_global',
    ];

    protected $casts = [
        'is_global' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sermon(): BelongsTo
    {
        return $this->belongsTo(Sermon::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(BibleBook::class, 'book_id');
    }

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(BibleChapter::class, 'chapter_id');
    }

    public function verse(): BelongsTo
    {
        return $this->belongsTo(BibleVerse::class, 'verse_id');
    }
}
