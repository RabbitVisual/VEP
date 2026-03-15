<?php

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
        // Tabela para o Dicionário Strong
        Schema::create('bible_strongs', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // Ex: H7225, G1
            $table->string('lemma')->nullable();
            $table->string('transliteration')->nullable();
            $table->string('pronunciation')->nullable();
            $table->text('description')->nullable();
            $table->char('language', 1)->index(); // 'H' para Hebraico, 'G' para Grego
            $table->timestamps();
        });

        // Tabela Pivot para linkar Versículos aos números Strong
        Schema::create('bible_verse_strongs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('verse_id')->constrained('bible_verses')->onDelete('cascade');
            $table->foreignId('strong_id')->constrained('bible_strongs')->onDelete('cascade');

            $table->index(['verse_id', 'strong_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bible_verse_strongs');
        Schema::dropIfExists('bible_strongs');
    }
};
