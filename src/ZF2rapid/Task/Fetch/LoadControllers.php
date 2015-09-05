<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Fetch;

use Zend\Code\Reflection\ClassReflection;
use Zend\Code\Reflection\DocBlock\Tag\ReturnTag;
use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class LoadControllers
 *
 * @package ZF2rapid\Task\Fetch
 */
class LoadControllers extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // initialize controller list
        $loadedControllers = array();

        // loop through loaded modules and fetch controllers for module
        foreach ($this->params->loadedModules as $moduleName => $moduleObject) {
            $loadedControllers[$moduleName] = $this->loadControllerForModule(
                $moduleObject
            );
        }

        // set loaded modules
        $this->params->loadedControllers = $loadedControllers;

        // check loaded modules
        if (!empty($this->params->loadedControllers)) {
            return 0;
        }

        $this->console->writeTaskLine(
            'task_fetch_load_controllers_not_found',
            array(
                $this->console->colorize(
                    $this->params->workingPath, Color::GREEN
                )
            )
        );

        return 1;
    }

    /**
     * Get controllers for a module
     *
     * @param $moduleObject
     *
     * @return array
     */
    protected function loadControllerForModule($moduleObject)
    {
        // initialize controllers
        $controllers = array();

        // check for configuration
        if (!method_exists($moduleObject, 'getConfig')) {
            return $controllers;
        }

        // get module configuration
        $moduleConfig = $moduleObject->getConfig();

        // check for no configured controllers
        if (!isset($moduleConfig['controllers'])) {
            return $controllers;
        }

        // loop through controllers
        foreach ($moduleConfig['controllers'] as $type => $loadedControllers) {
            // skip if not invokable nor factory
            if (!in_array($type, array('invokables', 'factories'))) {
                continue;
            }

            // initialize controller type
            $controllers[$type] = array();

            // loop through controller list
            foreach ($loadedControllers as $controllerKey => $controllerClass) {

                // check for controller param
                if (count($this->params->paramControllerList) > 0
                    && !in_array(
                        $controllerKey, $this->params->paramControllerList
                    )
                ) {
                    continue;
                }

                // check if controller class or factory does not exist
                if (!class_exists($controllerClass)) {
                    continue;
                }

                // check for factory
                if (in_array(
                    'Zend\ServiceManager\FactoryInterface',
                    class_implements($controllerClass)
                )) {
                    // start class reflection
                    $classReflection = new ClassReflection($controllerClass);

                    // get create method and doc block
                    $method = $classReflection->getMethod('createService');
                    $docBlock = $method->getDocBlock();

                    // check doc block for return tag and use class
                    if ($docBlock) {
                        // loop through all doc blocks
                        foreach ($docBlock->getTags() as $tag) {
                            /** @var $tag ReturnTag */
                            if ($tag->getName() != 'return') {
                                continue;
                            }

                            $controllerClass
                                = $classReflection->getNamespaceName() . '\\'
                                . $tag->getTypes()[0];

                        }
                    } else {
                        // try to read controller instantiation from method
                        preg_match_all(
                            '^\$controller\s*=\s*new\s*([a-zA-z0-9]+)\(\)\;^',
                            $method->getContents(),
                            $matches
                        );

                        $controllerClass
                            = $classReflection->getNamespaceName() . '\\'
                            . $matches[1][0];

                    }
                }

                // check for class existence
                if (!class_exists($controllerClass)) {
                    break;
                }

                // add controller class for type
                $controllers[$type][$controllerKey] = $controllerClass;
            }
        }

        // loop through controllers
        foreach ($controllers as $type => $controllerList) {
            // check if any controller exists for type
            if (empty($controllerList)) {
                unset ($controllers[$type]);

                // otherwise sort controllers
            } else {
                ksort($controllers[$type]);
            }
        }

        return $controllers;
    }


}