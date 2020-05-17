<?php

ini_set('display_errors',0);
error_reporting(0);

require 'vendor/autoload.php';

use Symfony\Component\Console\Application;
use JsonModels\Command\ModelCommand;
$application = new Application();

$name = "A tool for generate models written by Json<fanqingxuan@163.com>";
$application->setName($name);

$application->add(new ModelCommand());

$application->run();
