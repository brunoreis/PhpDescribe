<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class BeAnInstanceOf {
    function execute($subject, $expectedClassName) {

        $message = get_class($subject)
                .' <span class="expectationText">should be an instance of</span> '
                . $expectedClassName;

        return new ExpectationResult(
            ($subject instanceof $expectedClassName),
            $message
        );
    }
}