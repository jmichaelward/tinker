<?php
namespace JMW\Tinker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class WPInfo
 * @package JMW\Tinker\Command
 */
class WPInfo extends Command
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('wp:check')
            ->setDescription('Check for an active installation of WP-CLI.')
            ->setHelp("This command runs WP-CLI's info command & informs the caller whether the utility is installed");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $script = dirname(__FILE__, 3) . '/scripts/wp-cli-check.sh';
        $output->writeln(`sh {$script}`);
    }
}
