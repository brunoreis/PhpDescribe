<?php
namespace PhpDescribe\Spec;
use \PhpDescribe\TestHooks\BeforeEach,
    \PhpDescribe\TestHooks\AfterEach,
    \PhpDescribe\TestHooks\AfterAll,
    \PhpDescribe\TestHooks\BeforeAll,
    \PhpDescribe\Example\Example,
    \PhpDescribe\Exception,
    \PhpDescribe\Expectation\Expectation,
    \PhpDescribe\Example\ExampleSpec,
    \PhpDescribe\PhpDescribe,
    \PhpDescribe\Definition\Definition,
    \PhpDescribe\Example\ExampleGroup,
    \Closure,
    \System\PhpDescribe\PhpDescribeCatalog;

function it($name, $exampleTestFunction = null) {
    if($exampleTestFunction) {
        Spec::addExampleToActual(Example::buildExample($name, $exampleTestFunction));
    }
    else {
        Spec::addExampleToActualBasedOnADefinition($name);
    }
}

function def($name, $definitionTestFunction) {
    Spec::addDefinitionToActual(Definition::build($name,$definitionTestFunction));
}

function beforeEach($beforeEachFunction) {
    if(is_string($beforeEachFunction)) {
        $beforeEachFunction = PhpDescribeCatalog::get($beforeEachFunction);
    }
    Spec::setBeforeEachInActual(BeforeEach::buildHook($beforeEachFunction));
}

function force_working() {
    expect(true)->should('be', true);
}

function force_not_working() {
    expect(false)->should('be', true);
}

function afterEach($afterEachFunction) {
    if(is_string($afterEachFunction)) {
        $afterEachFunction = PhpDescribeCatalog::get($afterEachFunction);
    }
    Spec::setAfterEachInActual(AfterEach::buildHook($afterEachFunction));
}

function expect($value) {
    return new Expectation($value);
}

function expectException($exceptionClassName) {
    Example::addExpectedExceptionToOpenExample($exceptionClassName);
}

function addSpec($filePath) {
    if(file_exists($filePath)) {
        Spec::getActualExampleGroup()->setFile($filePath);
        include($filePath);
    }
    elseif(file_exists($filePath.'.spec.php')) {
        $filePath = $filePath.'.spec.php';
        Spec::getActualExampleGroup()->setFile($filePath);
        include($filePath);
    }
    else {
        throw new Exception('Spec file not found:' . $filePath);
    }
}

function showFileData($name, $path, $escapeHtml = true) {
    $content = file_get_contents($path);
    if($escapeHtml) {
        $content = '<pre>'.\htmlentities($content).'</pre>';
    }
    PhpDescribe::getActual()->getOpenExampleResult()->addExtraInformation($name, $content);
}

function showData($name, $content, $escapeHtml = true) {
    if($escapeHtml) {
        $content = '<pre>'.\htmlentities($content).'</pre>';
    }
    PhpDescribe::getActual()->getOpenExampleResult()->addExtraInformation($name, $content);
}

function describe($name, $descriptionFunction ) {
    $oldActual = Spec::getActualExampleGroup();
    if(!$oldActual) {
        $newExampleGroup = ExampleSpec::buildExampleSpec($name);
        $newExampleGroup->setPhpDescribe(PhpDescribe::getActual());
        //$specData = PhpDescribe::getActualSpecData();
        //$newExampleGroup->setSpecData($specData);
        Spec::setActualExampleGroup($newExampleGroup);
        $descriptionFunction();
        Spec::emptyActualExampleGroup();
        PhpDescribe::addExampleGroupToActual($newExampleGroup);
    }
    else {
        $newExampleGroup = ExampleGroup::buildExampleGroup($name, $descriptionFunction);
        $newExampleGroup->setPhpDescribe(PhpDescribe::getActual());
        $oldActual->addExample($newExampleGroup);
        Spec::setActualExampleGroup($newExampleGroup);
        $descriptionFunction();
        Spec::setActualExampleGroup($oldActual);
    }
}