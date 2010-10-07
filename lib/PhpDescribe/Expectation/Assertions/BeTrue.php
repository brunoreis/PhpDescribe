<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class BeTrue implements AssertionInterface{
    function execute($subject) {
        if($subject === true) {
            return new ExpectationResult(true);
        }
        else {
            $message = var_export($subject)
                .' <span class="expectationText">should be true</span>';
            return new ExpectationResult(false,$message);
        }
    }

}