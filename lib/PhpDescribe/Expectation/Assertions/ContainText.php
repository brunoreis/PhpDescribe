<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class ContainText implements AssertionInterface{

    function execute($subject, $expectedValue) {
        $message = $expectedValue
                .' <span class="expectationText">should be contained by:</span> '
                . htmlentities($subject);
        return new ExpectationResult(
            (strpos($subject, $expectedValue) !== false),
            $message
        );
    }
    
}