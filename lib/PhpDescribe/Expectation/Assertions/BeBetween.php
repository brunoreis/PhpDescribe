<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class BeBetween implements AssertionInterface{
    function execute($subject, $v1, $v2) {
        //\Dbg::x(func_get_args());
        $message = var_export($subject,1)
                .' <span class="expectationText">should be between</span> '
                . var_export($v1,1) . ' and '
                . var_export($v2,1);

        return new ExpectationResult(
            ($subject > $v1 && $subject < $v2),
            $message
        );
    }
}