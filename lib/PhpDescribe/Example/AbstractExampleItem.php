<?php
namespace PhpDescribe\Example;
use PhpDescribe\PhpDescribe,
    PhpDescribe\World,
    \Closure;
abstract class AbstractExampleItem {


    protected $name;
    protected $function;
    protected $PhpDescribe;
    protected $parentExampleGroup;
    protected $args;
    protected $variables = array();
    

    function __construct($name, Closure $function, $args = null) {
        $this->name = $name;
        $this->function = $function;
        $this->args = $args;
        $this->variables = array();
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
    
    abstract function run($parameters, World $world);

    function setPhpDescribe(PhpDescribe $PhpDescribe) {
        $this->PhpDescribe = $PhpDescribe;
    }

    function overrideVariables($variables) {
        foreach($variables as $k=>$v) {
            $this->variables[$k] = $v;
        }
    }

    function getVariables() {
        return $this->variables;
    }
}