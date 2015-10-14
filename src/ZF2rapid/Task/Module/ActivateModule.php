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
 * Class ActivateModule
 *
 * @package ZF2rapid\Task\Module
 */
class ActivateModule extends AbstractTask
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
        if (!$this->params->paramActivation) {
            return 0;
        }

        // output message
        $this->console->writeTaskLine(
            'task_module_activate_module_config_file',
            [
                $this->console->colorize(
                    $this->params->configFile, Color::GREEN
                )
            ]
        );

        // set config dir and file
        $configFile
            = $this->params->projectConfigDir . '/' . $this->params->configFile;

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeFailLine(
                'task_module_activate_module_config_file_not_exists',
                [
                    $this->console->colorize(
                        $configFile, Color::GREEN
                    )
                ]
            );

            return 1;
        }

        // get config data from file
        $configData = include $configFile;

        // create src module
        if (!is_array($configData) || !isset($configData['modules'])) {
            $this->console->writeFailLine(
                'task_module_activate_module_not_zf2_config_file',
                [
                    $this->console->colorize(
                        $configFile, Color::GREEN
                    )
                ]
            );
            $this->console->writeFailLine(
                'task_module_activate_module_no_modules_array_section',
                [
                    $this->console->colorize(
                        'modules', Color::GREEN
                    )
                ]
            );

            return 1;
        }

        // add module to application configuration
        if (!in_array($this->params->paramModule, $configData['modules'])) {
            $configData['modules'][] = $this->params->paramModule;
        }

        // create config array
        $config = new ConfigArrayGenerator($configData, $this->params);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write class to file
        file_put_contents($configFile, $file->generate());

        return 0;
    }
}