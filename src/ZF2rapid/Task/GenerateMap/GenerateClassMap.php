<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateMap;

use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateClassMap
 *
 * @package ZF2rapid\Task\GenerateMap
 */
class GenerateClassMap extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine('task_generate_map_class_map_running');

        // define generator files
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $generator = $this->params->projectPath . '/vendor/bin/classmap_generator.php.bat';
            $command = $generator . ' -l ' . $this->params->moduleDir . ' -s';
        } else {
            $generator = $this->params->projectPath . '/vendor/bin/classmap_generator.php';
            $command = 'php ' . $generator . ' -l ' . $this->params->moduleDir . ' -s';
        }

        // create src module
        if (!file_exists($generator)) {
            $this->console->writeFailLine(
                'task_generate_map_class_map_not_exists'
            );

            return 1;
        }

        // run classmap generator
        exec($command, $output, $return);

        return 0;
    }
}