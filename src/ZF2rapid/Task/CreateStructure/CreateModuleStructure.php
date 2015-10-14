<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\CreateStructure;

use Zend\Console\ColorInterface as Color;

/**
 * Class CreateModuleStructure
 *
 * @package ZF2rapid\Task\Module
 */
class CreateModuleStructure extends AbstractCreateStructureTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check for module directory
        if (is_dir($this->params->moduleDir)) {
            $this->console->writeFailLine(
                'task_create_structure_module_dir_exists',
                [
                    $this->console->colorize(
                        $this->params->moduleDir, Color::GREEN
                    )
                ]
            );

            return 1;
        }

        // @todo needs to be refactored, added @ to mkdir to let the test pass
        // create module directory
        if (!@mkdir($this->params->moduleDir)) {
            $this->console->writeFailLine(
                'task_create_structure_module_dir_not_created',
                [
                    $this->console->colorize(
                        $this->params->moduleDir, Color::GREEN
                    )
                ]
            );

            return 1;
        }

        // create config module
        if (!@mkdir($this->params->moduleConfigDir, 0777, true)) {
            $this->console->writeFailLine(
                'task_create_structure_module_config_dir_not_created',
                [
                    $this->console->colorize(
                        $this->params->moduleConfigDir, Color::GREEN
                    )
                ],
                false
            );

            return 1;
        }

        // create src module
        if (!@mkdir($this->params->moduleSrcDir, 0777, true)) {
            $this->console->writeFailLine(
                'task_create_structure_module_src_dir_not_created',
                [
                    $this->console->colorize(
                        $this->params->moduleSrcDir, Color::GREEN
                    )
                ]
            );

            return 1;
        }

        // create view module
        if (!@mkdir($this->params->moduleViewDir, 0777, true)) {
            $this->console->writeFailLine(
                'task_create_structure_module_view_dir_not_created',
                [
                    $this->console->colorize(
                        $this->params->moduleViewDir, Color::GREEN
                    )
                ]
            );

            return 1;
        }

        // output message
        $this->console->writeTaskLine(
            'task_create_structure_module_root_created',
            [
                $this->console->colorize(
                    $this->params->moduleDir, Color::GREEN
                )
            ]
        );

        return 0;
    }

}