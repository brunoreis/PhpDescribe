<?php
namespace PhpDescribe\Spec;
describe('Failing spec', function() {
    it('Example with error', function() {
        $a = $undefined;
    });
    it('Example with exception', function() {
        throw new \Exception;
    });
});
