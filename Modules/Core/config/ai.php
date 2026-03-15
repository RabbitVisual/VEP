<?php

declare(strict_types=1);

/**
 * AI Theological Engine – Core module config.
 * Cache TTL, model, and feature flags.
 *
 * @author Vertex Solutions LTDA
 */

return [
    // openai | gemini — qual provedor usar para Explicador de Versículos e demais recursos de IA
    'provider' => env('AI_PROVIDER', 'openai'),

    'openai' => [
        'model' => env('OPENAI_MODEL', 'gpt-4o-mini'),
        'request_timeout' => (int) env('OPENAI_REQUEST_TIMEOUT', 60),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.0-flash'),
        'request_timeout' => (int) env('GEMINI_REQUEST_TIMEOUT', 60),
    ],

    'cache' => [
        'enabled' => env('AI_CACHE_ENABLED', true),
        'exegesis_ttl' => (int) env('AI_EXEGESIS_CACHE_TTL', 86400), // 24h
        'prefix' => 'ai_',
        'lexicon_forever' => env('AI_LEXICON_CACHE_FOREVER', true),
    ],

    'context' => [
        'max_commentaries' => (int) env('AI_MAX_COMMENTARIES', 3),
        'max_comment_length' => (int) env('AI_MAX_COMMENT_LENGTH', 1500),
    ],

    'theological_orientation' => env('AI_THEOLOGICAL_ORIENTATION', 'pastoral'), // academic | pastoral | devotional
    'system_prompt_suffix' => env('AI_SYSTEM_PROMPT_SUFFIX', ''),

    'usage_log' => [
        'enabled' => env('AI_USAGE_LOG_ENABLED', true),
    ],

    'rate_limit' => [
        'exegesis_per_minute' => (int) env('AI_EXEGESIS_RATE_LIMIT_PER_MINUTE', 10),
    ],

    'cost_per_1k_tokens' => [
        'gpt-4o-mini' => ['input' => 0.00015, 'output' => 0.0006],
        'gpt-4o' => ['input' => 0.0025, 'output' => 0.01],
        'gemini-1.5-flash' => ['input' => 0.000075, 'output' => 0.0003],
        'gemini-1.5-pro' => ['input' => 0.00125, 'output' => 0.005],
    ],
];
