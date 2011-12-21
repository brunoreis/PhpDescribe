<?php
namespace PhpDescribe\Reporter;
use \PhpDescribe\Result\ResultGroup;
class ResultReporter {

    private $actualNesting = 0;
    private $resultNumber = 0;

    private function  __construct() {}

    /**
     * @param ResultGroup $resultGroup
     * @return ResultReporter
     */
    static function build() {
        return new ResultReporter();
    }

    function report($resultGroup) {
        $this->resultNumber++;
        $this->actualNesting++;
        $html = "";
        
        if($resultGroup instanceof \PhpDescribe\Result\ExampleResult) {
            $html .= $this->reportExampleResult($resultGroup);
        }
        else if($resultGroup instanceof \PhpDescribe\Result\Result) {
            $html .= $this->reportResult($resultGroup);
        }
        else if($resultGroup instanceof \PhpDescribe\Result\ResultGroup) {
            $html .= $this->reportResultGroup($resultGroup);
        }
        
        $this->actualNesting--;
        return $html;
    }

    function reportExampleResult(\PhpDescribe\Result\ExampleResult $exampleResult) {
        $rand = rand(1,5000);
        $name = self::slugify($exampleResult->getExampleName()) . $rand ;
        $html =
            "<div class='exampleResult'>" . $this->getStatusBox($exampleResult,'.')
            . "<span class='resultItemTitle'>" . $exampleResult->getExampleName()
            . "</span>";
        
        switch($exampleResult->calculateStatus()) {
            case \PhpDescribe\Result\ResultGroup::STATUS_ERROR:
                $html .= 
                    "<a href='javascript:;' onclick='$(\"#$name\").toggle()'>+++</a>"
                    ."<br/><div id='$name' class='errors' style='display:none';>" .nl2br($exampleResult->getError()) . '</div>';
                break;
            case \PhpDescribe\Result\ResultGroup::STATUS_NOT_WORKING:
                $html .=
                    "<a href='javascript:;' onclick='$(\"#$name\").toggle()'>+++</a>"
                    . "<br/><div id='$name' class='failures' style='display:none;'>";
                foreach($exampleResult as $num=>$expectationResult) {
                    if($expectationResult->failed()) {
                        $html .= $expectationResult->getMessage() . '<span class="expectationNumber"> - expectation # '. ( $num + 1) . '</span><br/><br/>';
                    }
                }
                $html .= '</div>';
                break;
            case \PhpDescribe\Result\ResultGroup::STATUS_WORKING:

                break;

        }
        $html .= "<span class='resultGroupData'>" . $this->showFile($exampleResult) . "</span>";
        $html .= $this->generateExtraInformation($exampleResult->getExtraInformation());
        $html .= "</div>";
        
        return $html;
    }

    private function generateExtraInformation($extraInformation) {
        $html = '';
        foreach($extraInformation as $title=>$value) {
            $html .= " <span class='resultGroupData'><a href='javascript:;' onclick='$(this).parent().next().toggle()'> " . $title . "</a></span>";
            $html .= " <div class='extraInformation' style='display:none;'>" . $value . "</div>";
        }
        return $html;
    }

    private function reportResultGroup(\PhpDescribe\Result\ResultGroup $resultGroup) {
        $name = self::slugify($resultGroup->getExampleName()) . $this->resultNumber;
        $html =
            "<div class='resultGroup status " . $this->calculateHtmlClass($resultGroup) . "'>"
            ."<span class='resultGroupTitle'><a href='javascript:;' onclick='toggleDescription(\"$name\")'>" . $resultGroup->getExampleName() . "</a></span>"
            ."<span class='resultGroupData'>"
            . $resultGroup->countExamples() 
            . $this->innerResults($resultGroup);
        $html .= $this->showFile($resultGroup);
        $html .= "</span>";
        $html .= $this->generateExtraInformation($resultGroup->getExtraInformation());

        $html .= "<div class='innerResults' id='$name'>";
        foreach($resultGroup as $innerResultGroup) {
            $html .= $this->report($innerResultGroup);
        }
        $html .= "</div></div>";
        return $html;
    }

    private function innerResults(ResultGroup $result) {
        $html = '';
        if($result->countNotWorking()) {
            $html .= " | "
            . $this->getStatusBox(ResultGroup::STATUS_NOT_WORKING,'.') . $result->countNotWorking();
        }
        if($result->countIncomplete()) {
            $html .= " | "
            . $this->getStatusBox(ResultGroup::STATUS_INCOMPLETE,'.') . $result->countIncomplete();
        }
        if($result->countErrors()) {
            $html .= " | "
            . $this->getStatusBox(ResultGroup::STATUS_ERROR,'.') . $result->countErrors();
        }
        return $html;
    }

    private function showFile($result) {
        $html = '';
        /**
         * @todo:refactoring - these data are being set in the RenameListener
         * we need to change it to somewhere more logical
         */
        $filename = $result->getFile();
        $lineNumber = $result->getStartLineNumber();
        if($filename) {
            $html .= " <a href='?act=open&file=$filename&line=$lineNumber'> > </a>";
        }
        return $html;
    }
    
    private function reportResult(\PhpDescribe\Result\Result $result) {
        $name = self::slugify($result->getExampleName()) . $this->resultNumber;
        $html =
            "<div class='allSpecs'>" . $this->getStatusBox($result,'...')
            ."<span class='resultGroupData'>"
            . $result->countExamples()
            . $this->innerResults($result);
        $html .= $this->showFile($result);
        $html .= "</span>";
        
        $html .= $this->generateExtraInformation($result->getExtraInformation());
        $html .= "<div class='allResults' id='$name'>";
        foreach($result as $innerResultGroup) {
            $html .= $this->report($innerResultGroup);
        }
        $html .= "</div></div>";

        //$html .= "<div class='extraInformation'>asdsadfasdf</div>";
        return $html;
    }

    private function calculateHtmlClass(\PhpDescribe\Result\ResultGroup $resultGroup) {
        return $resultGroup->calculateStatus();
    }

    private function getStatusBox($resultGroupOrResult, $content) {
        if($resultGroupOrResult instanceof ResultGroup) {
            $resultGroupOrResult = $this->calculateHtmlClass($resultGroupOrResult);
        }
        return "<div style='width:20px; margin-right: 5px; display:inline;' class='" . $resultGroupOrResult . "'>$content</div>";
    }

    
    static public function slugify($text)
    {
        $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
        $text = trim($text, '-');
        if (function_exists('iconv'))
        {
            $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        }
        $text = strtolower($text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        if (empty($text))
        {
            return 'n-a';
        }
        return $text;
    }
    
}