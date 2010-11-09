<?php
namespace PhpDescribe\Result;
use \Exception,
    \PhpDescribe\PhpDescribe,
    \SplDoublyLinkedList;

class ResultGroup implements \Iterator {

    private $position = 0;
    protected $exampleName;

    protected $file;
    protected $startLineNumber;
    protected $endLineNumber;
    
    protected $closureBody;
    protected $results = array();
    /**
     * @var a map with names and html as values to be showed on the result
     */
    protected $extraInformation = array();
    
    const STATUS_WORKING      = 'WORKING';
    const STATUS_NOT_WORKING  = 'NOT_WORKING';
    const STATUS_ERROR        = 'ERROR';
    const STATUS_INCOMPLETE   = 'INCOMPLETE';

    function __construct($exampleName) {
        $this->exampleName = $exampleName;
        $this->position = 0;
        $this->results = new SplDoublyLinkedList();
    }

    function setFileInfo($file,$startLineNumber, $endLineNumber) {
        $this->file = $file;
        $this->startLineNumber = $startLineNumber;
        $this->endLineNumber = $endLineNumber;
    }

    function getFile() {
        return $this->file;
    }

    function getStartLineNumber() {
        return $this->startLineNumber;
    }
    function getEndLineNumber() {
        return $this->endLineNumber;
    }

    static function formatExceptionMessage(Exception $e) {
        return "<i>Exception Caught:</i> " . get_class($e) . "\n"
                    . '<i>message:</i>"' . $e->getMessage() . "\"\n"
                    . "<i>trace:</i><br/>"
                    . $e->getTraceAsString();
    }

    function calculateStatus() {
        if($this->countErrors()) return self::STATUS_ERROR;
        if($this->countNotWorking()) return self::STATUS_NOT_WORKING;
        if($this->countIncomplete()) return self::STATUS_INCOMPLETE;
        return self::STATUS_WORKING;
    }

    function addExtraInformation($name,$value) {
        $this->extraInformation[$name] = $value;
    }

    function getExtraInformation() {
        return $this->extraInformation;
    }

    function countErrors() {
        $count = 0;
        foreach($this->results as $result) {
            $count += $result->countErrors();
        }
        return $count;
    }
    
    function countExamples() {
        $count = 0;
        foreach($this->results as $result) {
            $count += $result->countExamples();
        }
        return $count;
    }
    
    function countNotWorking() {
        $count = 0;
        foreach($this->results as $result) {
            $count += $result->countNotWorking();
        }
        return $count;
    }
    
    function countIncomplete() {
        $count = 0;
        foreach($this->results as $result) {
            $count += $result->countIncomplete();
        }
        return $count;
    }

    function countResults() {
        return count($this->results);
    }

    function getResult($index) {
        return $this->results[$index];
    }

    function getExampleName() {
        return $this->exampleName;
    }

    function addResult($result) {
        $this->results[] = $result;
    }

    function rewind() {
        $this->position = 0;
    }

    function setClosureBody($closureBody) {
        $this->closureBody = $closureBody;
    }

    function current() {
        return $this->results[$this->position];
    }

    function key() {
        return $this->position;
    }

    function next() {
        ++$this->position;
    }

    function getResults() {
        return $this->results;
    }

    function valid() {
        return isset($this->results[$this->position]);
    }
}