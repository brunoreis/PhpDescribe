<?php 
namespace PhpDescribe\Spec;
use \PhpDescribe\Result\ResultGroup;

it('should match an example pattern and run it', function() {
    $spec1 = __DIR__.'/fixtureSpecs/dsls.spec.php';
    $result = \PhpDescribe\Runner::build()->setSpec($spec1)->run();
    expect($result->getResult(0)->getResult(0)->calculateStatus())->should('be',  ResultGroup::STATUS_WORKING);
    showFileData('spec for dsls', $spec1);
});

it('should match an example pattern found on a parent example group and run it', function() {
    $spec1 = __DIR__.'/fixtureSpecs/dsls.spec.php';
    $result = \PhpDescribe\Runner::build()->setSpec($spec1)->run();
    $r = $result->getResult(0)->getResult(1)->getResult(0);
    expect($r->calculateStatus())->should('be',  ResultGroup::STATUS_WORKING);
    showFileData('spec for dsls', $spec1);
});

it('should not match an example pattern found on a different branch of the tree', function() {
    $spec1 = __DIR__.'/fixtureSpecs/dsls.spec.php';
    $result = \PhpDescribe\Runner::build()->setSpec($spec1)->run();
    $r = $result->getResult(0)->getResult(2)->getResult(0);
    expect($r->calculateStatus())->should('be',  ResultGroup::STATUS_ERROR);
    $r = $result->getResult(0)->getResult(1)->getResult(1);
    expect($r->calculateStatus())->should('be',  ResultGroup::STATUS_WORKING);
    showFileData('spec for dsls', $spec1);
});