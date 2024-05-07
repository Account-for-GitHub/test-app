<?php

namespace app\io;

use Exception;

class IOHelpers
{
    private static string $inputFileName = "";
    private static string $outputFileName = "";

    public static function setInputFileName(): void
    {
        self::$inputFileName = $_SERVER["argv"][1] ?? throw new Exception("Input file path is not specified.");
        if (!is_file(self::$inputFileName)) throw new Exception("Input file not found.");
    }

    /**
     * @throws Exception
     */
    public static function setOutputFileName(): void
    {
        self::$outputFileName = $_SERVER["argv"][2] ?? throw new Exception("Output file path is not specified.");
        if (!is_dir(dirname(self::$outputFileName))) throw new Exception("Output directory not found.");
    }

    public static function getInputFileName(): string
    {
        return self::$inputFileName;
    }

    public static function getOutputFileName(): string
    {
        return self::$outputFileName;
    }

    public static function resultOutput(array $tree): void
    {
        $jsonTree = json_encode($tree, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $resultJSON = preg_replace("/ {4}/", "  ", $jsonTree);
        file_put_contents(self::getOutputFileName(), $resultJSON);
    }
}