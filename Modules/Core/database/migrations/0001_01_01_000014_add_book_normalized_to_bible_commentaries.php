<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bible_commentaries')) {
            return;
        }

        Schema::table('bible_commentaries', function (Blueprint $table) {
            $table->string('book_normalized', 64)->nullable()->after('book')->index();
        });

        $rows = DB::table('bible_commentaries')->select('id', 'book')->get();
        foreach ($rows as $row) {
            $normalized = Str::lower(Str::ascii(trim((string) $row->book)));
            DB::table('bible_commentaries')->where('id', $row->id)->update(['book_normalized' => $normalized]);
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('bible_commentaries')) {
            return;
        }

        Schema::table('bible_commentaries', function (Blueprint $table) {
            $table->dropColumn('book_normalized');
        });
    }
};
