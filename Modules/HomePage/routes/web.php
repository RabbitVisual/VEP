<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

use Illuminate\Support\Facades\Route;
use VertexSolutions\HomePage\Http\Controllers\HomePageController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('homepages', HomePageController::class)->names('homepage');
});
