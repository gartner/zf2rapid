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
 * Class CreateModule
 *
 * @package ZF2rapid\Command\Create
 */
class CreateModule extends AbstractCommand
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
            'ZF2rapid\Task\CreateStructure\CreateModuleStructure',
            'ZF2rapid\Task\Module\GenerateModuleClass',
            'ZF2rapid\Task\GenerateMap\GenerateClassMap',
            'ZF2rapid\Task\GenerateMap\GenerateTemplateMap',
            'ZF2rapid\Task\Module\GenerateModuleConfig',
            'ZF2rapid\Task\Module\ChooseApplicationConfigFile',
            'ZF2rapid\Task\Module\ActivateModule',
        );

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_create_module_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        $this->console->writeOkLine(
            'command_create_module_stop',
            array(
                $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                )
            )
        );
    }
}
