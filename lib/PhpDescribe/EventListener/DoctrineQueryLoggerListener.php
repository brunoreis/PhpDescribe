<?php
namespace PhpDescribe\EventListener;
use PhpDescribe\Example\Example,
    PhpDescribe\Result\ResultGroup,
    \Doctrine\DBAL\Logging\SQLLogger;

class DoctrineQueryLoggerListener extends EventListener implements SQLLogger {

    protected $queries = null;
    protected $beforeEachQueries = null;
    protected $actualScope = null;
    
    static function build() {
        return new DoctrineQueryLoggerListener();
    }

    private function __construct() {
        $this->cleanQueries();
    }

    private function cleanQueries() {
        $this->queries = array(
            'beforeEach'=>array(),
            'normal'=>array()
        );
    }

    function preExampleRun(Example $e,ResultGroup $resultGroup) {
        $this->cleanQueries();
        $this->actualScope = 'normal';
    }

    function postExampleRun(Example $e,ResultGroup $resultGroup) {
        $resultGroup->addExtraInformation(
            count($this->queries['beforeEach']) . ' beforeEach queries',
            $this->queriesHtml('beforeEach')
        );
        $resultGroup->addExtraInformation(
            count($this->queries['normal']) . ' queries',
            $this->queriesHtml('normal')
        );
    }

    public function preBeforeEachRun(Example $example,ResultGroup $resultGroup){
        $this->actualScope = 'beforeEach';
    }
    public function postBeforeEachRun(Example $example,ResultGroup $resultGroup){
        $this->actualScope = 'normal';
    }
    public function preAfterEachRun(Example $example,ResultGroup $resultGroup){
        $this->actualScope = 'afterEach';
    }
    public function postAfterEachRun(Example $example,ResultGroup $resultGroup){
        $this->actualScope = 'normal';
    }

    private function queriesHtml($scope) {
        $html = '';
        foreach($this->queries[$scope] as $index=>$query) {
            $html .= '<div class=query>';
            $html .= '<span class="queryNumber">' . ($index + 1) . '</span>';
            $html .= '<span class="queryText">' . $query[0] . '</span>';
            $html .= '<span class="queryParameters">' . var_export($query[1],1) . '</span>';
            $html .= '</div>';
        }
        return $html;
    }

    public function logSQL($sql, array $params = null)
    {
        $this->queries[$this->actualScope][] = array(
            $sql,
            $params
        );
    }
}