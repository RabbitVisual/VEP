<?php

declare(strict_types=1);

namespace App\Services;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Mention\Mention;
use League\CommonMark\Extension\Mention\MentionExtension;
use League\CommonMark\MarkdownConverter;
use App\Services\InlineBibleReferenceRenderer;

/**
 * Converts Markdown to HTML with @mention support for Bible references.
 * Only references with verse (e.g. @João 3:16, @Salmos 3:1-4) are converted to inline expandable components.
 * Chapter-only refs (e.g. @Salmos 3) are not converted.
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
     * Get the singleton converter with @ref mentions configured (verse required).
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
                    'generator' => function (Mention $mention): ?Mention {
                        $ref = trim($mention->getIdentifier());
                        if (! preg_match('/\d+:\d+(-\d+)?/', $ref)) {
                            return null;
                        }
                        $mention->setUrl('#inline-' . rawurlencode($ref));

                        return $mention;
                    },
                ],
            ],
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new MentionExtension);
        $environment->addRenderer(Mention::class, new InlineBibleReferenceRenderer(), 10);

        self::$converter = new MarkdownConverter($environment);

        return self::$converter;
    }

    public static function reset(): void
    {
        self::$converter = null;
    }
}
