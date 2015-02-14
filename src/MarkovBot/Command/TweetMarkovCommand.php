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

class TweetMarkovCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('twitter:tweet')
            ->setDescription('Post an update on Twitter using current configuration');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
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

        $content = wordwrap($result, 140, '----');
        $split = explode('----', $content);
        $tweet = $split[0];

        $output->writeln("<comment><info>Posting:</info> $tweet</comment>");

        $twitter->update($tweet);

        return 1;
    }
}
