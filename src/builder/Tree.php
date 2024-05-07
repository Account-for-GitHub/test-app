<?php

namespace app\builder;

class Tree
{
    private array $tree;

    public function addRootNodeElement(IElement $e): void
    {
        $this->tree[] = $e;
    }

    public function getTree(): array
    {
        return $this->tree;
    }
}