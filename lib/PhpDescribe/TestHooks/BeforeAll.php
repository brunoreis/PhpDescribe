<?php
namespace PhpDescribe\TestHooks;
use \Closure;

class BeforeAll extends TestHook {
    static function buildHook(Closure $function) {
        return new BeforeAll($function);
    }
}