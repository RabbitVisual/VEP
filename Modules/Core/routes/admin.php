<?php
/**
 * Rotas Admin do Core (ex.: editor Strong).
 * Carregadas via routes/admin.php.
 */

use Illuminate\Support\Facades\Route;
use VertexSolutions\Core\Http\Controllers\Admin\BibleStrongController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::prefix('admin/bible/strong')->name('admin.bible.strong.')->group(function () {
        Route::get('/', [BibleStrongController::class, 'index'])->name('index');
        Route::get('/{strong}/edit', [BibleStrongController::class, 'edit'])->name('edit');
        Route::put('/{strong}', [BibleStrongController::class, 'update'])->name('update');
    });
});
