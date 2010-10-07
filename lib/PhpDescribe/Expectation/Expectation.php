<?php
namespace PhpDescribe\Expectation;
use PhpDescribe\PhpDescribe;

class Expectation {
    
    protected $subject;
    static $registeredAssertions;

    static function getRegisteredAssertions() {
        if(!is_array(self::$registeredAssertions)) {
            self::$registeredAssertions = array(
                'be'  => 'PhpDescribe\Expectation\Assertions\Be',
                'be an'  => 'PhpDescribe\Expectation\Assertions\BeAn',
                'be a'  => 'PhpDescribe\Expectation\Assertions\BeAn',
                'be greater than'  => 'PhpDescribe\Expectation\Assertions\BeGreaterThan',
                'have'  => 'PhpDescribe\Expectation\Assertions\Have',
                'not be' => 'PhpDescribe\Expectation\Assertions\NotBe',
                'be an instance of' => 'PhpDescribe\Expectation\Assertions\BeAnInstanceOf',
                'be between' => 'PhpDescribe\Expectation\Assertions\BeBetween',
                'only have instances of' => 'PhpDescribe\Expectation\Assertions\OnlyHaveInstancesOf',
                'be a date equals or before' => 'PhpDescribe\Expectation\Assertions\BeADateEqualsOrBefore',
                'have the same properties as' => 'PhpDescribe\Expectation\Assertions\HaveTheSamePropertiesAs',
                'have the key' => 'PhpDescribe\Expectation\Assertions\HaveTheKey',
                'contain text' => 'PhpDescribe\Expectation\Assertions\ContainText'
            );
        }
        return self::$registeredAssertions;
    }

    static function registerAssertionClass($name,$className) {
        // only to initialize the static property
        self::getRegisteredAssertions();
        self::$registeredAssertions[$name] = $className;
    }

    function  __construct($subject) {
        $this->subject = $subject;
    }

    function should($assertionName) { 
        $args = func_get_args();
        $registeredAssertions = self::getRegisteredAssertions();
        
        if(!array_key_exists($assertionName,$registeredAssertions)) {
            throw new \Exception('Assertion "' . $assertionName . '" does not exists');
        }
        $assertionClass = $registeredAssertions[$assertionName];
        $assertion = new $assertionClass;
        $args[0] = $this->subject;
        $expectationResult = call_user_func_array(array($assertion,'execute'), $args);
        PhpDescribe::getActual()->getOpenExampleResult()->addResult($expectationResult);
    }
}