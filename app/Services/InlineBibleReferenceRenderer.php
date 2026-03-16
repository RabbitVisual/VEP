<?php

declare(strict_types=1);

namespace App\Services;

use League\CommonMark\Extension\Mention\Mention;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

/**
 * Renders Mention nodes (biblical refs) as inline Blade component HTML for expand-on-click verse fetch.
 */
final class InlineBibleReferenceRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): \Stringable|string
    {
        Mention::assertInstanceOf($node);

        $ref = trim($node->getIdentifier());
        if ($ref === '') {
            return '';
        }

        if (! preg_match('/\d+:\d+(-\d+)?/', $ref)) {
            return new \League\CommonMark\Util\HtmlElement('span', [], '@' . htmlspecialchars($ref, \ENT_QUOTES, 'UTF-8'));
        }

        $refSafe = htmlspecialchars($ref, \ENT_QUOTES, 'UTF-8');

        try {
            $html = view('community::components.inline-bible-reference', ['ref' => $ref])->render();
        } catch (\Throwable) {
            $html = '<span class="inline-bible-ref text-amber-600 dark:text-amber-400" data-ref="' . $refSafe . '">@' . $refSafe . '</span>';
        }

        return $html;
    }
}
