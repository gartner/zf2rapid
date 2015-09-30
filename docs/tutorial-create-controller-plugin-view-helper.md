# ZF2rapid tutorial

In this tutorial you will learn how to create an application step by step with
ZF2rapid.

 * [Create new project](tutorial-create-project.md)
 * [Create new module](tutorial-create-module.md)
 * [Create controllers and actions](tutorial-create-controllers-actions.md)
 * [Create routing and generate maps](tutorial-create-routing-maps.md)
 * **Create controller plugin and view helper**
 * [Create model classes](tutorial-crud-create-model.md)
 * [Create application](tutorial-crud-create-application.md)

## Create controller plugin

When you want to create a new controller plugin in a module you need to specify both
the names of the module and the controller plugin. Optionally, you can specify the 
project path. At default the controller plugin will be installed and configured 
without a factory.
 
    $ zf2rapid create-controller-plugin Shop CalcBasket

The following tasks are executed when creating a new controller plugin:

 * Check if module exists
 * Create controller plugin directory
 * Creating controller plugin class
 * Creating controller plugin factory
 * Updating controller plugin configuration
 
## Structure of new controller plugin

The generated structure of your module after creating a controller plugin should look 
like this:

    --- module
      +--- Application
      +--- Shop
         +--- config
         |  +--- module.config.php
         +--- src
         |  +--- Shop
         |     +--- Application
         |        +--- Controller
         |           +--- Plugin                        <---- new directory
         |           |    +--- CalcBasket.php           <---- new file
         |           |    +--- CalcBasketFactory.php    <---- new file
         |           +--- BasketController.php
         |           +--- BasketControllerFactory.php
         +--- view
         |  +--- shop
         |     +--- basket
         |        +--- index.phtml
         |        +--- show.phtml
         +--- autoload_classmap.php
         +--- Module.php
         +--- template_map.php
         
The `/module/Shop/src/Shop/Application/Controller/Plugin/CalcBasket.php` file 
contains the `CalcBasket` class with the `__invoke()` method which is called 
whenever the controller plugin is called. This method can simply be filled 
with your controller plugin logic. 

    <?php
    /**
     * ZF2rapid Tutorial
     *
     * @copyright (c) 2015 John Doe
     * @license All rights reserved
     */
    namespace Shop\Application\Controller\Plugin;
    
    use Zend\Mvc\Controller\Plugin\AbstractPlugin;
    
    /**
     * CalcBasket
     *
     * Provides the CalcBasket plugin for the Shop Module
     *
     * @package Shop\Application\Controller\Plugin
     */
    class CalcBasket extends AbstractPlugin
    {
        /**
         * Called when controller plugin is executed
         *
         * @return mixed
         */
        public function __invoke()
        {
            // add controller plugin code here
        }
    }
    
The created factory `CalcBasketFactory` will be placed in the same path as the
controller plugin and is readily configured for your needs. You only need to get the 
dependencies you want and pass them to your controller plugin.

    <?php
    /**
     * ZF2rapid Tutorial
     *
     * @copyright (c) 2015 John Doe
     * @license All rights reserved
     */
    namespace Shop\Application\Controller\Plugin;
    
    use Zend\ServiceManager\FactoryInterface;
    use Zend\ServiceManager\ServiceLocatorAwareInterface;
    use Zend\ServiceManager\ServiceLocatorInterface;
    
    /**
     * CalcBasketFactory
     *
     * Creates an instance of CalcBasket
     *
     * @package Shop\Application\Controller\Plugin
     */
    class CalcBasketFactory implements FactoryInterface
    {
        /**
         * Create service
         *
         * @param ServiceLocatorInterface $controllerPluginManager
         * @return CalcBasket
         */
        public function createService(ServiceLocatorInterface $controllerPluginManager)
        {
            /** @var ServiceLocatorAwareInterface $controllerPluginManager */
            $serviceLocator = $controllerPluginManager->getServiceLocator();
    
            $instance = new CalcBasket();
    
            return $instance;
        }
    }

If you want to create a controller plugin without creating a factory you can 
simply run this command: 

    $ zf2rapid create-controller-plugin Shop CalcBasket -no-factory

## Create a factory for a controller plugin

If you want to create a factory for an existing controller plugin you just need to 
specify the name of module and controller plugin and optionally the project path:

    $ zf2rapid create-controller-plugin-factory Shop CalcBasket

The following tasks are executed when creating a new controller plugin:

 * Check if module exists
 * Check if controller plugin exists
 * Creating controller plugin factory class
 * Updating controller plugin configuration

## Deleting controller plugins and controller plugin factories

If you want to delete a controller plugin you need to specify the name of the module, 
the name of the controller plugin and optionally you can specify the project path. If 
a factory exists for this controller plugin, this factory will also be deleted. 
Additionally, the configuration for this controller plugin will also be deleted. 

    $ zf2rapid delete-controller-plugin Shop CalcBasket

The following tasks are executed when deleting an existing controller plugin:

 * Check if module exists
 * Check if controller plugin exists
 * Delete controller plugin class
 * Delete controller plugin factory class (if any exists)
 * Remove controller plugin configuration

If you only want to delete the factory of a controller plugin you need to specify the 
name of the module, the name of the controller plugin and optionally you can specify 
the project path. The controller plugin factory will be deleted and the controller 
plugin configuration will be updated.

    $ zf2rapid delete-controller-plugin-factory Shop CalcBasket

The following tasks are executed when deleting an existing controller plugin factory:

 * Check if module exists
 * Check if controller plugin exists
 * Delete controller plugin factory class (if any exists)
 * Update controller plugin configuration (from `factories` to `invokables`)

## List controller plugins

If you want to get an overview about all the controller plugins in your current project 
you list them with a simple command. Naturally, you can optionally specify the 
project path like in almost any other command.
 
    $ zf2rapid show-controller-plugins
    
If you want to display the controller plugins of some specific module(s) you can add the
names of these modules to the option `--modules=` and separate them with commas.

    $ zf2rapid show-controller-plugins --modules=Shop,Application

## Create view helper

When you want to create a new view helper in a module you need to specify both
the names of the module and the view helper. Optionally, you can specify the 
project path. At default the view helper will be installed and configured 
without a factory.
 
    $ zf2rapid create-view-helper Shop ShowBasket

The following tasks are executed when creating a new view helper:

 * Check if module exists
 * Create view helper directory
 * Creating view helper class
 * Creating view helper factory class
 * Updating view helper configuration
 
## Structure of new view helper

The generated structure of your module after creating a view helper should look 
like this:

    --- module
      +--- Application
      +--- Shop
         +--- config
         |  +--- module.config.php
         +--- src
         |  +--- Shop
         |     +--- Application
         |        +--- Controller
         |           +--- Plugin
         |           |    +--- CalcBasket.php
         |           +--- BasketController.php
         |           +--- BasketControllerFactory.php
         |        +--- View                             <---- new directory
         |           +--- Helper                        <---- new directory
         |           |    +--- ShowBasket.php           <---- new file
         |           |    +--- ShowBasketFactory.php    <---- new file
         +--- view
         |  +--- shop
         |     +--- basket
         |        +--- index.phtml
         |        +--- show.phtml
         +--- autoload_classmap.php
         +--- Module.php
         +--- template_map.php
         
The `/module/Shop/src/Shop/Application/View/Helper/ShowBasket.php` file 
contains the `ShowBasket` class with the `__invoke()` method which is called whenever 
the view helper is called. This method can simply be filled 
with your view helper logic. 

    <?php
    /**
     * ZF2rapid Tutorial
     *
     * @copyright (c) 2015 John Doe
     * @license All rights reserved
     */
    namespace Shop\View\Helper;
    
    use Zend\View\Helper\AbstractHelper;
    
    /**
     * ShowBasket
     *
     * Provides the ShowBasket view helper for the Shop Module
     *
     * @package Shop\View\Helper
     */
    class ShowBasket extends AbstractHelper
    {
        /**
         * Called when view helper is executed
         *
         * @return string
         */
        public function __invoke()
        {
            // add view helper code here
            $output = '';
    
            return $output;
        }
    }

The factory `ShowBasketFactory` will be placed in the same path as the
view helper and is readily configured for your needs. You only need to get the 
dependencies you want and pass them to your view helper.

    <?php
    /**
     * ZF2rapid Tutorial
     *
     * @copyright (c) 2015 John Doe
     * @license All rights reserved
     */
    namespace Shop\View\Helper;
    
    use Zend\ServiceManager\FactoryInterface;
    use Zend\ServiceManager\ServiceLocatorAwareInterface;
    use Zend\ServiceManager\ServiceLocatorInterface;
    
    /**
     * ShowBasketFactory
     *
     * Creates an instance of ShowBasket
     *
     * @package Shop\View\Helper
     */
    class ShowBasketFactory implements FactoryInterface
    {
        /**
         * Create service
         *
         * @param ServiceLocatorInterface $viewHelperManager
         * @return ShowBasket
         */
        public function createService(ServiceLocatorInterface $viewHelperManager)
        {
            /** @var ServiceLocatorAwareInterface $viewHelperManager */
            $serviceLocator = $viewHelperManager->getServiceLocator();
    
            $instance = new ShowBasket();
    
            return $instance;
        }
    }

If you want to create a view helper without creating a factory you can simply 
run this command: 

    $ zf2rapid create-view-helper Shop ShowBasket --no-factory

## Create a factory for a view helper

If you want to create a factory for an existing view helper you just need to 
specify the name of module and view helper and optionally the project path:

    $ zf2rapid create-view-helper-factory Shop ShowBasket

The following tasks are executed when creating a new view helper:

 * Check if module exists
 * Check if view helper exists
 * Creating view helper factory class
 * Updating view helper configuration


## Deleting view helpers and view helper factories

If you want to delete a view helper you need to specify the name of the module, 
the name of the view helper and optionally you can specify the project path. If 
a factory exists for this view helper, this factory will also be deleted. 
Additionally, the configuration for this view helper will also be deleted. 

    $ zf2rapid delete-view-helper Shop ShowBasket

The following tasks are executed when deleting an existing view helper:

 * Check if module exists
 * Check if view helper exists
 * Delete view helper class
 * Delete view helper factory class (if any exists)
 * Remove view helper configuration

If you only want to delete the factory of a view helper you need to specify the 
name of the module, the name of the view helper and optionally you can specify 
the project path. The view helper factory will be deleted and the view 
helper configuration will be updated.

    $ zf2rapid delete-view-helper-factory Shop ShowBasket

The following tasks are executed when deleting an existing view helper factory:

 * Check if module exists
 * Check if view helper exists
 * Delete view helper factory class (if any exists)
 * Update view helper configuration (from `factories` to `invokables`)

## List view helpers

If you want to get an overview about all the view helpers in your current project 
you list them with a simple command. Naturally, you can optionally specify the 
project path like in almost any other command.
 
    $ zf2rapid show-view-helpers
    
If you want to display the view helpers of some specific module(s) you can add the
names of these modules to the option `--modules=` and separate them with commas.

    $ zf2rapid show-view-helpers --modules=Shop,Application

[Continue to create model classes](tutorial-crud-create-model.md)
