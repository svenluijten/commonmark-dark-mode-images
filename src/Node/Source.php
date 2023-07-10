<?php

namespace Sven\CommonMark\DarkModeImages\Node;

use League\CommonMark\Node\Node;

final class Source extends Node
{
    private string $url;
    private string $mediaQuery;

    public function __construct(string $url, string $mediaQuery)
    {
        parent::__construct();

        $this->url = $url;
        $this->mediaQuery = $mediaQuery;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getMediaQuery(): string
    {
        return $this->mediaQuery;
    }
}
