<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use ZF2rapid\Task\GenerateFactory\AbstractGenerateFactory;

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
        foreach ($this->params->tableConfig as $tableKey => $tableConfig) {
            if (isset($this->params->currentHydratorStrategies[$tableKey])) {
                $hydratorStrategies = $this->params->currentHydratorStrategies[$tableKey];
            } else {
                $hydratorStrategies = [];
            }

            $result = $this->generateFactory(
                $this->params->hydratorDir,
                $tableConfig['hydratorClass'],
                'hydrator',
                $this->params->config['namespaceHydrator'],
                'hydratorManager',
                $hydratorStrategies
            );

            if (!$result) {
                return 1;
            }
        }

        return 0;
    }
}