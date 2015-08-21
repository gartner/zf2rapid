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
 * Class UpdateInputFilterConfig
 *
 * @package ZF2rapid\Task\UpdateConfig
 */
class UpdateInputFilterConfig extends AbstractUpdateServiceManagerConfig
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

        $result = $this->updateConfig(
            'input_filters',
            $configKey,
            $this->params->paramInputFilter,
            $this->params->config['namespaceInputFilter']
        );

        return $result == true ? 0 : 1;
    }
}