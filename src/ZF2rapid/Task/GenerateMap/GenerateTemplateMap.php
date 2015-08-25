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
        // output message
        $this->console->writeTaskLine('task_generate_map_template_map_running');

        // define generator files
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $generator = $this->params->projectPath
                . '/vendor/bin/templatemap_generator.php.bat';
            $command   = $generator . ' -l ' . $this->params->moduleDir . ' -v '
                . $this->params->moduleDir . DIRECTORY_SEPARATOR . 'view';
        } else {
            $generator = $this->params->projectPath
                . '/vendor/bin/templatemap_generator.php';
            $command   = 'php ' . $generator . ' -l ' . $this->params->moduleDir
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

        return 0;
    }
}