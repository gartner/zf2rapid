<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Crud;

use ZF2rapid\Command\AbstractCommand;

/**
 * Class CreateApplication
 *
 * @package ZF2rapid\Command\Crud
 */
class CreateApplication extends AbstractCommand
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
            'ZF2rapid\Task\Check\ModuleExists',
            'ZF2rapid\Task\Crud\CheckDbConnection',
            'ZF2rapid\Task\Crud\LoadEntity',
            'ZF2rapid\Task\Crud\CreateApplicationStructure',
            'ZF2rapid\Task\Crud\GenerateControllerClasses',
            'ZF2rapid\Task\Crud\GenerateControllerFactories',
            'ZF2rapid\Task\Crud\GenerateDataFormClass',
            'ZF2rapid\Task\Crud\GenerateDataFormFactory',
            'ZF2rapid\Task\Crud\GenerateDeleteFormClass',
            'ZF2rapid\Task\Crud\GenerateViews',
            'ZF2rapid\Task\Crud\CreateApplicationConfig',
            'ZF2rapid\Task\Crud\GenerateTranslationFile',
        ];

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_crud_create_application_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        // output success message
        $this->console->writeOkLine('command_crud_create_application_stop');
    }
}
