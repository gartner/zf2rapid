# ZF2rapid Command-Guide

In this Command-Guide all ZF2rapid commands and their arguments ar explained.

 * [Help](#help)
 * [Projects](#projects)
 * [Project configuration](#project-configuration)
 * [Modules](#modules)
 * [Controllers](#controllers)
 * [Actions](#actions)
 * [Routing](#routing)
 * [Maps](#maps)
 * [Controller plugins](#controller-plugins)
 * [View helpers](#view-helpers)
 * [Filters](#filters)
 * [Validators](#validators)
 * [Input filters](#input-filters)
 * [Forms](#forms)
 * [Hydrators](#hydrators)

## Help

For each command you can display the command help with all supported arguments.

    $ zf2rapid help create-module 

## Projects

When creating a new project you need to specify the `--projectPath=` to create the 
project in. You will be asked which Skeleton Application you want to install. 
After project creation you should switch to the new project path.

    $ zf2rapid create-project --projectPath=

## Project configuration

You can list the configuration for the current project. Optionally, you can 
specify the `--projectPath=` of the Zend Framework 2 project to show the configuration
for.

    $ zf2rapid tool-config [--projectPath=]

You can display the value for a configuration single configuration key, for 
example the namespace to be used for hydrators. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project.

    $ zf2rapid tool-config  [--projectPath=] --configKey=namespaceHydrator

You can change the value for a configuration single configuration key, for 
example the namespace to be used for hydrators (Please note that you need to 
use the double backslash to define a namespace with at least two levels). 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project.

    $ zf2rapid tool-config  [--projectPath=] --configKey=namespaceHydrator --configValue=Model\\Hydrator

The following configuration keys can be changed for your current project:

| Option                    | Description                                                 | 
| ------------------------- | ----------------------------------------------------------- | 
| configFileFormat          | format of the configuration files (not supported yet)       | 
| flagAddDocBlocks          | whether to automatically create doc blocks or not           | 
| fileDocBlockText          | text to be used in a file doc block                         | 
| fileDocBlockCopyright     | copyright to be used in a file doc block                    | 
| fileDocBlockLicense       | license to be used in a file doc block                      | 
| namespaceController       | namespace for all controller classes within a module        | 
| namespaceControllerPlugin | namespace for all controller plugin classes within a module | 
| namespaceViewHelper       | namespace for all view helper classes within a module       | 
| namespaceFilter           | namespace for all filter classes within a module            | 
| namespaceValidator        | namespace for all validator classes within a module         | 
| namespaceInputFilter      | namespace for all input filter classes within a module      | 
| namespaceForm             | namespace for all form classes within a module              | 
| namespaceHydrator         | namespace for all hydrator classes within a module          |

## Modules

When creating a new module you need to specify the `<module>`. Optionally, you 
can specify the `--projectPath=` of the Zend Framework 2 project to create the new
module in. You will be asked in which application configuration file the new 
module should be activated. If you want to prevent the activation of the module, 
you can use the optional `--no-activation` flag. 

    $ zf2rapid create-module <module> [--projectPath=] [--modulePath=] [--no-activation]

You can activate a module manually for any application configuration file. You
need to specify the `<module>` and optionally the `--projectPath=` of the Zend 
Framework 2 project.
 
    $ zf2rapid activate-module <module> [--projectPath=] [--modulePath=]

You can deactivate a module manually in any application configuration file. You
need to specify the `<module>` and optionally the `--projectPath=` of the Zend 
Framework 2 project.
 
    $ zf2rapid deactivate-module <module> [--projectPath=] [--modulePath=]

You can delete a module `<module>`. Optionally, you can specify the `--projectPath=` of 
the Zend Framework 2 project to delete the module from. If you want to prevent 
the deactivation of the module, you can use the optional `--no-deactivation` flag. 
 
    $ zf2rapid delete-module <module> [--projectPath=] [--modulePath=] [--no-deactivation]

You can display all modules of the current Zend Framework 2 project. Optionally, 
you can specify the `--projectPath=` of the Zend Framework 2 project to display the 
modules from.

    $ zf2rapid show-modules [--projectPath=]

## Controllers

When creating a new controller you need to specify the `<module>` and the 
`<controller>`. Optionally, you can specify the `--projectPath=` of the Zend Framework 
2 project to create the new controller in. Optionally, you can disable the 
creation of a factory for the new controller.

    $ zf2rapid create-controller <module> <controller> [--projectPath=] [--modulePath=] [--no-factory]

You can create a factory for an existing controller by specifying the `<module>` 
and the `<controller>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project.

    $ zf2rapid create-controller-factory <module> <controller> [--projectPath=] [--modulePath=]

You can delete an existing factory for a controller by specifying the `<module>` 
and the `<controller>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project. The controller for this factory will not be deleted.

    $ zf2rapid delete-controller-factory <module> <controller> [--projectPath=] [--modulePath=]

You can delete a controller by specifying the `<module>` and the `<controller>`. 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project. Any 
existing factory for this controller will also be deleted.  

    $ zf2rapid delete-controller <module> <controller> [--projectPath=] [--modulePath=]

You can display all controllers of the current Zend Framework 2 project. 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project to 
display the controllers from. You can also pass a comma-separated list of 
modules to show the controllers for.

    $ zf2rapid show-controllers [--projectPath=] [--modules=]

## Actions

When creating a new action you need to specify the `<module>`, the 
`<controller>` and the `<action>`. Optionally, you can specify the `--projectPath=` of 
the Zend Framework 2 project to create the new controller action in. The view
script for this new action will also be created.

    $ zf2rapid create-action <module> <controller> <action> [--projectPath=] [--modulePath=]

You can delete a action by specifying the `<module>`, the `<controller>` and 
the `<action>`. Optionally, you can specify the `--projectPath=` of the Zend Framework 
2 project. Any existing view script for this action will also be deleted.  

    $ zf2rapid delete-action <module> <controller> <action> [--projectPath=] [--modulePath=]

You can display all actions of the current Zend Framework 2 project. 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project to 
display the actions from. You can also pass a comma-separated list of 
modules and controllers to show the actions for.

    $ zf2rapid show-actions [--projectPath=] [--modules=] [--controllers=]

## Routing

You can create the routing for a module `<module>`. Optionally, you can specify 
the `--projectPath=` of the Zend Framework 2 project to create the module routing in. 
In the strict mode the routing only allows existing controllers and actions for 
the routing (disabled by default). 
 
    $ zf2rapid create-routing <module> [--projectPath=] [--modulePath=] [--strict]

## Maps

You can create and update a class map for a module `<module>`. Optionally, you 
can specify the `--projectPath=` of the Zend Framework 2 project to create the class 
map in. 

    $ zf2rapid generate-classmap <module> [--projectPath=] [--modulePath=]

You can create and update a template map for a module `<module>`. Optionally, 
you can specify the `--projectPath=` of the Zend Framework 2 project to create the 
template map in. 

    $ zf2rapid generate-templatemap <module> [--projectPath=] [--modulePath=]

## Controller plugins

When creating a new controller plugin you need to specify the `<module>` and the 
`<controllerPlugin>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project to create the new controller plugin in. Optionally, you can 
disable the creation of a factory for the new controller plugin.

    $ zf2rapid create-controller-plugin <module> <controllerPlugin> [--projectPath=] [--modulePath=] [--no-factory]

You can create a factory for an existing controller plugin by specifying 
the `<module>` and the `<controllerPlugin>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project.

    $ zf2rapid create-controller-plugin-factory <module> <controllerPlugin> [--projectPath=] [--modulePath=]

You can delete an existing factory for a controller plugin by specifying the 
`<module>` and the `<controllerPlugin>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project. The controller plugin for this 
factory will not be deleted.

    $ zf2rapid delete-controller-plugin-factory <module> <controllerPlugin> [--projectPath=] [--modulePath=]

You can delete a controller plugin by specifying the `<module>` and the 
`<controllerPlugin>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project. Any existing factory for this controller plugin will also 
be deleted.  

    $ zf2rapid delete-controller-plugin <module> <controllerPlugin> [--projectPath=] [--modulePath=]

You can display all controller plugins of the current Zend Framework 2 project. 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project to 
display the controller plugins from. You can also pass a comma-separated list of 
modules to show the controller plugins for.

    $ zf2rapid show-controller-plugins [--projectPath=] [--modules=]

## View helpers

When creating a new view helper you need to specify the `<module>` and the 
`<viewHelper>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project to create the new view helper in. Optionally, you can 
disable the creation of a factory for the new view helper.

    $ zf2rapid create-view-helper <module> <viewHelper> [--projectPath=] [--modulePath=] [--no-factory]

You can create a factory for an existing view helper by specifying 
the `<module>` and the `<viewHelper>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project.

    $ zf2rapid create-view-helper-factory <module> <viewHelper> [--projectPath=] [--modulePath=]

You can delete an existing factory for a view helper by specifying the 
`<module>` and the `<viewHelper>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project. The view helper for this 
factory will not be deleted.

    $ zf2rapid delete-view-helper-factory <module> <viewHelper> [--projectPath=] [--modulePath=]

You can delete a view helper by specifying the `<module>` and the 
`<viewHelper>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project. Any existing factory for this view helper will also 
be deleted.  

    $ zf2rapid delete-view-helper <module> <viewHelper> [--projectPath=] [--modulePath=]

You can display all view helpers of the current Zend Framework 2 project. 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project to 
display the view helpers from. You can also pass a comma-separated list of 
modules to show the view helpers for.

    $ zf2rapid show-view-helpers [--projectPath=] [--modules=]

## Filters

When creating a new filter you need to specify the `<module>` and the 
`<filter>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project to create the new filter in. Optionally, you can disable the 
creation of a factory for the new filter.

    $ zf2rapid create-filter <module> <filter> [--projectPath=] [--modulePath=] [--no-factory]

You can create a factory for an existing filter by specifying 
the `<module>` and the `<filter>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project.

    $ zf2rapid create-filter-factory <module> <filter> [--projectPath=] [--modulePath=]

You can delete an existing factory for a filter by specifying the 
`<module>` and the `<filter>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project. The filter for this 
factory will not be deleted.

    $ zf2rapid delete-filter-factory <module> <filter> [--projectPath=] [--modulePath=]

You can delete a filter by specifying the `<module>` and the 
`<filter>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project. Any existing factory for this filter will also 
be deleted.  

    $ zf2rapid delete-filter <module> <filter> [--projectPath=] [--modulePath=]

You can display all filters of the current Zend Framework 2 project. 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project to 
display the filters from. You can also pass a comma-separated list of 
modules to show the filters for.

    $ zf2rapid show-filters [--projectPath=] [--modules=]

## Validators

When creating a new validator you need to specify the `<module>` and the 
`<validator>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project to create the new validator in. Optionally, you can disable 
the creation of a factory for the new validator.

    $ zf2rapid create-validator <module> <validator> [--projectPath=] [--modulePath=] [--no-factory]

You can create a factory for an existing validator by specifying 
the `<module>` and the `<validator>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project.

    $ zf2rapid create-validator-factory <module> <validator> [--projectPath=] [--modulePath=]

You can delete an existing factory for a validator by specifying the 
`<module>` and the `<validator>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project. The validator for this 
factory will not be deleted.

    $ zf2rapid delete-validator-factory <module> <validator> [--projectPath=] [--modulePath=]

You can delete a validator by specifying the `<module>` and the 
`<validator>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project. Any existing factory for this validator will also 
be deleted.  

    $ zf2rapid delete-validator <module> <validator> [--projectPath=] [--modulePath=]

You can display all validators of the current Zend Framework 2 project. 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project to 
display the validators from. You can also pass a comma-separated list of 
modules to show the validators for.

    $ zf2rapid show-validators [--projectPath=] [--modules=]

## Input Filters

When creating a new input filter you need to specify the `<module>` and the 
`<inputFilter>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project to create the new input filter in. Optionally, you can 
disable the creation of a factory for the new input filter.

    $ zf2rapid create-input-filter <module> <inputFilter> [--projectPath=] [--modulePath=] [--no-factory]

You can create a factory for an existing input filter by specifying 
the `<module>` and the `<inputFilter>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project.

    $ zf2rapid create-input-filter-factory <module> <inputFilter> [--projectPath=] [--modulePath=]

You can delete an existing factory for a input filter by specifying the 
`<module>` and the `<inputFilter>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project. The input filter for this 
factory will not be deleted.

    $ zf2rapid delete-input-filter-factory <module> <inputFilter> [--projectPath=] [--modulePath=]

You can delete a input filter by specifying the `<module>` and the 
`<inputFilter>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project. Any existing factory for this input filter will also 
be deleted.  

    $ zf2rapid delete-input-filter <module> <inputFilter> [--projectPath=] [--modulePath=]

You can display all input filters of the current Zend Framework 2 project. 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project to 
display the input filters from. You can also pass a comma-separated list of 
modules to show the input filters for.

    $ zf2rapid show-input-filters [--projectPath=] [--modules=]

## Forms

When creating a new form you need to specify the `<module>` and the 
`<form>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project to create the new form in. Optionally, you can disable the 
creation of a factory for the new form.

    $ zf2rapid create-form <module> <form> [--projectPath=] [--modulePath=] [--no-factory]

You can create a factory for an existing form by specifying 
the `<module>` and the `<form>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project.

    $ zf2rapid create-form-factory <module> <form> [--projectPath=] [--modulePath=]

You can delete an existing factory for a form by specifying the 
`<module>` and the `<form>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project. The form for this 
factory will not be deleted.

    $ zf2rapid delete-form-factory <module> <form> [--projectPath=] [--modulePath=]

You can delete a form by specifying the `<module>` and the 
`<form>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project. Any existing factory for this form will also 
be deleted.  

    $ zf2rapid delete-form <module> <form> [--projectPath=] [--modulePath=]

You can display all forms of the current Zend Framework 2 project. 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project to 
display the forms from. You can also pass a comma-separated list of 
modules to show the forms for.

    $ zf2rapid show-forms [--projectPath=] [--modules=]

## Hydrators

When creating a new hydrator you need to specify the `<module>` and the 
`<hydrator>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project to create the new hydrator in. Optionally, you can specify a
baseHydrator to extend which defaults to the ClassMethods hydrator. Optionally, 
you can disable the creation of a factory for the new hydrator.

    $ zf2rapid create-hydrator <module> <hydrator> [--projectPath=] [--modulePath=] [--baseHydrator=] [--no-factory]

You can create a factory for an existing hydrator by specifying 
the `<module>` and the `<hydrator>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project.

    $ zf2rapid create-hydrator-factory <module> <hydrator> [--projectPath=] [--modulePath=]

You can delete an existing factory for a hydrator by specifying the 
`<module>` and the `<hydrator>`. Optionally, you can specify the 
`--projectPath=` of the Zend Framework 2 project. The hydrator for this 
factory will not be deleted.

    $ zf2rapid delete-hydrator-factory <module> <hydrator> [--projectPath=] [--modulePath=]

You can delete a hydrator by specifying the `<module>` and the 
`<hydrator>`. Optionally, you can specify the `--projectPath=` of the Zend 
Framework 2 project. Any existing factory for this hydrator will also 
be deleted.  

    $ zf2rapid delete-hydrator <module> <hydrator> [--projectPath=] [--modulePath=]

You can display all hydrators of the current Zend Framework 2 project. 
Optionally, you can specify the `--projectPath=` of the Zend Framework 2 project to 
display the hydrators from. You can also pass a comma-separated list of 
modules to show the hydrators for.

    $ zf2rapid show-hydrators [--projectPath=] [--modules=]

