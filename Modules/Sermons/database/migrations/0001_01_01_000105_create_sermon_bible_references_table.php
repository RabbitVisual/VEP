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
        Schema::create('sermon_bible_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->cascadeOnDelete();

            // Referência bíblica texto
            $table->string('book');
            $table->integer('chapter')->nullable();
            $table->string('verses')->nullable();
            $table->text('reference_text')->nullable();

            // Vinculação com o módulo Bible
            $table->foreignId('bible_version_id')->nullable()->constrained('bible_versions')->nullOnDelete();
            $table->foreignId('book_id')->nullable()->constrained('bible_books')->nullOnDelete();
            $table->foreignId('chapter_id')->nullable()->constrained('bible_chapters')->nullOnDelete();

            // Contexto e Notas
            $table->enum('type', ['main', 'support', 'illustration', 'other'])->default('main');
            $table->text('context')->nullable();
            $table->text('exegesis_notes')->nullable();
            $table->integer('order')->default(0);

            $table->timestamps();

            $table->index(['sermon_id', 'type', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermon_bible_references');
    }
};
