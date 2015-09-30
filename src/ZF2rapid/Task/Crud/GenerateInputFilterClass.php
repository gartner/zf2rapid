<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use ZF2rapid\Generator\Crud\InputFilterClassGenerator;
use ZF2rapid\Task\GenerateClass\AbstractGenerateClass;

/**
 * Class GenerateInputFilterClass
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateInputFilterClass extends AbstractGenerateClass
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        foreach ($this->params->tableConfig as $tableKey => $tableConfig) {
            $result = $this->generateClass(
                $this->params->inputFilterDir,
                $tableConfig['inputFilterClass'],
                'input filter',
                new InputFilterClassGenerator(
                    $tableKey,
                    $this->params->loadedTables[$tableKey],
                    $this->params->config
                )
            );


            if (!$result) {
                return 1;
            }
        }

        return 0;
    }

}