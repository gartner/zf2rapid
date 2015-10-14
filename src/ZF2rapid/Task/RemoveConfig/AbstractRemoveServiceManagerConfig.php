<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\RemoveConfig;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class AbstractRemoveServiceManagerConfig
 *
 * @package ZF2rapid\Task\RemoveConfig
 */
abstract class AbstractRemoveServiceManagerConfig extends AbstractTask
{
    /**
     * Remove service manager config
     *
     * @param string $configType
     * @param string $configKey
     *
     * @return bool
     */
    public function removeConfig($configType, $configKey)
    {
        // set config dir
        $configFile = $this->params->moduleConfigDir . '/module.config.php';

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeFailLine(
                'task_remove_config_module_config_file_not_exists',
                [
                    $this->console->colorize($configFile, Color::GREEN),
                ]
            );

            return false;
        }

        // get config data from file
        $configData = include $configFile;

        // delete factories key if set
        if (isset($configData[$configType]['factories'])
            && isset($configData[$configType]['factories'][$configKey])
        ) {
            unset($configData[$configType]['factories'][$configKey]);
        }

        // delete invokables key if set
        if (isset($configData[$configType]['invokables'])
            && isset($configData[$configType]['invokables'][$configKey])
        ) {
            unset($configData[$configType]['invokables'][$configKey]);
        }

        // create config array
        $config = new ConfigArrayGenerator($configData, $this->params);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write file
        file_put_contents($configFile, $file->generate());

        return true;
    }
}