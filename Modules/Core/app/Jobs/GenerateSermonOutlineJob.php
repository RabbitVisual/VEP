<?php

declare(strict_types=1);

namespace VertexSolutions\Core\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use VertexSolutions\Core\Services\AIService;

class GenerateSermonOutlineJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $timeout = 120;

    public function __construct(
        public readonly string $theme,
        public readonly array $references,
        public readonly ?int $userId = null,
        public readonly ?string $requestId = null,
    ) {
        $this->onQueue(config('queue.queues.ai', 'default'));
    }

    public function handle(AIService $aiService): void
    {
        $outline = $aiService->generateSermonOutline($this->theme, $this->references);

        $cacheKey = $this->requestId
            ? 'ai_outline_' . $this->requestId
            : 'ai_outline_' . ($this->userId ?? 'guest') . '_' . md5($this->theme . json_encode($this->references));
        $ttl = (int) config('core.ai.cache.exegesis_ttl', 3600);
        Cache::put($cacheKey, $outline, $ttl);

        if ($this->userId && class_exists(\Illuminate\Support\Facades\Notification::class)) {
            try {
                $user = \App\Models\User::find($this->userId);
                if ($user) {
                    $this->notifyUser($user, $outline, $cacheKey);
                }
            } catch (\Throwable $e) {
                Log::warning('GenerateSermonOutlineJob: could not notify user', ['user_id' => $this->userId, 'error' => $e->getMessage()]);
            }
        }
    }

    private function notifyUser(\App\Models\User $user, array $outline, string $cacheKey): void
    {
        if (! class_exists(\VertexSolutions\Notifications\App\Notifications\SystemNotification::class)) {
            return;
        }
        \Illuminate\Support\Facades\Notification::send(
            $user,
            new \VertexSolutions\Notifications\App\Notifications\SystemNotification(
                'Esboço de sermão pronto',
                'O esboço para "' . $this->theme . '" foi gerado. ' . ($outline['title'] ?? ''),
                url()->route('home'),
                'info',
                'sparkles'
            )
        );
    }
}
