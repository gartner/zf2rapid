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
 * Class UpdateHydratorConfig
 *
 * @package ZF2rapid\Task\UpdateConfig
 */
class UpdateHydratorConfig extends AbstractUpdateServiceManagerConfig
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
            'Writing hydrator configuration...'
        );

        $configKey = lcfirst($this->params->paramModule)
            . $this->params->paramHydrator;

        $result = $this->updateConfig(
            'hydrators',
            $configKey,
            $this->params->paramHydrator,
            $this->params->config['namespaceHydrator']
        );

        return $result == true ? 0 : 1;
    }
}