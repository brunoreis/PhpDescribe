<?php
namespace PhpDescribe\EventListener;
use PhpDescribe\Example\Example,
    PhpDescribe\Example\ExampleGroup,
    PhpDescribe\Result\ResultGroup;
class DebugEventListener extends EventListener {
    public function preExampleGroupRun(ExampleGroup $exampleGroup,ResultGroup $resultGroup){
        echo '[preExampleGroup.....]<b>' . $exampleGroup->getName() . '</b><br/>'."\n";
        flush();
    }
    public function postExampleGroupRun(ExampleGroup $exampleGroup,ResultGroup $resultGroup){
        echo '[.....postExampleGroup]<b>' . $exampleGroup->getName() . '</b><br/>'."\n";
        flush();
    }
    public function preExampleRun(Example $example,ResultGroup $resultGroup){
        echo '[pre.....]' . $example->getName() . '<br/>'."\n";
        flush();
    }
    public function postExampleRun(Example $example,ResultGroup $resultGroup){
        echo '[.....post]' . $example->getName() . '<br/>'."\n";
        flush();
    }
    public function preBeforeEachRun(Example $example,ResultGroup $resultGroup){
        echo '[pre before each.....]<br/>';
        flush();
    }
    public function postBeforeEachRun(Example $example,ResultGroup $resultGroup){
        echo '[.....post before each]<br/>'."\n";
        flush();
    }
    public function preAfterEachRun(Example $example,ResultGroup $resultGroup){
        echo '[pre after each.....]<br/>'."\n";
        flush();
    }
    public function postAfterEachRun(Example $example,ResultGroup $resultGroup){
        echo '[.....post after each]<br/>'."\n";
        flush();
    }
}