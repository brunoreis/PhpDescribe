<?php
namespace PhpDescribe\Spec;
describe('Passing spec', function() {

    it('frustrated expectation', function() {
        expectException('Exception');
    });

    it('exception is thrown', function() {
        expectException('Exception');
        throw new \Exception();
    });
    
});