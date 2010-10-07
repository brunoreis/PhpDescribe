<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class BeADateEqualsOrBefore implements AssertionInterface{

    function execute($subject, $expectedValue) {
        if(!($expectedValue instanceof \DateTime)) {
            throw new InvalidExpectationParameterException('Expected value parameter has to be a DateTime object.');
        }
        if(!($subject instanceof \DateTime)) {
            throw new InvalidExpectationParameterException('The subject is not a DateTime object.');
        }
        $message = $subject->format('Y-m-d H:i:s')
            .' <span class="expectationText">should be a Datetime</span> '
            . $expectedValue->format('Y-m-d H:i:s');
        
        
        return new ExpectationResult(
            ($subject <= $expectedValue),
            $message
        );
    }

}