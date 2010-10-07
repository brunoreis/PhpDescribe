<?php
namespace PhpDescribe\TestHooks;
use \Closure;

class AfterAll extends TestHook {
    static function buildHook(Closure $function) {
        return new AfterAll($function);
    }
}