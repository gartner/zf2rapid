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
use ZF2rapid\Generator\Crud\FormFactoryGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateFormFactory
 *
 * @package ZF2rapid\Task\Crud
 */
class GenerateFormFactory extends AbstractTask
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
                'form'
            )
        );

        // set factory file
        $factoryFile = $this->params->applicationFormDir . '/' . $this->params->paramModule . 'FormFactory.php';

        // check if factory file exists
        if (file_exists($factoryFile)) {
            $this->console->writeFailLine(
                'task_generate_factory_exists',
                array(
                    'form',
                    $this->console->colorize(
                        $this->params->paramModule . 'Form', Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    )
                )
            );

            return 1;
        }

        // create class
        $class = new FormFactoryGenerator(
            $this->params->paramModule . 'Form',
            $this->params->paramModule,
            $this->params->paramEntityModule,
            $this->params->loadedTables,
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