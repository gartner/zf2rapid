<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Tool;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Console\Application;

/**
 * Class Version
 *
 * @package ZF2rapid\Task\Tool
 */
class Version extends AbstractTask
{
    /**
     * @return int
     */
    public function processCommandTask()
    {
        // output done message
        $this->console->writeTaskLine(
            'task_tool_version_show',
            [
                $this->console->colorize(Application::NAME, Color::GREEN),
                $this->console->colorize(Application::VERSION, Color::BLUE)
            ]
        );

        return 0;
    }
}
