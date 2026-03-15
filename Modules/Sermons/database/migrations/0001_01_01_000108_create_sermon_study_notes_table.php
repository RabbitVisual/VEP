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
        Schema::create('sermon_study_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('sermon_id')->nullable()->constrained('sermons')->nullOnDelete();
            $table->string('reference_text');
            $table->foreignId('book_id')->nullable()->constrained('bible_books')->nullOnDelete();
            $table->foreignId('chapter_id')->nullable()->constrained('bible_chapters')->nullOnDelete();
            $table->text('content');
            $table->boolean('is_global')->default(false);
            $table->timestamps();

            $table->index(['user_id', 'reference_text']);
            $table->index(['sermon_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermon_study_notes');
    }
};
