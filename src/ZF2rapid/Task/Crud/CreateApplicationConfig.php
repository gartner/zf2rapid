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
                [
                    $this->console->colorize($configFile, Color::GREEN)
                ]
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
            $configData['controllers'] = [];
        }

        // check for factories config key
        if (!isset($configData['controllers']['factories'])) {
            $configData['controllers']['factories'] = [];
        }

        // generate config for all needed controllers
        foreach (['Index', 'Show', 'Create', 'Update', 'Delete'] as $controllerName) {
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
            $configData['form_elements'] = [];
        }

        // check for invokables config key
        if (!isset($configData['form_elements']['invokables'])) {
            $configData['form_elements']['invokables'] = [];
        }

        // check for factories config key
        if (!isset($configData['form_elements']['factories'])) {
            $configData['form_elements']['factories'] = [];
        }

        // set class and namespace
        $configKey = $this->params->paramModule . '\Data\Form';
        $class     = $this->params->paramModule . '\\' . $this->params->config['namespaceForm'] . '\\'
            . str_replace('Entity', '', $this->params->paramEntityClass) . 'DataFormFactory';

        // add class
        $configData['form_elements']['factories'][$configKey] = $class;

        // set class and namespace
        $configKey = $this->params->paramModule . '\Delete\Form';
        $class     = $this->params->paramModule . '\\' . $this->params->config['namespaceForm'] . '\\'
            . str_replace('Entity', '', $this->params->paramEntityClass) . 'DeleteForm';

        // add class
        $configData['form_elements']['invokables'][$configKey] = $class;

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
        $childRoutes = [];

        // loop through loaded controller actions
        foreach (
            ['Show', 'Create', 'Update', 'Delete'] as $controllerName
        ) {
            $controllerKey = $this->filterCamelCaseToDash(
                str_replace(
                    $this->params->paramModule . '\\', '', $controllerName
                )
            );

            if ($controllerName == 'Create') {
                $route = '/' . $controllerKey;
                $constraints = [];
            } else {
                $route = '/' . $controllerKey . '[/:id]';
                $constraints = [
                    'id' => '[a-z0-9-]*',
                ];
            }

            $childRoutes[$controllerKey] = [
                'type'    => 'segment',
                'options' => [
                    'route'       => $route,
                    'defaults'    => [
                        'controller' => $controllerName,
                    ],
                    'constraints' => $constraints,
                ],
            ];
        }

        // check for routing config
        if (!isset($configData['router'])) {
            $configData['router'] = [
                'routes' => [],
            ];
        }

        // prepare module key
        $moduleKey = $this->filterCamelCaseToDash($this->params->paramModule);

        // create route
        $configData['router']['routes'][$moduleKey] = [
            'type'          => 'Literal',
            'options'       => [
                'route'    => '/' . $this->filterCamelCaseToDash($this->params->paramModule),
                'defaults' => [
                    '__NAMESPACE__' => $this->params->paramModule,
                    'controller'    => 'Index',
                    'action'        => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes'  => $childRoutes,
        ];

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
            $configData['translator'] = [];
        }

        // check for translator translation_file_patterns config
        if (!isset($configData['translator']['translation_file_patterns'])) {
            $configData['translator']['translation_file_patterns'] = [];
        }

        // create route
        $configData['translator']['translation_file_patterns'][] = [
            'type'     => 'phpArray',
            'base_dir' => $this->params->moduleRootConstant . ' . \'/language\'',
            'pattern'  => '%s.php',
        ];

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
            $configData['navigation'] = [];
        }

        // check for navigation default config
        if (!isset($configData['navigation']['default'])) {
            $configData['navigation']['default'] = [];
        }

        // create navigation for module
        $configData['navigation']['default'][$moduleKey] = [
            'type'          => 'mvc',
            'order'         => '200',
            'label'         => $this->filterCamelCaseToUnderscore($this->params->paramModule) . '_navigation_index',
            'route'         => $moduleKey,
            '__NAMESPACE__' => $this->params->paramModule,
            'controller'    => 'Index',
            'action'        => 'index',
            'pages'         => [
                'show' => [
                    'type'    => 'mvc',
                    'route'   => $moduleKey . '/show',
                    'visible' => false,
                ],
                'create' => [
                    'type'    => 'mvc',
                    'route'   => $moduleKey . '/create',
                    'visible' => false,
                ],
                'update' => [
                    'type'    => 'mvc',
                    'route'   => $moduleKey . '/update',
                    'visible' => false,
                ],
                'delete' => [
                    'type'    => 'mvc',
                    'route'   => $moduleKey . '/delete',
                    'visible' => false,
                ],
            ],
        ];

        return $configData;
    }
}