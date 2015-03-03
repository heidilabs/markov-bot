<?php
/**
 * Tests current configuration
 */

namespace MarkovBot\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TweetTestCommand extends ContainerAwareCommand
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

        $markov = $this->get('markov');
        $result = $markov->generate();

        $output->writeln("<comment><info>Would Post:</info> $result </comment>");

        return 1;
    }
}
