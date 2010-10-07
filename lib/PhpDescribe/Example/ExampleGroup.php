<?php
namespace PhpDescribe\Example;
use PhpDescribe\TestHooks\BeforeEach,
    PhpDescribe\PhpDescribe,
    PhpDescribe\TestHooks\AfterEach,
    PhpDescribe\TestHooks\BeforeAll,
    PhpDescribe\TestHooks\AfterAll,
    PhpDescribe\Result\ExampleResult,
    \Closure,
    \PhpDescribe\Definition\Definition,
    \PhpDescribe\Result\ResultGroup;

class ExampleGroup extends AbstractExampleItem {

    protected $name;
    protected $definitions = array();
    protected $beforeEach = null;
    protected $afterEach = null;
    protected $filePath;
    protected $examples = array();
    
    

    //protected $function;

//    function __construct($name) {
//        $this->name = $name;
//    }

    function addDefinition(Definition $definition) {
        $this->definitions[] = $definition;
    }

    function findDefinition($name) {
        foreach($this->definitions as $definition) {
            if($definition->match($name)) {
                return $definition;
            }
        }
        if($this->parentExampleGroup) {
            return $this->parentExampleGroup->findDefinition($name);
        }
    }

    static function buildExampleGroup($name, Closure $function) {
        $exampleGroup = new ExampleGroup($name, $function);
        return $exampleGroup;
    }

    function setFile($filePath) {
        $this->filePath = $filePath;
    }

    function getFile($recursive = false) {
        if($this->filePath) {
            return $this->filePath;
        }
        elseif($this->parentExampleGroup && $recursive) {
            return $this->parentExampleGroup->getFile();
        }
    }

    
    
    function addExample(AbstractExampleItem $example) {
        $example->setPhpDescribe($this->PhpDescribe);
        $example->setParentExampleGroup($this);
        $this->examples[] = $example;
    }

    function setBeforeEach(BeforeEach $item) {
        $this->beforeEach = $item;
    }
    
    function setAfterEach(AfterEach $item) {
        $this->afterEach = $item;
    }

    /**
     * @return PhpDescribe\TestHooks\BeforeEach
     */
    function getBeforeEach() {
        return $this->beforeEach;
    }

    /**
     * @return PhpDescribe\TestHooks\AfterEach
     */
    function getAfterEach() {
        return $this->afterEach;
    }
    
    function getName() {
        return $this->name;
    }

    function toString($level) {
        $s = str_repeat('::::::', $level) . '< ' . $this->name . " >\n";
        foreach($this->examples as $example) {
            $s.= $example->toString($level + 1);
        }
        return $s;
    }

    /**
     * @return ResultGroup
     */
    function run($parameters) {
        $resultGroup = new \PhpDescribe\Result\ResultGroup($this->getName(),$this->getFile());
        $this->PhpDescribe->notify(PhpDescribe::EVENT_PRE_EXAMPLE_GROUP_RUN,$this,$resultGroup);
        $ret = $this->_run($resultGroup,$parameters);
        $this->PhpDescribe->notify(PhpDescribe::EVENT_POST_EXAMPLE_GROUP_RUN,$this,$resultGroup);
        return $ret;
    }

    function _run($resultGroup,$parameters) {
        foreach($this->examples as $example) {
            if($example instanceof  Example) {
                if($this->beforeEach) {
                    $example->setBeforeEach($this->beforeEach);
                }
                if($this->afterEach) {
                    $example->setAfterEach($this->afterEach);
                }
            }
            $result = $example->run($parameters);
            $resultGroup->addResult($result);
        }
        return $resultGroup;
    }
}