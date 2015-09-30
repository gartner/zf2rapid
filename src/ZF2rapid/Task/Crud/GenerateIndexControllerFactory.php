<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Generator\Crud\ControllerFactoryGenerator;
use ZF2rapid\Generator\Crud\IndexControllerFactoryGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateIndexControllerFactory
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateIndexControllerFactory extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine(
            'task_generate_factory_writing',
            array(
                'index controller'
            )
        );

        // set factory file
        $factoryFile = $this->params->applicationControllerDir . '/IndexControllerFactory.php';

        // check if factory file exists
        if (file_exists($factoryFile)) {
            $this->console->writeFailLine(
                'task_generate_factory_exists',
                array(
                    'index controller',
                    $this->console->colorize(
                        'IndexControllerFactory', Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    )
                )
            );

            return false;
        }

        // create class
        $class = new ControllerFactoryGenerator(
            'IndexController',
            $this->params->paramModule,
            $this->params->paramEntityModule,
            $this->params->config
        );

        // create file
        $file = new ClassFileGenerator(
            $class->generate(), $this->params->config
        );

        // write file
        file_put_contents($factoryFile, $file->generate());

        return 0;
    }

}