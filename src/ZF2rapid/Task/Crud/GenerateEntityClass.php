<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use ZF2rapid\Generator\EntityClassGenerator;
use ZF2rapid\Task\GenerateClass\AbstractGenerateClass;

/**
 * Class GenerateEntityClass
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateEntityClass extends AbstractGenerateClass
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        foreach ($this->params->loadedTables as $tableKey => $tableData) {
            $result = $this->generateClass(
                $this->params->entityDir,
                $this->params->tableConfig[$tableKey]['entityClass'],
                'entity',
                new EntityClassGenerator($this->params->config, $tableData)
            );

            if (!$result) {
                return 1;
            }
        }

        return 0;
    }

}