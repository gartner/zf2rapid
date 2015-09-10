<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateClass;

use Zend\Db\Metadata\Object\ConstraintObject;
use Zend\Db\Metadata\Object\TableObject;
use ZF2rapid\Generator\HydratorStrategyGenerator;

/**
 * Class GenerateHydratorStrategy
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateHydratorStrategy extends AbstractGenerateClass
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $this->params->currentHydratorStrategies = array();

        $tableObjects = $this->params->currentTableObjects;

        /** @var TableObject $currentTable */
        $currentTable = $tableObjects[$this->params->paramTableName];

        $foreignKeys = array();

        /** @var $tableConstraint ConstraintObject */
        foreach ($currentTable->getConstraints() as $tableConstraint) {
            if (!$tableConstraint->isForeignKey()) {
                continue;
            }

            $foreignKeys[] = $tableConstraint;
        }

        if (empty($foreignKeys)) {
            return 0;
        }

        // output message
        $this->console->writeTaskLine(
            'task_generate_strategy_writing',
            array(
                'hydrator'
            )
        );

        /** @var ConstraintObject $foreignKey */
        foreach ($foreignKeys as $foreignKey) {

            $refTable = $foreignKey->getReferencedTableName();
            $result = $this->generateClass(
                $this->params->hydratorStrategyDir,
                ucfirst($refTable) . 'Strategy',
                'hydrator strategy',
                new HydratorStrategyGenerator(
                    $this->params, $refTable
                )
            );

            if (!$result) {
                return 1;
            }

            $this->params->currentHydratorStrategies[$refTable] = ucfirst(
                    $refTable
                ) . 'Strategy';
        }

        return 0;
    }
}