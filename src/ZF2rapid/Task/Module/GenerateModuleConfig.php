<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateModuleConfig
 *
 * @package ZF2rapid\Task\Module
 */
class GenerateModuleConfig extends AbstractTask
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
            'task_module_generate_module_config_writing'
        );

        // setup config data for view manager
        $configData = [
            'view_manager' => [
                'template_map'        => 'include ' . $this->params->moduleRootConstant . ' . \'/template_map.php\',',
                'template_path_stack' => '[' . $this->params->moduleRootConstant . ' . \'/view\']',
            ],
        ];

        // create config array
        $config = new ConfigArrayGenerator($configData, $this->params);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write file
        file_put_contents(
            $this->params->moduleConfigDir . '/module.config.php',
            $file->generate()
        );

        return 0;
    }
}