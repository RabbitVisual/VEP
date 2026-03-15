<?php

namespace VertexSolutions\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SensitiveFieldChangeRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SensitiveFieldChangeRequestController extends Controller
{
    public function index(): View
    {
        $pending = SensitiveFieldChangeRequest::with(['user', 'reviewer'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        $history = SensitiveFieldChangeRequest::with(['user', 'reviewer'])
            ->where('status', '!=', SensitiveFieldChangeRequest::STATUS_PENDING)
            ->orderBy('reviewed_at', 'desc')
            ->limit(50)
            ->get();
        return view('admin::admin.change-requests.index', [
            'pending' => $pending,
            'history' => $history,
        ]);
    }

    public function approve(Request $request, SensitiveFieldChangeRequest $change_request): RedirectResponse
    {
        if ($change_request->status !== SensitiveFieldChangeRequest::STATUS_PENDING) {
            return redirect()->route('admin.change-requests.index')->with('error', 'Solicitação já foi processada.');
        }
        $user = $change_request->user;
        $field = $change_request->field_name;
        $previousValue = $user->{$field};
        $user->{$field} = $change_request->requested_value;
        $user->save();
        $change_request->update([
            'status' => SensitiveFieldChangeRequest::STATUS_APPROVED,
            'previous_value' => $previousValue,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => null,
        ]);
        return redirect()->route('admin.change-requests.index')->with('success', 'Solicitação aprovada e dado atualizado. Registro de alteração mantido (anterior → novo).');
    }

    public function reject(Request $request, SensitiveFieldChangeRequest $change_request): RedirectResponse
    {
        if ($change_request->status !== SensitiveFieldChangeRequest::STATUS_PENDING) {
            return redirect()->route('admin.change-requests.index')->with('error', 'Solicitação já foi processada.');
        }
        $reason = $request->input('rejection_reason', '');
        $change_request->update([
            'status' => SensitiveFieldChangeRequest::STATUS_REJECTED,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'rejection_reason' => $reason,
        ]);
        return redirect()->route('admin.change-requests.index')->with('success', 'Solicitação recusada.');
    }
}

