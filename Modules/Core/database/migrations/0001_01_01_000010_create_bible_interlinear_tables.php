<?php
/**
 * Bíblia Interlinear (nível NEPE): Strong expandido, versos e segmentos hebraico/grego.
 * Suporta hebrew_tagged.json, strongs.json, GRC-Κοινη/trparsed.json (e tr.json).
 *
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Metadados do léxico Strong (ex.: strongs.json "metadados")
        if (! Schema::hasTable('bible_interlinear_lexicon_metadata')) {
            Schema::create('bible_interlinear_lexicon_metadata', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('title')->nullable();
            $table->string('version', 32)->nullable();
            $table->string('year', 20)->nullable();
            $table->string('author')->nullable();
            $table->string('license', 500)->nullable();
            $table->text('note')->nullable();
            $table->json('extra')->nullable();
            $table->timestamps();
        });
        }

        // Colunas extras em bible_strongs (Concordância Strong estilo NEPE: Gematria, DITAT/TWOT, lemma_br)
        Schema::table('bible_strongs', function (Blueprint $table) {
            if (! Schema::hasColumn('bible_strongs', 'lemma_br')) {
                $table->text('lemma_br')->nullable()->after('lemma');
            }
            if (! Schema::hasColumn('bible_strongs', 'part_of_speech')) {
                $table->string('part_of_speech', 64)->nullable()->after('pronunciation');
            }
            if (! Schema::hasColumn('bible_strongs', 'twot_ref')) {
                $table->string('twot_ref', 32)->nullable()->after('description');
            }
            if (! Schema::hasColumn('bible_strongs', 'ditat_ref')) {
                $table->string('ditat_ref', 64)->nullable()->after('twot_ref');
            }
            if (! Schema::hasColumn('bible_strongs', 'gematria_hechrachi')) {
                $table->unsignedInteger('gematria_hechrachi')->nullable()->after('language');
            }
            if (! Schema::hasColumn('bible_strongs', 'gematria_gadol')) {
                $table->unsignedInteger('gematria_gadol')->nullable()->after('gematria_hechrachi');
            }
            if (! Schema::hasColumn('bible_strongs', 'gematria_siduri')) {
                $table->unsignedInteger('gematria_siduri')->nullable()->after('gematria_gadol');
            }
            if (! Schema::hasColumn('bible_strongs', 'gematria_katan')) {
                $table->unsignedInteger('gematria_katan')->nullable()->after('gematria_siduri');
            }
            if (! Schema::hasColumn('bible_strongs', 'gematria_perati')) {
                $table->unsignedBigInteger('gematria_perati')->nullable()->after('gematria_katan');
            }
            if (! Schema::hasColumn('bible_strongs', 'lexicon_metadata_id')) {
                $table->foreignId('lexicon_metadata_id')->nullable()->after('gematria_perati')
                    ->constrained('bible_interlinear_lexicon_metadata')->nullOnDelete();
            }
        });

        // Definições hierárquicas do Strong (ex.: 1. 1.1 1.2 como no NEPE)
        if (! Schema::hasTable('bible_strong_definitions')) {
            Schema::create('bible_strong_definitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bible_strong_id')->constrained('bible_strongs')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('bible_strong_definitions')->onDelete('cascade');
            $table->unsignedTinyInteger('level')->default(1);
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('definition_text');
            $table->timestamps();

            $table->index(['bible_strong_id', 'sort_order']);
        });
        }

        // Fonte do texto interlinear (hebrew_tagged, grc_trparsed, etc.)
        if (! Schema::hasTable('bible_interlinear_sources')) {
            Schema::create('bible_interlinear_sources', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('name');
            $table->string('language', 16)->index();
            $table->string('testament', 8)->index(); // old | new
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
        }

        // Verso interlinear (referência livro/capítulo/versículo + fonte)
        if (! Schema::hasTable('bible_interlinear_verses')) {
            Schema::create('bible_interlinear_verses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interlinear_source_id')->constrained('bible_interlinear_sources')->onDelete('cascade');
            $table->unsignedSmallInteger('book_number'); // 1-66
            $table->unsignedSmallInteger('chapter_number');
            $table->unsignedSmallInteger('verse_number');
            $table->text('raw_text')->nullable(); // texto bruto do verso (opcional)
            $table->timestamps();

            $table->unique(['interlinear_source_id', 'book_number', 'chapter_number', 'verse_number'], 'interlinear_verse_ref_unique');
            $table->index(['interlinear_source_id', 'book_number', 'chapter_number'], 'interlinear_verse_source_book_ch_idx');
        });
        }

        // Segmento (palavra) do verso interlinear: palavra original + Strong + morfologia
        if (! Schema::hasTable('bible_interlinear_segments')) {
            Schema::create('bible_interlinear_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('interlinear_verse_id')->constrained('bible_interlinear_verses')->onDelete('cascade');
            $table->unsignedInteger('position')->default(0); // ordem na frase
            $table->string('word_original', 255); // palavra no hebraico/grego
            $table->foreignId('strong_id')->nullable()->constrained('bible_strongs')->nullOnDelete();
            $table->string('morph_tag', 64)->nullable(); // ex.: N-NSF, HVqp3ms, HR/Ncfsa
            $table->string('compound_prefix', 32)->nullable(); // ex.: Hb, Hd, Hc (hebraico composto)
            $table->timestamps();

            $table->index(['interlinear_verse_id', 'position']);
        });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('bible_interlinear_segments');
        Schema::dropIfExists('bible_interlinear_verses');
        Schema::dropIfExists('bible_interlinear_sources');
        Schema::dropIfExists('bible_strong_definitions');

        Schema::table('bible_strongs', function (Blueprint $table) {
            $table->dropForeign(['lexicon_metadata_id']);
            $table->dropColumn([
                'lemma_br', 'part_of_speech', 'twot_ref', 'ditat_ref',
                'gematria_hechrachi', 'gematria_gadol', 'gematria_siduri',
                'gematria_katan', 'gematria_perati', 'lexicon_metadata_id',
            ]);
        });

        Schema::dropIfExists('bible_interlinear_lexicon_metadata');
    }
};
