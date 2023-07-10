<?php

namespace Sven\CommonMark\DarkModeImages\Renderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Sven\CommonMark\DarkModeImages\Node\Source;

class SourceRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        Source::assertInstanceOf($node);

        $attributes = [
            ...$node->data->get('attributes'),
            'media' => $node->getMediaQuery(),
            'srcset' => $node->getUrl(),
        ];

        return new HtmlElement('source', $attributes, selfClosing: true);
    }
}
