<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateFactory;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Generator\FactoryGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateControllerPluginFactory
 *
 * @package ZF2rapid\Task\ControllerPlugin
 */
abstract class AbstractGenerateFactory extends AbstractTask
{
    /**
     * Generate the factory
     *
     * @param string $factoryDir
     * @param string $factoryName
     * @param string $factoryText
     * @param string $namespaceName
     * @param string $managerName
     * @param array  $hydratorStrategies
     *
     * @return bool
     */
    protected function generateFactory(
        $factoryDir, $factoryName, $factoryText, $namespaceName, $managerName,
        array $hydratorStrategies = array()
    ) {
        if (!$this->params->paramFactory) {
            return true;
        }

        // output message
        $this->console->writeTaskLine(
            'task_generate_factory_writing',
            array(
                $factoryText
            )
        );

        // set factory file
        $factoryFile = $factoryDir . '/' . $factoryName . 'Factory.php';

        // check if factory file exists
        if (file_exists($factoryFile)) {
            $this->console->writeFailLine(
                'task_generate_factory_exists',
                array(
                    $factoryText,
                    $this->console->colorize(
                        $factoryName, Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    )
                )
            );

            return false;
        }

        // create class
        $class = new FactoryGenerator(
            $factoryName,
            $this->params->paramModule,
            $namespaceName,
            $managerName,
            $this->params->config,
            $hydratorStrategies
        );

        // create file
        $file = new ClassFileGenerator(
            $class->generate(), $this->params->config
        );

        // write file
        file_put_contents($factoryFile, $file->generate());

        return true;
    }
}