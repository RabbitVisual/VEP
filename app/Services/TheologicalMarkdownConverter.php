<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Route;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Mention\Mention;
use League\CommonMark\Extension\Mention\MentionExtension;
use League\CommonMark\MarkdownConverter;

/**
 * Converts Markdown to HTML with @mention support for Bible references.
 * Format: @João 3:16 or @Gênesis 1:1 → link to Bible search/verse.
 */
final class TheologicalMarkdownConverter
{
    private static ?MarkdownConverter $converter = null;

    public static function convert(string $markdown): string
    {
        if ($markdown === '') {
            return '';
        }

        $converter = self::getConverter();

        return (string) $converter->convert($markdown);
    }

    /**
     * Get the singleton converter with @ref mentions configured.
     */
    public static function getConverter(): MarkdownConverter
    {
        if (self::$converter !== null) {
            return self::$converter;
        }

        $config = [
            'mentions' => [
                'ref' => [
                    'prefix' => '@',
                    'pattern' => '[\p{L}\p{N}\s:.\-]+',
                    'generator' => function (Mention $mention): Mention {
                        $ref = trim($mention->getIdentifier());
                        $url = self::verseReferenceUrl($ref);
                        $mention->setUrl($url);

                        return $mention;
                    },
                ],
            ],
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new MentionExtension);

        self::$converter = new MarkdownConverter($environment);

        return self::$converter;
    }

    public static function reset(): void
    {
        self::$converter = null;
    }

    private static function verseReferenceUrl(string $reference): string
    {
        if (function_exists('route')) {
            try {
                $searchRoute = Route::has('painel.bible.search')
                    ? route('painel.bible.search')
                    : route('memberpanel.bible.search');

                return $searchRoute . '?ref=' . rawurlencode($reference);
            } catch (\Throwable) {
                // route not available (e.g. console)
            }
        }

        return '#ref-' . rawurlencode($reference);
    }
}
