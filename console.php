<?php
#!/usr/bin/env php

require __DIR__ . '/vendor/autoload.php';

use MarkovBot\Command\ConnectCommand;
use MarkovBot\Command\MarkovTestCommand;
use MarkovBot\Command\TweetMarkovCommand;
use Symfony\Component\Console\Application;
use MarkovBot\MarkovBot;

$markovbot = new MarkovBot(__DIR__ . '/config/config.yml');
$markovbot->init();

$application = new Application();
$application->add(new ConnectCommand($markovbot));
$application->add(new MarkovTestCommand($markovbot));
$application->add(new TweetMarkovCommand($markovbot));

$application->run();