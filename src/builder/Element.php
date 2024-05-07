<?php

namespace app\builder;

class Element implements IElement
{
    function __construct(
        public string  $itemName,
        public ?string $parent = null,
        private string $relation = "",
    )
    {
    }

    public array $children = [];

    public function getRelation(): string
    {
        return $this->relation;
    }

    public function getName(): string
    {
        return $this->itemName;
    }

    public function addSubElement(IElement $subElement): void
    {
        $this->children[] = $subElement;
    }

    public function getAllSubElements(): array
    {
        return $this->children;
    }

    private function changeBaseNodeTo(string $nodeName): void
    {
        $this->parent = $nodeName;
    }

    public function addAllSubElements(array $allSubElements): void
    {
        foreach ($allSubElements as $originalSubElement) {
            /** @var IElement $subElement */
            $subElement = clone $originalSubElement;
            $subElement->changeBaseNodeTo($this->getName());
            $this->children[] = $subElement;
        }
    }
}