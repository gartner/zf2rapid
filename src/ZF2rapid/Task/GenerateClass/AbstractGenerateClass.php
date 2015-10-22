<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateClass;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Generator\ClassGeneratorInterface;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateControllerPluginClass
 *
 * @package ZF2rapid\Task\GenerateClass
 */
abstract class AbstractGenerateClass extends AbstractTask
{
    /**
     * Generate the class
     *
     * @param string                  $classDir
     * @param string                  $className
     * @param string                  $classText
     * @param ClassGeneratorInterface $generator
     * @param bool                    $fileCheck
     *
     * @return bool
     */
    protected function generateClass(
        $classDir, $className, $classText, ClassGeneratorInterface $generator, $fileCheck = true
    ) {
        // output message
        $this->console->writeTaskLine(
            'task_generate_class_writing',
            [
                $classText
            ]
        );

        // set class file
        $classFile = $classDir . '/' . $className . '.php';

        // check if controller plugin file exists
        if ($fileCheck && file_exists($classFile)) {
            $this->console->writeFailLine(
                'task_generate_class_exists',
                [
                    $classText,
                    $this->console->colorize(
                        $className, Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    )
                ]
            );

            return false;
        }

        // create class
        $generator->build($className, $this->params->paramModule);

        // create file
        $file = new ClassFileGenerator(
            $generator->generate(), $this->params->config
        );

        // write file
        file_put_contents($classFile, $file->generate());

        return true;
    }
}