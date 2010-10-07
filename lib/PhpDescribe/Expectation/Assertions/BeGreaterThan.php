<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class BeGreaterThan implements AssertionInterface{

    function execute($subject, $expectedValue) {
        $isGreater = $subject > $expectedValue;
        $message = null;
        if(!$isGreater) {
            $message = var_export($subject,true)
               .' <span class="expectationText">should be greater than </span> '
               . var_export($expectedValue,true);
        }
        return new ExpectationResult($isGreater,$message);
    }

}