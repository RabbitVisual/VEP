<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayer_request_prays', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('prayer_request_id')->constrained('prayer_requests')->cascadeOnDelete();
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['user_id', 'prayer_request_id']);
            $table->index('prayer_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayer_request_prays');
    }
};
