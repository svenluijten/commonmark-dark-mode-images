<?php

namespace Sven\CommonMark\DarkModeImages;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\ConfigurableExtensionInterface;
use League\Config\ConfigurationBuilderInterface;
use Nette\Schema\Expect;
use Sven\CommonMark\DarkModeImages\Event\DarkModeImagesListener;
use Sven\CommonMark\DarkModeImages\Node\Picture;
use Sven\CommonMark\DarkModeImages\Node\Source;
use Sven\CommonMark\DarkModeImages\Renderer\PictureRenderer;
use Sven\CommonMark\DarkModeImages\Renderer\SourceRenderer;

final class DarkModeImagesExtension implements ConfigurableExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $extensions = $environment->getExtensions();

        $this->assertArrayContainsInstanceOf(AttributesExtension::class, $extensions);

        $environment
            ->addEventListener(DocumentParsedEvent::class, [new DarkModeImagesListener(), 'processDocument'])
            ->addRenderer(Picture::class, new PictureRenderer())
            ->addRenderer(Source::class, new SourceRenderer())
        ;
    }

    public function configureSchema(ConfigurationBuilderInterface $builder): void
    {
        $builder->addSchema('dark_mode_images', Expect::structure([
            'dark_image_class' => Expect::string()->default('dark-image'),
            'light_image_class' => Expect::string()->default('light-image'),
            'picture_class' => Expect::string()->default('dark-mode-images-picture'),
            'fallback' => Expect::anyOf('light', 'dark')->default('light'),
        ]));
    }

    private function assertArrayContainsInstanceOf(string $class, iterable $extensions): void
    {
        foreach ($extensions as $extension) {
            if ($extension instanceof $class) {
                return;
            }
        }

        throw new \RuntimeException('The "'.self::class.'" extension requires the "'.$class.'" extension to loaded before it.');
    }
}
