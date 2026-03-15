<?php

namespace VertexSolutions\Sermons\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Stub quando o módulo Worship não está instalado.
 * Usa a tabela worship_songs (criada por migration opcional no Sermons).
 */
class WorshipSongStub extends Model
{
    protected $table = 'worship_songs';

    protected $fillable = ['title', 'artist'];

    public $timestamps = true;
}
