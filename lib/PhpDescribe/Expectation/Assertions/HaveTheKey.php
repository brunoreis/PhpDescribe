<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class HaveTheKey implements AssertionInterface{

    function execute($subject, $key) {
        
        $message = var_export($subject,1)
                .' <span class="expectationText">should have the key</span> '
                . var_export($key,1);
        return new ExpectationResult(
            array_key_exists($key, $subject),
            $message
        );
    }
    
}