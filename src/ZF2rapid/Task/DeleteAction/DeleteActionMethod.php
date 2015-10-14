<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Action;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Reflection\FileReflection;
use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class DeleteActionMethod
 *
 * @package ZF2rapid\Task\Action
 */
class DeleteActionMethod extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine('task_delete_action_method_deleting');

        // set controller file and action method
        $controllerFile = $this->params->controllerDir . '/'
            . $this->params->paramController . 'Controller.php';

        // get file and class reflection
        $fileReflection = new FileReflection(
            $controllerFile,
            true
        );

        $classReflection = $fileReflection->getClass(
            $this->params->paramModule . '\\'
            . $this->params->config['namespaceController'] . '\\'
            . $this->params->paramController . 'Controller'
        );

        // setup class generator with reflected class
        $class = ClassGenerator::fromReflection($classReflection);

        // set method to check
        $actionMethod = strtolower($this->params->paramAction) . 'action';

        // check for action method
        if (!$class->hasMethod($actionMethod)) {
            $this->console->writeFailLine(
                'task_delete_action_method_not_exists',
                [
                    $this->console->colorize(
                        $this->params->paramAction, Color::GREEN
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
        // fix namespace usage
        $class->addUse('Zend\Mvc\Controller\AbstractActionController');
        $class->addUse('Zend\View\Model\ViewModel');
        $class->setExtendedClass('AbstractActionController');
        $class->removeMethod($actionMethod);

        // create file
        $file = new ClassFileGenerator(
            $class->generate(), $this->params->config
        );

        // write file
        file_put_contents($controllerFile, $file->generate());

        return 0;
    }
}