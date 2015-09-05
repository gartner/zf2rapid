<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Show;

use ZF2rapid\Command\AbstractCommand;

/**
 * Class ShowViewHelpers
 *
 * @package ZF2rapid\Command\Show
 */
class ShowViewHelpers extends AbstractCommand
{
    /**
     * @var array
     */
    protected $tasks
        = array(
            'ZF2rapid\Task\Setup\WorkingPath',
            'ZF2rapid\Task\Setup\ConfigFile',
            'ZF2rapid\Task\Setup\Params',
            'ZF2rapid\Task\Check\ModulePathExists',
            'ZF2rapid\Task\Fetch\LoadModules',
            'ZF2rapid\Task\Fetch\LoadViewHelpers',
            'ZF2rapid\Task\Display\UnknownModules',
            'ZF2rapid\Task\Display\LoadedViewHelpers',
        );

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_show_view_helpers_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        // output success message
        $this->console->writeOkLine('command_show_view_helpers_stop');
    }
}
