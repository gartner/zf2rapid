<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Fetch;

use ReflectionMethod;
use Zend\Code\Reflection\ClassReflection;
use ZF2rapid\Task\AbstractTask;

/**
 * Class LoadActions
 *
 * @package ZF2rapid\Task\Fetch
 */
class LoadActions extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // initialize action list
        $loadedActions = [];

        // loop through controllers by module
        foreach (
            $this->params->loadedControllers as $moduleKey => $controllerTypes
        ) {
            $loadedActions[$moduleKey] = [];

            $moduleViewPath = $this->filterCamelCaseToDash($moduleKey);

            // loop through controllers by controller type
            foreach ($controllerTypes as $controllerList) {

                // loop through controllers
                foreach ($controllerList as $controllerKey => $controllerClass)
                {
                    $loadedActions[$moduleKey][$controllerKey] = [];

                    // start class reflection
                    $classReflection = new ClassReflection($controllerClass);

                    // convert method name to get dashed action
                    $controllerName = substr(
                        $classReflection->getShortName(), 0, -10
                    );
                    $controllerName = $this->filterCamelCaseToDash(
                        $controllerName
                    );

                    // get public methods
                    $methods = $classReflection->getMethods(
                        ReflectionMethod::IS_PUBLIC
                    );

                    // loop through methods
                    foreach ($methods as $method) {
                        // get class and method name
                        $methodClass = $method->getDeclaringClass()->getName();
                        $methodName  = $method->name;

                        // continue for methods from extended class
                        if ($methodClass != $controllerClass) {
                            continue;
                        }

                        // continue for no-action methods
                        if (substr($methodName, -6) != 'Action') {
                            continue;
                        }

                        // convert method name to get dashed action
                        $actionName = substr($methodName, 0, -6);
                        $actionName = $this->filterCamelCaseToDash(
                            $actionName
                        );

                        // build action file
                        $actionFile = '/module/' . $moduleKey
                            . '/view/' . $moduleViewPath . '/' . $controllerName
                            . '/' . $actionName . '.phtml';

                        // check action file exists
                        if (!file_exists(
                            $this->params->workingPath . $actionFile
                        )
                        ) {
                            $actionFile = false;
                        }

                        // add action to list
                        $loadedActions[$moduleKey][$controllerKey][$actionName]
                            = $actionFile;
                    }
                }
            }
        }

        // set loaded modules
        $this->params->loadedActions = $loadedActions;

        return 0;
    }

}