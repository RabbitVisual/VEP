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
        Schema::create('bible_commentaries', function (Blueprint $table) {
            $table->id();

            // Reference
            $table->string('book');
            $table->integer('chapter');
            $table->integer('verse_start');
            $table->integer('verse_end')->nullable();

            // Content
            $table->string('title')->nullable();
            $table->longText('content');
            $table->string('audio_path')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('cover_image')->nullable();

            // Relationships
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Status
            $table->enum('status', ['draft', 'published'])->default('published');
            $table->boolean('is_official')->default(false);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['book', 'chapter', 'verse_start']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_commentaries');
    }
};
