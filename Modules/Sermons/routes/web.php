<?php

use Illuminate\Support\Facades\Route;
use VertexSolutions\Sermons\Http\Controllers\AISermonSuggestController;
use VertexSolutions\Sermons\Http\Controllers\MemberPanel\SermonConsultantController;

/*
|--------------------------------------------------------------------------
| Web Routes (Sermons) – apenas rotas globais; painel em routes/member.php
|--------------------------------------------------------------------------
*/

// AI suggest (usado no studio pastoral/admin)
Route::middleware(['web', 'auth'])->group(function () {
    Route::post('sermons/{sermon}/ai-suggest-tags-refs', [AISermonSuggestController::class, 'suggest'])
        ->name('sermons.ai.suggest');
});

// Consultant chat (usado por views do módulo; painel usa painel.sermons.consultant)
Route::middleware(['web', 'auth', 'verified'])->group(function () {
    Route::post('memberpanel/sermons/consultant', [SermonConsultantController::class, 'chat'])
        ->name('memberpanel.sermons.consultant');
});
