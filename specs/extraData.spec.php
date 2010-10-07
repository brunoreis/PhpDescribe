<?php
namespace PhpDescribe\Spec;

it('To show the content of a file, use: showFileData($name, $path, $escapeHtml = true)',function() {
    $spec1 = __DIR__.'/fixtureSpecs/specWithExtraData.spec.php';
    $result = \PhpDescribe\Runner::build()->setSpec($spec1)->runAndReport();
    expect($result)->should('contain text','ad98as5sad786as8fas87df');
});

it('To show any string data, use: showData($name, $content, $escapeHtml = true)',function() {
    $spec1 = __DIR__.'/fixtureSpecs/specWithExtraData.spec.php';
    $result = \PhpDescribe\Runner::build()->setSpec($spec1)->runAndReport();
    expect($result)->should('contain text','diosadfsa87svjslakj');
});

