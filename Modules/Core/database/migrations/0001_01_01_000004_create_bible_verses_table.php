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
        Schema::create('bible_verses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chapter_id')->constrained('bible_chapters')->onDelete('cascade');
            $table->integer('verse_number');
            $table->text('text');
            $table->bigInteger('original_verse_id')->nullable(); // ID original do CSV
            $table->timestamps();

            $table->unique(['chapter_id', 'verse_number']);
            $table->index('chapter_id');

            if (config('database.default') !== 'sqlite') {
                $table->fullText('text'); // Para busca de texto completo
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_verses');
    }
};
