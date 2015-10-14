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
 * Class LoadedControllers
 *
 * @package ZF2rapid\Task\Display
 */
class LoadedControllers extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output done message
        $this->console->writeTaskLine(
            'task_display_loaded_controllers_found_in_path',
            [
                $this->console->colorize(
                    $this->params->workingPath, Color::GREEN
                )
            ]
        );

        // loop through modules
        foreach ($this->params->loadedModules as $moduleName => $moduleObject) {
            $this->console->writeListItemLine(
                'task_display_loaded_controllers_module_class',
                [
                    $this->console->colorize(
                        $moduleName, Color::GREEN
                    ),
                    $this->console->colorize(
                        get_class($moduleObject), Color::BLUE
                    )
                ]
            );

            // check for empty controller list
            if (empty($this->params->loadedControllers[$moduleName])) {
                $this->console->writeListItemLineLevel2(
                    'task_display_loaded_controllers_no_controllers'
                );

                continue;
            }

            // loop through controllers
            foreach (
                $this->params->loadedControllers[$moduleName]
                as $controllerType => $moduleControllers
            ) {
                $this->console->writeListItemLineLevel2(
                    'task_display_loaded_controllers_type',
                    [
                        $this->console->colorize(
                            $controllerType, Color::GREEN
                        ),
                    ],
                    false
                );

                // output controllers for module
                foreach (
                    $moduleControllers as $controllerName => $controllerClass
                ) {
                    $this->console->writeListItemLineLevel3(
                        'task_display_loaded_controllers_controller_class',
                        [
                            $this->console->colorize(
                                $controllerName, Color::GREEN
                            ),
                            $this->console->colorize(
                                $controllerClass, Color::BLUE
                            )
                        ],
                        false
                    );
                }
            }
        }

        return 0;
    }

}