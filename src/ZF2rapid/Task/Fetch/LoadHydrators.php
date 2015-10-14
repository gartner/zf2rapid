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
 * Class LoadHydrators
 *
 * @package ZF2rapid\Task\Fetch
 */
class LoadHydrators extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // initialize plugin list
        $loadedHydrators = [];

        // loop through loaded modules and fetch controllers for module
        foreach ($this->params->loadedModules as $moduleName => $moduleObject) {
            $loadedHydrators[$moduleName] = $this->loadPluginsForModule(
                $moduleObject
            );
        }

        // set loaded plugin
        $this->params->loadedHydrators = $loadedHydrators;

        // check loaded modules
        if (!empty($this->params->loadedHydrators)) {
            return 0;
        }

        $this->console->writeTaskLine(
            'task_fetch_load_hydrators_not_found',
            [
                $this->console->colorize(
                    $this->params->workingPath, Color::GREEN
                )
            ]
        );

        return 1;
    }

    /**
     * Get hydrators for a module
     *
     * @param $moduleObject
     *
     * @return array
     */
    protected function loadPluginsForModule($moduleObject)
    {
        // initialize plugins
        $plugins = [];

        // check for configuration
        if (!method_exists($moduleObject, 'getConfig')) {
            return $plugins;
        }

        // get module configuration
        $moduleConfig = $moduleObject->getConfig();

        // check for no configured plugins
        if (!isset($moduleConfig['controller_plugins'])) {
            return $plugins;
        }

        // loop through plugins
        foreach ($moduleConfig['controller_plugins'] as $type => $loadedPlugins) {
            // skip if not invokable nor factory
            if (!in_array($type, ['invokables', 'factories'])) {
                continue;
            }

            // initialize plugin type
            $plugins[$type] = [];

            // loop through plugin list
            foreach ($loadedPlugins as $pluginKey => $pluginClass) {

                // check if plugin class or factory does not exist
                if (!class_exists($pluginClass)) {
                    continue;
                }

                // check for factory
                if (in_array(
                    'Zend\ServiceManager\FactoryInterface',
                    class_implements($pluginClass)
                )) {
                    // start class reflection
                    $classReflection = new ClassReflection($pluginClass);

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

                            $pluginClass
                                = $classReflection->getNamespaceName() . '\\'
                                . $tag->getTypes()[0];

                        }
                    } else {
                        // try to read plugin instantiation from method
                        preg_match_all(
                            '^\$plugin\s*=\s*new\s*([a-zA-z0-9]+)\(\)\;^',
                            $method->getContents(),
                            $matches
                        );

                        $pluginClass
                            = $classReflection->getNamespaceName() . '\\'
                            . $matches[1][0];

                    }
                }

                // check for class existence
                if (!class_exists($pluginClass)) {
                    break;
                }

                // add plugin class for type
                $plugins[$type][$pluginKey] = $pluginClass;
            }
        }

        // loop through plugins
        foreach ($plugins as $type => $pluginList) {
            // check if any plugin exists for type
            if (empty($pluginList)) {
                unset ($plugins[$type]);

                // otherwise sort plugins
            } else {
                ksort($plugins[$type]);
            }
        }

        return $plugins;
    }


}