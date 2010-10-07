<?php
namespace PhpDescribe\Spec;
use \PhpDescribe\TestHooks\BeforeEach,
    \PhpDescribe\TestHooks\AfterEach,
    \PhpDescribe\TestHooks\BeforeAll,
    \PhpDescribe\TestHooks\AfterAll,
    \PhpDescribe\Definition\Definition,
    \PhpDescribe\Example\ExampleGroup,
    \PhpDescribe\Example\Example,
    \Exception;
        
class Spec {
    static protected $actualExampleGroup;

    static function setActualExampleGroup(ExampleGroup $exampleGroup) {
        self::$actualExampleGroup = $exampleGroup;
    }

    static function emptyActualExampleGroup() {
        self::$actualExampleGroup = null;
    }

    /**
     *
     * @return ExampleGroup
     */
    static function getActualExampleGroup() {
        return self::$actualExampleGroup;
    }

    static function addExampleToActual(Example $example) {
        self::$actualExampleGroup->addExample($example);
    }

    static function setBeforeEachInActual(BeforeEach $item) {
        self::$actualExampleGroup->setBeforeEach($item);
    }
    
    static function setBeforeAllInActual(BeforeAll $item) {
        self::$actualExampleGroup->setBeforeAll($item);
    }
    
    static function setAfterEachInActual(AfterEach $item) {
        self::$actualExampleGroup->setAfterEach($item);
    }
    
    static function setAfterAllInActual(AfterAll $item) {
        self::$actualExampleGroup->setAfterAll($item);
    }

    static function addDefinitionToActual(Definition $def) {
        self::$actualExampleGroup->addDefinition($def);
    }

    static function addExampleToActualBasedOnADefinition($name) {
        $definition = self::$actualExampleGroup->findDefinition($name);
        if(!$definition) {
            self::addExampleToActual(Example::buildExample(
                $name,
                function() {
                    throw new Exception('This example does not match any definition expression');
                }
            ));
        }
        else {
            self::addExampleToActual($definition->buildExample($name));
        }
    }
}