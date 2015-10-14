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
 * Class UpdateValidatorConfig
 *
 * @package ZF2rapid\Task\UpdateConfig
 */
class UpdateValidatorConfig extends AbstractUpdateServiceManagerConfig
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
            'Writing validator configuration...'
        );

        $configKey = lcfirst($this->params->paramModule)
            . $this->params->paramValidator;

        $result = $this->updateConfig(
            'validators',
            $configKey,
            $this->params->paramValidator,
            $this->params->config['namespaceValidator']
        );

        return $result == true ? 0 : 1;
    }
}