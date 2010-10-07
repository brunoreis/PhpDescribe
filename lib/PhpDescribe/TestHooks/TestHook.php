<?php
namespace PhpDescribe\TestHooks;
use \Closure;

class TestHook extends \PhpDescribe\Example\AbstractExampleItem{
    protected $function;

    function __construct(Closure $function) {
        $this->function = $function;
    }

    function run($parameters) {
        $f = $this->function;
        $f();
    }

    /**
     * @return Closure
     */
    function getFunction() {
        return $this->function;
    }
}