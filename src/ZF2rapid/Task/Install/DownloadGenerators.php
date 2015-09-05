<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Install;

use ZF2rapid\Task\AbstractTask;

/**
 * Class DownloadGenerators
 *
 * @package ZF2rapid\Task\Install
 */
class DownloadGenerators extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // define generator files
        $classMapGenerator    = $this->params->workingPath
            . '/vendor/bin/classmap_generator.php';
        $templateMapGenerator = $this->params->workingPath
            . '/vendor/bin/templatemap_generator.php';

        // check if class map generator exists
        if (!file_exists($classMapGenerator)) {
            // get latest class map generator
            file_put_contents(
                $classMapGenerator,
                file_get_contents(
                    'https://raw.githubusercontent.com/zendframework/zf2/master/bin/classmap_generator.php'
                )
            );

            // output message
            $this->console->writeTaskLine(
                'task_install_download_generators_class_map'
            );
        }

        // check if template map generator exists
        if (!file_exists($templateMapGenerator)) {
            // get latest template map generator
            file_put_contents(
                $templateMapGenerator,
                file_get_contents(
                    'https://raw.githubusercontent.com/zendframework/zf2/master/bin/templatemap_generator.php'
                )
            );

            // output message
            $this->console->writeTaskLine(
                'task_install_download_generators_template_map'
            );
        }

        return 0;
    }

}