<?php

namespace io;

use app\io\CSV;
use app\io\IOHelpers;
use PHPUnit\Framework\TestCase;

class CSVTest extends TestCase
{
    const ROOT_NODE_ITEM = [
        CSV::ITEM_NAME__INDEX => "Total",
        CSV::TYPE__INDEX => "Изделия и компоненты",
        CSV::PARENT__INDEX => "",
        CSV::RELATION__INDEX => "",
    ];

    const COMMON_COMPONENT_ITEM = [
        CSV::ITEM_NAME__INDEX => "Тележка Б25",
        CSV::TYPE__INDEX => "Изделия и компоненты",
        CSV::PARENT__INDEX => "Total",
        CSV::RELATION__INDEX => "",
    ];

    const DIRECT_COMPONENT_ITEM = [
        CSV::ITEM_NAME__INDEX => "Тележка Б25.#2",
        CSV::TYPE__INDEX => "Прямые компоненты",
        CSV::PARENT__INDEX => "Стандарт.#1",
        CSV::RELATION__INDEX => "Тележка Б25",
    ];

    public function testItemIsNotRootNode()
    {
        $this->assertTrue(CSV::itemIsNotRootNode(self::COMMON_COMPONENT_ITEM));
        $this->assertTrue(CSV::itemIsNotRootNode(self::DIRECT_COMPONENT_ITEM));
        $this->assertFalse(CSV::itemIsNotRootNode(self::ROOT_NODE_ITEM));
    }

    public function testItemIsCommonComponent()
    {
        $this->assertTrue(CSV::itemIsCommonComponent(self::COMMON_COMPONENT_ITEM));
        $this->assertFalse(CSV::itemIsCommonComponent(self::DIRECT_COMPONENT_ITEM));
    }

    public function testItemIsDirectComponent()
    {
        $this->assertFalse(CSV::itemIsDirectComponent(self::COMMON_COMPONENT_ITEM));
        $this->assertTrue(CSV::itemIsDirectComponent(self::DIRECT_COMPONENT_ITEM));
    }

    public function testItemHasRelation()
    {
        $this->assertTrue(CSV::itemHasRelation(self::DIRECT_COMPONENT_ITEM));
        $this->assertFalse(CSV::itemHasRelation(self::COMMON_COMPONENT_ITEM));
    }

    public function testGetBaseDataArray()
    {
        $_SERVER["argv"][1] = "../../php_dev_test_task_v8/input.csv";
        IOHelpers::setInputFileName();
        $this->assertIsArray(CSV::getBaseDataArray());
    }
}
