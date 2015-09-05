<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Check;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class ModulePathExists
 *
 * @package ZF2rapid\Task\Check
 */
class ModulePathExists extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check module path
        if (!file_exists($this->params->projectModuleDir)) {
            $this->console->writeFailLine(
                'task_check_project_path_not_exists',
                array(
                    $this->console->colorize(
                        realpath($this->params->workingPath), Color::GREEN
                    )
                )
            );

            return 1;
        }

        return 0;
    }

}