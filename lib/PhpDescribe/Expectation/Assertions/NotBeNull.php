<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class NotBeNull implements AssertionInterface{
    function execute($subject) {
        if($subject !== null) {
            return new ExpectationResult(true);
        }
        else {
            return new ExpectationResult(
                    false,
                    var_export($subject)
                        .' <span class="expectationText">should NOT be NULL</span>'
            );
        }
    }

}