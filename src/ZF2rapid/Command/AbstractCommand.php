<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command;

use Zend\Stdlib\Parameters;
use ZF\Console\Route;
use ZF2rapid\Task\TaskInterface;
use ZF2rapid\Console\ConsoleInterface;

/**
 * Class AbstractCommand
 *
 * @package ZF2rapid\Command
 */
abstract class AbstractCommand implements CommandInterface
{
    /**
     * @var ConsoleInterface
     */
    protected $console;

    /**
     * @var Route
     */
    protected $route;

    /**
     * @var Parameters
     */
    protected $params;

    /**
     * @var array
     */
    protected $tasks = [];

    /**
     * Start command processing
     *
     * @param Route            $route
     * @param ConsoleInterface $console
     *
     * @return integer
     */
    public function __invoke(Route $route, ConsoleInterface $console)
    {
        $this->route   = $route;
        $this->console = $console;
        $this->params  = new Parameters();

        $this->startCommand();

        if (!$this->processTasks()) {
            return 1;
        }

        $this->stopCommand();

        return 0;
    }

    /**
     * Process command tasks
     */
    public function processTasks()
    {
        /** @var TaskInterface $task */
        foreach ($this->tasks as $task) {
            $callable = new $task();

            $result = call_user_func(
                $callable, $this->route, $this->console, $this->params
            );

            if (1 === $result) {
                return false;
            }
        }

        return true;
    }
}
