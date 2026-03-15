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
        Schema::create('bible_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bible_version_id')->constrained('bible_versions')->onDelete('cascade');
            $table->string('name'); // Nome do livro (ex: "Gênesis")
            $table->integer('book_number'); // Número do livro (1-66)
            $table->string('abbreviation')->nullable(); // Abreviação (ex: "Gn")
            $table->string('testament')->default('old'); // old ou new
            $table->integer('total_chapters')->default(0);
            $table->integer('total_verses')->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['bible_version_id', 'book_number']);
            $table->index(['bible_version_id', 'testament']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_books');
    }
};
