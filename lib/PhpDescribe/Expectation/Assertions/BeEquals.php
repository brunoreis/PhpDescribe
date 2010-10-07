<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class BeEquals implements AssertionInterface{

    function execute($subject, $expectedValue) {
        $message = var_export($subject,1)
                .' <span class="expectationText">should be equals</span> '
                . var_export($expectedValue,1);

        return new ExpectationResult(
            ($subject === $expectedValue),
            $message
        );
    }
    
}