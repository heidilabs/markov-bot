<?php
/**
 * Tests current configuration
 */

namespace MarkovBot\Command;

use MarkovBot\MarkovBot;
use MarkovBot\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TTools\App;

class ConnectCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('twitter:test')
            ->setDescription('Test current configuration for Twitter');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Testing Twitter configuration...</info>");
        $twitter = $this->get('twitter');
        $credentials = $twitter->getCredentials();

        if (!isset($credentials['screen_name'])) {
            $output->writeln("<error>An error ocurred: " . $credentials['raw_response'] . "</error>");
            return 0;
        }

        $output->writeln("<comment>Account in use: " . $credentials['screen_name'] ."</comment>");
    }
}
