<?php
    
    include __DIR__.'/../lib/PhpDescribe/Runner.php';
    echo PhpDescribe\Runner::build()
        ->addListener(new \PhpDescribe\EventListener\DisplayCodeListener())
        ->addListener(new \PhpDescribe\EventListener\RenameListener())
        ->setSpec('PhpDescribe')
        ->runAndReport($_REQUEST);