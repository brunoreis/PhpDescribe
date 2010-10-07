<?php
namespace PhpDescribe\TestHooks;
use \Closure;

class BeforeEach extends TestHook {
    static function buildHook(Closure $function) {
        return new BeforeEach($function);
    }
}