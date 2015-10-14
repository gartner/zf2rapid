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
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateControllerFactories
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateControllerFactories extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        foreach (['Index', 'Show', 'Create', 'Update', 'Delete'] as $controllerName) {
            // output message
            $this->console->writeTaskLine(
                'task_generate_factory_writing',
                [
                    $controllerName . ' controller'
                ]
            );

            // set factory file
            $factoryFile = $this->params->applicationControllerDir . '/' . $controllerName . 'ControllerFactory.php';

            // check if factory file exists
            if (file_exists($factoryFile)) {
                $this->console->writeFailLine(
                    'task_generate_factory_exists',
                    [
                        'index controller',
                        $this->console->colorize(
                            $controllerName . 'ControllerFactory', Color::GREEN
                        ),
                        $this->console->colorize(
                            $this->params->paramModule, Color::GREEN
                        )
                    ]
                );

                return false;
            }

            // create class
            $class = new ControllerFactoryGenerator(
                $controllerName . 'Controller',
                $this->params->paramModule,
                $this->params->paramEntityModule,
                $this->params->paramEntityClass,
                $this->params->config
            );

            // create file
            $file = new ClassFileGenerator(
                $class->generate(), $this->params->config
            );

            // write file
            file_put_contents($factoryFile, $file->generate());
        }

        return 0;
    }

}