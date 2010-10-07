<?php
namespace PhpDescribe\Functional\Symfony;
use \JsCallListener,
    \JsCall;

class SfBrowserTester implements JsCallListener {

    static $instance;
    private $sfBrowser;
    
    private $jsCallBeforeSerialization;
    private $jsCallsBeforeSerialization;

    private function __construct($action = null, $method = null, $parameters = array()) {
        $this->sfBrowser = new \sfBrowser();
        $this->jsCallsBeforeSerialization = array();
        if($action) {
            JsCall::setSerializationListener($this);
          //  try {
                $this->sfBrowser->call($action, $method, $parameters);
        //    }
            /*catch(\Exception $e) {
                //engulindo porque a versão do código (php < 5.3 usa o @ para suprimir)
                //echo $e->getTraceAsString();
            }*/
        }
    }

    /**
     * @return SfBrowserTester
     */
    public static function build($action = null, $method = 'get', $parameters = array()) {
        self::$instance = new SfBrowserTester($action, $method, $parameters);
        return self::$instance;
    }

    /**
     * @return SfBrowserTester
     */
    public static function instance() {
        if(!self::$instance) {
            self::$instance = new SfBrowserTester();
        }
        return self::$instance;
    }

    /**
     * @param symfony action $action
     * @return SfBrowserTest
     */
    function call($action) {
        $this->sfBrowser->call($action);
        return $this;
    }

    /**
     * @return string http status code
     */
    function getResponseStatusCode() {
        $browser = $this->sfBrowser;
        if(!$browser) {
            throw new \PhpDescribe\Exception('There is no browser in this tester.');
        }
        if(null == $browser->getContext()) {
            throw new \PhpDescribe\Exception('There is no context on this browser.');
        }
        return $browser->getResponse()->getStatusCode();
    }

    /**
     * @return dom document nodes
     */
    function getResponseElements($selectorText) {
        $selector = $this->sfBrowser->getResponseDomCssSelector();
        return $selector->matchAll($selectorText)->getValues();
    }
    
    function getResponseContent() {
        return $this->sfBrowser->getResponse()->getContent();
    }
    
    function getResponse() {
        return $this->sfBrowser->getResponse();
    }

    function getContext() {
        return $this->sfBrowser->getContext()->getStorage();
    }

    /**
     * Listen to JsCall
     */
    public function beforeToJs(JsCall $jsCall) {
        $this->jsCallBeforeSerialization = $jsCall;
        $this->jsCallsBeforeSerialization[] = clone $jsCall;
    }

    function getJsonResponse() {
        if(count($this->jsCallBeforeSerialization) < 1) {
            $jsCallResponse = $this->getResponseContent();
        }
        else {
            //$jsCallResponse = $this->jsCallBeforeSerialization->toJson();
            $jsCall = new JsCall;
            foreach($this->jsCallsBeforeSerialization as $c) {
                $jsCall->mergeCommands($c->getArCommand());
            }
            $jsCallResponse = $jsCall->toJson();
        }
        return json_decode($jsCallResponse);
    }

    function getJsonResponseInspector() {
        return new JsonResponseInspector($this->getJsonResponse());
    }
    
}
