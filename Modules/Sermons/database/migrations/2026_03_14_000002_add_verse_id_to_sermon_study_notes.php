<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sermon_study_notes', function (Blueprint $table) {
            $table->foreignId('verse_id')->nullable()->after('chapter_id')->constrained('bible_verses')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('sermon_study_notes', function (Blueprint $table) {
            $table->dropForeign(['verse_id']);
        });
    }
};
