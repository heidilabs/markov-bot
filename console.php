<?php
#!/usr/bin/env php

require __DIR__ . '/vendor/autoload.php';

use MarkovBot\Command\TweetTestCommand;
use MarkovBot\Command\MarkovTestCommand;
use MarkovBot\Command\TweetMarkovCommand;
use Symfony\Component\Console\Application;
use MarkovBot\Command\CacheUpdateCommand;
use MarkovBot\MarkovBot;

$markovbot = new MarkovBot(__DIR__ . '/config/config.yml');
$markovbot->init();

$application = new Application();
$application->add(new TweetTestCommand($markovbot));
$application->add(new MarkovTestCommand($markovbot));
$application->add(new TweetMarkovCommand($markovbot));
$application->add(new CacheUpdateCommand($markovbot));

$application->run();