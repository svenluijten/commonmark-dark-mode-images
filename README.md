# CommonMark Dark Mode Images Extension

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-build]][link-build]

This [CommonMark](https://commonmark.thephpleague.com) extension allows you to determine what images to show in dark- or 
light mode in Markdown converted using CommonMark.

## Installation
Via [composer](http://getcomposer.org):

```bash
composer require sven/commonmark-dark-mode-images
```

## Usage
To enable the extension, first make sure [the `Attributes` extension](https://commonmark.thephpleague.com/2.4/extensions/attributes/) 
that ships with CommonMark is enabled. Then, add the extension to the CommonMark environment:

```php
use Sven\CommonMark\DarkModeImages\DarkModeImagesExtension;

$environment->addExtension(new DarkModeImagesExtension());
```

You'll have to apply some classes to your Markdown images to indicate to this extension that they should be converted:

```md
![Screenshot of a settings dialog](/images/settings-light.jpg){.light-image}
![dark](/images/settings-dark.jpg){.dark-image}
```

These `{.light-image}` and `{.dark-image}` tags are part of [the `Attributes` extension](https://commonmark.thephpleague.com/2.4/extensions/attributes/). 
They apply classes to the converted `<img>` elements, which this extension uses to do its magic. The resulting HTML will
look something like this:

```html
<picture class="dark-mode-images-picture">
    <source srcset="/images/settings-dark.jpg" media="(prefers-color-scheme: dark)" />
    <img src="/images/settings-light.jpg" alt="Screenshot of a settings dialog"/>
</picture>
```

## Configuration

### Fallback or original
This option allows you to select either `'light'` or `'dark'` as the "original" form of the picture you're embedding. 
This determines which picture will be used if the browser doesn't support the `<picture>` element.

```php
use League\CommonMark\Environment\Environment;
use Sven\CommonMark\DarkModeImages\DarkModeImagesExtension;

$environment = new Environment([
    'dark_mode_images' => [
        'fallback' => 'dark', // Default: 'light'.
    ],
]);

$environment->addExtension(new DarkModeImagesExtension());
```

### Class on `<picture>` element
To change the class that is eventually applied to the rendered `<picture>` element, use the `picture_class` 
configuration option:

```php
use League\CommonMark\Environment\Environment;
use Sven\CommonMark\DarkModeImages\DarkModeImagesExtension;

$environment = new Environment([
    'dark_mode_images' => [
        'picture_class' => '<your class here>', // Default: 'dark-mode-images-picture'.
    ],
]);

$environment->addExtension(new DarkModeImagesExtension());
```

### Light and dark classes
Use the `dark_image_class` and `light_image_class` configuration options to determine what classes to apply to your 
Markdown images to indicate which is for dark mode, and which is for light mode.

```php
use League\CommonMark\Environment\Environment;
use Sven\CommonMark\DarkModeImages\DarkModeImagesExtension;

$environment = new Environment([
    'dark_mode_images' => [
        'light_image_class' => 'l', // Default: 'light-image'.
        'dark_image_class' => 'd', // Default: 'dark-image'.
    ],
]);

$environment->addExtension(new DarkModeImagesExtension());
```

### Note
It is _not_ recommended to use this extension when converting to Markdown on-the-fly on every request. This is best 
suited for use in a static site generator like [Jigsaw](https://jigsaw.tighten.co/).

## Contributing
All contributions (pull requests, issues and feature requests) are welcome. Make sure to read through the 
[CONTRIBUTING.md](CONTRIBUTING.md) first, though. See the [contributors page](../../graphs/contributors) for all 
contributors.

## License
`sven/commonmark-dark-mode-images` is licensed under the MIT License (MIT). Please see [the license file](LICENSE.md) 
for more information.

[ico-version]: https://img.shields.io/packagist/v/sven/commonmark-dark-mode-images.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-green.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/sven/commonmark-dark-mode-images.svg?style=flat-square
[ico-build]: https://img.shields.io/github/actions/workflow/status/svenluijten/commonmark-dark-mode-images/run-tests.yml?branch=main&style=flat-square

[link-packagist]: https://packagist.org/packages/sven/commonmark-dark-mode-images
[link-downloads]: https://packagist.org/packages/sven/commonmark-dark-mode-images
[link-build]: https://github.com/svenluijten/commonmark-dark-mode-images/actions/workflows/run-tests.yml
