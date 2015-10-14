<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Delete;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class DeleteFilter
 *
 * @package ZF2rapid\Command\Delete
 */
class DeleteFilter extends AbstractCommand
{
    /**
     * @var array
     */
    protected $tasks
        = [
            'ZF2rapid\Task\Setup\WorkingPath',
            'ZF2rapid\Task\Setup\ConfigFile',
            'ZF2rapid\Task\Setup\Params',
            'ZF2rapid\Task\Check\ModulePathExists',
            'ZF2rapid\Task\Check\FilterExists',
            'ZF2rapid\Task\DeleteClass\DeleteFilter',
            'ZF2rapid\Task\DeleteFactory\DeleteFilterFactory',
            'ZF2rapid\Task\RemoveConfig\RemoveFilterConfig',
        ];

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_delete_filter_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        $this->console->writeOkLine(
            'command_delete_filter_stop',
            [
                $this->console->colorize(
                    $this->params->paramFilter, Color::GREEN
                ),
                $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                )
            ]
        );
    }
}
