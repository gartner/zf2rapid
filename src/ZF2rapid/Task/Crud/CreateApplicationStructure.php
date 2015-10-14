<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use ZF2rapid\Task\CreateStructure\AbstractCreateStructureTask;

/**
 * Class CreateApplicationStructure
 *
 * @package ZF2rapid\Task\Controller
 */
class CreateApplicationStructure extends AbstractCreateStructureTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $result = $this->createDirectory(
            $this->params->applicationControllerDir, 'Controller'
        );

        if (!$result) {
            return 1;
        }

        $result = $this->createDirectory(
            $this->params->applicationFormDir, 'Form'
        );

        if (!$result) {
            return 1;
        }

        // loop through controller
        foreach (['index', 'show', 'create', 'update', 'delete'] as $controllerView) {
            $result = $this->createDirectory(
                $this->params->moduleViewDir . DIRECTORY_SEPARATOR . $controllerView, 'Controller view'
            );

            if (!$result) {
                return 1;
            }
        }

        $result = $this->createDirectory(
            $this->params->applicationLanguageDir, 'Language'
        );

        if (!$result) {
            return 1;
        }

        return 0;
    }

}