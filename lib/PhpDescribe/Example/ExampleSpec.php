<?php
namespace PhpDescribe\Example;
use \PhpDescribe\World;
class ExampleSpec extends ExampleGroup {

    /**
     * @param string $name
     * @return ExampleSpec
     */
    static function buildExampleSpec($name) {
        $exampleSpec = new ExampleSpec($name);
        return $exampleSpec;
    }

    function __construct($name) {
        $this->name = $name;
    }

    function run($parameters, World $world) {
        $resultGroup = new \PhpDescribe\Result\SpecResult($this->getName());
        return $this->_run($resultGroup,$parameters, $world);
    }
}
