<?php

use Illuminate\Support\Facades\Route;
use VertexSolutions\Core\Http\Controllers\BibleController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('bibles/compare', [BibleController::class, 'compare'])->name('bible.compare');
    Route::resource('bibles', BibleController::class)->names('bible.web');

    // Admin (admin/bible/strong) → Modules/Core/routes/admin.php, carregado por routes/admin.php

    // Member Panel (social/bible*) – prefix social; painel do membro usa painel/bible em MemberPanel
    Route::prefix('social/bible/plans')->name('member.bible.')->group(function () {
        Route::get('/', [\VertexSolutions\Core\Http\Controllers\MemberPanel\ReadingPlanController::class, 'index'])->name('plans.index');
        Route::get('/catalog', [\VertexSolutions\Core\Http\Controllers\MemberPanel\ReadingPlanController::class, 'catalog'])->name('plans.catalog');
        Route::get('/{id}/preview', [\VertexSolutions\Core\Http\Controllers\MemberPanel\ReadingPlanController::class, 'preview'])->name('plans.preview');
        Route::post('/{id}/subscribe', [\VertexSolutions\Core\Http\Controllers\MemberPanel\ReadingPlanController::class, 'subscribe'])->name('plans.subscribe');
        Route::get('/resume/{id}', [\VertexSolutions\Core\Http\Controllers\MemberPanel\ReadingPlanController::class, 'show'])->name('plans.show');
        Route::post('/{subscriptionId}/recalculate', [\VertexSolutions\Core\Http\Controllers\MemberPanel\ReadingPlanController::class, 'recalculate'])->name('plans.recalculate');
        Route::get('/download/{id}/pdf', [\VertexSolutions\Core\Http\Controllers\MemberPanel\ReadingPlanController::class, 'downloadPdf'])->name('plans.pdf');

        Route::get('/read/{subscriptionId}/{day}', [\VertexSolutions\Core\Http\Controllers\MemberPanel\PlanReaderController::class, 'read'])->name('reader');
        Route::post('/read/{subscriptionId}/{dayId}/complete', [\VertexSolutions\Core\Http\Controllers\MemberPanel\PlanReaderController::class, 'complete'])->name('reader.complete');
        Route::post('/read/{subscriptionId}/{dayId}/uncomplete', [\VertexSolutions\Core\Http\Controllers\MemberPanel\PlanReaderController::class, 'uncomplete'])->name('reader.uncomplete');
        Route::get('/read/{subscriptionId}/{dayId}/congratulations', [\VertexSolutions\Core\Http\Controllers\MemberPanel\PlanReaderController::class, 'congratulations'])->name('reader.congratulations');
        Route::post('/read/{subscriptionId}/{dayId}/note', [\VertexSolutions\Core\Http\Controllers\MemberPanel\PlanReaderController::class, 'storeNote'])->name('reader.note.store');

        Route::get('/search', [\VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController::class, 'search'])->name('search');
        Route::get('/api/find', [\VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController::class, 'performSearch'])->name('api.search');
    });

    Route::prefix('social/bible/favorites')->name('member.bible.favorites.')->group(function () {
        Route::post('/batch', [\VertexSolutions\Core\Http\Controllers\MemberPanel\FavoriteController::class, 'batchUpdate'])->name('batch');
        Route::post('/{id}', [\VertexSolutions\Core\Http\Controllers\MemberPanel\FavoriteController::class, 'toggle'])->name('toggle');
        Route::delete('/{id}', [\VertexSolutions\Core\Http\Controllers\MemberPanel\FavoriteController::class, 'destroy'])->name('destroy');
    });

    // Bíblia área do membro – rotas nomeadas memberpanel.bible.* (compatibilidade com views)
    Route::prefix('social/bible')->name('memberpanel.bible.')->group(function () {
        Route::get('/', [\VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController::class, 'index'])->name('index');
        Route::get('read/{versionAbbr?}', [\VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController::class, 'read'])->name('read');
        Route::get('book/{versionAbbr}/{bookNumber}', [\VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController::class, 'showBook'])->name('book');
        Route::get('chapter/{versionAbbr}/{bookNumber}/{chapterNumber}', [\VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController::class, 'showChapter'])->name('chapter');
        Route::get('search', [\VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController::class, 'search'])->name('search');
        Route::get('api/find', [\VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController::class, 'performSearch'])->name('api.search');
        Route::get('favorites', [\VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController::class, 'favorites'])->name('favorites');
        Route::get('verse/{verse}', [\VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController::class, 'verse'])->name('verse');
    });
});
