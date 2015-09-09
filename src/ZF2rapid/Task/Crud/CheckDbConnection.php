<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use Zend\Config\Factory as ConfigFactory;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\Exception\InvalidArgumentException;
use Zend\Db\Adapter\Exception\RuntimeException;
use ZF2rapid\Task\AbstractTask;

/**
 * Class CheckDbConnection
 *
 * @package ZF2rapid\Task\Crud
 */
class CheckDbConnection extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // load autoload configuration from project
        $config = ConfigFactory::fromFiles(
            glob(
                $this->params->projectConfigDir
                . '/autoload/{,*.}{global,development,local}.php',
                GLOB_BRACE
            )
        );

        // check for db config
        if (empty($config) || !isset($config['db'])) {
            $this->console->writeFailLine(
                'task_crud_check_db_connection_no_config'
            );

            return 1;
        }

        // create db adapter instance
        try {
            $dbAdapter = new Adapter($config['db']);
        } catch (InvalidArgumentException $e) {
            $this->console->writeFailLine(
                'task_crud_check_db_connection_config_inconsistent'
            );

            return 1;
        }

        // connect to database
        try {
            $connection = $dbAdapter->getDriver()->getConnection()->connect();
        } catch (RuntimeException $e) {
            $this->console->writeFailLine(
                'task_crud_check_db_connection_failed'
            );

            return 1;
        }

        $this->params->dbAdapter = $dbAdapter;

        return 0;
    }

}