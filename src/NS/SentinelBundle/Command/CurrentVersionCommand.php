<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 25/05/16
 * Time: 11:58 AM
 */

namespace NS\SentinelBundle\Command;

use NS\SentinelBundle\Version;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CurrentVersionCommand
 * @package NS\SentinelBundle\Command
 *
 * @codeCoverageIgnore
 */
class CurrentVersionCommand extends Command
{
    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this
            ->setName('nssentinel:version')
            ->setDescription('Get the current app version');
    }

    /**
     * @inheritDoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf('Current Version: %s',Version::VERSION));
    }
}
