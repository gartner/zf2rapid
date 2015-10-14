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
use ZF2rapid\Generator\Crud\CreateActionViewGenerator;
use ZF2rapid\Generator\Crud\DeleteActionViewGenerator;
use ZF2rapid\Generator\Crud\IndexActionViewGenerator;
use ZF2rapid\Generator\Crud\ShowActionViewGenerator;
use ZF2rapid\Generator\Crud\UpdateActionViewGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateViews
 *
 * @package ZF2rapid\Task\Crud
 */
class GenerateViews extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        foreach (['index', 'show', 'create', 'update', 'delete'] as $viewName) {
            // output message
            $this->console->writeTaskLine(
                'Writing ' . $viewName . ' action view script...'
            );

            // set action file
            $actionFile = $this->params->moduleViewDir . DIRECTORY_SEPARATOR . $viewName . DIRECTORY_SEPARATOR
                . 'index.phtml';

            // check if controller file exists
            if (file_exists($actionFile)) {
                $this->console->writeFailLine(
                    'task_generate_action_view_exists',
                    [
                        $this->console->colorize(
                            $actionFile, Color::GREEN
                        ),
                        $this->console->colorize(
                            $viewName, Color::GREEN
                        ),
                        $this->console->colorize(
                            $this->params->paramEntityModule, Color::GREEN
                        )
                    ]
                );

                return 1;
            }

            // set generator class
            $generatorClass = 'ZF2rapid\Generator\Crud\\' . ucfirst($viewName) . 'ActionViewGenerator';

            // create class
            /** @var IndexActionViewGenerator $viewScript */
            /** @var ShowActionViewGenerator $viewScript */
            /** @var CreateActionViewGenerator $viewScript */
            /** @var UpdateActionViewGenerator $viewScript */
            /** @var DeleteActionViewGenerator $viewScript */
            $viewScript = new $generatorClass(
                $this->params->paramModule,
                $this->params->loadedEntity
            );

            // create file
            $file = new ClassFileGenerator(
                $viewScript->generate(), $this->params->config
            );

            // write file
            file_put_contents($actionFile, '<?php' . "\n" . $file->generate());
        }

        return 0;
    }
}