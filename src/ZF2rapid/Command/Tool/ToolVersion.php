<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Tool;

use ZF2rapid\Command\AbstractCommand;

/**
 * Class ToolVersion
 *
 * @package ZF2rapid\Command\Tool
 */
class ToolVersion extends AbstractCommand
{
    /**
     * @var array
     */
    protected $tasks
        = [
            'ZF2rapid\Task\Tool\Version',
        ];

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_tool_version_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        // output success message
        $this->console->writeOkLine('command_tool_version_stop');
    }
}
