<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\UpdateConfig;

/**
 * Class UpdateModelConfig
 *
 * @package ZF2rapid\Task\UpdateConfig
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

        $this->params->paramFactory = true;

        $configKey = $this->params->paramModule . '\Db\\'
            . $this->filterUnderscoreToCamelCase($this->params->paramTableName);

        $result = $this->updateConfig(
            'hydrators',
            $configKey,
            $this->params->hydratorClassName,
            $this->params->config['namespaceHydrator']
        );

        if (!$result) {
            return 1;
        }

        $configKey = $this->params->paramModule . '\Model\TableGateway\\'
            . $this->filterUnderscoreToCamelCase($this->params->paramTableName);

        $result = $this->updateConfig(
            'service_manager',
            $configKey,
            $this->params->tableGatewayClassName,
            $this->params->config['namespaceTableGateway']
        );

        if (!$result) {
            return 1;
        }

        $configKey = $this->params->paramModule . '\Model\Repository\\'
            . $this->filterUnderscoreToCamelCase($this->params->paramTableName);

        $result = $this->updateConfig(
            'service_manager',
            $configKey,
            $this->params->repositoryClassName,
            $this->params->config['namespaceRepository']
        );

        return $result == true ? 0 : 1;
    }
}