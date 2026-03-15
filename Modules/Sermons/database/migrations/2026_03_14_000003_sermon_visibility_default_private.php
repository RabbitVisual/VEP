<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE sermons MODIFY COLUMN visibility ENUM('public', 'members', 'private') NOT NULL DEFAULT 'private'");
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE sermons MODIFY COLUMN visibility ENUM('public', 'members', 'private') NOT NULL DEFAULT 'members'");
        }
    }
};
