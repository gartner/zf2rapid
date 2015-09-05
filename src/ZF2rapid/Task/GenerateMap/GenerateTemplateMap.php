<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateMap;

use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateTemplateMap
 *
 * @package ZF2rapid\Task\GenerateMap
 */
class GenerateTemplateMap extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check for project
        if ($this->params->paramWithProject) {
            // output message
            $this->console->writeTaskLine(
                'task_generate_map_template_map_running'
            );

            // define generator files
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $generator = $this->params->workingPath
                    . '/vendor/bin/templatemap_generator.php.bat';
                $command   = $generator . ' -l ' . $this->params->moduleDir
                    . ' -v '
                    . $this->params->moduleDir . DIRECTORY_SEPARATOR . 'view';
            } else {
                $generator = $this->params->workingPath
                    . '/vendor/bin/templatemap_generator.php';
                $command   = 'php ' . $generator . ' -l '
                    . $this->params->moduleDir
                    . ' -v ' . $this->params->moduleDir . DIRECTORY_SEPARATOR
                    . 'view';
            }
            // create src module
            if (!file_exists($generator)) {
                $this->console->writeFailLine(
                    'task_generate_map_template_map_not_exists'
                );

                return 1;
            }

            // run templatemap generator
            exec($command, $output, $return);
        } else {
            // create config array
            $config = new ConfigArrayGenerator(array(), $this->params);

            // create file
            $file = new ConfigFileGenerator(
                $config->generate(), $this->params->config
            );

            // setup map file
            $mapFile = $this->params->moduleDir . DIRECTORY_SEPARATOR
                . 'template_map.php';

            // write file
            file_put_contents($mapFile, $file->generate());
        }

        return 0;
    }
}