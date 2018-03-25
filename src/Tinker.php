<?php
/**
 * Primary class for the Tinker application.
 *
 * @author  Jeremy Ward <jeremy@jmichaelward.com>
 * @package JMW\Tinker
 */

namespace JMW\Tinker;

use JMW\Tinker\Command as Command;
use Symfony\Component\Console\Application;

/**
 * Class Tinker
 *
 * The main plugin class. This probably isn't needed since I could set everything up in the bootstrap file, but then
 * I might wind up with a bunch of convoluted logic and other garbage in that file, and I'd rather organize that
 * process into a class and keep the main file lean and mean. So it goes...maybe this is a horrible approach and I'll
 * come to know regret, but since this application doesn't actually do anything meaningful, who cares, right? YOLO.
 *
 * @package JMW\Tinker
 */
class Tinker
{
    /**
     * Our benevolent leader.
     *
     * @var Application
     */
    private $app;

    /**
     * The set of commands we're going to register. The anticipation is killing you, I know.
     *
     * @var array
     */
    private $commands = [
        Command\Start::class,
        Command\Posts::class,
        Command\WPInfo::class,
    ];

    /**
     * Tinker constructor.
     *
     * @param $app Application
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Register our incredible set of Tinker commands.
     */
    public function registerCommands()
    {
        foreach ($this->commands as $command) {
            $this->app->add(new $command());
        }
    }
}
