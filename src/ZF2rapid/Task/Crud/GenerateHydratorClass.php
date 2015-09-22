<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use ZF2rapid\Generator\HydratorClassGenerator;
use ZF2rapid\Task\GenerateClass\AbstractGenerateClass;

/**
 * Class GenerateHydratorClass
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateHydratorClass extends AbstractGenerateClass
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        foreach ($this->params->tableConfig as $tableConfig) {
            $result = $this->generateClass(
                $this->params->hydratorDir,
                $tableConfig['hydratorClass'],
                'hydrator',
                new HydratorClassGenerator(
                    $this->params->config,
                    $this->params->paramBaseHydrator
                )
            );

            if (!$result) {
                return 1;
            }
        }

        return 0;
    }
}