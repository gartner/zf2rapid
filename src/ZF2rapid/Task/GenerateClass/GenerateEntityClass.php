<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateClass;

use ZF2rapid\Generator\EntityClassGenerator;

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
        $currentTable
            = $this->params->currentTableObjects[$this->params->paramTableName];

        $result = $this->generateClass(
            $this->params->entityDir,
            $this->params->entityClassName,
            'entity',
            new EntityClassGenerator($this->params->config, $currentTable)
        );

        return $result == true ? 0 : 1;
    }

}