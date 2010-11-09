<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class BeIn implements AssertionInterface{

    function execute($subject, $array) {
        $result = in_array($subject, $array);
        $message = null;
        if(!$result) {
            $message = var_export($subject,1)
                .' <span class="expectationText">should be in</span> '
                . var_export($array,1);
        }
        return new ExpectationResult($result,$message);
    } 
}