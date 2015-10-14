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
 * Class UpdateFilterConfig
 *
 * @package ZF2rapid\Task\UpdateConfig
 */
class UpdateFilterConfig extends AbstractUpdateServiceManagerConfig
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check for config
        if (!$this->params->paramWriteConfig) {
            return 0;
        }

        // output message
        $this->console->writeTaskLine(
            'Writing filter configuration...'
        );

        $configKey = lcfirst($this->params->paramModule)
            . $this->params->paramFilter;

        $result = $this->updateConfig(
            'filters',
            $configKey,
            $this->params->paramFilter,
            $this->params->config['namespaceFilter']
        );

        return $result == true ? 0 : 1;
    }
}