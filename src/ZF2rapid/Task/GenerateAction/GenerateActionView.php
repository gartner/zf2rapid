<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateAction;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;
use ZF2rapid\Generator\ActionViewGenerator;
use ZF2rapid\Generator\ClassFileGenerator;

/**
 * Class GenerateActionView
 *
 * @package ZF2rapid\Task\GenerateAction
 */
class GenerateActionView extends AbstractTask
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
            'Writing action view script...'
        );

        // set action
        if ($this->params->paramAction) {
            $action = $this->filterCamelCaseToDash($this->params->paramAction);
        } else {
            $action = 'index';
        }

        // set action file
        $actionFile = $this->params->controllerViewDir . DIRECTORY_SEPARATOR
            . $action . '.phtml';

        // check if controller file exists
        if (file_exists($actionFile)) {
            $this->console->writeFailLine(
                'task_generate_action_view_exists',
                [
                    $this->console->colorize(
                        $actionFile, Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramController, Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    )
                ]
            );

            return 1;
        }

        // create class
        $view = new ActionViewGenerator(
            $this->filterDashToCamelCase($action),
            $this->params->paramController,
            $this->params->paramModule
        );

        // create file
        $file = new ClassFileGenerator(
            $view->generate(), $this->params->config
        );

        // write file
        file_put_contents($actionFile, $file->generate());

        return 0;
    }
}