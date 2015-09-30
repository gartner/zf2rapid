<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\FileReflection;
use Zend\Console\ColorInterface as Color;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Metadata\Object\ConstraintObject;
use Zend\Db\Metadata\Object\TableObject;
use ZF2rapid\Task\AbstractTask;

/**
 * Class LoadEntity
 *
 * @package ZF2rapid\Task\Crud
 */
class LoadEntity extends AbstractTask
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
            'task_crud_load_entity_class',
            array(
                $this->console->colorize(
                    $this->params->paramEntityClass, Color::GREEN
                ),
                $this->console->colorize(
                    $this->params->paramEntityModule, Color::GREEN
                ),
            )
        );

        if (!file_exists($this->params->entityModuleDir)) {
            $this->console->writeFailLine(
                'task_crud_load_entity_module_not_found',
                array(
                    $this->console->colorize(
                        $this->params->paramEntityModule, Color::GREEN
                    ),
                )
            );

            return 1;
        }

        if (!file_exists($this->params->entityFile)) {
            $this->console->writeFailLine(
                'task_crud_load_entity_entity_not_found',
                array(
                    $this->console->colorize(
                        $this->params->paramEntityClass, Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramEntityModule, Color::GREEN
                    ),
                )
            );

            return 1;
        }

        require_once $this->params->entityFile;

        $entityClass = new FileReflection($this->params->entityFile);

        $this->params->loadedEntity = $entityClass->getClass();

        return 0;
    }

}