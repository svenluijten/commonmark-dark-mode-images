<?php

namespace Sven\CommonMark\DarkModeImages\Tests\Query;

use League\CommonMark\Node\Node;
use PHPUnit\Framework\TestCase;
use Sven\CommonMark\DarkModeImages\Query\HasClass;

class HasClassTest extends TestCase
{
    public function testDoesNotHaveClass(): void
    {
        $query = new HasClass('test-class');

        $node = new class extends Node {};

        self::assertFalse($query($node));
    }

    public function testHasClass(): void
    {
        $query = new HasClass('test-class');

        $node = new class extends Node {};
        $node->data->set('attributes/class', 'test-class');

        self::assertTrue($query($node));
    }

    public function testHasClassAmongstMany(): void
    {
        $query = new HasClass('test-class');

        $node = new class extends Node {};
        $node->data->set('attributes/class', 'class-one test-class class-two');

        self::assertTrue($query($node));
    }
}
