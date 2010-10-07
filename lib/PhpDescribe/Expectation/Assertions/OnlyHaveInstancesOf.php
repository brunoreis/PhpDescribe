<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class OnlyHaveInstancesOf implements AssertionInterface{
    function execute($subject, $expectedClassName, $numberOfItens = null) {
        foreach($subject as $item) {
            if(!($item instanceof $expectedClassName)) {
                $message =
                    gettype($subject)
                    .' <span class="expectationText">should have only instances of</span> '
                    . $expectedClassName;
                return new ExpectationResult(false,$message);
            }
        }
        if(
                $numberOfItens &&
                count($subject) !== $numberOfItens
        ) {
            $message =
                gettype($subject)
                .' <span class="expectationText">should have exactly </span>' . $numberOfItens . '<span class="expectationText"> instance(s) of</span> '
                . $expectedClassName;
            return new ExpectationResult(false,$message);
        }
        return new ExpectationResult(true);
    }
}