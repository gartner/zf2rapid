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
            'ZF2rapid\Task\Crud\CheckTable',
            'ZF2rapid\Task\CreateStructure\CreateModelStructure',
            'ZF2rapid\Task\GenerateClass\GenerateEntityClass',
            'ZF2rapid\Task\GenerateClass\GenerateHydratorClass',
            'ZF2rapid\Task\GenerateClass\GenerateTableGatewayClass',
            'ZF2rapid\Task\GenerateFactory\GenerateTableGatewayFactory',
            'ZF2rapid\Task\GenerateClass\GenerateRepositoryClass',
            'ZF2rapid\Task\GenerateFactory\GenerateRepositoryFactory',
            'ZF2rapid\Task\UpdateConfig\UpdateModelConfig',
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
