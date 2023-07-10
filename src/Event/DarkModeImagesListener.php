<?php

namespace Sven\CommonMark\DarkModeImages\Event;

use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Query;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;
use Sven\CommonMark\DarkModeImages\Node\Picture;
use Sven\CommonMark\DarkModeImages\Node\Source;
use Sven\CommonMark\DarkModeImages\Query\HasClass;

final class DarkModeImagesListener implements ConfigurationAwareInterface
{
    private ConfigurationInterface $config;

    public function processDocument(DocumentParsedEvent $event): void
    {
        $defaultScheme = $this->config->get('dark_mode_images/fallback');

        if ($defaultScheme === 'light') {
            $originalClass = $this->config->get('dark_mode_images/light_image_class');
            $otherClass = $this->config->get('dark_mode_images/dark_image_class');
        } else {
            $originalClass = $this->config->get('dark_mode_images/dark_image_class');
            $otherClass = $this->config->get('dark_mode_images/light_image_class');
        }

        $originalImages = (new Query())
            ->where(Query::type(Image::class))
            ->andWhere(new HasClass($originalClass))
            ->findAll($event->getDocument());

        foreach ($originalImages as $originalImage) {
            $otherImage = (new Query())
                ->where(Query::type(Image::class))
                ->andWhere(new HasClass($otherClass))
                ->findOne($originalImage->parent());

            if ($otherImage === null || in_array($originalImage, [$otherImage->previous(), $otherImage->next()], true)) {
                continue;
            }

            $otherScheme = $defaultScheme === 'light' ? 'dark' : 'light';

            $otherSource = new Source($otherImage->getUrl(), '(prefers-color-scheme: '.$otherScheme.')');

            $picture = new Picture();
            $picture->data->set('dark_mode_images', true);

            $picture->appendChild($otherSource);
            $classes = explode(' ', $originalImage->data->get('attributes/class'));
            $newClasses = array_filter($classes, fn ($cls) => $cls !== $originalClass);

            $fallbackImage = clone $originalImage;
            $fallbackImage->data->set('attributes/class', implode(' ', $newClasses));

            $picture->appendChild($fallbackImage);

            $otherImage->detach();
            $originalImage->replaceWith($picture);
        }
    }

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }
}
