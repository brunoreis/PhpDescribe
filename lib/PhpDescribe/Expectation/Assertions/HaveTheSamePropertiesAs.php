<?php
namespace PhpDescribe\Expectation\Assertions;
use PhpDescribe\Expectation\InvalidExpectationParameterException,
    PhpDescribe\Result\ExpectationResult;

class HaveTheSamePropertiesAs implements AssertionInterface{
    function execute($subject, $referenceObject, $propertiesArray) {
        /*$message = get_class($subject)
                .' <span class="expectationText">should have only instances of</span> '
                . $expectedClassName;*/

        $message = null;
        foreach($propertiesArray as $propertyName) {
            $getterName = 'get' . ucfirst($propertyName);
            if(
                !method_exists($subject, $getterName) ||
                !method_exists($subject, $getterName)
            ) {
                throw new InvalidExpectationParameterException(
                    'To use this assertion both objects have to implement a getter named ' . $getterName
                );
            }
            if(!is_object($subject) || !is_object($referenceObject)) {
                throw new InvalidExpectationParameterException(
                    'The subject or expected value is not an object.'
                );
            }
            $expectedValue = $referenceObject->$getterName();
            $actualValue = $subject->$getterName();
            if($expectedValue !== $actualValue) {
                $message .= "<span class='expectationText'>'" . $propertyName . "' value is </span>"
                . var_export($actualValue,1)
                . ' <span class="expectationText">. It should be : </span> '
                . var_export($expectedValue,1);
            }
        }
        return new ExpectationResult($message == null ? true : false,$message);
        
    }
}