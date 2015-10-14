<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Generator\ModuleClassGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateModuleClass
 *
 * @package ZF2rapid\Task\Module
 */
class GenerateModuleClass extends AbstractTask
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
            'task_module_generate_module_class_writing',
            [],
            false
        );

        // create class
        $class = new ModuleClassGenerator(
            $this->params->paramModule,
            $this->params->moduleRootConstant,
            $this->params->config
        );

        // create file
        $file = new ClassFileGenerator(
            $class->generate(), $this->params->config
        );

        // write file
        file_put_contents(
            $this->params->moduleDir . '/Module.php',
            $file->generate()
        );

        return 0;
    }
}