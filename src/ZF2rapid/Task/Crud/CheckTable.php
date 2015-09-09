<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use Exception;
use Zend\Console\ColorInterface as Color;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use ZF2rapid\Task\AbstractTask;

/**
 * Class CheckTable
 *
 * @package ZF2rapid\Task\Crud
 */
class CheckTable extends AbstractTask
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
            'task_crud_check_table_name',
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

        // fetch table object
        try {
            $table = $metaData->getTable($this->params->paramTableName);
        } catch (Exception $e) {
            $this->console->writeFailLine(
                'task_crud_check_table_not_exists',
                array(
                    $this->console->colorize(
                        $this->params->paramTableName, Color::GREEN
                    ),
                    $this->console->colorize(
                        $database, Color::GREEN
                    ),
                )
            );

            return 1;
        }

        $this->params->currentTableObject = $table;

        return 0;
    }

}