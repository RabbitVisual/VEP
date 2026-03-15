<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

use Illuminate\Support\Facades\Route;
use VertexSolutions\MemberPanel\Http\Controllers\MemberPanelController;
use VertexSolutions\MemberPanel\Http\Controllers\VerseExplainerController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('memberpanels', MemberPanelController::class)->names('memberpanel');

    Route::prefix('social')->name('social.')->group(function () {
        Route::get('verse-explainer', [VerseExplainerController::class, 'index'])->name('verse-explainer');
        Route::post('verse-explainer/explain', [VerseExplainerController::class, 'explain'])->name('verse-explainer.explain');
    });
});
