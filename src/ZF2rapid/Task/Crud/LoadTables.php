<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use Zend\Console\ColorInterface as Color;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Metadata\Object\ConstraintObject;
use Zend\Db\Metadata\Object\TableObject;
use ZF2rapid\Task\AbstractTask;

/**
 * Class LoadTables
 *
 * @package ZF2rapid\Task\Crud
 */
class LoadTables extends AbstractTask
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
            'task_crud_load_tables_name',
            array(
                $this->console->colorize(
                    $this->params->paramTableName, Color::GREEN
                ),
            )
        );

        /** @var Adapter $dbAdapter */
        $dbAdapter = $this->params->dbAdapter;

        // get Metadata for database adapter
        $metaData = new Metadata($dbAdapter);

        // fetch database
        $database = $dbAdapter->getCurrentSchema();

        // init loaded tables
        $loadedTables = array();

        /** @var TableObject $tableObject */
        foreach ($metaData->getTables() as $tableObject) {
            $columns     = array();
            $primaryKey  = array();
            $foreignKeys = array();

            /** @var ColumnObject $columnObject */
            foreach ($tableObject->getColumns() as $columnObject) {
                $columns[$columnObject->getName()] = $columnObject;
            }

            /** @var ConstraintObject $constraintObject */
            foreach ($tableObject->getConstraints() as $constraintObject) {
                if ($constraintObject->isPrimaryKey()) {
                    $primaryKey = $constraintObject;
                } elseif ($constraintObject->isForeignKey()) {
                    $foreignKeys[$constraintObject->getName()]
                        = $constraintObject;
                }
            }

            $loadedTables[$tableObject->getName()] = array(
                'columns'     => $columns,
                'primaryKey'  => $primaryKey,
                'foreignKeys' => $foreignKeys,
            );

        }

        // get missing tables
        $missingTables = array_values(
            array_diff($this->params->paramTableList, array_keys($loadedTables))
        );

        // check missing tables
        if (count($missingTables) == 1) {
            $this->console->writeFailLine(
                'task_crud_load_tables_not_exists_one',
                array(
                    $this->console->colorize(
                        $missingTables[0], Color::GREEN
                    ),
                    $this->console->colorize(
                        $database, Color::GREEN
                    ),
                )
            );

            return 1;
        } elseif (count($missingTables) > 1) {
            $this->console->writeFailLine(
                'task_crud_load_tables_not_exists_more',
                array(
                    $this->console->colorize(
                        implode(', ', $missingTables), Color::GREEN
                    ),
                    $this->console->colorize(
                        $database, Color::GREEN
                    ),
                )
            );

            return 1;
        }

        // Ini needed tables
        $neededTables = array();

        // loop through table list
        foreach ($this->params->paramTableList as $tableName) {
            $neededTables[] = $tableName;

            /** @var ConstraintObject $foreignKey */
            foreach ($loadedTables[$tableName]['foreignKeys'] as $foreignKey) {
                $neededTables[] = $foreignKey->getReferencedTableName();
            }
        }

        // get missing tables
        $missingTables = array_values(
            array_diff($neededTables, $this->params->paramTableList)
        );

        // check missing tables
        if (count($missingTables) == 1) {
            $this->console->writeFailLine(
                'task_crud_load_tables_needed_one',
                array(
                    $this->console->colorize(
                        $missingTables[0], Color::GREEN
                    ),
                    $this->console->colorize(
                        $database, Color::GREEN
                    ),
                )
            );

            return 1;
        } elseif (count($missingTables) > 1) {
            $this->console->writeFailLine(
                'task_crud_load_tables_needed_more',
                array(
                    $this->console->colorize(
                        implode(', ', $missingTables), Color::GREEN
                    ),
                    $this->console->colorize(
                        $database, Color::GREEN
                    ),
                )
            );

            return 1;
        }

        // get unneeded tables
        $unneededTables = array_diff(
            array_keys($loadedTables), $this->params->paramTableList
        );

        // clear unneeded tables
        foreach ($unneededTables as $tableName) {
            unset($loadedTables[$tableName]);
        }

        $this->params->loadedTables = $loadedTables;

        return 0;
    }

}