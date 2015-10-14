<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use ZF2rapid\Generator\Crud\ControllerClassGenerator;
use ZF2rapid\Task\GenerateClass\AbstractGenerateClass;

/**
 * Class GenerateControllerClasses
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateControllerClasses extends AbstractGenerateClass
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        foreach (['Index', 'Show', 'Create', 'Update', 'Delete'] as $controllerName) {
            $result = $this->generateClass(
                $this->params->applicationControllerDir,
                $controllerName . 'Controller',
                $controllerName . ' controller',
                new ControllerClassGenerator(
                    $controllerName,
                    $this->params->paramModule,
                    $this->params->paramEntityModule,
                    $this->params->paramEntityClass,
                    $this->params->config
                )
            );

            if (!$result) {
                return 1;
            }
        }

        return 0;
    }

}