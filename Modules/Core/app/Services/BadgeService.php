<?php
/**
 * Autor - Reinan Rodrigues
 * Empresa - Vertex Solutions LTDA.
 * Versão - vs1.0.0
 */

declare(strict_types=1);

namespace VertexSolutions\Core\Services;

use Carbon\Carbon;
use App\Models\User;
use VertexSolutions\Core\Models\BiblePlanDay;
use VertexSolutions\Core\Models\BiblePlanSubscription;
use VertexSolutions\Core\Models\BibleUserBadge;
use VertexSolutions\Core\Models\UserReadingLog;

/**
 * CBAV2026: Awards badges based on user_reading_logs after each successful complete().
 */
final class BadgeService
{
    private const CONSECUTIVE_DAYS_BEREANO = 7;

    private const TOTAL_DAYS_FIEL_PACTO = 30;

    private const TOTAL_DAYS_LEITOR_CORPO = 15;

    private const BEREANO_COOLDOWN_DAYS = 7;

    public function evaluateAfterCompletion(BiblePlanSubscription $subscription, BiblePlanDay $day): void
    {
        $subscription->load('plan');
        $this->maybeAwardBereanoDaSemana($subscription, (int) $day->day_number);
        $this->maybeAwardFielAoPacto($subscription);
        $this->maybeAwardLeitorDoCorpo($subscription);
    }

    private function maybeAwardBereanoDaSemana(BiblePlanSubscription $subscription, int $justCompletedDayNumber): void
    {
        $firstInWindow = $justCompletedDayNumber - self::CONSECUTIVE_DAYS_BEREANO + 1;
        if ($firstInWindow < 1) {
            return;
        }
        $dayNumbers = range($firstInWindow, $justCompletedDayNumber);
        $logs = UserReadingLog::where('subscription_id', $subscription->id)
            ->whereIn('day_number', $dayNumbers)
            ->orderBy('day_number')
            ->get();
        if ($logs->count() !== self::CONSECUTIVE_DAYS_BEREANO) {
            return;
        }
        $startDate = Carbon::parse($subscription->start_date)->startOfDay();
        $allOnTime = true;
        foreach ($dayNumbers as $dayNum) {
            $log = $logs->firstWhere('day_number', $dayNum);
            if (! $log) {
                $allOnTime = false;
                break;
            }
            $expectedDate = $startDate->copy()->addDays($dayNum - 1);
            $completedDate = Carbon::parse($log->completed_at)->startOfDay();
            if ($completedDate->gt($expectedDate)) {
                $allOnTime = false;
                break;
            }
        }
        if (! $allOnTime) {
            return;
        }
        $recentBadge = BibleUserBadge::where('subscription_id', $subscription->id)
            ->where('badge_key', BibleUserBadge::BADGE_BEREANO_SEMANA)
            ->where('awarded_at', '>=', now()->subDays(self::BEREANO_COOLDOWN_DAYS))
            ->exists();
        if ($recentBadge) {
            return;
        }
        BibleUserBadge::create([
            'user_id' => $subscription->user_id,
            'badge_key' => BibleUserBadge::BADGE_BEREANO_SEMANA,
            'subscription_id' => $subscription->id,
            'awarded_at' => now(),
        ]);
    }

    private function maybeAwardFielAoPacto(BiblePlanSubscription $subscription): void
    {
        $count = UserReadingLog::where('subscription_id', $subscription->id)->count();
        if ($count < self::TOTAL_DAYS_FIEL_PACTO) {
            return;
        }
        $exists = BibleUserBadge::where('subscription_id', $subscription->id)
            ->where('badge_key', BibleUserBadge::BADGE_FIEL_AO_PACTO)
            ->exists();
        if ($exists) {
            return;
        }
        BibleUserBadge::create([
            'user_id' => $subscription->user_id,
            'badge_key' => BibleUserBadge::BADGE_FIEL_AO_PACTO,
            'subscription_id' => $subscription->id,
            'awarded_at' => now(),
        ]);
    }

    private function maybeAwardLeitorDoCorpo(BiblePlanSubscription $subscription): void
    {
        if (! $subscription->plan->is_church_plan) {
            return;
        }
        $count = UserReadingLog::where('subscription_id', $subscription->id)->count();
        if ($count < self::TOTAL_DAYS_LEITOR_CORPO) {
            return;
        }
        $exists = BibleUserBadge::where('subscription_id', $subscription->id)
            ->where('badge_key', BibleUserBadge::BADGE_LEITOR_DO_CORPO)
            ->exists();
        if ($exists) {
            return;
        }
        BibleUserBadge::create([
            'user_id' => $subscription->user_id,
            'badge_key' => BibleUserBadge::BADGE_LEITOR_DO_CORPO,
            'subscription_id' => $subscription->id,
            'awarded_at' => now(),
        ]);
    }

    public function awardTheologianBadge(User $user): void
    {
        $exists = BibleUserBadge::where('user_id', $user->id)
            ->where('badge_key', BibleUserBadge::BADGE_TEOLOGO)
            ->exists();
        if ($exists) {
            return;
        }
        BibleUserBadge::create([
            'user_id' => $user->id,
            'badge_key' => BibleUserBadge::BADGE_TEOLOGO,
            'subscription_id' => null,
            'awarded_at' => now(),
        ]);
    }

    public function awardBereanoForPlanCompletion(BiblePlanSubscription $subscription): void
    {
        $exists = BibleUserBadge::where('subscription_id', $subscription->id)
            ->where('badge_key', BibleUserBadge::BADGE_BEREANO)
            ->exists();
        if ($exists) {
            return;
        }
        BibleUserBadge::create([
            'user_id' => $subscription->user_id,
            'badge_key' => BibleUserBadge::BADGE_BEREANO,
            'subscription_id' => $subscription->id,
            'awarded_at' => now(),
        ]);
    }
}
