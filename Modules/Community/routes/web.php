<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

use Illuminate\Support\Facades\Route;
use VertexSolutions\Community\Http\Controllers\CommunityController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('communities', CommunityController::class)->names('community');
});
