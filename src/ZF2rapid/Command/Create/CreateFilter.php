<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Create;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class CreateFilter
 *
 * @package ZF2rapid\Command\Create
 */
class CreateFilter extends AbstractCommand
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
            'ZF2rapid\Task\Check\ModuleExists',
            'ZF2rapid\Task\CreateStructure\CreateFilterStructure',
            'ZF2rapid\Task\GenerateClass\GenerateFilterClass',
            'ZF2rapid\Task\GenerateFactory\GenerateFilterFactory',
            'ZF2rapid\Task\UpdateConfig\UpdateFilterConfig',
        );

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_create_filter_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        $this->console->writeOkLine(
            'command_create_filter_stop',
            array(
                $this->console->colorize(
                    $this->params->paramFilter, Color::GREEN
                ),
                $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                )
            )
        );
    }
}
