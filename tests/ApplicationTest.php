<?php

use app\Application;
use PHPUnit\Framework\TestCase;

class ApplicationTest extends TestCase
{
    const INPUT_CSV_FILE = "../php_dev_test_task_v8/input.csv";
    const ORIGINAL_OUTPUT_JSON_FILE = "../php_dev_test_task_v8/output.json";
    const GENERATED_OUTPUT_JSON_FILE = "../output/output.json";

    public function testRun()
    {
        $_SERVER["argv"][1] = self::INPUT_CSV_FILE;
        $_SERVER["argv"][2] = self::GENERATED_OUTPUT_JSON_FILE;
        (new Application())->run();
        $this->assertFileEquals(self::ORIGINAL_OUTPUT_JSON_FILE, self::GENERATED_OUTPUT_JSON_FILE);
    }
}
