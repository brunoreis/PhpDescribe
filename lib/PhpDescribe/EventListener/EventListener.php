<?php
namespace PhpDescribe\EventListener;
use PhpDescribe\Example\Example,
    PhpDescribe\Example\ExampleGroup,
    PhpDescribe\Result\ResultGroup;

class EventListener implements EventListenerInterface {
    public function preExampleGroupRun(ExampleGroup $example,ResultGroup $resultGroup){}
    public function postExampleGroupRun(ExampleGroup $example,ResultGroup $resultGroup){}
    public function preExampleRun(Example $example,ResultGroup $resultGroup){}
    public function postExampleRun(Example $example,ResultGroup $resultGroup){}
    public function preBeforeEachRun(Example $example,ResultGroup $resultGroup){}
    public function postBeforeEachRun(Example $example,ResultGroup $resultGroup){}
    public function preAfterEachRun(Example $example,ResultGroup $resultGroup){}
    public function postAfterEachRun(Example $example,ResultGroup $resultGroup){}
}