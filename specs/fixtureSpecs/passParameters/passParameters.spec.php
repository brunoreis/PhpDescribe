<?php
namespace PhpDescribe\Spec;
describe('spec', function() {
    it('eight should be equals number "8"', function($args) {
        expect($args[0])->should('be','8');
    });
    
    it('Hello world my name is "argument" and my brother is "parameter"', function($args) {
        expect($args[0])->should('be','argument');
        expect($args[1])->should('be','parameter');
    });
});

