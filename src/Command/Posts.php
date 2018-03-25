<?php
/**
 * A command to get the latest post titles from random WordPress URLs.
 *
 * @package JMW\Tinker\Command
 */

namespace JMW\Tinker\Command;

use JMW\Tinker\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Posts
 * @package JMW\Tinker\Command
 */
class Posts extends Command
{
    /**
     *
     */
    protected function configure()
    {
        $this->setName('posts')
            ->setDescription('Get the latest posts from a WordPress site.')
            ->setHelp('This command will return the titles of the latest posts from any WordPress site with '
                . 'REST API support.');

        $this->addArgument('url', InputArgument::REQUIRED, 'The URL of the WordPress site from which to get posts.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $url = $input->getArgument('url');

        // @TODO Parse url to check if it's validly formatted, return error if not.
        $request = curl_init();

        curl_setopt($request, CURLOPT_URL, "{$url}/wp-json/wp/v2/posts");
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec($request);

        curl_close($request);

        foreach (json_decode($response, true) as $post) {
            $output->writeln($post['title']);
        }
    }
}
