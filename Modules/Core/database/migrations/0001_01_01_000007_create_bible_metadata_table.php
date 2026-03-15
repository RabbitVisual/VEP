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
        Schema::create('bible_metadata', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bible_version_id')->constrained('bible_versions')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('bible_books')->onDelete('cascade');
            $table->integer('chapter_number');
            $table->integer('verse_count')->default(0);
            $table->timestamps();

            $table->unique(['bible_version_id', 'book_id', 'chapter_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_metadata');
    }
};
