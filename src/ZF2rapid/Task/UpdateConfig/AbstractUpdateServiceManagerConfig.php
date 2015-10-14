<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\UpdateConfig;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class AbstractUpdateServiceManagerConfig
 *
 * @package ZF2rapid\Task\UpdateConfig
 */
abstract class AbstractUpdateServiceManagerConfig extends AbstractTask
{
    /**
     * Update service manager config
     *
     * @param string $configType
     * @param string $configKey
     * @param string $configClass
     * @param string $namespaceName
     *
     * @return bool
     */
    public function updateConfig(
        $configType, $configKey, $configClass, $namespaceName
    ) {
        // set config dir
        $configFile = $this->params->moduleConfigDir . '/module.config.php';

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeFailLine(
                'task_update_config_module_config_not_exists',
                [
                    $this->console->colorize($configFile, Color::GREEN)
                ]
            );

            return false;
        }

        // get config data from file
        $configData = include $configFile;

        // check for config key
        if (!isset($configData[$configType])) {
            $configData[$configType] = [];
        }

        // remove factory if requested
        if ($this->params->paramRemoveFactory) {
            // delete factories key if set
            if (isset($configData[$configType]['factories'])
                && isset($configData[$configType]['factories'][$configKey])
            ) {
                unset($configData[$configType]['factories'][$configKey]);
            }
        }

        // check for factory
        if ($this->params->paramFactory) {
            // check for factories config key
            if (!isset($configData[$configType]['factories'])) {
                $configData[$configType]['factories'] = [];
            }

            // set class and namespace
            $class = $this->params->paramModule . '\\' . $namespaceName
                . '\\' . $configClass . 'Factory';

            // add class
            $configData[$configType]['factories'][$configKey] = $class;

            // delete invokables key if set
            if (isset($configData[$configType]['invokables'])
                && isset($configData[$configType]['invokables'][$configKey])
            ) {
                unset($configData[$configType]['invokables'][$configKey]);
            }
        } else {
            // check for invokables config key
            if (!isset($configData[$configType]['invokables'])) {
                $configData[$configType]['invokables'] = [];
            }

            // set class and namespace
            $class = $this->params->paramModule . '\\' . $namespaceName
                . '\\' . $configClass;

            // add class
            $configData[$configType]['invokables'][$configKey] = $class;
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