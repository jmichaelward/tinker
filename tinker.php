#!/usr/bin/env php
<?php
/**
 * Tinker: A Symfony console application that doesn't really do anything. Yet?
 *
 * @author Jeremy Ward <jeremy@jmichaelward.com
 * @package JMW\Tinker
 */

namespace JMW\Tinker;

use Symfony\Component\Console\Application;

require_once file_exists( './vendor/autoload.php' ) ? './vendor/autoload.php' : __DIR__ . '/vendor/autoload.php';

$app = new Application(Config::APP_NAME, Config::APP_VERSION);

(new Tinker($app))->registerCommands();

$app->run();
