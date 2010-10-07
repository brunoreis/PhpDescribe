<?php
namespace PhpDescribe;

class SpecData {
    protected $filename;
    protected $name;

    function  __construct($filename, $name) {
        $this->filename = $filename;
        $this->name = $name;
    }

    function getFilename() {
        return $this->filename;
    }
    
    function getName() {
        return $this->name;
    }
}