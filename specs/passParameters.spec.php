<?php
namespace PhpDescribe\Spec;

it('The arguments are declared with commas and will be available inside the example function' , function() {
    $spec2 = __DIR__.'/fixtureSpecs/passParameters/passParameters.spec.php';
    $results = \PhpDescribe\Runner::build()->setSpec($spec2)->run()->getResults();
    $results = $results[0]->getResults();
    expect($results[0]->calculateStatus())->should('be', \PhpDescribe\Result\ResultGroup::STATUS_WORKING);
    showFileData('spec with passing parameter', $spec2);
});

it('The parameters will be available as an ordered array, according to the order they appear on the example title' , function() {
    $spec2 = __DIR__.'/fixtureSpecs/passParameters/passParameters.spec.php';
    $results = \PhpDescribe\Runner::build()->setSpec($spec2)->run()->getResults();
    $results = $results[0]->getResults();
    expect($results[1]->calculateStatus())->should('be', \PhpDescribe\Result\ResultGroup::STATUS_WORKING);
    showFileData('spec with passing parameter', $spec2);
});