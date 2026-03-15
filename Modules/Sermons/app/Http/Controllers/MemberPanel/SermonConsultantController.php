<?php

declare(strict_types=1);

namespace VertexSolutions\Sermons\Http\Controllers\MemberPanel;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use VertexSolutions\Core\Services\AIService;

/**
 * Consultor de Bancada – IA para o editor de sermões (ilustração, coerência CBB, pesquisa histórica, revisão de formatação).
 */
final class SermonConsultantController
{
    public function __construct(
        private readonly AIService $ai
    ) {
    }

    /**
     * POST memberpanel/sermons/consultant
     * Body: { message, context: { sermon_studio, action, main_point, reference, excerpt, full_content } }
     * Response: { reply, formatted_html? }
     */
    public function chat(Request $request): JsonResponse
    {
        $validated = Validator::make($request->all(), [
            'message' => 'nullable|string|max:4000',
            'context' => 'nullable|array',
            'context.action' => 'nullable|string|in:suggest_illustration,check_coherence,historical_research,revise_format',
            'context.main_point' => 'nullable|string',
            'context.reference' => 'nullable|string',
            'context.excerpt' => 'nullable|string',
            'context.full_content' => 'nullable|string',
        ])->validate();

        $message = $validated['message'] ?? '';
        $context = $validated['context'] ?? [];
        $action = $context['action'] ?? 'suggest_illustration';
        $mainPoint = $context['main_point'] ?? '';
        $reference = trim((string) ($context['reference'] ?? ''));
        $excerpt = $context['excerpt'] ?? '';
        $fullContent = $context['full_content'] ?? '';

        $reply = '';
        $formattedHtml = null;

        try {
            if ($action === 'historical_research') {
                $ref = $reference ?: 'contexto do texto';
                $result = $this->ai->explainVerse($ref, $message ?: 'Contexto cultural, histórico e arqueológico desta passagem.');
                $reply = $result['content'] ?? '';
            } elseif ($action === 'revise_format') {
                $result = $this->runReviseFormat($fullContent, $message);
                $reply = $result['content'] ?? '';
                $formattedHtml = $this->extractHtmlFromResponse($reply) ?? (str_contains($reply, '<') ? $reply : null);
            } else {
                $result = $this->runChatAction($action, $message, $mainPoint, $reference, $excerpt);
                $reply = $result['content'] ?? '';
            }
        } catch (\Throwable $e) {
            $reply = 'Ocorreu um erro ao consultar. Tente novamente.';
            if (config('app.debug')) {
                $reply .= ' ' . $e->getMessage();
            }
        }

        return response()->json([
            'reply' => $reply,
            'formatted_html' => $formattedHtml,
        ]);
    }

    private function runChatAction(string $action, string $message, string $mainPoint, string $reference, string $excerpt): array
    {
        $system = $this->ai->getSystemPromptBase();

        if ($action === 'suggest_illustration') {
            $system .= "\n\nVocê é um consultor homilético. O pastor precisa de uma sugestão de ilustração (história, analogia, exemplo) para o ponto central do sermão. Sugira uma ou duas ilustrações breves, adequadas ao contexto evangélico e ao ponto principal. Seja conciso.";
            $user = "Ponto central do sermão: " . ($mainPoint ?: $excerpt ?: 'não informado') . "\n\n" . ($excerpt ? "Trecho do rascunho:\n" . mb_substr($excerpt, 0, 800) : '');
        } elseif ($action === 'check_coherence') {
            $system .= "\n\nVocê é um consultor que verifica a coerência da interpretação com os princípios da Hermenêutica Bíblica (CBB – Contexto, Biblicamente, Biblicamente). Avalie se o ponto principal e o uso do texto estão coerentes com o contexto, a intenção do autor e a analogia da fé. Dê um parecer breve e construtivo.";
            $user = "Referência: " . ($reference ?: 'não informada') . "\nPonto principal: " . ($mainPoint ?: $excerpt ?: 'não informado') . "\n\nTrecho:\n" . mb_substr($excerpt, 0, 1000);
        } else {
            $user = $message ?: 'Preciso de ajuda com o sermão.';
        }

        return $this->ai->chatWithMessages([
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $user],
        ], ['action' => 'sermon_consultant']);
    }

    private function runReviseFormat(string $fullContent, string $message): array
    {
        $system = $this->ai->getSystemPromptBase()
            . "\n\nVocê é um revisor de formatação para sermões no púlpito. O pastor enviou o HTML do sermão. Sua tarefa é devolver o MESMO conteúdo com formatação limpa: parágrafos <p>, citações bíblicas em <blockquote> ou <cite>, e marcar transições e apelos. Use [TRANSIÇÃO] e [APELO] no texto onde fizer sentido, ou mantenha se já existirem. Responda APENAS com o HTML revisado, sem explicação antes ou depois. Não invente conteúdo.";
        $user = "HTML do sermão a revisar:\n\n" . mb_substr($fullContent, 0, 15000);

        return $this->ai->chatWithMessages([
            ['role' => 'system', 'content' => $system],
            ['role' => 'user', 'content' => $user],
        ], ['action' => 'revise_format']);
    }

    private function extractHtmlFromResponse(string $content): ?string
    {
        $content = trim($content);
        if (preg_match('/<body[^>]*>(.*?)<\/body>/s', $content, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/<(?:div|article|section)[^>]*>.*/s', $content)) {
            return $content;
        }
        if (str_contains($content, '<p>') || str_contains($content, '<blockquote>')) {
            return $content;
        }
        return null;
    }
}
