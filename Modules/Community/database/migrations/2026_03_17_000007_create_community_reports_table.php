<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // reporter
            $table->morphs('reportable');
            $table->text('reason')->nullable();
            $table->string('status', 32)->default('pending'); // pending, resolved, dismissed
            $table->timestamp('resolved_at')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'created_at'], 'community_reports_status_created_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_reports');
    }
};

