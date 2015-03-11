<?php
/**
 * Tests current configuration
 */

namespace MarkovBot\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class TweetMarkovCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('markov:tweet')
            ->setDescription('Post an update on Twitter using current configuration')
            ->addOption(
                'config',
                null,
                InputOption::VALUE_REQUIRED,
                'If set, the bot will use an alternative settings file'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('config')) {
            $this->loadConfig(__DIR__ . '/../../../config/' . $input->getOption('config'));
            $output->writeln("<info>Alternative config file loaded.</info>");
        }

        $output->writeln("<info>Preparing Tweet...</info>");
        $twitter = $this->get('twitter');
        $credentials = $twitter->getCredentials();

        if (!isset($credentials['screen_name'])) {
            $output->writeln("<error>An error ocurred: " . $credentials['raw_response'] . "</error>");
            return 0;
        }
        $output->writeln("<info>Account in use: " . $credentials['screen_name'] ."</info>");

        $markov = $this->get('markov');
        $result = $markov->generate();

        $output->writeln("<comment><info>Posting:</info> $result </comment>");

        $twitter->update($result);

        return 1;
    }
}
