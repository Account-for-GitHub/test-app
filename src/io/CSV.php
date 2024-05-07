<?php

namespace app\io;

use Exception;

class CSV
{
    const ITEM_NAME__INDEX = 0;
    const TYPE__INDEX = 1;
    const PARENT__INDEX = 2;
    const RELATION__INDEX = 3;

    const ROOT_NODE_NAME = "";
    const COMMON_COMPONENT_TYPE = "Изделия и компоненты";
    const DIRECT_COMPONENT_TYPE = "Прямые компоненты";

    /**
     * @throws Exception
     */
    private static function getCsvFileHandler(string $inputFileName)
    {
        $handleCsvFile = fopen($inputFileName, "r");
        if ($handleCsvFile === false) throw new Exception("Csv file cannot be open.");
        return $handleCsvFile;
    }

    private static function dropFirstCsvLine($csvFileHandler): void
    {
        fgetcsv($csvFileHandler, 1000, ";");
    }

    /**
     * @return resource
     * @throws Exception
     */
    public static function getPreparedCsvFileHandler(string $inputFileName)
    {
        $csvFileHandler = self::getCsvFileHandler($inputFileName);
        self::dropFirstCsvLine($csvFileHandler);
        return $csvFileHandler;
    }

    /**
     * @throws \Exception
     */
    public static function getBaseDataArray(): array
    {
        $handleCsvFile = self::getPreparedCsvFileHandler(IOHelpers::getInputFileName());
        $itemsGroupedByParent = [];
        while (($data = fgetcsv($handleCsvFile, 1000, ";")) !== false) {
            $parentNodeName = $data[self::PARENT__INDEX];
            $itemsGroupedByParent[$parentNodeName][] = [
                $data[self::ITEM_NAME__INDEX],
                $data[self::TYPE__INDEX],
                $data[self::PARENT__INDEX],
                $data[self::RELATION__INDEX],
            ];
        }
        fclose($handleCsvFile);
        return $itemsGroupedByParent;
    }

    public static function itemIsNotRootNode(array $item): bool
    {
        return $item[CSV::PARENT__INDEX] !== "";
    }

    public static function itemIsDirectComponent(array $item): bool
    {
        return $item[CSV::TYPE__INDEX] == self::DIRECT_COMPONENT_TYPE;
    }

    public static function itemHasRelation(array $item): bool
    {
        return $item[CSV::RELATION__INDEX] !== "";
    }

    public static function itemIsCommonComponent(array $item): bool
    {
        return $item[CSV::TYPE__INDEX] == self::COMMON_COMPONENT_TYPE;
    }
}