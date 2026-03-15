<?php

declare(strict_types=1);

namespace VertexSolutions\Core\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;
use OpenAI\Responses\Chat\CreateResponse;
use VertexSolutions\Sermons\Models\SermonCategory;

/**
 * AI Theological Engine – Maestro service.
 * Consumes local Bible data (Strong, Interlinear, Commentaries) and sermon data to generate high-fidelity content.
 */
final class AIService
{
    private const EXEGESIS_CACHE_PREFIX = 'ai_exegesis_';

    public function __construct(
        private readonly ExegesisContextResolver $exegesisResolver,
        private readonly BibleApiService $bibleApi,
    ) {
    }

    /**
     * Get exegesis context for a verse (segments, Strong definitions, commentaries) and optionally cache it.
     *
     * @return array{segments: array, strong_definitions: array, commentaries: array, reference: string, book_number: int, chapter_number: int, verse_number: int}
     */

    /**
     * System prompt base from theological_orientation and optional system_prompt_suffix.
     */
    public function getSystemPromptBase(): string
    {
        $orientation = config('core.ai.theological_orientation', 'pastoral');
        $suffix = (string) config('core.ai.system_prompt_suffix', '');
        $strongNote = 'Strong H = Hebraico, Strong G = Grego. Use essa distinção na análise.';

        $bases = [
            'academic' => 'Você é um assistente de exegese com foco acadêmico: análise lexical, morfologia e fontes secundárias. ' . $strongNote,
            'pastoral' => 'Você é um assistente de exegese com equilíbrio entre profundidade e aplicação prática. ' . $strongNote,
            'devotional' => 'Você é um assistente de exegese com linguagem acessível e aplicação devocional. ' . $strongNote,
        ];

        $base = $bases[$orientation] ?? $bases['pastoral'];

        return $suffix !== '' ? $base . "\n\n" . $suffix : $base;
    }

    /**
     * Get exegesis context for a verse (segments, Strong definitions, commentaries) and optionally cache it.
     *
     * @return array{segments: array, strong_definitions: array, commentaries: array, reference: string, book_number: int, chapter_number: int, verse_number: int}
     */
    public function getExegesisContext(int $verseId, string $verseType = 'bible'): array
    {
        $cacheEnabled = config('core.ai.cache.enabled', true);
        $ttl = config('core.ai.cache.exegesis_ttl', 86400);
        $prefix = config('core.ai.cache.prefix', 'ai_');
        $key = $prefix . 'exegesis_' . $verseId . '_' . md5($verseType);

        if ($cacheEnabled && $ttl > 0) {
            return Cache::remember($key, $ttl, fn () => $this->exegesisResolver->resolve($verseId, $verseType));
        }

        return $this->exegesisResolver->resolve($verseId, $verseType);
    }

    /**
     * Generate a sermon outline from theme and references, crossing sermon_categories and bible_verses.
     * Results are cached by theme + references hash.
     *
     * @param  array<int, string>  $references  e.g. ["João 3:16", "Romanos 8:28"]
     * @return array{title: string, points: array<int, array{label: string, subpoints?: array<int, string>}>, references: array<int, string>}
     */
    public function generateSermonOutline(string $theme, array $references): array
    {
        $cacheEnabled = config('core.ai.cache.enabled', true);
        $ttl = (int) config('core.ai.cache.exegesis_ttl', 86400);
        $prefix = config('core.ai.cache.prefix', 'ai_');
        $cacheKey = $prefix . 'outline_' . md5($theme . '|' . implode(',', $references));

        if ($cacheEnabled && $ttl > 0) {
            $cached = Cache::get($cacheKey);
            if (is_array($cached)) {
                return $cached;
            }
        }

        $categoriesContext = SermonCategory::query()
            ->orderBy('name')
            ->get(['name', 'description'])
            ->map(fn ($c) => '- ' . $c->name . ($c->description ? ': ' . $c->description : ''))
            ->implode("\n");

        $versesContext = [];
        foreach ($references as $ref) {
            $found = $this->bibleApi->findByReference($ref);
            if ($found && $found['verses']->isNotEmpty()) {
                $texts = $found['verses']->map(fn ($v) => $v->verse_number . '. ' . $v->text)->implode(' ');
                $versesContext[] = $found['reference'] . ': ' . $texts;
            }
        }

        $systemPrompt = $this->getSystemPromptBase() . "\n\n"
            . 'Gere um esboço de sermão em JSON com a seguinte estrutura exata: '
            . '{"title": "Título do sermão", "points": [{"label": "Ponto 1", "subpoints": ["Sub 1.1", "Sub 1.2"]}, {"label": "Ponto 2", "subpoints": []}], "references": ["Ref 1", "Ref 2"]}. '
            . 'Use APENAS as definições de Strong e os comentários fornecidos no contexto. Responda somente com o JSON, sem texto adicional.';

        $userContent = "Tema: " . $theme . "\n\n";
        $userContent .= "Categorias disponíveis:\n" . ($categoriesContext ?: '(nenhuma)') . "\n\n";
        $userContent .= "Textos bíblicos:\n" . implode("\n", $versesContext ?: ['(nenhum)']);

        $response = $this->chatWithMessages([
            ['role' => 'system', 'content' => $systemPrompt],
            ['role' => 'user', 'content' => $userContent],
        ], ['action' => 'sermon_outline']);

        $text = $response['content'] ?? '';
        $decoded = $this->parseJsonFromResponse($text);
        if (is_array($decoded) && isset($decoded['title'], $decoded['points'])) {
            $decoded['references'] = $decoded['references'] ?? $references;
            if ($cacheEnabled && $ttl > 0) {
                Cache::put($cacheKey, $decoded, $ttl);
            }

            return $decoded;
        }

        $fallback = [
            'title' => $theme,
            'points' => [['label' => 'Ponto 1', 'subpoints' => []]],
            'references' => $references,
        ];
        if ($cacheEnabled && $ttl > 0) {
            Cache::put($cacheKey, $fallback, $ttl);
        }

        return $fallback;
    }

    /**
     * Generic chat with context injection. Returns Markdown or JSON depending on usage.
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @param  array{action?: string, return_json?: bool}  $options
     * @return array{content: string, usage?: array{prompt_tokens: int, completion_tokens: int, total_tokens: int}}
     */
    public function chat(array $messages, array $context = [], array $options = []): array
    {
        $systemParts = [];
        if (! empty($context)) {
            $systemParts[] = "Contexto (use obrigatoriamente na resposta):\n" . json_encode($context, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
        $flattened = $this->flattenMessages($messages, $systemParts);
        return $this->chatWithMessages($flattened, $options);
    }

    /**
     * Chat using raw messages array (no flattening).
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @param  array{action?: string}  $options
     * @return array{content: string, usage?: array{prompt_tokens: int, completion_tokens: int, total_tokens: int}}
     */
    public function chatWithMessages(array $messages, array $options = []): array
    {
        $provider = config('core.ai.provider', 'openai');
        if ($provider === 'gemini') {
            return $this->chatWithMessagesGemini($messages, $options);
        }

        $model = config('core.ai.openai.model', config('openai.model', 'gpt-4o-mini'));
        $timeout = config('core.ai.openai.request_timeout', 60);

        $response = OpenAI::chat()->create([
            'model' => $model,
            'messages' => $messages,
        ]);

        if (! $response instanceof CreateResponse || $response->choices === []) {
            return ['content' => ''];
        }

        $content = $response->choices[0]->message->content ?? '';
        $usage = $response->usage;

        if (config('core.ai.usage_log.enabled', true) && $usage) {
            $this->logUsage(
                $usage->promptTokens,
                $usage->completionTokens ?? 0,
                $usage->totalTokens,
                $options['action'] ?? 'chat',
                $model
            );
        }

        return [
            'content' => $content,
            'usage' => $usage ? [
                'prompt_tokens' => $usage->promptTokens,
                'completion_tokens' => $usage->completionTokens ?? 0,
                'total_tokens' => $usage->totalTokens,
            ] : null,
        ];
    }

    /**
     * Chat using Google Gemini API (when core.ai.provider = gemini).
     *
     * @param  array<int, array{role: string, content: string}>  $messages
     * @param  array{action?: string}  $options
     * @return array{content: string, usage?: array{prompt_tokens: int, completion_tokens: int, total_tokens: int}}
     */
    private function chatWithMessagesGemini(array $messages, array $options = []): array
    {
        $apiKey = config('core.ai.gemini.api_key');
        $model = config('core.ai.gemini.model', 'gemini-2.0-flash');
        $timeout = config('core.ai.gemini.request_timeout', 60);

        if (! is_string($apiKey) || $apiKey === '') {
            return ['content' => 'Gemini não configurado. Defina GEMINI_API_KEY no .env e AI_PROVIDER=gemini.'];
        }

        $systemParts = [];
        $userContent = '';
        foreach ($messages as $m) {
            $role = $m['role'] ?? '';
            $content = (string) ($m['content'] ?? '');
            if ($role === 'system') {
                $systemParts[] = $content;
            }
            if ($role === 'user') {
                $userContent = $content;
            }
        }

        $body = [
            'contents' => [
                ['role' => 'user', 'parts' => [['text' => $userContent ?: 'Olá']]],
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                'maxOutputTokens' => 2048,
            ],
        ];
        if ($systemParts !== []) {
            $body['systemInstruction'] = ['parts' => [['text' => implode("\n\n", $systemParts)]]];
        }

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $apiKey;

        $response = Http::timeout($timeout)
            ->acceptJson()
            ->post($url, $body);

        if (! $response->successful()) {
            throw new \RuntimeException('Gemini API: ' . ($response->json('error.message') ?? $response->body()));
        }

        $data = $response->json();
        $candidate = $data['candidates'][0] ?? null;
        if (! $candidate || ($candidate['finishReason'] ?? '') === 'SAFETY') {
            return ['content' => 'A resposta não está disponível (bloqueio ou sem conteúdo).'];
        }
        $text = $candidate['content']['parts'][0]['text'] ?? '';
        $usageMeta = $data['usageMetadata'] ?? null;
        $promptTokens = (int) ($usageMeta['promptTokenCount'] ?? 0);
        $completionTokens = (int) ($usageMeta['candidatesTokenCount'] ?? 0);
        $totalTokens = (int) ($usageMeta['totalTokenCount'] ?? $promptTokens + $completionTokens);

        if (config('core.ai.usage_log.enabled', true) && $totalTokens > 0) {
            $this->logUsage($promptTokens, $completionTokens, $totalTokens, $options['action'] ?? 'chat', $model);
        }

        return [
            'content' => $text,
            'usage' => [
                'prompt_tokens' => $promptTokens,
                'completion_tokens' => $completionTokens,
                'total_tokens' => $totalTokens,
            ],
        ];
    }

    /**
     * Build exegesis prompt and run chat for a verse (e.g. PastoralPanel Assistant).
     */
    public function exegesisChat(int $verseId, string $verseType, string $userMessage): array
    {
        $ctx = $this->getExegesisContext($verseId, $verseType);
        $contextForPrompt = [
            'reference' => $ctx['reference'],
            'segments' => $ctx['segments'],
            'strong_definitions' => $ctx['strong_definitions'],
            'commentaries_excerpts' => array_map(fn ($c) => ['title' => $c['title'], 'content' => mb_substr($c['content'], 0, 2000)], $ctx['commentaries']),
        ];

        $system = $this->getSystemPromptBase()
            . ' Use exclusivamente os dados de Strong e os comentários fornecidos no contexto. '
            . 'Para referências bíblicas na resposta use o formato @Livro Cap:Versículo (ex: @João 3:16).';

        return $this->chatWithMessages([
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => "Contexto:\n" . json_encode($contextForPrompt, JSON_UNESCAPED_UNICODE) . "\n\nPergunta do usuário: " . $userMessage],
        ], ['action' => 'exegesis']);
    }

    /**
     * Simplify commentaries for a verse (MemberPanel Verse Explainer).
     */
    public function explainVerse(string $reference, string $userQuestion = ''): array
    {
        $found = $this->bibleApi->findByReference($reference);
        if (! $found) {
            return $this->chatWithMessages([
                ['role' => 'user', 'content' => 'Não foi possível localizar a referência: ' . $reference],
            ], ['action' => 'explainer']);
        }

        $bookName = $found['book'];
        $chapter = $found['chapter'];
        $verseIds = $found['verses']->pluck('id')->all();
        $verseNumbers = $found['verses']->pluck('verse_number')->all();
        $texts = $found['verses']->map(fn ($v) => $v->verse_number . '. ' . $v->text)->implode(' ');

        $commentaries = \VertexSolutions\Sermons\Models\BibleCommentary::query()
            ->where('book', $bookName)
            ->where('chapter', $chapter)
            ->where(function ($q) use ($verseNumbers) {
                foreach ($verseNumbers as $vn) {
                    $q->orWhere(function ($q2) use ($vn) {
                        $q2->where('verse_start', '<=', $vn)->where(function ($q3) use ($vn) {
                            $q3->whereNull('verse_end')->orWhere('verse_end', '>=', $vn);
                        });
                    });
                }
            })
            ->where('status', 'published')
            ->get(['title', 'content'])
            ->map(fn ($c) => ['title' => $c->title, 'content' => mb_substr($c->content, 0, 3000)])
            ->all();

        $system = $this->getSystemPromptBase()
            . ' Simplifique o conteúdo para alunos mantendo profundidade teológica. '
            . 'Use o formato @Livro Cap:Versículo para referências (ex: @João 3:16).';

        $userContent = "Referência: " . $reference . "\nTexto: " . $texts . "\n\nComentários disponíveis:\n" . json_encode($commentaries, JSON_UNESCAPED_UNICODE);
        if ($userQuestion !== '') {
            $userContent .= "\n\nPergunta do aluno: " . $userQuestion;
        }

        return $this->chatWithMessages([
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $userContent],
        ], ['action' => 'explainer']);
    }

    /**
     * Suggest tags and Bible references from sermon content and study notes.
     *
     * @return array{suggested_tags: array<int, string>, suggested_references: array<int, array{reference_text: string, type: string}>}
     */
    public function suggestTagsAndReferences(string $sermonContent, array $studyNotesContent): array
    {
        $context = [
            'sermon_excerpt' => mb_substr($sermonContent, 0, 4000),
            'study_notes' => $studyNotesContent,
        ];

        $system = $this->getSystemPromptBase() . "\n\n"
            . 'Com base no conteúdo do sermão e nas notas de estudo, sugira: (1) tags em português (temas, tópicos); (2) referências bíblicas no formato "Livro Cap:Versículos" com tipo (main, support, illustration). Responda APENAS com um JSON: {"suggested_tags": ["tag1","tag2"], "suggested_references": [{"reference_text": "João 3:16", "type": "main"}]}';

        $userContent = json_encode($context, JSON_UNESCAPED_UNICODE);
        $result = $this->chatWithMessages([
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $userContent],
        ], ['action' => 'suggest_tags_refs']);

        $decoded = $this->parseJsonFromResponse($result['content'] ?? '');
        if (is_array($decoded)) {
            return [
                'suggested_tags' => $decoded['suggested_tags'] ?? [],
                'suggested_references' => $decoded['suggested_references'] ?? [],
            ];
        }

        return ['suggested_tags' => [], 'suggested_references' => []];
    }

    private function flattenMessages(array $messages, array $systemParts): array
    {
        $out = [];
        foreach ($messages as $m) {
            if (($m['role'] ?? '') === 'system' && ! empty($systemParts)) {
                $systemParts[] = $m['content'];
                continue;
            }
            $out[] = $m;
        }
        if (! empty($systemParts)) {
            array_unshift($out, ['role' => 'system', 'content' => implode("\n\n", $systemParts)]);
        }
        return $out;
    }

    private function parseJsonFromResponse(string $text): mixed
    {
        $text = trim($text);
        if (preg_match('/\{(?:[^{}]|(?R))*\}/s', $text, $m)) {
            $decoded = json_decode($m[0], true);
            return is_array($decoded) ? $decoded : null;
        }
        return null;
    }

    private function logUsage(int $promptTokens, int $completionTokens, int $totalTokens, string $action, string $model): void
    {
        if (! config('core.ai.usage_log.enabled', true)) {
            return;
        }
        try {
            $table = 'ai_usage_logs';
            if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                $costEstimate = $this->estimateCost($model, $promptTokens, $completionTokens);
                $row = [
                    'user_id' => auth()->id(),
                    'session_id' => session()->getId(),
                    'model' => $model,
                    'prompt_tokens' => $promptTokens,
                    'completion_tokens' => $completionTokens,
                    'total_tokens' => $totalTokens,
                    'action' => $action,
                    'created_at' => now(),
                ];
                if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'cost_estimate')) {
                    $row['cost_estimate'] = $costEstimate;
                }
                \Illuminate\Support\Facades\DB::table($table)->insert($row);
            }
        } catch (\Throwable) {
            // ignore
        }
    }

    private function estimateCost(string $model, int $promptTokens, int $completionTokens): ?float
    {
        $rates = config('core.ai.cost_per_1k_tokens', [
            'gpt-4o-mini' => ['input' => 0.00015, 'output' => 0.0006],
            'gpt-4o' => ['input' => 0.0025, 'output' => 0.01],
        ]);
        $rate = $rates[$model] ?? $rates['gpt-4o-mini'] ?? null;
        if (! $rate) {
            return null;
        }

        return round(($promptTokens / 1000) * $rate['input'] + ($completionTokens / 1000) * $rate['output'], 6);
    }
}
