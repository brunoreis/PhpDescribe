<?php
function x($object,$varDump = false,$die = true) {
    if($object instanceof Doctrine_Record) {
        echo "<b> DOCTRINE RECORD - CALLING toArray()</b>";
        $object = $object->toArray();
    }
    if($object instanceof Doctrine_Collection) {
        echo "<b> DOCTRINE COLLECTION - CALLING toArray()</b>";
        $object = $object->toArray();
    }
    $ar = debug_backtrace();
    $ret = '';
    $line = $ar[0]['line'];
    //print_r($ar[0]);
    $ret = $ar[0]['file'];
    $ret .= '(line '.$line.')<br/>';
    $ret .= '<div><pre>';
    if($varDump) {
        $ret .= _getVarDump($object);
    }
    else {
        $ret .= print_r($object,true);
    }
    $ret .= '</pre></div>';
    echo $ret;
    if($die) die();
}

function _getVarDump($obj) {
        ob_start();
        var_dump($obj);
        return ob_get_clean();
}