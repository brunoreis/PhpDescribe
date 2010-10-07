<?php
namespace PhpDescribe\EventListener;
use PhpDescribe\Example\Example,
    PhpDescribe\Result\ResultGroup,
    PhpDescribe\Example\ExampleGroup,
    Closure,
    ReflectionFunction;

class DisplayCodeListener  extends EventListener {

    protected $readFiles = array();
    function postExampleRun(Example $e,ResultGroup $resultGroup) {
        $resultGroup->addExtraInformation(
            'code',
            $this->getClosureCode($e->getFunction())
        );
    }

    public function preExampleGroupRun(ExampleGroup $example,ResultGroup $resultGroup) {
        $beforeEach = $example->getBeforeEach();
        if($beforeEach) {
            $resultGroup->addExtraInformation(
                'beforeEach code',
                $this->getClosureCode($beforeEach->getFunction())
            );
        }

        $afterEach = $example->getAfterEach();
        if($afterEach) {
            $resultGroup->addExtraInformation(
                'afterEach code',
                $this->getClosureCode($afterEach->getFunction())
            );
        }
    }

    private function getClosureCode(Closure $function) {
        $info = new ReflectionFunction($function);
        $fileName = $info->getFileName();
        $startLine = $info->getStartLine();
        $endLine = $info->getEndLine();
        return '<br/>'
                . '<span class="filename">' . $fileName . ' (lines ' . $startLine . ' to ' . $endLine . ')' . '</span>'
                ."<div class='code'>"
                . highlight_string("<?php \n".$this->readCode($fileName, $startLine, $endLine),true)
                ."</div>";
    }

    private function readCode($fileName, $startLine, $endLine) {
        if(!array_key_exists($fileName, $this->readFiles)) {
            $this->readFiles[$fileName] = file($fileName);
        }
        $code = '';
        for($a = $startLine; $a <= $endLine; $a++) {
            $code .= $this->readFiles[$fileName][$a-1];
        }
        return $code;
    }

}
