<?php

namespace Sven\CommonMark\DarkModeImages\Tests;

use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\Attributes\AttributesExtension;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\MarkdownConverter;
use PHPUnit\Framework\TestCase;
use RuntimeException;
use Spatie\Snapshots\MatchesSnapshots;
use Sven\CommonMark\DarkModeImages\DarkModeImagesExtension;

class DarkModeImagesExtensionTest extends TestCase
{
    use MatchesSnapshots;

    public function testRequiresTheAttributesExtension(): void
    {
        $environment = new Environment();
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new DarkModeImagesExtension());

        $converter = new MarkdownConverter($environment);

        $this->expectException(RuntimeException::class);

        $converter->convert('# test');
    }

    /** @dataProvider dataProvider */
    public function testImages(string $markdown, array $config = []): void
    {
        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new AttributesExtension());
        $environment->addExtension(new DarkModeImagesExtension());

        $converter = new MarkdownConverter($environment);

        $output = rtrim((string) $converter->convert($markdown));

        $this->assertMatchesSnapshot($output);
    }

    public static function dataProvider(): iterable
    {
        yield 'no image tags' => [
            'test',
        ];

        yield 'an image tag without siblings' => [
            '![test](/example.jpg)',
        ];

        yield 'an image with 2 (non-image) siblings' => [
            <<<MD
# test header
![test](/example.jpg)
test text
MD,
        ];

        yield '2 images with light and dark mode class' => [
            <<<MD
![image one](/example-light.jpg){.light-image}
![image two](/example-dark.jpg){.dark-image}
MD,
        ];

       yield '2 images without dark- or light mode classes' => [<<<MD
![image one](/example-one.jpg)
![image two](/example-two.jpg)
MD];

       yield '2 images with only one light mode class' => [<<<MD
![image one](/example-light.jpg){.light-image}
![image two](/example-two.jpg)
MD];

       yield '3 images, 2 of which have dark- and light mode classes' => [<<<MD
![image one](/example-light.jpg){.light-image}
![image two](/example-dark.jpg){.dark-image}
![image three](/example-three.jpg)
MD];

       yield '2 picture elements' => [<<<MD
![image one light](/example-one-light.jpg){.light-image}
![image one dark](/example-one-dark.jpg){.dark-image}
![image two light](/example-two-light.jpg){.light-image}
![image two dark](/example-two-dark.jpg){.dark-image}
MD];

       yield 'light and dark image separated by another element' => [<<<MD
![image one light](/example-one-light.jpg){.light-image}

a bit of text here

![image one dark](/example-one-dark.jpg){.dark-image}
MD];

       yield 'dark image first' => [<<<MD
![image one](/example-dark.jpg){.dark-image}
![image two](/example-light.jpg){.light-image}
MD];

       yield 'dark as fallback' => [<<<MD
![image one](/example-dark.jpg){.dark-image}
![image two](/example-light.jpg){.light-image}
MD, ['dark_mode_images' => ['fallback' => 'dark']]];

       yield 'other light class' => [<<<MD
![image one](/example-dark.jpg){.dark-image}
![image two](/example-light.jpg){.light}
MD, ['dark_mode_images' => ['light_image_class' => 'light']]];

        yield 'other dark class' => [<<<MD
![image one](/example-dark.jpg){.dark}
![image two](/example-light.jpg){.light-image}
MD, ['dark_mode_images' => ['dark_image_class' => 'dark']]];

        yield 'other light and dark classes' => [<<<MD
![image one](/example-dark.jpg){.dark}
![image two](/example-light.jpg){.light}
MD, ['dark_mode_images' => ['dark_image_class' => 'dark', 'light_image_class' => 'light']]];
    }
}
