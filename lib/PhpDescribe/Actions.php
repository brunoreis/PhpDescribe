<?php
namespace PhpDescribe;
class Actions {

    const IDE_OPEN_FILE_SHELL_COMMAND = 'C:\"Program Files"\"NetBeans 6.9.1"\bin\netbeans.exe --open "%s":%s';
    
    static function rename($args) {
        // a little bit of securtity so that the web user can only change spec files.
        $file = $args['file'];
        $lineNumber = $args['line']-1;
        
        if(!strpos($file, '.spec.php')) {
            return;
        }

        $ar = file($file);
        $line = $ar[$lineNumber];
        $newName = str_replace("'", '"', $args['newName']);
        $newLine = str_replace('(\''.$args['name'], '(\''.$newName, $line);
        $newLine = str_replace('( \''.$args['name'], '( \''.$newName, $newLine);
        $ar[$lineNumber] = $newLine;
        $content = implode('',$ar);
        //echo $content;
        file_put_contents($file, $content);
    }

    static function open($args) {
        $filepath = $args['file'];
        $line = $args['line'];
        $cmd = self::IDE_OPEN_FILE_SHELL_COMMAND;
        $cmd = sprintf($cmd, $filepath, $line);
        if (substr(php_uname(), 0, 7) == "Windows"){
                pclose(popen("start /B ". $cmd, "r"));
        }
        else {
                exec($cmd . " > /dev/null &");
        }
    }
   
}