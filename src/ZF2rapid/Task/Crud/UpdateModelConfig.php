<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use ZF2rapid\Task\UpdateConfig\AbstractUpdateServiceManagerConfig;

/**
 * Class UpdateModelConfig
 *
 * @package ZF2rapid\Task\Crud
 */
class UpdateModelConfig extends AbstractUpdateServiceManagerConfig
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
            'Writing model configuration...'
        );

        foreach ($this->params->tableConfig as $tableKey => $tableConfig) {
            $this->params->paramFactory = true;

            $configKey = $this->params->paramModule . '\\' . $this->filterUnderscoreToCamelCase($tableKey);

            $result = $this->updateConfig(
                'hydrators',
                $configKey,
                $tableConfig['hydratorClass'],
                $this->params->config['namespaceHydrator']
            );

            if (!$result) {
                return 1;
            }

            $configKey = $this->params->paramModule . '\\'
                . $this->params->config['namespaceTableGateway'] . '\\'
                . $this->filterUnderscoreToCamelCase($tableKey);

            $result = $this->updateConfig(
                'service_manager',
                $configKey,
                $tableConfig['tableGatewayClass'],
                $this->params->config['namespaceTableGateway']
            );

            if (!$result) {
                return 1;
            }

            $configKey = $this->params->paramModule . '\\'
                . $this->params->config['namespaceRepository'] . '\\'
                . $this->filterUnderscoreToCamelCase($tableKey);

            $result = $this->updateConfig(
                'service_manager',
                $configKey,
                $tableConfig['repositoryClass'],
                $this->params->config['namespaceRepository']
            );

            if (!$result) {
                return 1;
            }

            $configKey = $this->params->paramModule . '\\'
                . $this->filterUnderscoreToCamelCase($tableKey);

            $result = $this->updateConfig(
                'input_filters',
                $configKey,
                $tableConfig['inputFilterClass'],
                $this->params->config['namespaceInputFilter']
            );

            if (!$result) {
                return 1;
            }
        }

        return 0;
    }
}