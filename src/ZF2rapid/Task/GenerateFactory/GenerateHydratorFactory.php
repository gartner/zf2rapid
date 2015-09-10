<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateFactory;

/**
 * Class GenerateHydratorFactory
 *
 * @package ZF2rapid\Task\GenerateFactory
 */
class GenerateHydratorFactory extends AbstractGenerateFactory
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        if (isset($this->params->hydratorClassName)) {
            $hydrator = $this->params->hydratorClassName;
        } else {
            $hydrator = $this->params->paramHydrator;
        }

        $result = $this->generateFactory(
            $this->params->hydratorDir,
            $hydrator,
            'hydrator',
            $this->params->config['namespaceHydrator'],
            'hydratorManager'
        );

        return $result == true ? 0 : 1;
    }
}