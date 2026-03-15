<?php

/*
|--------------------------------------------------------------------------
| Bible API – Autocomplete, Interlinear, Compare (v1)
|--------------------------------------------------------------------------
*/

use Illuminate\Support\Facades\Route;
use VertexSolutions\Core\Http\Controllers\Api\BibleDataController;
use VertexSolutions\Core\Http\Controllers\Api\V1\BibleCompareController;

Route::middleware('api')->prefix('api/bible')->group(function () {
    Route::get('autocomplete', [BibleDataController::class, 'autocomplete'])->name('api.bible.autocomplete');
    Route::get('interlinear-segments', [BibleDataController::class, 'interlinearSegments'])->name('api.bible.interlinear_segments');
});

use VertexSolutions\Core\Http\Controllers\Api\V1\BiblePanoramaController;

Route::middleware('api')->prefix('api/v1/bible')->name('api.v1.bible.')->group(function () {
    Route::get('compare', [BibleCompareController::class, 'compare'])->name('compare');
    Route::get('search', [BibleCompareController::class, 'search'])->name('search');
    Route::get('panorama', [BiblePanoramaController::class, 'panorama'])->name('panorama');
    Route::get('find', [BiblePanoramaController::class, 'find'])->name('find');
});
