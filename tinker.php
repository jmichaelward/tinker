#!/usr/bin/env php
<?php
/**
 * Tinker: A Symfony console application that doesn't really do anything. Yet?
 *
 * @author Jeremy Ward <jeremy@jmichaelward.com
 * @package JMW\Tinker
 */

namespace JMW\Tinker;

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;

$app = new Application();

(new Tinker($app))->registerCommands();

$app->run();
