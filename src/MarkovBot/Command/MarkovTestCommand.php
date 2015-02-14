<?php
/**
 * Tests current configuration
 */

namespace MarkovBot\Command;

use MarkovBot\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MarkovTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('markov:test')
            ->setDescription('Test current configuration for Markov generation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $markov = $this->get('markov');
        $result = $markov->generate();

        $output->writeln("<comment>$result</comment>");
    }
}
