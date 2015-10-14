<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class CreateModuleRouting
 *
 * @package ZF2rapid\Task\Module
 */
class CreateModuleRouting extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine('task_module_create_routing_writing');

        // set config dir
        $configFile = $this->params->moduleConfigDir . '/module.config.php';

        // create src module
        if (!file_exists($configFile)) {
            $this->console->writeFailLine(
                'task_module_create_routing_config_file_not_exists',
                [
                    $this->console->colorize($configFile, Color::GREEN),
                ]
            );

            return 1;
        }

        // get config data from file
        $configData = include $configFile;

        // check if controller exists
        if (!isset($configData['controllers'])
            || count($configData['controllers']) == 0
        ) {
            $this->console->writeFailLine(
                'task_module_create_routing_controller_not_exists',
                [
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    ),
                ]
            );

            return 1;
        }

        // set routing key
        $routingKey = strtolower($this->params->paramModule);

        // check for existing router config
        if (isset($configData['router'])) {
            // check for existing routes config
            if (isset($configData['router']['routes'])
                && isset($configData['router']['routes'][$routingKey])
            ) {
                // output confirm prompt
                $deleteConfirmation = $this->console->writeConfirmPrompt(
                    'task_module_create_routing_prompt_1',
                    'task_module_create_routing_yes_answer',
                    'task_module_create_routing_no_answer'
                );

                if (!$deleteConfirmation) {
                    // output success message
                    $this->console->writeOkLine(
                        'task_module_create_routing_not_overwritten',
                        [
                            $this->console->colorize(
                                $this->params->paramModule, Color::GREEN
                            )
                        ]
                    );

                    return 1;
                }
            }
        } else {
            $configData['router'] = [
                'routes' => [],
            ];
        }

        // check for strict mode
        if ($this->params->paramStrict) {
            // create child routes
            $childRoutes = [];

            // loop through loaded controller actions
            foreach (
                $this->params->loadedActions[$this->params->paramModule] as
                $loadedController => $loadedActions
            ) {
                $controllerName = $this->filterCamelCaseToDash(
                    str_replace(
                        $this->params->paramModule . '\\', '', $loadedController
                    )
                );

                $actionList = array_keys($loadedActions);

                $childRoutes[$controllerName . '-action'] = [
                    'type'    => 'segment',
                    'options' => [
                        'route'       => '/' . $controllerName
                            . '[/:action[/:id]]',
                        'defaults'    => [
                            'controller' => $controllerName,
                        ],
                        'constraints' => [
                            'action' => '(' . implode('|', $actionList) . ')',
                            'id'     => '[0-9_-]*',
                        ],
                    ],
                ];
            }

        } else {
            // create child routes
            $childRoutes = [
                'controller-action' => [
                    'type'    => 'segment',
                    'options' => [
                        'route'       => '/:controller[/:action[/:id]]',
                        'constraints' => [
                            'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'         => '[0-9_-]*',
                        ],
                    ],
                ],
            ];
        }

        // set controller keys
        $controllerKeys = [];

        // merge controller keys
        foreach ($configData['controllers'] as $group) {
            $controllerKeys = array_merge(
                $controllerKeys,
                array_keys($group)
            );
        }

        // identify default controller
        if (count($controllerKeys) == 1) {
            $defaultController = reset($controllerKeys);
        } else {
            $indexController  = $this->params->paramModule . '\Index';
            $moduleController = $this->params->paramModule . '\\'
                . $this->params->paramModule;

            if (in_array($indexController, $controllerKeys)) {
                $defaultController = $indexController;
            } elseif (in_array($moduleController, $controllerKeys)) {
                $defaultController = $moduleController;
            } else {
                $defaultController = reset($controllerKeys);
            }
        }

        // clear leading namespace
        if (stripos($defaultController, $this->params->paramModule) === 0) {
            $defaultController = str_replace(
                $this->params->paramModule . '\\', '', $defaultController
            );
        }

        // create route
        $configData['router']['routes'][$routingKey] = [
            'type'          => 'Literal',
            'options'       => [
                'route'    => '/' . $this->filterCamelCaseToDash(
                        $this->params->paramModule
                    ),
                'defaults' => [
                    '__NAMESPACE__' => $this->params->paramModule,
                    'controller'    => $defaultController,
                    'action'        => 'index',
                ],
            ],
            'may_terminate' => true,
            'child_routes'  => $childRoutes,
        ];

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
}