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
 * Class ShowValidators
 *
 * @package ZF2rapid\Command\Show
 */
class ShowValidators extends AbstractCommand
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
            'ZF2rapid\Task\Fetch\LoadModules',
            'ZF2rapid\Task\Fetch\LoadValidators',
            'ZF2rapid\Task\Display\UnknownModules',
            'ZF2rapid\Task\Display\LoadedValidators',
        ];

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_show_validators_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        // output success message
        $this->console->writeOkLine('command_show_validators_stop');
    }
}
