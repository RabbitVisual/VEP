<?php

namespace VertexSolutions\Core\Http\Controllers\MemberPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use VertexSolutions\Core\Models\BibleFavorite;

class FavoriteController extends Controller
{
    /**
     * Store or update a favorite/highlight for a verse.
     */
    public function toggle(Request $request, $id)
    {
        $user = Auth::user();
        $verseId = (int) $id;
        $exists = BibleFavorite::where('user_id', $user->id)->where('verse_id', $verseId)->exists();

        if ($exists) {
            BibleFavorite::where('user_id', $user->id)->where('verse_id', $verseId)->delete();
            return response()->json(['success' => true, 'removed' => true, 'message' => 'Removido dos favoritos.']);
        }

        $color = $request->input('color', '#e11d48');
        BibleFavorite::create([
            'user_id' => $user->id,
            'verse_id' => $verseId,
            'color' => $color,
        ]);

        return response()->json(['success' => true, 'message' => 'Versículo favoritado!']);
    }

    /**
     * Remove a favorite/highlight.
     */
    public function destroy($id)
    {
        $user = Auth::user();

        BibleFavorite::where('user_id', $user->id)
            ->where('verse_id', $id)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Destaque removido.',
        ]);
    }

    /**
     * Batch update multiple verses.
     */
    public function batchUpdate(Request $request)
    {
        $data = $request->validate([
            'verses' => 'required|array',
            'verses.*' => 'integer',
            'type' => 'required|in:highlight,note',
            'color' => 'nullable|string',
        ]);

        $userId = Auth::id();

        DB::transaction(function () use ($data, $userId) {
            foreach ($data['verses'] as $verseId) {
                $updateData = ['color' => $data['color'] ?? null];
                $fav = BibleFavorite::updateOrCreate(
                    ['user_id' => $userId, 'verse_id' => $verseId],
                    $updateData
                );
                if (empty($fav->color)) {
                    $fav->delete();
                }
            }
        });

        return response()->json(['status' => 'success', 'message' => 'Destaques atualizados com sucesso.']);
    }
}
