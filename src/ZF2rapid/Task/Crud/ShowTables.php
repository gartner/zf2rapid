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
use Zend\Db\Adapter\Exception\InvalidArgumentException;
use Zend\Db\Adapter\Exception\RuntimeException;
use Zend\Db\Metadata\Metadata;
use ZF2rapid\Task\AbstractTask;

/**
 * Class ShowTables
 *
 * @package ZF2rapid\Task\Crud
 */
class ShowTables extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        /** @var Adapter $dbAdapter */
        $dbAdapter = $this->params->dbAdapter;

        // get Metadata for database adapter
        $metaData = new Metadata($dbAdapter);

        // fetch database
        $database = $dbAdapter->getCurrentSchema();

        // fetch tables
        $tables = $metaData->getTables();

        // output found modules
        $this->console->writeTaskLine(
            'task_crud_show_tables_found',
            array(
                $this->console->colorize(
                    $database, Color::GREEN
                ),
            )
        );

        $this->console->writeLine();

        // loop through modules
        foreach ($tables as $tableObject) {
            $this->console->writeListItemLine(
                'task_crud_show_tables_table_name',
                array(
                    $this->console->colorize($tableObject->getName(), Color::GREEN),
                )
            );
        }

        return 0;
    }

}