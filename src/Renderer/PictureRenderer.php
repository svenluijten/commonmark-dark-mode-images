<?php

namespace Sven\CommonMark\DarkModeImages\Renderer;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;
use Sven\CommonMark\DarkModeImages\Node\Picture;

class PictureRenderer implements NodeRendererInterface, ConfigurationAwareInterface
{
    private ConfigurationInterface $config;

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        Picture::assertInstanceOf($node);

        // Only append the class if the element originates from this extension.
        if ($node->data->has('dark_mode_images') && $node->data->get('dark_mode_images') === true) {
            $node->data->append('attributes/class', $this->config->get('dark_mode_images/picture_class'));
        }

        return new HtmlElement(
            tagName: 'picture',
            attributes: $node->data->get('attributes'),
            contents: $childRenderer->renderNodes($node->children()),
        );
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }
}
