<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link        https://github.com/ZFrapid/zf2rapid
 * @copyright   Copyright (c) 2014 - 2015 Ralf Eggert
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 */
use ZF\Console\Filter\Explode as ExplodeFilter;
use ZF2rapid\Filter\NormalizeList as NormalizeListFilter;
use ZF2rapid\Filter\NormalizeParam as NormalizeParamFilter;

return array(
    array(
        'name'                 => 'activate-module',
        'route'                => 'activate-module <module> [<path>]',
        'description'          => 'route_activate_module_description',
        'short_description'    => 'route_activate_module_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_activate_module_option_module',
            '<path>'   => 'route_activate_module_option_path',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Activate\ActivateModule',
    ),
    array(
        'name'                 => 'create-action',
        'route'                => 'create-action <module> <controller> <action> [<path>]',
        'description'          => 'route_create_action_description',
        'short_description'    => 'route_create_action_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_create_action_option_module',
            '<controller>' => 'route_create_action_option_controller',
            '<action>'     => 'route_create_action_option_action',
            '<path>'       => 'route_create_action_option_path',
        ),
        'defaults'             => array(
            'path'       => '.',
            'no-factory' => false,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
            'action'     => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateAction',
    ),
    array(
        'name'                 => 'create-controller',
        'route'                => 'create-controller <module> <controller> [<path>] [--no-factory]',
        'description'          => 'route_create_controller_description',
        'short_description'    => 'route_create_controller_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_create_controller_option_module',
            '<controller>' => 'route_create_controller_option_controller',
            '<path>'       => 'route_create_controller_option_path',
            '--no-factory' => 'route_create_controller_option_factory',
        ),
        'defaults'             => array(
            'path'       => '.',
            'no-factory' => false,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateController',
    ),
    array(
        'name'                 => 'create-controller-factory',
        'route'                => 'create-controller-factory <module> <controller> [<path>]',
        'description'          => 'route_create_controller_factory_description',
        'short_description'    => 'route_create_controller_factory_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_create_controller_factory_option_module',
            '<controller>' => 'route_create_controller_factory_option_controller',
            '<path>'       => 'route_create_controller_factory_option_path',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateControllerFactory',
    ),
    array(
        'name'                 => 'create-controller-plugin',
        'route'                => 'create-controller-plugin <module> <controllerPlugin> [<path>] [--no-factory]',
        'description'          => 'route_create_controller_plugin_description',
        'short_description'    => 'route_create_controller_plugin_short_description',
        'options_descriptions' => array(
            '<module>'           => 'route_create_controller_plugin_option_module',
            '<controllerPlugin>' => 'route_create_controller_plugin_option_plugin',
            '<path>'             => 'route_create_controller_plugin_option_path',
            '--no-factory'       => 'route_create_controller_plugin_option_factory',
        ),
        'defaults'             => array(
            'path'       => '.',
            'no-factory' => false,
        ),
        'filters'              => array(
            'module'           => new NormalizeParamFilter(),
            'controllerPlugin' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateControllerPlugin',
    ),
    array(
        'name'                 => 'create-controller-plugin-factory',
        'route'                => 'create-controller-plugin-factory <module> <controllerPlugin> [<path>]',
        'description'          => 'route_create_controller_plugin_factory_description',
        'short_description'    => 'route_create_controller_plugin_factory_short_description',
        'options_descriptions' => array(
            '<module>'           => 'route_create_controller_plugin_factory_option_module',
            '<controllerPlugin>' => 'route_create_controller_plugin_factory_option_plugin',
            '<path>'             => 'route_create_controller_plugin_factory_option_path',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module'           => new NormalizeParamFilter(),
            'controllerPlugin' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateControllerPluginFactory',
    ),
    array(
        'name'                 => 'create-filter',
        'route'                => 'create-filter <module> <filter> [<path>] [--no-factory]',
        'description'          => 'route_create_filter_description',
        'short_description'    => 'route_create_filter_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_create_filter_option_module',
            '<filter>'     => 'route_create_filter_option_filter',
            '<path>'       => 'route_create_filter_option_path',
            '--no-factory' => 'route_create_filter_option_factory',
        ),
        'defaults'             => array(
            'path'       => '.',
            'no-factory' => false,
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
            'filter' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateFilter',
    ),
    array(
        'name'                 => 'create-filter-factory',
        'route'                => 'create-filter-factory <module> <filter> [<path>]',
        'description'          => 'route_create_filter_factory_description',
        'short_description'    => 'route_create_filter_factory_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_create_filter_factory_option_module',
            '<filter>' => 'route_create_filter_factory_option_filter',
            '<path>'   => 'route_create_filter_factory_option_path',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
            'filter' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateFilterFactory',
    ),
    array(
        'name'                 => 'create-form',
        'route'                => 'create-form <module> <form> [<path>] [--no-factory]',
        'description'          => 'route_create_form_description',
        'short_description'    => 'route_create_form_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_create_form_option_module',
            '<form>'       => 'route_create_form_option_form',
            '<path>'       => 'route_create_form_option_path',
            '--no-factory' => 'route_create_form_option_factory',
        ),
        'defaults'             => array(
            'path'       => '.',
            'no-factory' => false,
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
            'form'   => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateForm',
    ),
    array(
        'name'                 => 'create-form-factory',
        'route'                => 'create-form-factory <module> <form> [<path>]',
        'description'          => 'route_create_form_factory_description',
        'short_description'    => 'route_create_form_factory_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_create_form_factory_option_module',
            '<form>'   => 'route_create_form_factory_option_form',
            '<path>'   => 'route_create_form_factory_option_path',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
            'form'   => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateFormFactory',
    ),
    array(
        'name'                 => 'create-hydrator',
        'route'                => 'create-hydrator <module> <hydrator> [<path>]  [--baseHydrator=] [--no-factory]',
        'description'          => 'route_create_hydrator_description',
        'short_description'    => 'route_create_hydrator_short_description',
        'options_descriptions' => array(
            '<module>'       => 'route_create_hydrator_option_module',
            '<hydrator>'     => 'route_create_hydrator_option_hydrator',
            '<path>'         => 'route_create_hydrator_option_path',
            '--baseHydrator' => 'route_create_hydrator_option_base_hydrator',
            '--no-factory'   => 'route_create_hydrator_option_factory',
        ),
        'defaults'             => array(
            'path'         => '.',
            'factory'      => false,
            'baseHydrator' => 'ClassMethods',
        ),
        'filters'              => array(
            'module'       => new NormalizeParamFilter(),
            'hydrator'     => new NormalizeParamFilter(),
            'baseHydrator' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateHydrator',
    ),
    array(
        'name'                 => 'create-hydrator-factory',
        'route'                => 'create-hydrator-factory <module> <hydrator> [<path>]',
        'description'          => 'route_create_hydrator_factory_description',
        'short_description'    => 'route_create_hydrator_factory_short_description',
        'options_descriptions' => array(
            '<module>'   => 'route_create_hydrator_factory_option_module',
            '<hydrator>' => 'route_create_hydrator_factory_option_hydrator',
            '<path>'     => 'route_create_hydrator_factory_option_path',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module'   => new NormalizeParamFilter(),
            'hydrator' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateHydratorFactory',
    ),
    array(
        'name'                 => 'create-input-filter',
        'route'                => 'create-input-filter <module> <inputFilter> [<path>] [--no-factory]',
        'description'          => 'route_create_input_filter_description',
        'short_description'    => 'route_create_input_filter_short_description',
        'options_descriptions' => array(
            '<module>'      => 'route_create_input_filter_option_module',
            '<inputFilter>' => 'route_create_input_filter_option_input_filter',
            '<path>'        => 'route_create_input_filter_option_path',
            '--no-factory'  => 'route_create_input_filter_option_factory',
        ),
        'defaults'             => array(
            'path'       => '.',
            'no-factory' => false,
        ),
        'filters'              => array(
            'module'      => new NormalizeParamFilter(),
            'inputFilter' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateInputFilter',
    ),
    array(
        'name'                 => 'create-input-filter-factory',
        'route'                => 'create-input-filter-factory <module> <inputFilter> [<path>]',
        'description'          => 'route_create_input_filter_factory_description',
        'short_description'    => 'route_create_input_filter_factory_short_description',
        'options_descriptions' => array(
            '<module>'      => 'route_create_input_filter_factory_option_module',
            '<inputFilter>' => 'route_create_input_filter_factory_option_input_filter',
            '<path>'        => 'route_create_input_filter_factory_option_path',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module'      => new NormalizeParamFilter(),
            'inputFilter' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateInputFilterFactory',
    ),
    array(
        'name'                 => 'create-module',
        'route'                => 'create-module <module> [<path>]',
        'description'          => 'route_create_module_description',
        'short_description'    => 'route_create_module_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_create_module_option_module',
            '<path>'   => 'route_create_module_option_path',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateModule',
    ),
    array(
        'name'                 => 'create-project',
        'route'                => 'create-project <path>',
        'description'          => 'route_create_project_description',
        'short_description'    => 'route_create_project_short_description',
        'options_descriptions' => array(
            '<path>' => 'route_create_project_option_path',
        ),
        'defaults'             => array(
            'path' => false,
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateProject',
    ),
    array(
        'name'                 => 'create-routing',
        'route'                => 'create-routing <module> [<path>] [--strict]',
        'description'          => 'route_create_routing_description',
        'short_description'    => 'route_create_routing_short_description',
        'options_descriptions' => array(
            '<module>'    => 'route_create_routing_option_module',
            '<path>'      => 'route_create_routing_option_path',
            '--strict' => 'route_create_routing_option_strict',
        ),
        'defaults'             => array(
            'path'   => '.',
            'strict' => false,
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateRouting',
    ),
    array(
        'name'                 => 'create-validator',
        'route'                => 'create-validator <module> <validator> [<path>] [--no-factory]',
        'description'          => 'route_create_validator_description',
        'short_description'    => 'route_create_validator_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_create_validator_option_module',
            '<validator>'  => 'route_create_validator_option_validator',
            '<path>'       => 'route_create_validator_option_path',
            '--no-factory' => 'route_create_validator_option_factory',
        ),
        'defaults'             => array(
            'path'       => '.',
            'no-factory' => false,
        ),
        'filters'              => array(
            'module'    => new NormalizeParamFilter(),
            'validator' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateValidator',
    ),
    array(
        'name'                 => 'create-validator-factory',
        'route'                => 'create-validator-factory <module> <validator> [<path>]',
        'description'          => 'route_create_validator_factory_description',
        'short_description'    => 'route_create_validator_factory_short_description',
        'options_descriptions' => array(
            '<module>'    => 'route_create_validator_factory_option_module',
            '<validator>' => 'route_create_validator_factory_option_validator',
            '<path>'      => 'route_create_validator_factory_option_path',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module'    => new NormalizeParamFilter(),
            'validator' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateValidatorFactory',
    ),
    array(
        'name'                 => 'create-view-helper',
        'route'                => 'create-view-helper <module> <viewHelper> [<path>] [--no-factory]',
        'description'          => 'route_create_view_helper_description',
        'short_description'    => 'route_create_view_helper_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_create_view_helper_option_module',
            '<viewHelper>' => 'route_create_view_helper_option_view_helper',
            '<path>'       => 'route_create_view_helper_option_path',
            '--no-factory' => 'route_create_view_helper_option_factory',
        ),
        'defaults'             => array(
            'path'       => '.',
            'no-factory' => false,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'viewHelper' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateViewHelper',
    ),
    array(
        'name'                 => 'create-view-helper-factory',
        'route'                => 'create-view-helper-factory <module> <viewHelper> [<path>]',
        'description'          => 'route_create_view_helper_factory_description',
        'short_description'    => 'route_create_view_helper_factory_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_create_view_helper_factory_option_module',
            '<viewHelper>' => 'route_create_view_helper_factory_option_view_helper',
            '<path>'       => 'route_create_view_helper_factory_option_path',
        ),
        'defaults'             => array(
            'path'    => '.',
            'factory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'viewHelper' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Create\CreateViewHelperFactory',
    ),
    array(
        'name'                 => 'deactivate-module',
        'route'                => 'deactivate-module <module> [<path>]',
        'description'          => 'route_deactivate_module_description',
        'short_description'    => 'route_deactivate_module_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_deactivate_module_option_module',
            '<path>'   => 'route_deactivate_module_option_path',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Deactivate\DeactivateModule',
    ),
    array(
        'name'                 => 'delete-action',
        'route'                => 'delete-action <module> <controller> <action> [<path>]',
        'description'          => 'route_delete_action_description',
        'short_description'    => 'route_delete_action_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_delete_action_option_module',
            '<controller>' => 'route_delete_action_option_controller',
            '<action>'     => 'route_delete_action_option_action',
            '<path>'       => 'route_delete_action_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
            'action'     => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteAction',
    ),
    array(
        'name'                 => 'delete-controller',
        'route'                => 'delete-controller <module> <controller> [<path>]',
        'description'          => 'route_delete_controller_description',
        'short_description'    => 'route_delete_controller_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_delete_controller_option_module',
            '<controller>' => 'route_delete_controller_option_controller',
            '<path>'       => 'route_delete_controller_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteController',
    ),
    array(
        'name'                 => 'delete-controller-factory',
        'route'                => 'delete-controller-factory <module> <controller> [<path>]',
        'description'          => 'route_delete_controller_factory_description',
        'short_description'    => 'route_delete_controller_factory_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_delete_controller_factory_option_module',
            '<controller>' => 'route_delete_controller_factory_option_controller',
            '<path>'       => 'route_delete_controller_factory_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'controller' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteControllerFactory',
    ),
    array(
        'name'                 => 'delete-controller-plugin',
        'route'                => 'delete-controller-plugin <module> <controllerPlugin> [<path>]',
        'description'          => 'route_delete_controller_plugin_description',
        'short_description'    => 'route_delete_controller_plugin_short_description',
        'options_descriptions' => array(
            '<module>'           => 'route_delete_controller_plugin_option_module',
            '<controllerPlugin>' => 'route_delete_controller_plugin_option_plugin',
            '<path>'             => 'route_delete_controller_plugin_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'           => new NormalizeParamFilter(),
            'controllerPlugin' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteControllerPlugin',
    ),
    array(
        'name'                 => 'delete-controller-plugin-factory',
        'route'                => 'delete-controller-plugin-factory <module> <controllerPlugin> [<path>]',
        'description'          => 'route_delete_controller_plugin_factory_description',
        'short_description'    => 'route_delete_controller_plugin_factory_short_description',
        'options_descriptions' => array(
            '<module>'           => 'route_delete_controller_plugin_factory_option_module',
            '<controllerPlugin>' => 'route_delete_controller_plugin_factory_option_plugin',
            '<path>'             => 'route_delete_controller_plugin_factory_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'           => new NormalizeParamFilter(),
            'controllerPlugin' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteControllerPluginFactory',
    ),
    array(
        'name'                 => 'delete-filter',
        'route'                => 'delete-filter <module> <filter> [<path>]',
        'description'          => 'route_delete_filter_description',
        'short_description'    => 'route_delete_filter_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_delete_filter_option_module',
            '<filter>' => 'route_delete_filter_option_filter',
            '<path>'   => 'route_delete_filter_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
            'filter' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteFilter',
    ),
    array(
        'name'                 => 'delete-filter-factory',
        'route'                => 'delete-filter-factory <module> <filter> [<path>]',
        'description'          => 'route_delete_filter_factory_description',
        'short_description'    => 'route_delete_filter_factory_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_delete_filter_factory_option_module',
            '<filter>' => 'route_delete_filter_factory_option_filter',
            '<path>'   => 'route_delete_filter_factory_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
            'filter' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteFilterFactory',
    ),
    array(
        'name'                 => 'delete-form',
        'route'                => 'delete-form <module> <form> [<path>]',
        'description'          => 'route_delete_form_description',
        'short_description'    => 'route_delete_form_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_delete_form_option_module',
            '<form>'   => 'route_delete_form_option_form',
            '<path>'   => 'route_delete_form_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
            'form'   => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteForm',
    ),
    array(
        'name'                 => 'delete-form-factory',
        'route'                => 'delete-form-factory <module> <form> [<path>]',
        'description'          => 'route_delete_form_factory_description',
        'short_description'    => 'route_delete_form_factory_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_delete_form_factory_option_module',
            '<form>'   => 'route_delete_form_factory_option_form',
            '<path>'   => 'route_delete_form_factory_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
            'form'   => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteFormFactory',
    ),
    array(
        'name'                 => 'delete-hydrator',
        'route'                => 'delete-hydrator <module> <hydrator> [<path>]',
        'description'          => 'route_delete_hydrator_description',
        'short_description'    => 'route_delete_hydrator_short_description',
        'options_descriptions' => array(
            '<module>'   => 'route_delete_hydrator_option_module',
            '<hydrator>' => 'route_delete_hydrator_option_hydrator',
            '<path>'     => 'route_delete_hydrator_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'   => new NormalizeParamFilter(),
            'hydrator' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteHydrator',
    ),
    array(
        'name'                 => 'delete-hydrator-factory',
        'route'                => 'delete-hydrator-factory <module> <hydrator> [<path>]',
        'description'          => 'route_delete_hydrator_factory_description',
        'short_description'    => 'route_delete_hydrator_factory_short_description',
        'options_descriptions' => array(
            '<module>'   => 'route_delete_hydrator_factory_option_module',
            '<hydrator>' => 'route_delete_hydrator_factory_option_hydrator',
            '<path>'     => 'route_delete_hydrator_factory_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'   => new NormalizeParamFilter(),
            'hydrator' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteHydratorFactory',
    ),
    array(
        'name'                 => 'delete-input-filter',
        'route'                => 'delete-input-filter <module> <inputFilter> [<path>]',
        'description'          => 'route_delete_input_filter_description',
        'short_description'    => 'route_delete_input_filter_short_description',
        'options_descriptions' => array(
            '<module>'      => 'route_delete_input_filter_option_module',
            '<inputFilter>' => 'route_delete_input_filter_option_input_filter',
            '<path>'        => 'route_delete_input_filter_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'      => new NormalizeParamFilter(),
            'inputFilter' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteInputFilter',
    ),
    array(
        'name'                 => 'delete-input-filter-factory',
        'route'                => 'delete-input-filter-factory <module> <inputFilter> [<path>]',
        'description'          => 'route_delete_input_filter_factory_description',
        'short_description'    => 'route_delete_input_filter_factory_short_description',
        'options_descriptions' => array(
            '<module>'      => 'route_delete_input_filter_factory_option_module',
            '<inputFilter>' => 'route_delete_input_filter_factory_option_input_filter',
            '<path>'        => 'route_delete_input_filter_factory_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'      => new NormalizeParamFilter(),
            'inputFilter' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteInputFilterFactory',
    ),
    array(
        'name'                 => 'delete-module',
        'route'                => 'delete-module <module> [<path>]',
        'description'          => 'route_delete_module_description',
        'short_description'    => 'route_delete_module_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_delete_module_option_module',
            '<path>'   => 'route_delete_module_option_path',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteModule',
    ),
    array(
        'name'                 => 'delete-validator',
        'route'                => 'delete-validator <module> <validator> [<path>]',
        'description'          => 'route_delete_validator_description',
        'short_description'    => 'route_delete_validator_short_description',
        'options_descriptions' => array(
            '<module>'    => 'route_delete_validator_option_module',
            '<validator>' => 'route_delete_validator_option_validator',
            '<path>'      => 'route_delete_validator_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'    => new NormalizeParamFilter(),
            'validator' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteValidator',
    ),
    array(
        'name'                 => 'delete-validator-factory',
        'route'                => 'delete-validator-factory <module> <validator> [<path>]',
        'description'          => 'route_delete_validator_factory_description',
        'short_description'    => 'route_delete_validator_factory_short_description',
        'options_descriptions' => array(
            '<module>'    => 'route_delete_validator_factory_option_module',
            '<validator>' => 'route_delete_validator_factory_option_validator',
            '<path>'      => 'route_delete_validator_factory_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'    => new NormalizeParamFilter(),
            'validator' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteValidatorFactory',
    ),
    array(
        'name'                 => 'delete-view-helper',
        'route'                => 'delete-view-helper <module> <viewHelper> [<path>]',
        'description'          => 'route_delete_view_helper_description',
        'short_description'    => 'route_delete_view_helper_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_delete_view_helper_option_module',
            '<viewHelper>' => 'route_delete_view_helper_option_view_helper',
            '<path>'       => 'route_delete_view_helper_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'viewHelper' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteViewHelper',
    ),
    array(
        'name'                 => 'delete-view-helper-factory',
        'route'                => 'delete-view-helper-factory <module> <viewHelper> [<path>]',
        'description'          => 'route_delete_view_helper_factory_description',
        'short_description'    => 'route_delete_view_helper_factory_short_description',
        'options_descriptions' => array(
            '<module>'     => 'route_delete_view_helper_factory_option_module',
            '<viewHelper>' => 'route_delete_view_helper_factory_option_view_helper',
            '<path>'       => 'route_delete_view_helper_factory_option_path',
        ),
        'defaults'             => array(
            'path'          => '.',
            'removeFactory' => true,
        ),
        'filters'              => array(
            'module'     => new NormalizeParamFilter(),
            'viewHelper' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Delete\DeleteViewHelperFactory',
    ),
    array(
        'name'                 => 'generate-classmap',
        'route'                => 'generate-classmap <module> [<path>]',
        'description'          => 'route_generate_classmap_description',
        'short_description'    => 'route_generate_classmap_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_generate_classmap_option_module',
            '<path>'   => 'route_generate_classmap_option_path',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Generate\GenerateClassMap',
    ),
    array(
        'name'                 => 'generate-templatemap',
        'route'                => 'generate-templatemap <module> [<path>]',
        'description'          => 'route_generate_templatemap_description',
        'short_description'    => 'route_generate_templatemap_short_description',
        'options_descriptions' => array(
            '<module>' => 'route_generate_templatemap_option_module',
            '<path>'   => 'route_generate_templatemap_option_path',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'filters'              => array(
            'module' => new NormalizeParamFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Generate\GenerateTemplateMap',
    ),
    array(
        'name'                 => 'show-actions',
        'route'                => 'show-actions [<path>] [--modules=] [--controllers=]',
        'description'          => 'route_show_actions_description',
        'short_description'    => 'route_show_actions_short_description',
        'options_descriptions' => array(
            '<path>'        => 'route_show_actions_option_path',
            '--modules'     => 'route_show_actions_option_modules',
            '--controllers' => 'route_show_actions_option_controllers',
        ),
        'defaults'             => array(
            'path'        => '.',
            'modules'     => array(),
            'controllers' => array(),
        ),
        'filters'              => array(
            'modules'     => new NormalizeListFilter(),
            'controllers' => new ExplodeFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowActions',
    ),
    array(
        'name'                 => 'show-controllers',
        'route'                => 'show-controllers [<path>] [--modules=]',
        'description'          => 'route_show_controllers_description',
        'short_description'    => 'route_show_controllers_short_description',
        'options_descriptions' => array(
            '<path>'    => 'route_show_controllers_option_path',
            '--modules' => 'route_show_controllers_option_modules',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowControllers',
    ),
    array(
        'name'                 => 'show-controller-plugins',
        'route'                => 'show-controller-plugins [<path>] [--modules=]',
        'description'          => 'route_show_controller_plugins_description',
        'short_description'    => 'route_show_controller_plugins_short_description',
        'options_descriptions' => array(
            '<path>'    => 'route_show_controller_plugins_option_path',
            '--modules' => 'route_show_controller_plugins_option_modules',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowControllerPlugins',
    ),
    array(
        'name'                 => 'show-filters',
        'route'                => 'show-filters [<path>] [--modules=]',
        'description'          => 'route_show_filters_description',
        'short_description'    => 'route_show_filters_short_description',
        'options_descriptions' => array(
            '<path>'    => 'route_show_filters_option_path',
            '--modules' => 'route_show_filters_option_modules',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowFilters',
    ),
    array(
        'name'                 => 'show-forms',
        'route'                => 'show-forms [<path>] [--modules=]',
        'description'          => 'route_show_forms_description',
        'short_description'    => 'route_show_forms_short_description',
        'options_descriptions' => array(
            '<path>'    => 'route_show_forms_option_path',
            '--modules' => 'route_show_forms_option_modules',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowForms',
    ),
    array(
        'name'                 => 'show-hydrators',
        'route'                => 'show-hydrators [<path>] [--modules=]',
        'description'          => 'route_show_hydrators_description',
        'short_description'    => 'route_show_hydrators_short_description',
        'options_descriptions' => array(
            '<path>'    => 'route_show_hydrators_option_path',
            '--modules' => 'route_show_hydrators_option_modules',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowHydrators',
    ),
    array(
        'name'                 => 'show-input-filters',
        'route'                => 'show-input-filters [<path>] [--modules=]',
        'description'          => 'route_show_input_filters_description',
        'short_description'    => 'route_show_input_filters_short_description',
        'options_descriptions' => array(
            '<path>'    => 'route_show_input_filters_option_path',
            '--modules' => 'route_show_input_filters_option_modules',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowInputFilters',
    ),
    array(
        'name'                 => 'show-modules',
        'route'                => 'show-modules [<path>]',
        'description'          => 'route_show_modules_description',
        'short_description'    => 'route_show_modules_short_description',
        'options_descriptions' => array(
            '<path>' => 'route_show_modules_option_path',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowModules',
    ),
    array(
        'name'                 => 'show-validators',
        'route'                => 'show-validators [<path>] [--modules=]',
        'description'          => 'route_show_validators_description',
        'short_description'    => 'route_show_validators_short_description',
        'options_descriptions' => array(
            '<path>'    => 'route_show_validators_option_path',
            '--modules' => 'route_show_validators_option_modules',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowValidators',
    ),
    array(
        'name'                 => 'show-version',
        'route'                => 'show-version [<path>]',
        'description'          => 'route_show_version_description',
        'short_description'    => 'route_show_version_short_description',
        'options_descriptions' => array(
            '<path>' => 'route_show_version_option_path',
        ),
        'defaults'             => array(
            'path' => '.',
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowVersion',
    ),
    array(
        'name'                 => 'show-view-helpers',
        'route'                => 'show-view-helpers [<path>] [--modules=]',
        'description'          => 'route_show_view_helpers_description',
        'short_description'    => 'route_show_view_helpers_short_description',
        'options_descriptions' => array(
            '<path>'    => 'route_show_view_helpers_option_path',
            '--modules' => 'route_show_view_helpers_option_modules',
        ),
        'defaults'             => array(
            'path'    => '.',
            'modules' => array(),
        ),
        'filters'              => array(
            'modules' => new NormalizeListFilter(),
        ),
        'handler'              => 'ZF2rapid\Command\Show\ShowViewHelpers',
    ),
    array(
        'name'                 => 'tool-config',
        'route'                => 'tool-config [<path>] [--configKey=] [--configValue=]',
        'description'          => 'route_tool_config_description',
        'short_description'    => 'route_tool_config_short_description',
        'options_descriptions' => array(
            '<path>'           => 'route_tool_config_option_path',
            '[--configKey=]'   => 'route_tool_config_option_config_key',
            '[--configValue=]' => 'route_tool_config_option_config_value',
        ),
        'defaults'             => array(
            'path'        => '.',
            'configKey'   => false,
            'configValue' => false,
        ),
        'handler'              => 'ZF2rapid\Command\Tool\ToolConfiguration',
    ),
    array(
        'name'              => 'tool-version',
        'route'             => 'tool-version',
        'description'       => 'route_tool_version_description',
        'short_description' => 'route_tool_version_short_description',
        'handler'           => 'ZF2rapid\Command\Tool\ToolVersion',
    ),
);
