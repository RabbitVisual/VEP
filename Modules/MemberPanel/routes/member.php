<?php
/**
 * Vertex Hub – Rotas unificadas do Painel do Membro sob /painel.
 * Autor: Vertex Solutions LTDA. | vs1.0.0
 */

use Illuminate\Support\Facades\Route;
use Modules\Bible\App\Http\Controllers\InterlinearController as CoreInterlinearController;
use VertexSolutions\Core\Http\Controllers\MemberPanel\BibleController as CoreBibleController;
use VertexSolutions\Core\Http\Controllers\MemberPanel\FavoriteController as CoreFavoriteController;
use VertexSolutions\Core\Http\Controllers\MemberPanel\PlanReaderController as CorePlanReaderController;
use VertexSolutions\Core\Http\Controllers\MemberPanel\ReadingPlanController as CoreReadingPlanController;
use VertexSolutions\MemberPanel\Http\Controllers\DashboardController;
use VertexSolutions\MemberPanel\Http\Controllers\ProfileController;
use VertexSolutions\MemberPanel\Http\Controllers\VerseExplainerController;
use VertexSolutions\Ministry\Http\Controllers\MemberPanel\MinistryDashboardController;
use VertexSolutions\Sermons\Http\Controllers\MemberPanel\BibleCommentaryController as SermonsBibleCommentaryController;
use VertexSolutions\Sermons\Http\Controllers\MemberPanel\BibleSeriesController as SermonsBibleSeriesController;
use VertexSolutions\Sermons\Http\Controllers\MemberPanel\BibleStudyController as SermonsBibleStudyController;
use VertexSolutions\Sermons\Http\Controllers\MemberPanel\SermonConsultantController;
use VertexSolutions\Sermons\Http\Controllers\MemberPanel\SermonController as SermonsSermonController;
use VertexSolutions\Community\Http\Controllers\FeedController as CommunityFeedController;
use VertexSolutions\Community\Http\Controllers\PrayerController as CommunityPrayerController;
use VertexSolutions\Community\Http\Controllers\PublicProfileController as CommunityPublicProfileController;
use VertexSolutions\Community\Http\Controllers\FollowController as CommunityFollowController;
use VertexSolutions\Community\Http\Controllers\PostController as CommunityPostController;
use VertexSolutions\Community\Http\Controllers\PostLikeController as CommunityPostLikeController;
use VertexSolutions\Community\Http\Controllers\PostCommentController as CommunityPostCommentController;

Route::middleware(['web', 'auth', 'verified'])
    ->prefix('painel')
    ->name('painel.')
    ->group(function () {
        // Dashboard central
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Perfil (dados editáveis; dados sensíveis apenas via solicitação)
        Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('profile/request-sensitive-change', [ProfileController::class, 'requestSensitiveChange'])->name('profile.request-sensitive-change');

        // Verse Explainer (IA)
        Route::get('explainer', [VerseExplainerController::class, 'index'])->name('verse-explainer');
        Route::post('explainer/explain', [VerseExplainerController::class, 'explain'])->name('verse-explainer.explain');

        // Bíblia: read, book, chapter, search, favorites, interlinear (Core)
        Route::prefix('bible')->name('bible.')->group(function () {
            Route::get('/', [CoreBibleController::class, 'index'])->name('index');
            Route::get('read/{versionAbbr?}', [CoreBibleController::class, 'read'])->name('read');
            Route::get('book/{versionAbbr}/{bookNumber}', [CoreBibleController::class, 'showBook'])->name('book');
            Route::get('chapter/{versionAbbr}/{bookNumber}/{chapterNumber}', [CoreBibleController::class, 'showChapter'])->name('chapter');
            Route::get('search', [CoreBibleController::class, 'search'])->name('search');
            Route::get('api/find', [CoreBibleController::class, 'performSearch'])->name('api.search');
            Route::get('favorites', [CoreBibleController::class, 'favorites'])->name('favorites');
            Route::get('verse/{verse}', [CoreBibleController::class, 'verse'])->name('verse');
            Route::get('interlinear', [CoreInterlinearController::class, 'index'])->name('interlinear');
            Route::get('interlinear/books', [CoreInterlinearController::class, 'getBooksMetadata'])->name('interlinear.books');
            Route::get('interlinear/data', [CoreInterlinearController::class, 'getData'])->name('interlinear.data');
            Route::get('strong/{number}', [CoreInterlinearController::class, 'getStrongDefinition'])->name('strong');
        });

        Route::prefix('bible/favorites')->name('bible.favorites.')->group(function () {
            Route::post('batch', [CoreFavoriteController::class, 'batchUpdate'])->name('batch');
            Route::post('{id}', [CoreFavoriteController::class, 'toggle'])->name('toggle');
            Route::delete('{id}', [CoreFavoriteController::class, 'destroy'])->name('destroy');
        });

        // Planos de leitura (Core)
        Route::prefix('bible/plans')->name('bible.')->group(function () {
            Route::get('/', [CoreReadingPlanController::class, 'index'])->name('plans.index');
            Route::get('catalog', [CoreReadingPlanController::class, 'catalog'])->name('plans.catalog');
            Route::get('{id}/preview', [CoreReadingPlanController::class, 'preview'])->name('plans.preview');
            Route::post('{id}/subscribe', [CoreReadingPlanController::class, 'subscribe'])->name('plans.subscribe');
            Route::get('resume/{id}', [CoreReadingPlanController::class, 'show'])->name('plans.show');
            Route::post('{subscriptionId}/recalculate', [CoreReadingPlanController::class, 'recalculate'])->name('plans.recalculate');
            Route::get('download/{id}/pdf', [CoreReadingPlanController::class, 'downloadPdf'])->name('plans.pdf');

            Route::get('read/{subscriptionId}/{day}', [CorePlanReaderController::class, 'read'])->name('reader');
            Route::post('read/{subscriptionId}/{dayId}/complete', [CorePlanReaderController::class, 'complete'])->name('reader.complete');
            Route::post('read/{subscriptionId}/{dayId}/uncomplete', [CorePlanReaderController::class, 'uncomplete'])->name('reader.uncomplete');
            Route::get('read/{subscriptionId}/{dayId}/congratulations', [CorePlanReaderController::class, 'congratulations'])->name('reader.congratulations');
            Route::post('read/{subscriptionId}/{dayId}/note', [CorePlanReaderController::class, 'storeNote'])->name('reader.note.store');
        });

        // Sermões (Sermons)
        Route::post('sermons/consultant', [SermonConsultantController::class, 'chat'])->name('sermons.consultant');
        Route::prefix('sermons')->name('sermons.')->group(function () {
            Route::get('/', [SermonsSermonController::class, 'index'])->name('index');
            Route::get('my/sermons', [SermonsSermonController::class, 'mySermons'])->name('my-sermons');
            Route::get('my/favorites', [SermonsSermonController::class, 'myFavorites'])->name('my-favorites');
            Route::get('create', [SermonsSermonController::class, 'create'])->name('create');
            Route::post('/', [SermonsSermonController::class, 'store'])->name('store');
            Route::get('invite/{collaborator}', [SermonsSermonController::class, 'showCollaboratorInvite'])->name('collaborator.invite');
            Route::post('invite/{collaborator}/respond', [SermonsSermonController::class, 'respondCollaborator'])->name('collaborator.respond');
            Route::get('{sermon}', [SermonsSermonController::class, 'show'])->name('show');
            Route::get('{sermon}/edit', [SermonsSermonController::class, 'edit'])->name('edit');
            Route::put('{sermon}', [SermonsSermonController::class, 'update'])->name('update');
            Route::delete('{sermon}', [SermonsSermonController::class, 'destroy'])->name('destroy');
            Route::post('{sermon}/toggle-favorite', [SermonsSermonController::class, 'toggleFavorite'])->name('toggle-favorite');
            Route::post('{sermon}/comments', [SermonsSermonController::class, 'storeComment'])->name('store-comment');
            Route::get('{sermon}/export-pdf', [SermonsSermonController::class, 'exportPdf'])->name('export-pdf');
        });

        Route::get('series', [SermonsBibleSeriesController::class, 'index'])->name('series.index');
        Route::get('series/{series}', [SermonsBibleSeriesController::class, 'show'])->name('series.show');
        Route::get('studies', [SermonsBibleStudyController::class, 'index'])->name('studies.index');
        Route::get('studies/{study}', [SermonsBibleStudyController::class, 'show'])->name('studies.show');
        Route::get('commentaries', [SermonsBibleCommentaryController::class, 'index'])->name('commentaries.index');
        Route::get('commentaries/{commentary}', [SermonsBibleCommentaryController::class, 'show'])->name('commentaries.show');

        // Ministérios (Ministry)
        Route::prefix('ministries')->name('ministries.')->group(function () {
            Route::get('{ministry}/dashboard', [MinistryDashboardController::class, 'dashboard'])->name('dashboard');
        });

        // Community Hub (Feed, Mural de Intercessão, Perfil público)
        Route::prefix('community')->name('community.')->group(function () {
            Route::get('feed', [CommunityFeedController::class, 'index'])->name('feed.index');
            Route::post('posts', [CommunityPostController::class, 'store'])->name('posts.store');
            Route::post('posts/{post}/like', [CommunityPostLikeController::class, 'toggle'])->name('posts.like');
            Route::post('posts/{post}/comments', [CommunityPostCommentController::class, 'store'])->name('posts.comments.store');
            Route::get('prayers', [CommunityPrayerController::class, 'index'])->name('prayers.index');
            Route::post('prayers', [CommunityPrayerController::class, 'store'])->name('prayers.store');
            Route::post('prayers/{prayerRequest}/pray', [CommunityPrayerController::class, 'pray'])->name('prayers.pray');
            Route::get('perfil/{user}', [CommunityPublicProfileController::class, 'show'])->name('profile.show');
            Route::post('perfil/{user}/follow', [CommunityFollowController::class, 'toggle'])->name('follow.toggle');
        });
    });
