<?php

namespace Sven\CommonMark\DarkModeImages\Query;

use League\CommonMark\Node\Node;
use League\CommonMark\Node\Query\ExpressionInterface;

class HasClass implements ExpressionInterface
{
    private string $class;

    public function __construct(string $class)
    {
        $this->class = $class;
    }

    public function __invoke(Node $node): bool
    {
        if (! $node->data->has('attributes/class')) {
            return false;
        }

        $nodeClasses = $node->data->get('attributes/class');

        return in_array($this->class, explode(' ', $nodeClasses), true);
    }
}
