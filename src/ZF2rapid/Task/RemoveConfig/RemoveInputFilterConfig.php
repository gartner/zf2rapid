<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\RemoveConfig;

/**
 * Class RemoveInputFilterConfig
 *
 * @package ZF2rapid\Task\RemoveConfig
 */
class RemoveInputFilterConfig extends AbstractRemoveServiceManagerConfig
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
            'Writing input filter configuration...'
        );

        $configKey = lcfirst($this->params->paramModule)
            . $this->params->paramInputFilter;

        $result = $this->removeConfig(
            'input_filters',
            $configKey
        );

        return $result == true ? 0 : 1;
    }
}