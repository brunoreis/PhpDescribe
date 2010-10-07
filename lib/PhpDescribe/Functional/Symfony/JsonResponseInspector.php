<?php
namespace PhpDescribe\Functional\Symfony;
use ArrayAccess,
    InvalidArgumentException,
    PhpDescribe\Exception;

class JsonResponseInspector implements ArrayAccess {
    private $data = array();

    function __construct($data) {
        $this->data = $data;
    }

    public function offsetSet($offset, $value) {
        $this->data[$offset] = $value;
    }

    public function offsetExists($offset) {
        return isset($this->data[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->data[$offset]);
    }

    public function offsetGet($offset) {
        $objects = explode('.',$offset);
        $objectName = array_shift($objects);
        $object = $this->getRepositoryItemBeingSet($objectName);
        $fullObjectName = $objectName;
        if(count($objects)) {
            foreach($objects as $innerObjectName) {
                $fullObjectName = $fullObjectName .'.'.$innerObjectName;
                if(!array_key_exists($innerObjectName, $object)) {
                    throw new InvalidArgumentException('There is a command that sets the repository item, but it does not have the property "' . $fullObjectName . '"');
                }
                $object = $object->$innerObjectName;
            }
        }
        //x($object);
        return $object;
    }

    public function hasCommand($controller,$action,$parameters = array()) {
        foreach($this->data->commands as $command) {
            if(
                $command->controller == $controller &&
                $command->action == $action
            ) {
                $hasTheExpectedParameters = true;
                foreach($parameters as $name=>$expectedValue) {
                    if(
                       // does not have the expected parameter
                       !array_key_exists($name, $command->params)
                       // does not have the right value
                       || $command->params->$name !== $expectedValue
                    ) {
                        $hasTheExpectedParameters = false;
                    }
                }
                if($hasTheExpectedParameters) {
                    return true;
                }
            }
        }
        return false;
    }

    private function getRepositoryItemBeingSet($name) {
        if(!array_key_exists('commands',$this->data)) {
            throw new InvalidArgumentException('The json response has no commands!');
        }
        $alreadyFoundOne = false;
        foreach($this->data->commands as $command) {
            if(
                $command->controller == 'ria2.repository'
                && $command->action == 'setItems'
                && array_key_exists($name,$command->params)
            ) {
                if($alreadyFoundOne) {
                    throw new Exception('The json response sets the repository item twice!');
                }
                $itemFound = $command->params->$name;
                $alreadyFoundOne = true;
            }
        }
        if($alreadyFoundOne) {
            return $itemFound;
        }
        else {
            throw new InvalidArgumentException('There is no command on this response that sets the repository item "' . $name . '"');
        }
    }
}