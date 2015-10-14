<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Action;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Generator\ActionViewGenerator;
use ZF2rapid\Generator\ClassFileGenerator;

/**
 * Class DeleteActionView
 *
 * @package ZF2rapid\Task\Action
 */
class DeleteActionView extends AbstractTask
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
            'Deleting action view script...'
        );

        // set action
        $action = $this->filterCamelCaseToDash($this->params->paramAction);

        // set action file
        $actionFile = $this->params->controllerViewDir . DIRECTORY_SEPARATOR
            . $action . '.phtml';

        // check if controller file exists
        if (!file_exists($actionFile)) {
            $this->console->writeFailLine(
                'task_delete_action_view_not_exists',
                [
                    $this->console->colorize(
                        $actionFile, Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    )
                ]
            );

            return 1;
        }

        // delete file
        unlink($actionFile);

        return 0;
    }
}