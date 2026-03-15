<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

use Illuminate\Support\Facades\Route;
use VertexSolutions\Academy\Http\Controllers\AcademyController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('academies', AcademyController::class)->names('academy');
});
