<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Cria worship_songs se não existir (para quando o módulo Worship não está instalado).
     */
    public function up(): void
    {
        if (Schema::hasTable('worship_songs')) {
            return;
        }
        Schema::create('worship_songs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('artist')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('worship_songs');
    }
};
