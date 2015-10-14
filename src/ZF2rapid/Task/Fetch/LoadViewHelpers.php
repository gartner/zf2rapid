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
 * Class LoadViewHelpers
 *
 * @package ZF2rapid\Task\Fetch
 */
class LoadViewHelpers extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // initialize plugin list
        $loadedViewHelpers = [];

        // loop through loaded modules and fetch view helpers for module
        foreach ($this->params->loadedModules as $moduleName => $moduleObject) {
            $loadedViewHelpers[$moduleName] = $this->loadViewHelpersForModule(
                $moduleObject
            );
        }

        // set loaded plugin
        $this->params->loadedViewHelpers = $loadedViewHelpers;

        // check loaded modules
        if (!empty($this->params->loadedViewHelpers)) {
            return 0;
        }

        $this->console->writeTaskLine(
            'task_fetch_load_view_helpers_not_found',
            [
                $this->console->colorize(
                    $this->params->workingPath, Color::GREEN
                )
            ]
        );

        return 1;
    }

    /**
     * Get view helpers for a module
     *
     * @param $moduleObject
     *
     * @return array
     */
    protected function loadViewHelpersForModule($moduleObject)
    {
        // initialize view helpers
        $viewHelpers = [];

        // check for configuration
        if (!method_exists($moduleObject, 'getConfig')) {
            return $viewHelpers;
        }

        // get module configuration
        $moduleConfig = $moduleObject->getConfig();

        // check for no configured view helpers
        if (!isset($moduleConfig['view_helpers'])) {
            return $viewHelpers;
        }

        // loop through view helpers
        foreach ($moduleConfig['view_helpers'] as $type => $loadedPlugins) {
            // skip if not invokable nor factory
            if (!in_array($type, ['invokables', 'factories'])) {
                continue;
            }

            // initialize view helper type
            $viewHelpers[$type] = [];

            // loop through view helper list
            foreach ($loadedPlugins as $viewHelperKey => $viewHelperClass) {

                // check if view helper class or factory does not exist
                if (!class_exists($viewHelperClass)) {
                    continue;
                }

                // check for factory
                if (in_array(
                    'Zend\ServiceManager\FactoryInterface',
                    class_implements($viewHelperClass)
                )) {
                    // start class reflection
                    $classReflection = new ClassReflection($viewHelperClass);

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

                            $viewHelperClass
                                = $classReflection->getNamespaceName() . '\\'
                                . $tag->getTypes()[0];

                        }
                    } else {
                        // try to read view helper instantiation from method
                        preg_match_all(
                            '^\$viewHelper\s*=\s*new\s*([a-zA-z0-9]+)\(\)\;^',
                            $method->getContents(),
                            $matches
                        );

                        $viewHelperClass
                            = $classReflection->getNamespaceName() . '\\'
                            . $matches[1][0];

                    }
                }

                // check for class existence
                if (!class_exists($viewHelperClass)) {
                    break;
                }

                // add view helper class for type
                $viewHelpers[$type][$viewHelperKey] = $viewHelperClass;
            }
        }

        // loop through view helpers
        foreach ($viewHelpers as $type => $viewHelperList) {
            // check if any view helper exists for type
            if (empty($viewHelperList)) {
                unset ($viewHelpers[$type]);

                // otherwise sort view helpers
            } else {
                ksort($viewHelpers[$type]);
            }
        }

        return $viewHelpers;
    }


}