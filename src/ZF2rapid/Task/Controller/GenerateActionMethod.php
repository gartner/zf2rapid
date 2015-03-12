<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Controller;

use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Reflection\FileReflection;
use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ActionMethodGenerator;
use ZF2rapid\Generator\ClassFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateActionMethod
 *
 * @package ZF2rapid\Task\Controller
 */
class GenerateActionMethod extends AbstractTask
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
            'Writing action method for controller...'
        );

        // set controller file and action method
        $controllerFile = $this->params->controllerDir . '/'
            . $this->params->paramController . 'Controller.php';

        // get file and class reflection
        $fileReflection = new FileReflection(
            $controllerFile,
            true
        );

        $classReflection = $fileReflection->getClass(
            $this->params->paramModule . '\Controller\\'
            . $this->params->paramController . 'Controller'
        );

        // setup class generator with reflected class
        $class = ClassGenerator::fromReflection($classReflection);

        // set method to check
        $checkMethod = strtolower($this->params->paramAction) . 'action';

        // check for action method
        if ($class->hasMethod($checkMethod)) {
            $this->console->writeFailLine(
                'The action ' . $this->console->colorize(
                    $this->params->paramAction, Color::GREEN
                ) . ' already exists in controller ' . $this->console->colorize(
                    $this->params->paramController, Color::GREEN
                ) . ' of module ' . $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                ) . '.'
            );

            return 1;
        }

        // fix namespace usage
        $class->addUse('Zend\Mvc\Controller\AbstractActionController');
        $class->addUse('Zend\View\Model\ViewModel');
        $class->setExtendedClass('AbstractActionController');
        $class->addMethodFromGenerator(
            new ActionMethodGenerator(
                $this->params->paramAction, $this->params->paramController,
                $this->params->config
            )
        );

        // create file
        $file = new ClassFileGenerator(
            $class->generate(), $this->params->config
        );

        // write file
        file_put_contents($controllerFile, $file->generate());

        return 0;
    }
}