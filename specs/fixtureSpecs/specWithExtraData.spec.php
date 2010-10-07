<?php
namespace PhpDescribe\Spec;
describe('spec with extra data', function() {
    it('extra string data', function() {
        showData('string data', 'ad98as5sad786as8fas87df');
    });
    
    it('extra file data', function() {
        showFileData('file data', __DIR__ . '/extraFileData.txt');
    });
});