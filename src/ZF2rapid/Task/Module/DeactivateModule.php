<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class DeactivateModule
 *
 * @package ZF2rapid\Task\Module
 */
class DeactivateModule extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check for project
        if (!$this->params->paramWithProject) {
            return 0;
        }

        // check for activation
        if (!$this->params->paramDeactivation) {
            return 0;
        }

        // output message
        $this->console->writeTaskLine(
            'task_module_deactivate_module_config_file',
            array(
                $this->console->colorize(
                    $this->params->configFile, Color::GREEN
                )
            )
        );

        // check for chosen config file
        if ($this->params->configFile) {
            $configFiles = array($this->params->configFile);
        } else {
            // set filter dirs
            $filterDirs = array('..', '.', 'autoload');

            // get existing config files
            $configFiles = array_values(
                array_diff(
                    scandir($this->params->projectConfigDir),
                    $filterDirs
                )
            );
        }

        // loop through config files
        foreach ($configFiles as $configFile) {
            $configFile = $this->params->projectConfigDir . '/' . $configFile;

            // check for chosen config file
            if ($this->params->configFile && !file_exists($configFile)) {
                $this->console->writeFailLine(
                    'task_module_deactivate_module_config_file_not_exists',
                    array(
                        $this->console->colorize(
                            $configFile, Color::GREEN
                        )
                    )
                );

                return 1;
            }

            // get config data from file
            $configData = include $configFile;

            // check for chosen config file
            if ($this->params->configFile && !is_array($configData)
                || !isset($configData['modules'])
            ) {
                $this->console->writeFailLine(
                    'task_module_deactivate_module_not_zf2_config_file',
                    array(
                        $this->console->colorize(
                            $configFile, Color::GREEN
                        )
                    )
                );
                $this->console->writeFailLine(
                    'task_module_deactivate_module_no_modules_array_section',
                    array(
                        $this->console->colorize(
                            'modules', Color::GREEN
                        )
                    )
                );

                return 1;
            }

            // remove module from application configuration
            if (isset($configData['modules'])
                && in_array(
                    $this->params->paramModule, $configData['modules']
                )
            ) {
                $moduleKey = array_search(
                    $this->params->paramModule, $configData['modules']
                );
                unset($configData['modules'][$moduleKey]);
            }

            // create config array
            $config = new ConfigArrayGenerator($configData, $this->params);

            // create file
            $file = new ConfigFileGenerator(
                $config->generate(), $this->params->config
            );

            // write class to file
            file_put_contents($configFile, $file->generate());
        }

        return 0;
    }
}