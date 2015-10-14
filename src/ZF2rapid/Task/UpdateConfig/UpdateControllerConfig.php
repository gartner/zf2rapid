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
 * Class UpdateControllerConfig
 *
 * @package ZF2rapid\Task\UpdateConfig
 */
class UpdateControllerConfig extends AbstractUpdateServiceManagerConfig
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
            'Writing controller configuration...'
        );

        $configKey = $this->params->paramModule . '\\'
            . $this->params->paramController;

        $result = $this->updateConfig(
            'controllers',
            $configKey,
            $this->params->paramController . 'Controller',
            $this->params->config['namespaceController']
        );

        return $result == true ? 0 : 1;
    }
}