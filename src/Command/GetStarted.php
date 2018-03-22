<?php
/**
 * @package JMW\Tinker\Command
 */

namespace JMW\Tinker\Command;

use JMW\Tinker\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GetStarted
 */
class GetStarted extends Command
{
    /**
     * Configure the command.
     *
     * @see https://symfony.com/doc/current/console.html#configuring-the-command
     */
    protected function configure()
    {
        $this->setName(Config::APP_NAME.':start')
            ->setDescription('The command that started it all!')
            ->setHelp("This command doesn't really do anything meaningful, but then, neither does Tinker.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Now it is getting SERIOUS.');
    }
}
