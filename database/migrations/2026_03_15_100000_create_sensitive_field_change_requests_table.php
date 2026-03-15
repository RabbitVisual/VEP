<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sensitive_field_change_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('field_name', 64); // cpf, email, phone
            $table->string('requested_value'); // novo valor solicitado
            $table->string('previous_value')->nullable(); // preenchido ao aprovar (valor anterior)
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'field_name']); // 1 solicitação por campo por usuário (para sempre)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sensitive_field_change_requests');
    }
};
