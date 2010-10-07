<?php
namespace PhpDescribe\Spec;
describe('Passing spec', function() {
    it('passing example', function() {
        expect(1)->should('be',1);
    });
});
