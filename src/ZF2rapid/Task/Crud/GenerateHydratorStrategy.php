<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use Zend\Db\Metadata\Object\ConstraintObject;
use ZF2rapid\Generator\HydratorStrategyGenerator;
use ZF2rapid\Task\GenerateClass\AbstractGenerateClass;

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
        $this->params->currentHydratorStrategies = [];

        foreach ($this->params->loadedTables as $tableKey => $tableData) {
            if (count($tableData['foreignKeys']) == 0) {
                continue;
            }

            $foreignKeys = $tableData['foreignKeys'];

            /** @var ConstraintObject $foreignKey */
            foreach ($foreignKeys as $foreignKey) {

                $refTable = $foreignKey->getReferencedTableName();
                $result   = $this->generateClass(
                    $this->params->hydratorStrategyDir,
                    ucfirst($refTable) . 'Strategy',
                    'hydrator strategy',
                    new HydratorStrategyGenerator(
                        $this->params, $refTable
                    ),
                    false
                );

                if (!$result) {
                    return 1;
                }

                if (!isset($this->params->currentHydratorStrategies[$tableKey])) {
                    $this->params->currentHydratorStrategies[$tableKey]
                        = [];
                }

                $refColumn = $foreignKey->getColumns()[0];

                $this->params->currentHydratorStrategies[$tableKey][$refTable]
                    = [
                        'class' => $this->filterUnderscoreToCamelCase(
                            $refTable
                        ) . 'Strategy',
                        'column' => $refColumn,
                    ];
            }
        }

        return 0;
    }
}
