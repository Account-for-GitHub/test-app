<?php

namespace app\builder;

use app\io\CSV;

class TreeBuilder
{
    private Tree $objectsTree;
    private array $itemsGroupedByParent = [];
    private array $commonComponentObjectsByName = [];
    private array $allDirectComponentObjects = [];

    /**
     * @throws \Exception
     */
    public function getResultTree(): Tree
    {
        $this->itemsGroupedByParent = CSV::getBaseDataArray();
        $this->buildBaseTree();
        $this->buildAdvancedTree();
        return $this->getObjectsTree();
    }

    /**
     * @throws \Exception
     */
    private function buildBaseTree(): void
    {
        $this->objectsTree = new Tree();
        $this->buildBranchesFromNode();
    }

    private function buildAdvancedTree(): void
    {
        foreach ($this->allDirectComponentObjects as $directComponent) {
            /** @var IElement $directComponent */
            $this->buildRelationBranches($directComponent);
        }
    }

    private function getObjectsTree(): Tree
    {
        return $this->objectsTree ?? new Tree();
    }

    private function buildBranchesFromNode(IElement $nodeObject = null): void
    {
        if ($nodeObject === null) {
            $nodeName = CSV::ROOT_NODE_NAME;
            $nullableNodeName = null;
        } else {
            $nodeName = $nodeObject->getName();
            $nullableNodeName = $nodeName;
        }

        if (!isset($this->itemsGroupedByParent[$nodeName])) {
            return;
        }

        foreach ($this->itemsGroupedByParent[$nodeName] as $item) {
            $itemName = $item[CSV::ITEM_NAME__INDEX];

            $thisElementObject = new Element(
                $itemName,
                $nullableNodeName,
                $item[CSV::RELATION__INDEX],
            );

            if (CSV::itemIsCommonComponent($item) && CSV::itemIsNotRootNode($item)) {
                $this->commonComponentObjectsByName[$itemName] = $thisElementObject;
            } elseif (CSV::itemIsDirectComponent($item) && CSV::itemHasRelation($item)) {
                $this->allDirectComponentObjects[] = $thisElementObject;
            }

            if ($nodeObject === null) {
                $this->objectsTree->addRootNodeElement($thisElementObject);
            } else {
                $nodeObject->addSubElement($thisElementObject);
            }

            $this->buildBranchesFromNode($thisElementObject);
        }
    }

    private function buildRelationBranches(IElement $directComponent): void
    {
        $relationName = $directComponent->getRelation();
        if (empty($relationName)) {
            return;
        }
        /** @var IElement $commonComponent */
        $commonComponent = $this->commonComponentObjectsByName[$relationName];
        $allSubElements = $commonComponent->getAllSubElements();
        $directComponent->addAllSubElements($allSubElements);
        foreach ($allSubElements as $subElement) {
            $this->buildRelationBranches($subElement);
        }
    }
}