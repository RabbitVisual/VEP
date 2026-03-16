<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->morphs('reactable');
            $table->string('type', 32); // like, amen, praying
            $table->timestamps();

            $table->unique(['user_id', 'reactable_type', 'reactable_id', 'type'], 'reactions_user_reactable_type_unique');
            $table->index(['reactable_type', 'reactable_id'], 'reactions_reactable_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reactions');
    }
};

