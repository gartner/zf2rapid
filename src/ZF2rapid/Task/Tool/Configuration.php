<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Tool;

use Zend\Console\ColorInterface as Color;
use Zend\Validator\StaticValidator;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Task\Setup\ConfigFile;

/**
 * Class Configuration
 *
 * @package ZF2rapid\Task\Tool
 */
class Configuration extends AbstractTask
{
    /**
     * Change mode for configuration
     */
    const MODE_CHANGE = 'change';

    /**
     * Invalid mode for configuration
     */
    const MODE_INVALID = 'invalid';

    /**
     * Display mode for configuration
     */
    const MODE_DISPLAY = 'display';

    /**
     * @return int
     */
    public function processCommandTask()
    {
        // set params
        $paramKey   = $this->route->getMatchedParam('configKey');
        $paramValue = $this->route->getMatchedParam('configValue');

        // check for mode
        if ($paramKey && $paramValue) {
            $mode = self::MODE_CHANGE;
        } elseif (!$paramKey && $paramValue) {
            $mode = self::MODE_INVALID;
        } else {
            $mode = self::MODE_DISPLAY;
        }

        // check for invalid mode
        if (!$this->checkMode($mode)) {
            return 1;
        }

        // check for unknown config key
        if (!$this->checkUnknownKey($paramKey)) {
            return 1;
        }

        // check for change mode
        if ($mode == self::MODE_CHANGE) {
            // check value for flag config key
            if (!$this->checkValueForFlagKey($paramKey, $paramValue)) {
                return 1;
            }

            // check value for namespace config key
            if (!$this->checkValueForNamespaceKey($paramKey, $paramValue)) {
                return 1;
            }
        }

        // handle modes
        if ($mode == self::MODE_DISPLAY) {
            $this->displayConfiguration($paramKey);
        } else {
            $this->changeConfiguration($paramKey, $paramValue);
            $this->displayConfiguration();
        }

        return 0;
    }

    /**
     * Check mode
     *
     * @param $mode
     *
     * @return bool
     */
    protected function checkMode($mode)
    {
        // check for invalid mode
        if ($mode != self::MODE_INVALID) {
            return true;
        }

        // output fail message
        $this->console->writeFailLine(
            'task_tool_configuration_change_missing_config_key'
        );

        return false;
    }

    /**
     * Check for unknown configuration key
     *
     * @param $paramKey
     *
     * @return bool
     */
    protected function checkUnknownKey($paramKey)
    {
        // check if config key exists
        if (!$paramKey
            || in_array(
                $paramKey, array_keys($this->params->config)
            )
        ) {
            return true;
        }

        // output fail message
        $this->console->writeFailLine(
            'task_tool_configuration_change_unknown_config_key',
            [
                $this->console->colorize($paramKey, Color::GREEN),
            ]
        );

        return false;
    }

    /**
     * Check if the value for a flag configuration key is true or false
     *
     * @param $paramKey
     * @param $paramValue
     *
     * @return bool
     */
    protected function checkValueForFlagKey($paramKey, $paramValue)
    {
        if (substr($paramKey, 0, 4) != 'flag'
            || in_array(
                $paramValue, ['true', 'false']
            )
        ) {
            return true;
        }

        $this->console->writeFailLine(
            'task_tool_configuration_allowed_flag_config_keys',
            [
                $this->console->colorize($paramKey, Color::GREEN),
                $this->console->colorize('true', Color::BLUE),
                $this->console->colorize('false', Color::BLUE),
            ]
        );

        return false;
    }

    /**
     * Check if the value for a namespace configuration key is true or false
     *
     * @param $paramKey
     * @param $paramValue
     *
     * @return bool
     *
     * @todo Regex needs to be refactored to match namespaces better
     */
    protected function checkValueForNamespaceKey($paramKey, $paramValue)
    {
        if (substr($paramKey, 0, 9) != 'namespace') {
            return true;
        }

        $isValid = StaticValidator::execute(
            $paramValue,
            'Regex',
            ['pattern' => '=^[A-Z]{1}[a-zA-Z0-9\\\\]*$=']
        );

        if ($isValid) {
            return true;
        }

        $this->console->writeFailLine(
            'task_tool_configuration_allowed_namespace_config_keys',
            [
                $this->console->colorize($paramKey, Color::GREEN),
            ]
        );

        return false;
    }

    /**
     * @param $paramKey
     * @param $paramValue
     */
    protected function changeConfiguration($paramKey, $paramValue)
    {
        // set config file name
        $configFile = $this->params->workingPath . '/'
            . ConfigFile::CONFIG_FILE_NAME;

        // change value for configuration key
        $this->params->config[$paramKey] = $paramValue;

        // write config data to file
        file_put_contents(
            $configFile,
            json_encode(
                $this->params->config,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        $this->console->writeTaskLine(
            'task_tool_configuration_change_config_value_changed',
            [
                $this->console->colorize($paramKey, Color::GREEN),
                $this->console->colorize($paramValue, Color::BLUE),
            ]
        );
    }

    /**
     * Display the current configuration
     *
     * @param $paramKey
     */
    protected function displayConfiguration($paramKey = null)
    {
        // output found configuration
        $this->console->writeTaskLine(
            'task_tool_configuration_show_configuration_list',
            [
                $this->console->colorize(
                    $this->params->workingPath
                    . '/' . ConfigFile::CONFIG_FILE_NAME,
                    Color::GREEN
                )
            ]
        );

        $this->console->writeLine();

        // max config key length
        $maxLength = 0;

        // loop through configuration to identify max key length
        foreach ($this->params->config as $configKey => $configValue) {
            if ($paramKey && $configKey != $paramKey) {
                continue;
            }

            if (strlen($configKey) > $maxLength) {
                $maxLength = strlen($configKey);
            }
        }

        // loop through modules to display configuration
        foreach ($this->params->config as $configKey => $configValue) {
            if ($paramKey && $configKey != $paramKey) {
                continue;
            }

            $this->console->writeListItemLine(
                'task_tool_configuration_show_configuration_key',
                [
                    $this->console->colorize($configKey, Color::GREEN)
                    . str_pad('', $maxLength - strlen($configKey)),
                    $this->console->colorize($configValue, Color::BLUE)
                ]
            );
        }
    }

}
