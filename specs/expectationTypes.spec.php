<?php
namespace PhpDescribe\Spec;
use \PhpDescribe\Result\ResultGroup;
describe('expectException() can be used to assert that an exception will be thrown',function() {
    it('should have a not working status if an exception is not trown',function() {
        $results = runAndAskToShowSpecDataAndGetFirstExampleResults(
            __DIR__.'/fixtureSpecs/expectations/expectException'
        );
        expect($results[0]->calculateStatus())->should('be', ResultGroup::STATUS_NOT_WORKING);
    });
    it('should have a working status if an exception is trown',function() {
        $results = runAndAskToShowSpecDataAndGetFirstExampleResults(
            __DIR__.'/fixtureSpecs/expectations/expectException'
        );
        expect($results[1]->calculateStatus())->should('be', ResultGroup::STATUS_WORKING);
    });
});
describe('be',function() {
    it('should work if the subject has the same value and type as the expected value',function() {
        $results = runAndAskToShowSpecDataAndGetFirstExampleResults(
            __DIR__.'/fixtureSpecs/expectations/be'
        );
        expect($results[0]->calculateStatus())->should('be', ResultGroup::STATUS_WORKING);
    });

    it('should not work if the subject has the same value as the expected value, but a different type',function() {
        $results = runAndAskToShowSpecDataAndGetFirstExampleResults(
            __DIR__.'/fixtureSpecs/expectations/be'
        );
        expect($results[1]->calculateStatus())->should('be', ResultGroup::STATUS_NOT_WORKING);
    });

    it('should not work if the subject and expected value have different values',function() {
        $results = runAndAskToShowSpecDataAndGetFirstExampleResults(
            __DIR__.'/fixtureSpecs/expectations/be'
        );
        expect($results[2]->calculateStatus())->should('be', ResultGroup::STATUS_NOT_WORKING);
    });
});

describe('be greater than',function() {
    it('should work if the expected value is less than the subject',function() {
        $results = runAndAskToShowSpecDataAndGetFirstExampleResults(
            __DIR__.'/fixtureSpecs/expectations/beGreaterThan'
        );
        expect($results[0]->calculateStatus())->should('be', ResultGroup::STATUS_WORKING);
    });
    it('should not work if the expected value is less than the subject',function() {
        $results = runAndAskToShowSpecDataAndGetFirstExampleResults(
            __DIR__.'/fixtureSpecs/expectations/beGreaterThan'
        );
        expect($results[1]->calculateStatus())->should('be', ResultGroup::STATUS_NOT_WORKING);
    });
    it('should not work if the expected value is equals the subject',function() {
        $results = runAndAskToShowSpecDataAndGetFirstExampleResults(
            __DIR__.'/fixtureSpecs/expectations/beGreaterThan'
        );
        expect($results[2]->calculateStatus())->should('be', ResultGroup::STATUS_NOT_WORKING);
    });

    
});