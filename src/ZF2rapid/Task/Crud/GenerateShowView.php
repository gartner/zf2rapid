<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Generator\Crud\ShowActionViewGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateShowView
 *
 * @package ZF2rapid\Task\Crud
 */
class GenerateShowView extends AbstractTask
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
            'Writing show action view script...'
        );

        // set controller view
        $view = 'show';

        // set action file
        $actionFile = $this->params->moduleViewDir . DIRECTORY_SEPARATOR . $view . DIRECTORY_SEPARATOR . 'index.phtml';

        // check if controller file exists
        if (file_exists($actionFile)) {
            $this->console->writeFailLine(
                'task_generate_action_view_exists',
                array(
                    $this->console->colorize(
                        $actionFile, Color::GREEN
                    ),
                    $this->console->colorize(
                        'Show', Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramEntityModule, Color::GREEN
                    )
                )
            );

            return 1;
        }

        // create class
        $view = new ShowActionViewGenerator(
            $this->params->paramModule,
            $this->params->loadedEntity
        );

        // create file
        $file = new ClassFileGenerator(
            $view->generate(), $this->params->config
        );

        // write file
        file_put_contents($actionFile, '<?php' . "\n" . $file->generate());

        return 0;
    }
}