<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE bible_strongs MODIFY lemma_br TEXT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE bible_strongs MODIFY lemma_br VARCHAR(255) NULL');
    }
};
