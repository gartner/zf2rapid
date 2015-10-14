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
 * Class UpdateViewManagerConfig
 *
 * @package ZF2rapid\Task\Module
 */
class UpdateViewManagerConfig extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine(
            'task_module_update_view_manager_writing'
        );

        // set config dir
        $configFile = $this->params->moduleConfigDir . '/module.config.php';

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeFailLine(
                'task_module_update_view_manager_config_file_not_exists',
                [
                    $this->console->colorize($configFile, Color::GREEN),
                ]
            );

            return 1;
        }

        // get config data from file
        $configData = include $configFile;

        // check for view_manager config key
        if (!isset($configData['view_manager'])) {
            $configData['view_manager'] = [];
        }

        // check for view_manager config key
        if (!isset($configData['view_manager']['template_path_stack'])) {
            $configData['view_manager']['template_path_stack'] = [];
        }

        // check for template path stack
        if (!in_array(
            $this->params->moduleDir . '/view',
            $configData['view_manager']['template_path_stack']
        )
        ) {
            // set template path
            $templatePath = $this->params->moduleRootConstant . ' . \'/view\'';

            // add template path to stack
            $configData['view_manager']['template_path_stack'][]
                = $templatePath;
        }

        // create config array
        $config = new ConfigArrayGenerator($configData, $this->params);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write file
        file_put_contents($configFile, $file->generate());

        return 0;
    }
}