<?php
namespace PhpDescribe\Spec;
describe('Greater than specs', function() {

    it('should pass', function() {
        expect(5)->should('be greater than',2);
    });

    it('should fail', function() {
        expect(3)->should('be greater than',8);
    });
    
    it('should be greater than', function() {
        expect(3)->should('be greater than',3);
    });

});