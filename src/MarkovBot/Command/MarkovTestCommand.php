<?php
/**
 * Tests current configuration
 */

namespace MarkovBot\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MarkovTestCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('markov:test')
            ->setDescription('Test current configuration for tweets generation')
            ->addOption(
                'config',
                null,
                InputOption::VALUE_REQUIRED,
                'If set, the bot will use an alternative settings file'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('config')) {
            $this->loadConfig(__DIR__ . '/../../../config/' . $input->getOption('config'));
            $output->writeln("<info>Alternative config file loaded.</info>");
        }

        $markov = $this->get('markov');
        $result = $markov->generate();

        $output->writeln("<comment>$result</comment>");
    }
}
