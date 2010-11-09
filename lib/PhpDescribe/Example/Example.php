<?php
namespace PhpDescribe\Example;
use PhpDescribe\TestHooks\BeforeEach,
    PhpDescribe\TestHooks\AfterEach,
    PhpDescribe\PhpDescribe,
    PhpDescribe\World,
    \Closure,
    \PhpDescribe\Result\ResultGroup;

class Example extends AbstractExampleItem{

    
    protected $expectedExceptionClass = null;
    protected static $openExampleResult;
    protected static $openExample;
    protected $beforeEach = null;
    protected $afterEach = null;
    protected $args;
    protected $isWorkingIfNoError = false;
    protected $prefix = null;

    
    static function buildExample($name, Closure $function, $args = null, $isWorkingIfNoError = false, $prefix = null) {
        $example = new Example($name, $function, $args);
        $example->setIsWorkingIfNoError($isWorkingIfNoError);
        $example->setPrefix($prefix);
        return $example;
    }

    function setIsWorkingIfNoError($isWorkingIfNoError) {
        $this->isWorkingIfNoError = $isWorkingIfNoError;
    }
    function getIsWorkingIfNoError() {
        return $this->isWorkingIfNoError;
    }
    function setPrefix($prefix) {
        $this->prefix = $prefix;
    }
    function getPrefix() {
        return $this->prefix;
    }

    function toString($level) {
        $s = str_repeat('::::::', $level) . $this->name . "\n";
        return $s;
    }

    function setBeforeEach(BeforeEach $item) {
        $this->beforeEach = $item;
    }

    function setAfterEach(AfterEach $item) {
        $this->afterEach = $item;
    }
    
    static function addExpectedExceptionToOpenExample($exceptionClassName) {
        self::$openExample->setExpectedException($exceptionClassName);
    }

    function setExpectedException($exceptionClassName) {
        $this->expectedExceptionClass = $exceptionClassName;
    }
    

    /**
     * @return ExampleResult
     */
    function run($parameters, World $world) {
        $name = '';
        if($this->getPrefix()) {
          $name .= $this->getPrefix() . ' ';
        }
        $name .= $this->getName();
        $resultGroup = new \PhpDescribe\Result\ExampleResult( $name );
        if($parameters['only']) {
            if($parameters['only'] !== $this->getName()) {
                return $resultGroup;
            }
        }
        PhpDescribe::getActual()->setOpenExampleResult($resultGroup);
        self::$openExample = $this;
        $f = $this->function;
        $this->PhpDescribe->notify(PhpDescribe::EVENT_PRE_EXAMPLE_RUN,$this,$resultGroup);
        $continue = true;
        if($this->beforeEach) {
            try {
                $this->PhpDescribe->notify(PhpDescribe::EVENT_PRE_BEFORE_EACH_RUN,$this,$resultGroup);
                $this->beforeEach->run($parameters);
                $this->PhpDescribe->notify(PhpDescribe::EVENT_POST_BEFORE_EACH_RUN,$this,$resultGroup);
            }
            catch(\Exception $e) {
                $continue = false;
                self::$openExampleResult->setError("ON BEFORE EACH \n" . ResultGroup::formatExceptionMessage($e));
            }
        }
        if($continue) {
            try {
                $arguments = $this->defineArguments($world);
                $f($arguments);
                if($this->expectedExceptionClass) {
                    PhpDescribe::getActual()->getOpenExampleResult()->addResult(new \PhpDescribe\Result\ExpectationResult(
                            false,
                            'Expected ' . $this->expectedExceptionClass . ' to be thrown'
                    ));
                }

            }
            catch(\Exception $e) {
                if(
                    $this->expectedExceptionClass &&
                    ($e instanceof $this->expectedExceptionClass)
                ) {
                    PhpDescribe::getActual()->getOpenExampleResult()->addResult(new \PhpDescribe\Result\ExpectationResult(true));
                }
                else {
                    PhpDescribe::getActual()->getOpenExampleResult()->setError(ResultGroup::formatExceptionMessage($e));
                }
            }
        }
        if($this->afterEach) {
            try {
                $this->PhpDescribe->notify(PhpDescribe::EVENT_PRE_AFTER_EACH_RUN,$this,$resultGroup);
                $this->afterEach->run($parameters);
                $this->PhpDescribe->notify(PhpDescribe::EVENT_POST_AFTER_EACH_RUN,$this,$resultGroup);
            }
            catch(\Exception $e) {
                // PhpDescribe::getActual()->getOpenExampleResult()
                self::$openExampleResult->setError("ON AFTER EACH \n" . ResultGroup::formatExceptionMessage($e));
            }
        }

        if(
            PhpDescribe::getActual()->getOpenExampleResult()->calculateStatus() == ResultGroup::STATUS_INCOMPLETE
            && $this->isWorkingIfNoError
        ) {
            PhpDescribe::getActual()->getOpenExampleResult()->addResult(new \PhpDescribe\Result\ExpectationResult(true));
        }
        $this->PhpDescribe->notify(PhpDescribe::EVENT_POST_EXAMPLE_RUN,$this,$resultGroup);
        self::$openExampleResult = null;
        return $resultGroup;
    }

    private function defineArguments(&$world) {
        if(count($this->args)) {
            $args =  $this->args;
        }
        else {
            $args = $this->extractNameArguments($this->name);
        }
        $args['world'] = $world;
        return $args;
    }

    private function extractNameArguments($name) {
        preg_match_all('/"([^"]*)"/', $name, $matchesarray);
        return $matchesarray[1];
    }
}