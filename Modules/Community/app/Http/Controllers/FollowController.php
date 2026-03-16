<?php

declare(strict_types=1);

namespace VertexSolutions\Community\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use VertexSolutions\Community\Models\UserFollow;

class FollowController extends Controller
{
    public function toggle(Request $request, User $user): RedirectResponse
    {
        $follower = $request->user();
        if ($follower->id === $user->id) {
            return redirect()->back()->with('error', 'Você não pode seguir a si mesmo.');
        }

        $action = $request->input('action', 'follow');
        $follow = UserFollow::where('follower_id', $follower->id)->where('following_id', $user->id)->first();

        if ($action === 'unfollow' && $follow) {
            $follow->delete();
            return redirect()->back()->with('success', 'Você deixou de seguir este perfil.');
        }

        if ($action === 'follow' && ! $follow) {
            UserFollow::create([
                'follower_id' => $follower->id,
                'following_id' => $user->id,
            ]);
            return redirect()->back()->with('success', 'Você está seguindo este perfil.');
        }

        return redirect()->back();
    }
}
