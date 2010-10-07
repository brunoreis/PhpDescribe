<?php
namespace PhpDescribe\Result;

class Result extends SpecResult {

    function __construct($exampleGroupName = 'all specs',$exampleGroupFilename = null) {
        parent::__construct($exampleGroupName, $exampleGroupFilename);
        $this->exampleGroupName = $exampleGroupName;
        $this->position = 0;
    }
}