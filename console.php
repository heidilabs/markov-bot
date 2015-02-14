<?php
#!/usr/bin/env php

require __DIR__ . '/vendor/autoload.php';

use MarkovBot\Command\ConnectCommand;
use Symfony\Component\Console\Application;
use MarkovBot\MarkovBot;

$markovbot = new MarkovBot(__DIR__ . '/config/config.yml');
$markovbot->init();

$application = new Application();
$application->add(new ConnectCommand($markovbot));
$application->run();