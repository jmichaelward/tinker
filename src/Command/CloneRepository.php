<?php

namespace JMW\Tinker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CloneRepository
 * @package JMW\Tinker\Command
 */
class CloneRepository extends Command
{
    private $serviceDomains = [
        'github'    => 'github.com',
        'bitbucket' => 'bitbucket.org',
    ];


    /**
     *
     */
    protected function configure()
    {
        $this->setName('clone')
            ->setDescription('Clone a repository that I own')
            ->setHelp('This command is a wrapper for git clone, reducing the need for me to specify a full location.')
            ->addArgument('package', InputArgument::REQUIRED, 'The package to install')
            ->addOption('service', 's', InputArgument::OPTIONAL, 'Optional service other than GitHub.');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $package = $input->getArgument('package');
        $repo    = $input->getOption('service') ?: 'github';

        $output->writeln('Cloning repository...');

        shell_exec('git clone '. $this->getCloneCommand($package, $repo));
    }

    /**
     * @param string $package
     * @param string $repoUrl
     */
    private function getCloneCommand(string $package, string $serviceName)
    {
        $packageName = strtolower($package);
        $repoName    = false !== strpos($packageName, '/') ? $packageName : "jmichaelward/{$packageName}";
        $serviceDomain = $this->getServiceDomain($serviceName);

        return "git@{$serviceDomain}:$repoName.git";
    }

    /**
     * Get the domain of the service.
     *
     * @param string $name
     * @return mixed
     */
    private function getServiceDomain(string $name) {
        return $this->serviceDomains[ $name ];
    }
}