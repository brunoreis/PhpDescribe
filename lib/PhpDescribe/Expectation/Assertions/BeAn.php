<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class BeAn implements AssertionInterface{

    function execute($subject, $expectedValue) {
        $result = gettype($subject) == $expectedValue;
        $message = null;
        if(!$result) {
            $message = var_export($subject,1)
                .' <span class="expectationText">should be a</span> '
                . $expectedValue;
        }
        return new ExpectationResult($result,$message);
    } 
}