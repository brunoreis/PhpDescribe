<?php
namespace PhpDescribe;

use PhpDescribe\EventListener\EventListenerInterface,
    PhpDescribe\EventListener\InspectFileAndLineListener,
    \ErrorException,
    PhpDescribe\Reporter\ResultReporter;

class Runner {

    static private $instance;
    static private $suspendedInstance;
    private $phpdescribe;
    
    /**
     * @return Runner 
     */
    static function build() {
        $path = __DIR__ . '/../vendor/Doctrine/Common/ClassLoader.php';
        
        if(!class_exists('\Doctrine\Common\ClassLoader')) {
            require_once $path;
        }
        $classLoader = new \Doctrine\Common\ClassLoader('PhpDescribe', __DIR__.'/..');
        $classLoader->register();
        error_reporting(E_ALL);
        set_error_handler(function ($errno, $errstr, $errfile, $errline ) {
            $e = new ErrorException($errstr, 0, $errno, $errfile, $errline);
            throw $e;
        });
        require_once(__DIR__.'/Spec/spec_functions.php');
        $runner = new Runner;
        $runner->addListener(new InspectFileAndLineListener());
        return $runner;
    }

    /**
     * @return Runner
     */
    private function __construct() {
        $this->phpdescribe = PhpDescribe::build();
    }

    function setSpec($filePath) {
        $this->phpdescribe->addDescriptionFilePath($filePath);
        return $this;
    }

    /**
     * @return Runner
     */
    function addSuiteDir($dir) {
        include_once $dir . '/index.php';
        return $this;
    }

    function addListener(EventListenerInterface $e) {
        $this->phpdescribe->addEventListener($e);
        return $this;
    }

    /**
     * @return Runner
     */
    function runAndReport($parameters = array(), $decorate = true) {
        $resultGroup = $this->run($parameters);
        $reportHtml = ResultReporter::build()->report($resultGroup);

        if($decorate) {
            return $this->decorateHtml($reportHtml);
        }
        else {
            return $reportHtml;
        }

    }

    /**
     * @param array $parameters
     * @return PhpDescribe\Result\ResultGroup
     */
    function run($parameters = array()) {
        $this->phpdescribe->addParameters($parameters);
        try {
            $resultGroup = $this->phpdescribe->run();
        }
        catch(\Exception $e) {
            echo (get_class($e)) . ' - ';
            echo ($e->getMessage()) . '<br/><br/>';
            echo nl2br($e->getTraceAsString());
            die('exception error running the suite');
        }
        return $resultGroup;
    }

    

    private function decorateHtml($reportHtml) {
        return
"
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
    <script>".file_get_contents(__DIR__.'/assets/jquery.min.js')."</script>
    <script>".file_get_contents(__DIR__.'/assets/jquery.cookie.js')."</script>
    <style>".file_get_contents(__DIR__.'/assets/phpdescribe.css')."</style>
    
<script>
$(document).ready(function() {
        var name;
        $('.innerResults').each(function() {
            name = $(this).attr('id');
            if($.cookie('phpdescribe_' + name)) {
                $(this).hide();
            }
        });
});
function toggleDescription(name) {
    var element = $('#'+name);
    if (element.is(':visible')) {
        $.cookie('phpdescribe_' + name, 1);
        element.hide();
    }
    else {
        $.cookie('phpdescribe_' + name, null);
        element.show();
    }
}

</script>
</head>


<body>
    <div id='legenda'>
        <b>Legenda: </b>
        <span class='WORKING'>.</span> Working
        <span class='NOT_WORKING'>.</span> Not Working
        <span class='INCOMPLETE'>.</span> Incomplete
        <span class='ERROR'>.</span> Error
    </div>

    $reportHtml
</body>";
//<a rel='license' href='http://creativecommons.org/licenses/by/3.0/br/'>
//<img alt='Creative Commons License' style='border-width:0' src='http://i.creativecommons.org/l/by/3.0/br/88x31.png' /></a><br />
//<span xmlns:dc='http://purl.org/dc/elements/1.1/' href='http://purl.org/dc/dcmitype/Text' property='dc:title' rel='dc:type'>PhpDescribe</span>, propriedade da
//<a xmlns:cc='http://creativecommons.org/ns#' href='http://github.com/brunoreis/phpdescribe' property='cc:attributionName' rel='cc:attributionURL'>Humanidade</a>
//&#233; licenciado sob uma <a rel='license' href='http://creativecommons.org/licenses/by/3.0/br/'>Licen&#231;a Creative Commons Atribui&#231;&#227;o 3.0 Brasil</a>.<br />O repositório oficial esta no
//<a xmlns:dc='http://purl.org/dc/elements/1.1/' href='http://github.com/brunoreis/phpdescribe' rel='dc:source'>github.com</a>.
//</html>
//        ";
    }
}