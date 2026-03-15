<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bible_studies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();

            // Content
            $table->longText('content');
            $table->string('cover_image')->nullable();
            $table->string('video_url')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('audio_file')->nullable();

            // Relationships
            $table->foreignId('series_id')->nullable()->constrained('bible_series')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('sermon_categories')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('book_id')->nullable()->constrained('bible_books')->nullOnDelete();
            $table->foreignId('chapter_id')->nullable()->constrained('bible_chapters')->nullOnDelete();

            // Status
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'members', 'private'])->default('members');
            $table->boolean('is_featured')->default(false);

            // Metadata
            $table->integer('views')->default(0);
            $table->timestamp('published_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'visibility', 'series_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_studies');
    }
};
