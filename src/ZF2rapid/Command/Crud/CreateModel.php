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
 * Class CreateModel
 *
 * @package ZF2rapid\Command\Crud
 */
class CreateModel extends AbstractCommand
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
            'ZF2rapid\Task\Crud\CheckDbConnection',
            'ZF2rapid\Task\Crud\LoadTables',
            'ZF2rapid\Task\Crud\CreateModelStructure',
            'ZF2rapid\Task\Crud\GenerateEntityClass',
            'ZF2rapid\Task\Crud\GenerateHydratorStrategy',
            'ZF2rapid\Task\Crud\GenerateHydratorClass',
            'ZF2rapid\Task\Crud\GenerateHydratorFactory',
            'ZF2rapid\Task\Crud\GenerateTableGatewayClass',
            'ZF2rapid\Task\Crud\GenerateTableGatewayFactory',
            'ZF2rapid\Task\Crud\GenerateRepositoryClass',
            'ZF2rapid\Task\Crud\GenerateRepositoryFactory',
            'ZF2rapid\Task\Crud\GenerateInputFilterClass',
            'ZF2rapid\Task\Crud\GenerateInputFilterFactory',
            'ZF2rapid\Task\Crud\UpdateModelConfig',
        );

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_crud_create_model_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        // output success message
        $this->console->writeOkLine('command_crud_create_model_stop');
    }
}
