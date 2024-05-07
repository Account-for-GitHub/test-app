<?php

namespace app;

use app\builder\TreeBuilder;
use app\io\IOHelpers;
use Exception;

class Application
{
    public function run(): void
    {
        try {
            IOHelpers::setInputFileName();
            IOHelpers::setOutputFileName();
            $treeObject = (new TreeBuilder())->getResultTree();
            IOHelpers::resultOutput($treeObject->getTree());
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}