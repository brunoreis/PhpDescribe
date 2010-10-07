<?php
namespace PhpDescribe;
include ('x.php');

use PhpDescribe\Example\Example,
    PhpDescribe\Example\ExampleGroup,
    PhpDescribe\Example\AbstractExampleItem,
    PhpDescribe\Result\ResultGroup,
    PhpDescribe\Exception,
    PhpDescribe\EventListener\EventListenerInterface,
    PhpDescribe\EventListener\DebugEventListener,
    PhpDescribe\Result\ExampleResult;
/**
* PhpDescribe Project
* Licence: creative commons bla bla bla....
*
*/

class PhpDescribe {

    const EVENT_PRE_EXAMPLE_RUN = 'EVENT_PRE_EXAMPLE_RUN';
    const EVENT_POST_EXAMPLE_RUN = 'EVENT_POST_EXAMPLE_RUN';
    const EVENT_PRE_EXAMPLE_GROUP_RUN = 'EVENT_PRE_EXAMPLE_GROUP_RUN';
    const EVENT_POST_EXAMPLE_GROUP_RUN = 'EVENT_POST_EXAMPLE_GROUP_RUN';
    const EVENT_PRE_BEFORE_EACH_RUN = 'EVENT_PRE_BEFORE_EACH_RUN';
    const EVENT_POST_BEFORE_EACH_RUN = 'EVENT_POST_BEFORE_EACH_RUN';
    const EVENT_PRE_AFTER_EACH_RUN = 'EVENT_PRE_AFTER_EACH_RUN';
    const EVENT_POST_AFTER_EACH_RUN = 'EVENT_POST_AFTER_EACH_RUN';
    
    protected $exampleGroups = array();
    private $descriptionFilePaths;
    static $actualSpecData;
    static $beforeInstance;
    static $actualInstance;
    static $specFileBeingIncluded;
    protected $eventListeners;
    protected $parameters;
    protected $openExampleResult;
    

    /**
     * @var PhpDescribe
     */
    static $instance;
    
    /**
     * @return PhpDescribe
     */
    static function build() {
        return new PhpDescribe();
    }

    private function __construct() {
        $this->eventListeners = array();
        $this->parameters = array(
            'only' => null, // the name of an example to run only that
            'dbg' => null, // to show the events, generating debug info
            'act' => null
        );
    }

    private function getDescriptionFilePath($name) {
        return $this->specDir . '/' . $name . '.spec.php';
    }

    public function addParameters($parameters) {
        foreach($parameters as $name=>$value) {
            if(array_key_exists($name, $this->parameters)) {
                $this->parameters[$name] = $parameters[$name];
            }
            else {
                //throw new Exception('Parameter ' . $name . ' is not supported');
            }
        }
    }

    public function setOpenExampleResult(ExampleResult $openExampleResult) {
        $this->openExampleResult = $openExampleResult;
    }

    /**
     * @return ExampleResult
     */
    public function getOpenExampleResult() {
        return $this->openExampleResult;
    }
   
    
    function addDescriptionFilePath($filePath) {
        if(!file_exists($filePath)) {
            if(file_exists($filePath.'.spec.php')) {
                $filePath = $filePath.'.spec.php';
            }
            else {
                throw new \InvalidArgumentException('Spec file not found: ' . $filePath);
            }
        }
        $this->descriptionFilePaths[] = $filePath;
        return $this;
    }

    function countDescriptions() {
        return count($this->descriptionFilePaths);
    }

    /**
     * @return PhpDescribe
     */
    function clearDescriptions() {
        $this->descriptionFilePaths = array();
        return $this;
    }

    /**
     * @return PhpDescribe
     */
    static function getActual() {
        return self::$actualInstance;
    }
    
    static function addExampleGroupToActual(ExampleGroup $exampleGroup) {
        $exampleGroup->setPhpDescribe(self::$actualInstance);
        self::$actualInstance->addExampleGroup($exampleGroup);
    }
    
    function addExampleGroup(ExampleGroup $exampleGroup) {
        $this->exampleGroups[] = $exampleGroup;
        return $this;
    }

    function run() {
        $s = rand(1,5000);
        
        if(array_key_exists('act', $this->parameters)) {
            $act = $this->parameters['act'];
            if($act) {
                call_user_func(array('PhpDescribe\Actions',$act),$_REQUEST);
            }
        }
        if($this->parameters['dbg']) {
            $this->addEventListener(new DebugEventListener());
        }
        // code that allows a PhpDescribe::run() to be called inside a spec.
        // used to test the PhpDescribe code.
        if(self::$actualInstance) {
            self::$beforeInstance = self::$actualInstance;
        }
        else {
            self::$beforeInstance = null;
        }

        self::$actualInstance = $this;
        foreach($this->descriptionFilePaths as $filePath) {
            // the included specs staticaly add example groups to PhpDescribe instance.
            include( $filePath );
        }
        
        
        $resultGroup = new Result\Result(null);
        foreach($this->exampleGroups as $exampleGroup) {
            $resultGroup->addResult($exampleGroup->run($this->parameters));
        }
        //x($resultGroup,0,0);
        
        // code that allows a PhpDescribe::run() to be called inside a spec.
        // used to test the PhpDescribe code.
        if(self::$beforeInstance) {
            self::$actualInstance = self::$beforeInstance;
        }
        $this->exampleGroups = array();
        return $resultGroup;
    }

    function __toString() {
        $s = "";
        foreach($this->exampleGroups as $group) {
            $s .= $group->toString(1);
        }
        return $s;
    }

    function addEventListener(EventListenerInterface $listener) {
        $this->eventListeners[] = $listener;
    }

    function notify($event,AbstractExampleItem $example, Result\ResultGroup $resultGroup) {
        foreach($this->eventListeners as $listener) {
            switch ($event) {
                case self::EVENT_PRE_EXAMPLE_GROUP_RUN:
                    $listener->preExampleGroupRun($example, $resultGroup);
                    break;
                case self::EVENT_POST_EXAMPLE_GROUP_RUN:
                    $listener->postExampleGroupRun($example, $resultGroup);
                    break;
                case self::EVENT_PRE_EXAMPLE_RUN:
                    $listener->preExampleRun($example, $resultGroup);
                    break;
                case self::EVENT_POST_EXAMPLE_RUN:
                    $listener->postExampleRun($example, $resultGroup);
                    break;
                case self::EVENT_PRE_BEFORE_EACH_RUN:
                    $listener->preBeforeEachRun($example, $resultGroup);
                    break;
                case self::EVENT_POST_BEFORE_EACH_RUN:
                    $listener->postBeforeEachRun($example, $resultGroup);
                    break;
                case self::EVENT_PRE_AFTER_EACH_RUN:
                    $listener->preAfterEachRun($example, $resultGroup);
                    break;
                case self::EVENT_POST_AFTER_EACH_RUN:
                    $listener->postAfterEachRun($example, $resultGroup);
                    break;
                default:
                    throw new InvalidArgumentException('Event ' . $event . ' not found');
            }
        }
    }
}