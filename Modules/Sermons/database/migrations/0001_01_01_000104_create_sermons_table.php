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
        Schema::create('sermons', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->longText('introduction')->nullable();
            $table->longText('development')->nullable();
            $table->longText('conclusion')->nullable();
            $table->longText('application')->nullable();
            $table->longText('full_content')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('attachments')->nullable();

            // Layout/Structure
            $table->string('structure_type')->default('classic'); // classic, modular, exegesis

            // Relationships
            $table->foreignId('category_id')->nullable()->constrained('sermon_categories')->nullOnDelete();
            $table->foreignId('series_id')->nullable()->constrained('bible_series')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('sermons')->nullOnDelete();

            // Status e visibilidade
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->enum('visibility', ['public', 'members', 'private'])->default('members');
            $table->boolean('is_collaborative')->default(false);
            $table->boolean('is_featured')->default(false);

            // Metadados
            $table->integer('views')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('downloads')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('sermon_date')->nullable();
            $table->integer('version')->default(1);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'visibility', 'published_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sermons');
    }
};
