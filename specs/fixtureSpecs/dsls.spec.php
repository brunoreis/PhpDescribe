<?php

namespace PhpDescribe\Spec;

describe('trunk', function() {
    def('/(\d*) plus (\d*) should be (\d*)/',function($args) {
        expect($args[0] + $args[1])->should('be', (int)$args[2]);
    });

    def('/(\d*) times (\d*) should be (\d*)/',function($args) {
        expect($args[0] * $args[1])->should('be', (int)$args[2]);
    });

    it('3 plus 6 should be 9');

    describe('branch 1', function() {

        def('/(\d*) minus (\d*) should be (\d*)/',function($args) {
            expect($args[0] - $args[1])->should('be', (int)$args[2]);
        });

        it('7 plus 13 should be 20');
        
        it('10 minus 3 should be 7');
        
    });

    describe('branch 2', function() {

        it('10 minus 7 should be 3');

    });

});
