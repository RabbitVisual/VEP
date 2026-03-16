<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

use Illuminate\Support\Facades\Route;
use VertexSolutions\Community\Http\Controllers\CommunityController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('communities', CommunityController::class)->names('community');
});
