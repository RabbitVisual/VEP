<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

use Illuminate\Support\Facades\Route;
use VertexSolutions\PastoralPanel\Http\Controllers\DashboardController;
use VertexSolutions\PastoralPanel\Http\Controllers\ExegesisAssistantController;
use VertexSolutions\PastoralPanel\Http\Controllers\MemberManagementController;
use VertexSolutions\PastoralPanel\Http\Controllers\ProfileController;
use VertexSolutions\PastoralPanel\Http\Controllers\PastoralPanelController;
use VertexSolutions\Sermons\Http\Controllers\Pastoral\BibleCommentaryController as PastoralBibleCommentaryController;
use VertexSolutions\Sermons\Http\Controllers\Pastoral\BibleSeriesController as PastoralBibleSeriesController;
use VertexSolutions\Sermons\Http\Controllers\Pastoral\BibleStudyController as PastoralBibleStudyController;
use VertexSolutions\Sermons\Http\Controllers\Pastoral\CategoryController as PastoralCategoryController;
use VertexSolutions\Sermons\Http\Controllers\Pastoral\SermonController as PastoralSermonController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('pastoralpanels', PastoralPanelController::class)->names('pastoralpanel');

    Route::prefix('pastoral')->name('pastoral.')->group(function () {
        Route::get('', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('exegesis-assistant', [ExegesisAssistantController::class, 'index'])->name('exegesis-assistant');
        Route::get('exegesis-assistant/chapters', [ExegesisAssistantController::class, 'chapters'])->name('exegesis-assistant.chapters');
        Route::get('exegesis-assistant/verses', [ExegesisAssistantController::class, 'verses'])->name('exegesis-assistant.verses');
        Route::get('exegesis-assistant/interlinear-verse', [ExegesisAssistantController::class, 'interlinearVerse'])->name('exegesis-assistant.interlinear-verse');
        Route::get('exegesis-assistant/interlinear-data', [ExegesisAssistantController::class, 'interlinearData'])->name('exegesis-assistant.interlinear-data');
        Route::post('exegesis-assistant/chat', [ExegesisAssistantController::class, 'chat'])->name('exegesis-assistant.chat')->middleware('throttle:exegesis');

        Route::get('members', [MemberManagementController::class, 'index'])->name('members.index');
        Route::get('members/{user}', [MemberManagementController::class, 'show'])->name('members.show');
        Route::post('members/{user}/notes', [MemberManagementController::class, 'storeNote'])->name('members.notes.store');

        Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('profile/request-sensitive-change', [ProfileController::class, 'requestSensitiveChange'])->name('profile.request-sensitive-change');
    });

    // Sermon Studio (proxy to Sermons Pastoral controllers) – route names match Sermons views (pastor.sermoes.*)
    Route::prefix('pastoral/sermons')->name('pastor.sermoes.sermons.')->group(function () {
        Route::get('/', [PastoralSermonController::class, 'index'])->name('index');
        Route::get('create', [PastoralSermonController::class, 'create'])->name('create');
        Route::post('/', [PastoralSermonController::class, 'store'])->name('store');
        Route::get('{sermon}', [PastoralSermonController::class, 'show'])->name('show');
        Route::get('{sermon}/edit', [PastoralSermonController::class, 'edit'])->name('edit');
        Route::put('{sermon}', [PastoralSermonController::class, 'update'])->name('update');
        Route::delete('{sermon}', [PastoralSermonController::class, 'destroy'])->name('destroy');
        Route::get('{sermon}/export-pdf', [PastoralSermonController::class, 'exportPdf'])->name('export-pdf');
        Route::post('{sermon}/collaborators/invite', [PastoralSermonController::class, 'inviteCollaborator'])->name('collaborators.invite');
    });

    Route::prefix('pastoral/sermons/categories')->name('pastor.sermoes.categories.')->group(function () {
        Route::get('/', [PastoralCategoryController::class, 'index'])->name('index');
        Route::get('create', [PastoralCategoryController::class, 'create'])->name('create');
        Route::post('/', [PastoralCategoryController::class, 'store'])->name('store');
        Route::get('{category}/edit', [PastoralCategoryController::class, 'edit'])->name('edit');
        Route::put('{category}', [PastoralCategoryController::class, 'update'])->name('update');
        Route::delete('{category}', [PastoralCategoryController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pastoral/sermons/series')->name('pastor.sermoes.series.')->group(function () {
        Route::get('/', [PastoralBibleSeriesController::class, 'index'])->name('index');
        Route::get('create', [PastoralBibleSeriesController::class, 'create'])->name('create');
        Route::post('/', [PastoralBibleSeriesController::class, 'store'])->name('store');
        Route::get('{series}/edit', [PastoralBibleSeriesController::class, 'edit'])->name('edit');
        Route::put('{series}', [PastoralBibleSeriesController::class, 'update'])->name('update');
        Route::delete('{series}', [PastoralBibleSeriesController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pastoral/sermons/studies')->name('pastor.sermoes.studies.')->group(function () {
        Route::get('/', [PastoralBibleStudyController::class, 'index'])->name('index');
        Route::get('create', [PastoralBibleStudyController::class, 'create'])->name('create');
        Route::post('/', [PastoralBibleStudyController::class, 'store'])->name('store');
        Route::get('{study}/edit', [PastoralBibleStudyController::class, 'edit'])->name('edit');
        Route::put('{study}', [PastoralBibleStudyController::class, 'update'])->name('update');
        Route::delete('{study}', [PastoralBibleStudyController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('pastoral/sermons/commentaries')->name('pastor.sermoes.commentaries.')->group(function () {
        Route::get('/', [PastoralBibleCommentaryController::class, 'index'])->name('index');
        Route::get('create', [PastoralBibleCommentaryController::class, 'create'])->name('create');
        Route::post('/', [PastoralBibleCommentaryController::class, 'store'])->name('store');
        Route::get('{commentary}/edit', [PastoralBibleCommentaryController::class, 'edit'])->name('edit');
        Route::put('{commentary}', [PastoralBibleCommentaryController::class, 'update'])->name('update');
        Route::delete('{commentary}', [PastoralBibleCommentaryController::class, 'destroy'])->name('destroy');
    });
});
