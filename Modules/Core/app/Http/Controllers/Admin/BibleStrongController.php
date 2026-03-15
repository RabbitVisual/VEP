<?php
/**
 * Editor de Concordância Strong no painel admin.
 * Permite refinar lemma_br, description, part_of_speech, referências e Gematria (estilo NEPE).
 *
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 */

namespace VertexSolutions\Core\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use VertexSolutions\Core\Models\BibleInterlinearLexiconMetadata;
use VertexSolutions\Core\Models\BibleStrong;

class BibleStrongController extends Controller
{
    public function index(Request $request)
    {
        $query = BibleStrong::query()->with('lexiconMetadata');

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($qry) use ($q) {
                $qry->where('number', 'like', "%{$q}%")
                    ->orWhere('lemma', 'like', "%{$q}%")
                    ->orWhere('lemma_br', 'like', "%{$q}%")
                    ->orWhere('transliteration', 'like', "%{$q}%");
            });
        }

        if ($request->filled('language')) {
            $query->where('language', $request->input('language'));
        }

        $strongs = $query->orderByRaw("CASE WHEN language = 'H' THEN 0 ELSE 1 END")
            ->orderBy('number')
            ->paginate(50)
            ->withQueryString();

        return view('bible::admin.bible.strong-index', compact('strongs'));
    }

    public function edit(BibleStrong $strong)
    {
        $strong->load('lexiconMetadata');
        $lexicons = BibleInterlinearLexiconMetadata::orderBy('slug')->get();

        return view('bible::admin.bible.strong-edit', compact('strong', 'lexicons'));
    }

    public function update(Request $request, BibleStrong $strong)
    {
        $validated = $request->validate([
            'lemma' => 'nullable|string|max:255',
            'lemma_br' => 'nullable|string',
            'transliteration' => 'nullable|string|max:255',
            'pronunciation' => 'nullable|string|max:255',
            'part_of_speech' => 'nullable|string|max:64',
            'description' => 'nullable|string',
            'twot_ref' => 'nullable|string|max:32',
            'ditat_ref' => 'nullable|string|max:64',
            'language' => 'nullable|string|in:H,G',
            'gematria_hechrachi' => 'nullable|integer|min:0',
            'gematria_gadol' => 'nullable|integer|min:0',
            'gematria_siduri' => 'nullable|integer|min:0',
            'gematria_katan' => 'nullable|integer|min:0',
            'gematria_perati' => 'nullable|integer|min:0',
            'lexicon_metadata_id' => 'nullable|exists:bible_interlinear_lexicon_metadata,id',
        ]);

        $strong->update($validated);

        return redirect()
            ->route('admin.bible.strong.edit', $strong)
            ->with('success', 'Strong ' . $strong->number . ' atualizado com sucesso.');
    }
}
