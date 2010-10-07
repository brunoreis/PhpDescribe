<?php
namespace PhpDescribe\Spec;
describe('Passing spec', function() {

    it('works for same type', function() {
        expect('3')->should('be','3');
    });

    it('does not work for different types', function() {
        expect('3')->should('be',3);
    });
    
    it('does not work for different values', function() {
        expect('3')->should('be','4');
    });
    
});