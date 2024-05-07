<?php

namespace app\builder;

interface IElement
{
    public function getName(): string;

    public function getRelation(): string;

    public function getAllSubElements(): array;

    public function addSubElement(Element $subElement): void;

    public function addAllSubElements(array $allSubElements): void;
}