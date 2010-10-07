<?php
namespace PhpDescribe\Result;

class SpecResult extends ResultGroup {
    protected $specData;

    function setSpecData(\PhpDescribe\SpecData $specData) {
        $this->specData = $specData;
    }

    function getSpecData() {
        return $this->specData;
    }
}