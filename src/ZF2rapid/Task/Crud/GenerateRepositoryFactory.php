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
use ZF2rapid\Generator\RepositoryFactoryGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateRepositoryFactory
 *
 * @package ZF2rapid\Task\GenerateFactory
 */
class GenerateRepositoryFactory extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        foreach ($this->params->tableConfig as $tableKey => $tableConfig) {
            // output message
            $this->console->writeTaskLine(
                'task_generate_factory_writing',
                array(
                    'repository'
                )
            );

            // set factory file

            $factoryFile = $this->params->repositoryDir . '/'
                . $tableConfig['repositoryClass'] . 'Factory.php';

            // check if factory file exists
            if (file_exists($factoryFile)) {
                $this->console->writeFailLine(
                    'task_generate_factory_exists',
                    array(
                        'repository',
                        $this->console->colorize(
                            $tableConfig['repositoryClass'], Color::GREEN
                        ),
                        $this->console->colorize(
                            $this->params->paramModule, Color::GREEN
                        )
                    )
                );

                return 1;
            }

            // create class
            $class = new RepositoryFactoryGenerator(
                $tableConfig['repositoryClass'],
                $this->params->paramModule,
                $this->params->config['namespaceRepository'],
                $tableKey,
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