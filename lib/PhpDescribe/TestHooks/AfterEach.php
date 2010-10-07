<?php
namespace PhpDescribe\TestHooks;
use \Closure;

class AfterEach extends TestHook {
    static function buildHook(Closure $function) {
        return new AfterEach($function);
    }
}