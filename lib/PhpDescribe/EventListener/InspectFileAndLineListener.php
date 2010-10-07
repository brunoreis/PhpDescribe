<?php
namespace PhpDescribe\EventListener;
use PhpDescribe\Example\Example,
    PhpDescribe\Result\ResultGroup,
    PhpDescribe\Example\ExampleGroup,
    PhpDescribe\Example\AbstractExampleItem,
    Closure,
    ReflectionFunction;

class InspectFileAndLineListener  extends EventListener {

    protected $readFiles = array();
    function preExampleRun(Example $example,ResultGroup $resultGroup) {
        $this->registerFileAndLine($example,$resultGroup);
    }

    function preExampleGroupRun(ExampleGroup $example,ResultGroup $resultGroup) {
        $this->registerFileAndLine($example,$resultGroup);
    }

    private function registerFileAndLine(AbstractExampleItem $example, $resultGroup) {
        $function = $example->getFunction();
        $info = new ReflectionFunction($function);
        $fileName = $info->getFileName();
        $startLine = $info->getStartLine();
        $endLine = $info->getEndLine();
        $resultGroup->setFileInfo($fileName, $startLine, $endLine);
    }
}