<?php

namespace JMW\Tinker\Command\WP;

use JMW\Tinker\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * Class Setup
 * @package JMW\Tinker\Command\WP
 */
class Setup extends Command
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('wp:setup')
            ->setDescription('Set up database configuration values for WordPress.')
            ->setHelp("Triggers an interactive script to update database constants in a WordPress local config.");
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $creds = $this->requestDatabaseCredentials($input, $output);

        if (!$creds) {
            $output->writeln('You entered missing or invalid database values. Please run wp:setup again to retry.');
            return;
        }

        $output->writeln("You entered {$creds['user']}, {$creds['password']}, {$creds['name']}, {$creds['host']}");

        // Write values to .env
        $this->writeCredentialsToEnv($creds);

        // Move files to WordPress root if one is found.
        if (is_writable(Config::appRoot() . '/../../wp-config.php')) {
            rename('./.env', Config::appRoot() . '/../../.env');
        }

        $this->addEnvCredentialsToConfig();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return array
     */
    private function requestDatabaseCredentials(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $questions = [
            'user'     => new Question('Enter your database username: ', ''),
            'password' => new Question('Enter your database password: ', ''),
            'name'     => new Question('Enter your database name: ', ''),
            'host'     => new Question('Enter your database host [ENTER for localhost]: ', 'localhost'),
        ];

        $answers = array_map(function ($question) use ($helper, $input, $output) {
            return filter_var($helper->ask($input, $output, $question), FILTER_SANITIZE_STRING);
        }, $questions);

        if (count($answers) !== count(array_filter($answers))) {
            return [];
        }

        return $answers;
    }

    /**
     * @param $creds
     */
    private function writeCredentialsToEnv($creds)
    {
        $file = fopen('./.env', 'w');

        fwrite($file, "DB_USER={$creds['user']}\n");
        fwrite($file, "DB_PASSWORD={$creds['password']}\n");
        fwrite($file, "DB_NAME={$creds['name']}\n");
        fwrite($file, "DB_HOST={$creds['host']}\n");

        fclose($file);
    }

    /**
     * Read from the project's .env file and write the values to wp-config-local.php.
     */
    private function addEnvCredentialsToConfig()
    {
        $path = Config::appRoot();

        if (!is_readable($path . '/../../.env')
            || is_writable($path . '/../../wp-config-local.php')) {
            // @TODO Add some kind of error.
            return;
        }

        $envFile    = fopen($path . '/../../.env', 'r');
        $configFile = fopen($path . '/../../wp-config-local.php', 'w');

        while (($line = fgets($envFile)) !== false) {
            $setting = explode('=', $line, 1);

            if (!is_array($setting) || count($setting) !== 2) {
                continue;
            }

            preg_replace("/\[@{$setting[0]}\]/", $setting[1], $configFile);
        }

        fclose($envFile);
        fclose($configFile);
    }
}
