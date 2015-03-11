<?php
/**
 * Update cached samples for RSS and Twitter
 */

namespace MarkovBot\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CacheUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cache:update')
            ->setDescription('Update the cache for RSS and Twitter samples');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sources = $this->get('config')['markov.settings']['sources'];
        $markov = $this->get('markov');

        foreach ($sources as $source) {
            $output->writeln("<info>Updating source: $source</info>");
            $markov->updateSampleCache($source);
        }

        $output->writeln("<comment>Cache updated.</comment>");
    }
}
