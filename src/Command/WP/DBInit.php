<?php

namespace JMW\Tinker\Command\WP;

use JMW\Tinker\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class DBInit
 * @package JMW\Tinker\Command\WP
 */
class DBInit extends Command
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('wp:db-init')
            ->setDescription('Create the WordPress database if it does not exist.')
            ->setHelp("Attempts to create a new database for WordPress using MySQL in the user path."
                . "This script is not hella smart, so don't try to use this outside of demo purposes.");
    }

    /**
     *
     *
     * #!/usr/bin/env bash
     * # Derived from http://www.bluepiccadilly.com/2011/12/creating-mysql-database-and-user-command-line-and-bash-script-automate-process
     *
     * FILE='./.env'
     * if [[ ! -f ${FILE} ]]; then
     * echo "No environment file configured. Cancelling database setup."
     * exit 0
     * fi
     *
     * source ${FILE}
     *
     * MYSQL=`which mysql`
     *
     * Q1="CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;"
     * Q2="GRANT USAGE ON *.* TO '${DB_USER}'@'${DB_HOST}' IDENTIFIED BY '${DB_PASSWORD}';"
     * Q3="GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'${DB_HOST}';"
     * Q4="FLUSH PRIVILEGES;"
     * SQL="${Q1}${Q2}${Q3}${Q4}"
     *
     * $MYSQL -u${DB_USER} -p${DB_PASSWORD} -e "$SQL"
     */

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $envFile = dirname(Config::appRoot(), 3) . '/.env';

        if (!file_exists($envFile)) {
            $output->writeln('No environment file created yet for WordPress. You need to run wp:setup first.');
            return;
        }

        $creds = $this->getCredentialsFromFile($envFile);

        // @TODO Figure out how to get a response from mysql so we can show different messages depending on success.
        $this->setupDatabase($creds);

        $output->writeln('Database creation attempted. Try logging in to your new WordPress site.');
    }

    /**
     * @param $file
     * @return array
     */
    private function getCredentialsFromFile($file)
    {
        $envFile = fopen($file, 'r');
        $creds   = [];

        while (($line = fgets($envFile)) !== false) {
            $setting = explode('=', $line, 2);

            if (!is_array($setting) || count($setting) !== 2) {
                continue;
            }

            $creds[$setting[0]] = str_replace("\n", '', $setting[1]);
        }

        return $creds;
    }

    /**
     * @param $mysql
     * @param $creds
     */
    private function setupDatabase($creds)
    {
        $mysql = str_replace("\n", '', `which mysql`);

        $query1 = "CREATE DATABASE IF NOT EXISTS \`{$creds['DB_NAME']}\`;";
        $query2 = "GRANT USAGE ON *.* TO '{$creds['DB_USER']}'@'{$creds['DB_HOST']}' IDENTIFIED BY '{$creds['DB_PASSWORD']}';";
        $query3 = "GRANT ALL PRIVILEGES ON \`{$creds['DB_NAME']}\`.* TO '{$creds['DB_USER']}'@'{$creds['DB_HOST']}';";
        $query4 = "FLUSH PRIVILEGES;";
        $sql = "{$query1}{$query2}{$query3}{$query4}";

        return `{$mysql} -u{$creds['DB_USER']} -p{$creds['DB_PASSWORD']} -e "{$sql}"`;
    }
}
