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
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class CreateApplicationConfig
 *
 * @package ZF2rapid\Task\Crud
 */
class CreateApplicationConfig extends AbstractTask
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
            'Writing application configuration...'
        );

        // set config dir
        $configFile = $this->params->moduleConfigDir . '/module.config.php';

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeFailLine(
                'task_update_config_module_config_not_exists',
                array(
                    $this->console->colorize($configFile, Color::GREEN)
                )
            );

            return false;
        }

        // get config data from file
        $configData = include $configFile;

        // add controller config
        $configData = $this->addControllerConfig($configData);

        // add form config
        $configData = $this->addFormConfig($configData);

        // add routing config
        $configData = $this->addRoutingConfig($configData);

        // add translate config
        $configData = $this->addTranslateConfig($configData);

        // add navigation config
        $configData = $this->addNavigationConfig($configData);

        // create config array
        $config = new ConfigArrayGenerator($configData, $this->params);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write file
        file_put_contents($configFile, $file->generate());

        return 0;
    }

    /**
     * @param $configData
     *
     * @return mixed
     */
    protected function addControllerConfig($configData)
    {
        // check for config key
        if (!isset($configData['controllers'])) {
            $configData['controllers'] = array();
        }

        // check for factories config key
        if (!isset($configData['controllers']['factories'])) {
            $configData['controllers']['factories'] = array();
        }

        // generate config for all needed controllers
        foreach (array('Index', 'Show') as $controllerName) {
            // set class and namespace
            $configKey = $this->params->paramModule . '\\' . $controllerName;
            $class     = $this->params->paramModule . '\\' . $this->params->config['namespaceController'] . '\\'
                . $controllerName . 'ControllerFactory';

            // add class
            $configData['controllers']['factories'][$configKey] = $class;
        }

        return $configData;
    }

    /**
     * @param $configData
     *
     * @return mixed
     */
    protected function addFormConfig($configData)
    {
        // check for config key
        if (!isset($configData['form_elements'])) {
            $configData['form_elements'] = array();
        }

        // check for factories config key
        if (!isset($configData['form_elements']['factories'])) {
            $configData['form_elements']['factories'] = array();
        }

        // set class and namespace
        $configKey = $this->params->paramModule . '\Form';
        $class     = $this->params->paramModule . '\\' . $this->params->config['namespaceForm'] . '\\'
            . $this->params->paramModule . 'FormFactory';

        // add class
        $configData['form_elements']['factories'][$configKey] = $class;

        return $configData;
    }

    /**
     * @param $configData
     *
     * @return mixed
     */
    protected function addRoutingConfig($configData)
    {
        // create child routes
        $childRoutes = array();

        // loop through loaded controller actions
        foreach (
            array('Show') as $controllerName
        ) {
            $controllerKey = $this->filterCamelCaseToDash(
                str_replace(
                    $this->params->paramModule . '\\', '', $controllerName
                )
            );

            $childRoutes[$controllerKey] = array(
                'type'    => 'segment',
                'options' => array(
                    'route'       => '/' . $controllerKey . '[/:id]',
                    'defaults'    => array(
                        'controller' => $controllerName,
                    ),
                    'constraints' => array(
                        'id' => '[a-z0-9-]*',
                    ),
                ),
            );
        }

        // check for routing config
        if (!isset($configData['router'])) {
            $configData['router'] = array(
                'routes' => array(),
            );
        }

        // prepare module key
        $moduleKey = $this->filterCamelCaseToDash($this->params->paramModule);

        // create route
        $configData['router']['routes'][$moduleKey] = array(
            'type'          => 'Literal',
            'options'       => array(
                'route'    => '/' . $this->filterCamelCaseToDash($this->params->paramModule),
                'defaults' => array(
                    '__NAMESPACE__' => $this->params->paramModule,
                    'controller'    => 'Index',
                    'action'        => 'index',
                ),
            ),
            'may_terminate' => true,
            'child_routes'  => $childRoutes,
        );

        return $configData;
    }

    /**
     * @param $configData
     *
     * @return mixed
     */
    protected function addTranslateConfig($configData)
    {
        // check for translator config
        if (!isset($configData['translator'])) {
            $configData['translator'] = array();
        }

        // check for translator translation_file_patterns config
        if (!isset($configData['translator']['translation_file_patterns'])) {
            $configData['translator']['translation_file_patterns'] = array();
        }

        // create route
        $configData['translator']['translation_file_patterns'][] = array(
            'type'     => 'phpArray',
            'base_dir' => $this->params->moduleRootConstant . ' . \'/language\'',
            'pattern'  => '%s.php',
        );

        return $configData;
    }

    /**
     * @param $configData
     *
     * @return mixed
     */
    protected function addNavigationConfig($configData)
    {
        // prepare module key
        $moduleKey = $this->filterCamelCaseToDash($this->params->paramModule);

        // check for navigation config
        if (!isset($configData['navigation'])) {
            $configData['navigation'] = array();
        }

        // check for navigation default config
        if (!isset($configData['navigation']['default'])) {
            $configData['navigation']['default'] = array();
        }

        // create navigation for module
        $configData['navigation']['default'][$moduleKey] = array(
            'type'          => 'mvc',
            'order'         => '200',
            'label'         => $moduleKey . '_navigation_index',
            'route'         => $moduleKey,
            '__NAMESPACE__' => $this->params->paramModule,
            'controller'    => 'Index',
            'action'        => 'index',
            'pages'         => array(
                'show' => array(
                    'type'    => 'mvc',
                    'route'   => $moduleKey . '/show',
                    'visible' => false,
                ),
            ),
        );

        return $configData;
    }
}