<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Check;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class ModulePathExists
 *
 * @package ZF2rapid\Task\Check
 */
class ModulePathExists extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check module path
        if (!file_exists($this->params->projectModuleDir)) {
            // create new module path if it does not exists
            mkdir($this->params->projectModuleDir, 0777, true);

            $this->console->writeTaskLine(
                'task_check_module_path_created',
                array(
                    $this->console->colorize(
                        realpath($this->params->projectModuleDir), Color::GREEN
                    )
                )
            );

            // set filter dirs
            $filterDirs = array('..', '.', 'autoload');

            // get existing config files
            $configFiles = array_values(
                array_diff(
                    scandir($this->params->projectConfigDir), $filterDirs
                )
            );

            foreach ($configFiles as $configFile) {
                // set config dir and file
                $configFile = $this->params->projectConfigDir . '/'
                    . $configFile;

                // create src module
                if (file_exists($configFile)) {
                    // get config data from file
                    $configData = include $configFile;

                    // check config file
                    if (is_array($configData)
                        && isset($configData['module_listener_options'])
                        && isset($configData['module_listener_options']['module_paths'])
                    ) {
                        $currentPath = './' . $this->params->modulePath;

                        // add module to application configuration
                        if (!in_array(
                            $currentPath,
                            $configData['module_listener_options']['module_paths']
                        )
                        ) {
                            $configData['module_listener_options']['module_paths'][]
                                = $currentPath;

                            // create config array
                            $config = new ConfigArrayGenerator(
                                $configData, $this->params
                            );

                            // create file
                            $file = new ConfigFileGenerator(
                                $config->generate(), $this->params->config
                            );

                            // write class to file
                            file_put_contents($configFile, $file->generate());
                        }
                    }
                }
            }
        }

        return 0;
    }

}