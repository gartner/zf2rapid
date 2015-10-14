<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Display;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class LoadedModules
 *
 * @package ZF2rapid\Task\Display
 */
class LoadedModules extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output found modules
        $this->console->writeTaskLine(
            'task_display_loaded_modules_found_in_path',
            [
                $this->console->colorize(
                    $this->params->workingPath, Color::GREEN
                ),
            ]
        );

        $this->console->writeLine();

        // loop through modules
        foreach ($this->params->loadedModules as $moduleName => $moduleObject) {
            $this->console->writeListItemLine(
                'task_display_loaded_modules_module_class',
                [
                    $this->console->colorize($moduleName, Color::GREEN),
                    $this->console->colorize(
                        get_class($moduleObject), Color::BLUE
                    ),
                ]
            );
        }

        return 0;
    }

}