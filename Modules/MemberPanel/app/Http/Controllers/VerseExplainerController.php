<?php

declare(strict_types=1);

namespace VertexSolutions\MemberPanel\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\TheologicalMarkdownConverter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use OpenAI\Exceptions\RateLimitException;
use VertexSolutions\Core\Services\AIService;

class VerseExplainerController extends Controller
{
    public function __construct(
        private readonly AIService $aiService,
    ) {
    }

    public function index(): View
    {
        return view('memberpanel::verse-explainer.index');
    }

    public function explain(Request $request): JsonResponse
    {
        $request->validate([
            'reference' => 'required|string|max:100',
            'question' => 'nullable|string|max:1000',
        ]);
        $reference = $request->string('reference')->trim()->toString();
        $question = $request->string('question')->trim()->toString();

        // Normaliza referência (remove @ do início se o usuário colou do formato de menção)
        $referenceNormalized = ltrim($reference, '@');
        $verseText = null;
        $verseReference = null;

        try {
            $bibleApi = app(\VertexSolutions\Core\Services\BibleApiService::class);
            $found = $bibleApi->findByReference($referenceNormalized);
            if ($found && isset($found['verses'])) {
                $verseReference = $referenceNormalized;
                $verseText = $found['verses']->map(fn ($v) => $v->verse_number . '. ' . $v->text)->implode(' ');
            }

            $result = $this->aiService->explainVerse($referenceNormalized, $question);
        } catch (RateLimitException) {
            $message = 'Limite de requisições da API (OpenAI) atingido. Aguarde alguns minutos e tente novamente.';
            return $this->explainJsonResponse($message, $verseText, $verseReference);
        } catch (\RuntimeException $e) {
            report($e);
            $msg = $e->getMessage();
            if (str_contains($msg, 'quota') || str_contains($msg, 'exceeded') || str_contains($msg, 'rate limit')) {
                $message = 'Cota ou limite do provedor de IA atingido. Aguarde alguns segundos e tente de novo, ou altere no .env para OpenAI (AI_PROVIDER=openai).';
            } else {
                $message = 'Erro no provedor de IA. Tente novamente em instantes.';
            }
            return $this->explainJsonResponse($message, $verseText, $verseReference);
        } catch (\Throwable $e) {
            report($e);
            return $this->explainJsonResponse('Não foi possível explicar o versículo agora. Tente novamente em instantes.', $verseText, $verseReference);
        }

        $contentHtml = TheologicalMarkdownConverter::convert($result['content'] ?? '');

        return response()->json([
            'content' => $result['content'],
            'content_html' => $contentHtml,
            'usage' => $result['usage'] ?? null,
            'verse_text' => $verseText,
            'verse_reference' => $verseReference,
        ]);
    }

    private function explainJsonResponse(string $message, ?string $verseText, ?string $verseReference): JsonResponse
    {
        return response()->json([
            'content' => $message,
            'content_html' => '<p>' . e($message) . '</p>',
            'usage' => null,
            'verse_text' => $verseText,
            'verse_reference' => $verseReference,
        ]);
    }
}
