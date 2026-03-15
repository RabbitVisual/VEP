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
        Schema::create('bible_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('bible_books')->onDelete('cascade');
            $table->integer('chapter_number');
            $table->integer('total_verses')->default(0);
            $table->timestamps();

            $table->unique(['book_id', 'chapter_number']);
            $table->index('book_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_chapters');
    }
};
