<?php
require __DIR__.'/vendor/autoload.php';

use App\Command\SortCardCommand;
use App\Service\Card;
use Symfony\Component\Console\Application;


$application = new Application();
$application->add(new SortCardCommand());
$application->run();