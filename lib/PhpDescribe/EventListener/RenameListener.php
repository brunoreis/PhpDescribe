<?php
namespace PhpDescribe\EventListener;
use PhpDescribe\Example\Example,
    PhpDescribe\Result\ResultGroup,
    PhpDescribe\Example\ExampleGroup,
    PhpDescribe\Example\AbstractExampleItem,
    Closure,
    ReflectionFunction;

class RenameListener  extends EventListener {

    protected $readFiles = array();
    function postExampleRun(Example $example,ResultGroup $resultGroup) {
        $resultGroup->addExtraInformation(
            'rename',
            $this->registerFileAndLineAndGenerateInputHtml($example,$resultGroup)
        );
    }

    function postExampleGroupRun(ExampleGroup $example,ResultGroup $resultGroup) {
        $resultGroup->addExtraInformation(
            'rename',
            $this->registerFileAndLineAndGenerateInputHtml($example,$resultGroup)
        );
    }

    private function registerFileAndLineAndGenerateInputHtml(AbstractExampleItem $example, $resultGroup) {
        $fileName = $resultGroup->getFile();
        $startLine = $resultGroup->getStartLineNumber();
        $endLine = $resultGroup->getEndLineNumber();
        return '<br/>'
                . '<span class="filename">' . $fileName . ' (lines ' . $startLine . ' to ' . $endLine . ')' . '</span><br/>'
                . "<form>"
                . "<input type='hidden' name='act' value='rename'>"
                . "<input type='hidden' name='file' value='$fileName'>"
                . "<input type='hidden' name='line' value='$startLine'>"
                . "<input type='hidden' name='name' value='".$example->getName()."'></input>"
                . "<input size=100 name='newName' value='".$example->getName()."'></input>"
                . "<input type='submit' value='rename'>"
                . "</form>";
    }
}