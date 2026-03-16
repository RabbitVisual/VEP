<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use VertexSolutions\Community\Models\PrayerRequest;
use VertexSolutions\Community\Models\PrayerRequestPray;

class PrayerController extends Controller
{
    public function index(Request $request): View
    {
        $requests = PrayerRequest::with('user')
            ->orderByRaw("CASE WHEN status = 'active' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->paginate(12);

        $userPrayedIds = PrayerRequestPray::where('user_id', $request->user()->id)
            ->whereIn('prayer_request_id', $requests->pluck('id'))
            ->pluck('prayer_request_id')
            ->flip()
            ->toArray();

        return view('community::prayers', [
            'prayerRequests' => $requests,
            'userPrayedIds' => $userPrayedIds,
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:5000',
        ]);

        PrayerRequest::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'status' => PrayerRequest::STATUS_ACTIVE,
        ]);

        return redirect()->route('painel.community.prayers.index')
            ->with('success', 'Pedido de oração publicado. A comunidade pode orar por você.');
    }

    public function pray(Request $request, PrayerRequest $prayerRequest): JsonResponse
    {
        $user = $request->user();
        $exists = PrayerRequestPray::where('user_id', $user->id)
            ->where('prayer_request_id', $prayerRequest->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Você já está orando por este pedido.',
                'prays_count' => $prayerRequest->prays_count,
            ], 409);
        }

        PrayerRequestPray::create([
            'user_id' => $user->id,
            'prayer_request_id' => $prayerRequest->id,
        ]);

        $prayerRequest->increment('prays_count');

        return response()->json([
            'message' => 'Obrigado. Mais um irmão está orando por você.',
            'prays_count' => $prayerRequest->fresh()->prays_count,
        ]);
    }
}
