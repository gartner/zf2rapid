<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Fetch;

use Zend\Console\ColorInterface as Color;
use Zend\EventManager\SharedEventManager;
use Zend\ModuleManager\Listener\DefaultListenerAggregate;
use Zend\ModuleManager\Listener\ListenerOptions;
use Zend\ModuleManager\ModuleManager;
use ZF2rapid\Task\AbstractTask;

/**
 * Class LoadModules
 *
 * @package ZF2rapid\Task\Fetch
 */
class LoadModules extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $modulePaths = $this->getModulePathsForProject();

        // define module list
        if ($this->params->paramModuleList
            && count($this->params->paramModuleList) > 0
        ) {
            // use modules parameter
            $moduleList = $this->params->paramModuleList;
        } else {
            $moduleList = $this->loadModulesForProject($modulePaths);
        }

        // init loadable modules
        $loadableModules = array();

        // loop through module list
        foreach ($moduleList as $moduleName) {
            foreach ($modulePaths as $modulePath) {
                // check module file
                $moduleFile = $modulePath . '/' . $moduleName . '/Module.php';

                if (file_exists($moduleFile)) {
                    $loadableModules[] = $moduleName;
                }
            }
        }

        // sort by key
        sort($loadableModules);

        // configure event listeners for module manager
        $sharedEvents = new SharedEventManager();
        $defaultListeners = new DefaultListenerAggregate(
            new ListenerOptions(
                array('module_paths' => $modulePaths)
            )
        );

        // configure module manager
        $moduleManager = new ModuleManager($loadableModules);
        $moduleManager->getEventManager()->setSharedManager($sharedEvents);
        $moduleManager->getEventManager()->attachAggregate($defaultListeners);
        $moduleManager->loadModules();

        // set loaded modules
        $this->params->loadedModules = $moduleManager->getLoadedModules();

        // check loaded modules
        if (!empty($this->params->loadedModules)) {
            return 0;
        }

        // output fail message
        $this->console->writeTaskLine(
            'task_fetch_load_modules_not_found',
            array(
                $this->console->colorize(
                    $this->params->projectPath, Color::GREEN
                )
            )
        );

        return 1;
    }

    /**
     * @param array $modulePaths
     *
     * @return array
     */
    private function loadModulesForProject(array $modulePaths = array())
    {
        // init $moduleList
        $moduleList = array();

        // loop through module paths
        foreach ($modulePaths as $modulePath) {
            $moduleList = array_merge($moduleList, scandir($modulePath));
        }

        // clear double paths
        $moduleList = array_unique($moduleList);

        // clear unwanted entries
        unset($moduleList[array_search('.', $moduleList)]);
        unset($moduleList[array_search('..', $moduleList)]);

        return $moduleList;
    }

    /**
     * @return array
     */
    private function getModulePathsForProject()
    {
        // init modulePaths
        $modulePaths = array();

        // set filter dirs
        $filterDirs = array('..', '.', 'autoload');

        // get existing config files
        $configFiles = array_values(
            array_diff(
                scandir($this->params->projectConfigDir), $filterDirs
            )
        );

        // loop through config files
        foreach ($configFiles as $configFile) {
            // set config dir and file
            $configFile = $this->params->projectConfigDir . '/' . $configFile;

            // create src module
            if (!file_exists($configFile)) {
                continue;
            }

            // get config data from file
            $configData = include $configFile;

            if (is_array($configData)
                && isset($configData['module_listener_options'])
                && isset($configData['module_listener_options']['module_paths'])
            ) {
                $modulePaths = array_merge(
                    $modulePaths,
                    $configData['module_listener_options']['module_paths']
                );
            }
        }

        // clear double paths
        $modulePaths = array_unique($modulePaths);

        // clear vendor path if set
        unset($modulePaths[array_search('./vendor', $modulePaths)]);

        // add project path to module paths
        foreach ($modulePaths as $key => $modulePath) {
            $modulePaths[$key] = realpath(
                $this->params->projectPath . DIRECTORY_SEPARATOR . $modulePath
            );
        }

        return $modulePaths;
    }

}