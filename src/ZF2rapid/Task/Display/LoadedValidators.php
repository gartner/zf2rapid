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
 * Class LoadedValidators
 *
 * @package ZF2rapid\Task\Display
 */
class LoadedValidators extends AbstractTask
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
            'task_display_loaded_validators_found_in_path',
            array(
                $this->console->colorize(
                    $this->params->workingPath, Color::GREEN
                )
            )
        );

        // loop through modules
        foreach ($this->params->loadedModules as $moduleName => $moduleObject) {
            $this->console->writeListItemLine(
                'task_display_loaded_validators_module_class',
                array(
                    $this->console->colorize(
                        $moduleName, Color::GREEN
                    ),
                    $this->console->colorize(
                        get_class($moduleObject), Color::BLUE
                    )
                )
            );

            // check for empty validator list
            if (empty($this->params->loadedValidators[$moduleName])) {
                $this->console->writeListItemLineLevel2(
                    'task_display_loaded_validators_no_validators'
                );

                continue;
            }

            // loop through controllers
            foreach (
                $this->params->loadedValidators[$moduleName]
                as $pluginType => $pluginList
            ) {
                $this->console->writeListItemLineLevel2(
                    'task_display_loaded_validators_type',
                    array(
                        $this->console->colorize(
                            $pluginType, Color::GREEN
                        ),
                    ),
                    false
                );

                // output controllers for module
                foreach (
                    $pluginList as $pluginName => $pluginClass
                ) {
                    $this->console->writeListItemLineLevel3(
                        'task_display_loaded_validators_validator_class',
                        array(
                            $this->console->colorize(
                                $pluginName, Color::GREEN
                            ),
                            $this->console->colorize(
                                $pluginClass, Color::BLUE
                            )
                        ),
                        false
                    );
                }
            }
        }

        return 0;
    }

}