<?php
namespace PhpDescribe\Spec;
use \PhpDescribe\Result\ResultGroup;
describe('PhpDescribe is a tool to describe the expected behaviour of a system in a human readable document.', function() {
    describe('We organize this expected behaviour in specifications (specs) that contains examples', function() {

         describe('Specifications can be nested', function() {

            it('Inside a specification file', function() {
                $specification = __DIR__.'/fixtureSpecs/nested/nestedSpecs.spec.php';
                $resultGroup = \PhpDescribe\Runner::build()->setSpec($specification)->run();
                $result = $resultGroup->getResult(0)->getResult(0);
                expect($result->getExampleName())->should('be','nested spec');
                showFileData('specification', $specification);
                $resultHtml = \PhpDescribe\Runner::build()->setSpec($specification)->runAndReport(array(),false);
                showData('result html', $resultHtml, false);
            });

            it('One spec file can include other spec files using the addSpec() function.', function() {
                $parentSpecification = __DIR__.'/fixtureSpecs/nested/nestedSpecsByFileParent.spec.php';
                $childSpecification = __DIR__.'/fixtureSpecs/nested/nestedSpecsByFileChild.spec.php';
                $resultGroup = \PhpDescribe\Runner::build()->setSpec($parentSpecification)->run();
                $resultsLevel1 = $resultGroup->getResults();
                $resultsLevel2 = $resultsLevel1[0]->getResults();
                expect($resultsLevel2[0]->getExampleName())->should('be','nested spec');
                showFileData('parent specification', $parentSpecification);
                showFileData('child specification', $childSpecification);
                $resultHtml = \PhpDescribe\Runner::build()->setSpec($parentSpecification)->runAndReport(array(),false);
                showData('result html', $resultHtml, false);
            });
        });

        

        describe('Examples describe how the code should work', function() {

            describe('The main tool to describe the code are expectations', function() {
                it('should be a working example',function() {
                    expect(1)->should('be',1);
                    expect(3)->should('be greater than',1);
                });
            });

            describe('Examples have ways to show extra data used to better explain the system.', function() {
                addSpec(__DIR__ . '/extraData');
            });

        });

        describe('There are pre-defined and custom expectations types', function() {

            describe('PHPBehaviour has a lot of pre-defined expectation types', function() {
                addSpec(__DIR__ . '/expectationTypes');
            });

            describe('And you also can define your own expectations to fit your tools/design', function() {
                it('should run a custom expectation',function(){});
            });
        });
    });

    describe('The specifications are run and usualy produce a report like this where we can see if the code is working as expected.', function() {
        describe('To run a spec, we build a runner, set the spec and run it with Runner#runAndReport().', function() {
            it('The Runner::runAndReport method returns a full html string with the result of all specs',function() {
                $result = \PhpDescribe\Runner::build()->setSpec(__DIR__.'/fixtureSpecs/test.spec.php')->runAndReport();
                expect($result)->should('be a','string');
            });

            it('should give an error if the spec file is not found',function() {
                expectException('InvalidArgumentException');
                $result = \PhpDescribe\Runner::build()->setSpec(__DIR__.'/fixtureSpecs/testNotFound.spec.php')->runAndReport();
            });

            it('should allow to set the spec file withou the extension .spec.php',function() {
                $result = \PhpDescribe\Runner::build()->setSpec(__DIR__.'/fixtureSpecs/test')->runAndReport();
                force_working();
            });
        });

        describe('when we look at the reports we see if examples are working', function() {
            it('If all the expectations are correct, the example get a "working" status' , function() {
                $spec2 = __DIR__.'/fixtureSpecs/specWithWorkingExample.spec.php';
                $results = \PhpDescribe\Runner::build()->setSpec($spec2)->run()->getResults();
                $results = $results[0]->getResults();
                expect($results[0]->calculateStatus())->should('be', ResultGroup::STATUS_WORKING);
                showFileData('spec with working example', $spec2);
            });

            it('If any of the expectations is not correct, the example get a "not_working" status' , function() {
                $spec2 = __DIR__.'/fixtureSpecs/specWithNotWorkingExample.spec.php';
                $results = \PhpDescribe\Runner::build()->setSpec($spec2)->run()->getResults();
                $results = $results[0]->getResults();
                expect($results[0]->calculateStatus())->should('be', ResultGroup::STATUS_NOT_WORKING);
                showFileData('spec with not working example', $spec2);
            });
        });

        describe('After the specs runs, examples can have 4 result status: working, not_working, incomplete or error.', function() {
            it('An example without an expectation is counted and displayed as incomplete' , function() {
                $spec1 = __DIR__.'/fixtureSpecs/specWithEmptyExample.spec.php';
                $result = \PhpDescribe\Runner::build()->setSpec($spec1)->run();
                expect($result->countIncomplete())->should('be',1);
                $spec2 = __DIR__.'/fixtureSpecs/specWithForcedPassingExample.spec.php';
                $result = \PhpDescribe\Runner::build()->setSpec($spec2)->run();
                expect($result->countIncomplete())->should('be',0);
                showFileData('spec with empty example', $spec1);
            });

            it('If we need to force a spec to have a "working" result, we can use the force_working() function' , function() {
                $spec2 = __DIR__.'/fixtureSpecs/specWithForcedPassingExample.spec.php';
                $results = \PhpDescribe\Runner::build()->setSpec($spec2)->run()->getResults();
                $results = $results[0]->getResults();
                expect($results[0]->calculateStatus())->should('be', ResultGroup::STATUS_WORKING);
                showFileData('spec with passing example', $spec2);
            });

            it('If we need to force a spec to have a "not working" result, we can use the force_not_working() function' , function() {
                $spec2 = __DIR__.'/fixtureSpecs/specWithForcedNotWorkingExample.spec.php';
                $results = \PhpDescribe\Runner::build()->setSpec($spec2)->run()->getResults();
                expect($results[0]->calculateStatus())->should('be', ResultGroup::STATUS_NOT_WORKING);
                showFileData('spec with not working example', $spec2);
            });

            it('if an error or exception occours in an example, it receivers the error status (ResultGroup::STATUS_ERROR)' , function() {
                $spec2 = __DIR__.'/fixtureSpecs/specWithErrorAndExceptionExample.spec.php';
                $results = \PhpDescribe\Runner::build()->setSpec($spec2)->run()->getResults();
                expect($results[0]->getResult(0)->calculateStatus())->should('be', ResultGroup::STATUS_ERROR);
                expect($results[0]->getResult(1)->calculateStatus())->should('be', ResultGroup::STATUS_ERROR);
                showFileData('spec with error and exception example', $spec2);
            });
        });

        describe('We can pass parameters from the titles to the examples.', function() {
            addSpec(__DIR__ . '/passParameters');
        });
    });
    
    describe('The web interface also allow some edition of the specifications.', function() {
        it('should allow to rename an example', function() {
            $oldName = 'Rename Me';
            $newName = 'Rename Me 2';
            $spec1 = __DIR__.'/fixtureSpecs/specToRename.spec.php';
            $result = \PhpDescribe\Runner::build()->setSpec($spec1)->run();
            expect($result->getResult(0)->getExampleName())->should('be',$oldName);
            //expect($result->getResult(0)->getResult(0)->getExampleName())->should('be','Change my name');
            \PhpDescribe\Actions::rename(array(
                'file'    => $spec1,
                'line'    => 3,
                'name'    => $oldName,
                'newName' => $newName
            ));
            $result = \PhpDescribe\Runner::build()->setSpec($spec1)->run();
            expect($result->getResult(0)->getExampleName())->should('be',$newName);
            \PhpDescribe\Actions::rename(array(
                'file'    => $spec1,
                'line'    => 3,
                'name'    => $newName,
                'newName' => $oldName
            ));
        });

        it('should allow to rename an example group', function() {
            $oldName = 'Rename Me';
            $newName = 'Rename Me 2';
            $spec1 = __DIR__.'/fixtureSpecs/specToRename.spec.php';
            $result = \PhpDescribe\Runner::build()->setSpec($spec1)->run();
            expect($result->getResult(0)->getExampleName())->should('be',$oldName);
            //expect($result->getResult(0)->getResult(0)->getExampleName())->should('be','Change my name');
            \PhpDescribe\Actions::rename(array(
                'file'    => $spec1,
                'line'    => 3,
                'name'    => $oldName,
                'newName' => $newName
            ));
            $result = \PhpDescribe\Runner::build()->setSpec($spec1)->run();
            expect($result->getResult(0)->getExampleName())->should('be',$newName);
            \PhpDescribe\Actions::rename(array(
                'file'    => $spec1,
                'line'    => 3,
                'name'    => $newName,
                'newName' => $oldName
            ));
        });
        
        it('the results have the file and line number, allowing implementations of the ui to open on the IDE', function() {
            $spec1 = __DIR__.'/fixtureSpecs/specToRename.spec.php';
            $result = \PhpDescribe\Runner::build()->setSpec($spec1)->run();
            expect($result->getResult(0)->getResult(0)->getStartLineNumber())->should('be greater than',0);

        });
    });
    
    describe('Specification patterns can be used to define DSLs', function() {
        addSpec(__DIR__ . '/dsls');
    });

//    describe('PhpDescribe', function() {
//        //$PhpDescribe = \PhpDescribe\PhpDescribe::build(__DIR__.'/fixtureSpecs');
//        //$testDescName = 'test';
//
//        it('should catch an Exception', function() {
//            //expectException('\Exception');
//            //throw new \Exception;
//        });
//
//        it('should catch an error as an ErrorException', function() {
//            //expectException('\ErrorException');
//            //$dois = $um + 1;
//        });
//
//        it('getInstance() deve retornar instancia de PhpDescribe.', function() {
//            //expect( $PhpDescribe )->should('be an instance of','\PhpDescribe\PhpDescribe');
//        });
//
//        describe('addDescription()', function()  {
//            it('deve retornar instancia de PhpDescribe.', function()  {
//                //expect( $PhpDescribe->addDescription($testDescName) )->should('be an instance of','\PhpDescribe\PhpDescribe');
//                //$PhpDescribe->clearDescriptions();
//            });
//
//            it('deve adicionar uma especificaÃ§Ã£o.', function() {
////                $PhpDescribe->addDescription($testDescName);
////                expect( $PhpDescribe->countDescriptions($testDescName) )->should('be',1);
////                $PhpDescribe->clearDescriptions();
//            });
//
//            it('deve lanÃ§ar exceÃ§Ã£o se a especificaÃ§Ã£o nÃ£o existir.', function()  {
////                expectException('InvalidArgumentException');
////                $PhpDescribe->addDescription('testnaoexiste');
//            });
//        });
//
//        it('clearDescriptions() deve limpar todas as descriÃ§Ãµes', function()  {
//            //$PhpDescribe->addDescription($testDescName)->clearDescriptions();
//            //expect( $PhpDescribe->countDescriptions($testDescName) )->should('be',0);
//        });
//
//        describe('run()', function()  {
//            it('deve retornar o mesmo nÃºmero de resultados de primeiro nÃ­vel que o nÃºmero de descriÃ§Ãµes.', function()  {
////                $resultGroup =
////                    $PhpDescribe->clearDescriptions()
////                        ->addDescription($testDescName)
////                        ->addDescription('test2')
////                        ->run();
////                expect($resultGroup->countResults())->should('be',2);
//            });
//
//            it('deve ter um array de SpecResult nos resultados de primeiro nÃ­vel', function()  {
////                $resultGroup = $PhpDescribe->addDescription($testDescName)->run();
////                expect($resultGroup)->should('only have instances of', '\PhpDescribe\Result\SpecResult');
//            });
//
//            it('deve definir o SpecData em todos os resultados de primeiro nÃ­vel', function()  {
////                $resultGroup = $PhpDescribe->clearDescriptions()->addDescription($testDescName)->run();
////                foreach($resultGroup as $result) {
////                    expect($result->getSpecData())->should('be an instance of', '\PhpDescribe\SpecData');
////                }
//            });
//        });
//    });
});

function runAndAskToShowSpecDataAndGetFirstExampleResults($specFile,$specDisplayName = 'spec') {
    $results = \PhpDescribe\Runner::build()->setSpec($specFile)->run()->getResults();
    if( substr($specFile, -9) !== '.spec.php' ) {
        $specFile .= '.spec.php';
    }
    showFileData($specDisplayName, $specFile);
    return $results[0]->getResults();

}