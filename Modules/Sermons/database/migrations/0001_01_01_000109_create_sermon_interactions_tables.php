<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

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
        // Favoritos
        Schema::create('sermon_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'sermon_id']);
        });

        // Comentários
        Schema::create('sermon_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('sermon_comments')->onDelete('cascade');
            $table->text('content');
            $table->boolean('is_private')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        // Colaboradores
        Schema::create('sermon_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sermon_id')->constrained('sermons')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['editor', 'viewer', 'approver'])->default('editor');
            $table->boolean('has_accepted')->default(false);
            $table->timestamps();

            $table->unique(['sermon_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermon_collaborators');
        Schema::dropIfExists('sermon_comments');
        Schema::dropIfExists('sermon_favorites');
    }
};
