<?php
namespace PhpDescribe\Example;
use PhpDescribe\PhpDescribe,
    \Closure;
abstract class AbstractExampleItem {


    protected $name;
    protected $function;
    protected $PhpDescribe;
    protected $parentExampleGroup;
    protected $args;

    function __construct($name, Closure $function, $args = null) {
        $this->name = $name;
        $this->function = $function;
        $this->args = $args;
    }

    function setParentExampleGroup($parent) {
        $this->parentExampleGroup = $parent;
    }

    function getName() {
        return $this->name;
    }

    function getFunction() {
        return $this->function;
    }
    
    abstract function run($parameters);

    function setPhpDescribe(PhpDescribe $PhpDescribe) {
        $this->PhpDescribe = $PhpDescribe;
    }
}