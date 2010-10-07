<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class Have implements AssertionInterface{

    function execute($subject, $expectedCount, $propertyName) {
        $message = null;
        if(!array_key_exists($propertyName, $subject)) {
            $message = var_export($subject,1)
                .' <span class="expectationText">should have a property named </span> '
                . $propertyName;
        }
        else {
            if(count($subject->$propertyName) !== $expectedCount) {
                $message = var_export($subject,1)
                    .' <span class="expectationText">should have </span> '
                    . $expectedCount . ' ' . $propertyName
                    .' <span class="expectationText">but it has </span> '
                    . count($subject->$propertyName);
            }
        }
        if($message) {
            return new ExpectationResult(false,$message);
        }
        return new ExpectationResult(true);
    } 
}