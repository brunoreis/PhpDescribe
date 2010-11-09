<?php
namespace PhpDescribe\Definition;
use \Closure,
    \PhpDescribe\Example\Example;
class Definition {

    protected $pattern;
    protected $function;

    function __construct($pattern,Closure $function) {
        $this->pattern = $pattern;
        $this->function = $function;
    }

    static function build($pattern,Closure $function) {
        return new Definition($pattern, $function);
    }

    function buildExample($name, $isWorkingIfNoError = false, $prefix = null) {
        preg_match($this->pattern, $name, $matches);
        array_shift($matches);
        return Example::buildExample($name, $this->function, $matches, $isWorkingIfNoError, $prefix);
    }

    function match($name) {
        preg_match($this->pattern, $name, $matches);
        return (boolean)count($matches);
    }
}