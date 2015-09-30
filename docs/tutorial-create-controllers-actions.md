# ZF2rapid tutorial

In this tutorial you will learn how to create an application step by step with
ZF2rapid.

 * [Create new project](tutorial-create-project.md)
 * [Create new module](tutorial-create-module.md)
 * **Create controllers and actions**
 * [Create routing and generate maps](tutorial-create-routing-maps.md)
 * [Create controller plugin and view helper](tutorial-create-controller-plugin-view-helper.md)
 * [Create model classes](tutorial-crud-create-model.md)
 * [Create application](tutorial-crud-create-application.md)

## Create new controller

When you want to create a new controller in a module you need to specify both
the names of the module and the controller. Optionally, you can specify the 
project path. At default the controller will be installed and configured 
without a factory.
 
    $ zf2rapid create-controller Shop Basket

The following tasks are executed when creating a new controller:

 * Check if module exists
 * Create controller directory
 * Create view directory for controller
 * Creating controller class
 * Creating controller factory class
 * Creating controller configuration
 * Adding `index` action method for controller
 * Creating action view script
 
## Structure of new controller

The generated structure of your module after creating a controller should look 
like this:

    --- module
      +--- Application
      +--- Shop
         +--- config
         |  +--- module.config.php
         +--- src
         |  +--- Shop
         |     +--- Application                         <---- new directory
         |        +--- Controller                       <---- new directory
         |           +--- BasketController.php          <---- new file
         |           +--- BasketControllerFactory.php   <---- new file
             +--- view
         |  +--- shop
         |     +--- basket                              <---- new directory
         |        +--- index.phtml                      <---- new file
         +--- autoload_classmap.php
         +--- Module.php
         +--- template_map.php
         
The `/module/Shop/src/Shop/Application/Controller/BasketController.php` file 
contains the `BasketController` with the `indexAction()` method which just 
returns an empty `ViewModel` instance. This action method can simply be filled 
with your application controller logic. 

    <?php
    /**
     * ZF2rapid Tutorial
     *
     * @copyright (c) 2015 John Doe
     * @license All rights reserved
     */
    namespace Shop\Application\Controller;
    
    use Zend\Mvc\Controller\AbstractActionController;
    use Zend\View\Model\ViewModel;
    
    /**
     * BasketController
     *
     * Handles the BasketController requests for the Shop Module
     *
     * @package Shop\Application\Controller
     */
    class BasketController extends AbstractActionController
    {
        /**
         * Index action for BasketController
         *
         * @return ViewModel
         */
        public function indexAction()
        {
            $viewModel = new ViewModel();
    
            return $viewModel;
        }
    }

The view script `/module/Shop/view/shop/basket/index.phtml` outputs the names 
of current module, controller and action and can also be simply overwritten.

```
<?php
/**
 * ZF2rapid Tutorial
 *
 * @copyright (c) 2015 John Doe
 * @license All rights reserved
 */
?>
<h2>Shop Module</h2>
<h3>Basket Controller</h3>
<h4>Index Action</h4>
```

Additionally, a factory is created automatically for your new controller. 
The factory `BasketControllerFactory` will be placed in the same path as the
controller and is readily configured for your needs. You only need to get the 
dependencies you want and pass them to your controller.

    <?php
    /**
     * ZF2rapid Tutorial
     *
     * @copyright (c) 2015 John Doe
     * @license All rights reserved
     */
    namespace Shop\Application\Controller;
    
    use Zend\ServiceManager\FactoryInterface;
    use Zend\ServiceManager\ServiceLocatorAwareInterface;
    use Zend\ServiceManager\ServiceLocatorInterface;
    
    /**
     * BasketControllerFactory
     *
     * Creates an instance of BasketController
     *
     * @package Shop\Application\Controller
     */
    class BasketControllerFactory implements FactoryInterface
    {
        /**
         * Create service
         *
         * @param ServiceLocatorInterface $controllerManager
         * @return BasketController
         */
        public function createService(ServiceLocatorInterface $controllerManager)
        {
            /** @var ServiceLocatorAwareInterface $controllerManager */
            $serviceLocator = $controllerManager->getServiceLocator();
    
            $instance = new BasketController();
    
            return $instance;
        }
    }

If you want to create a controller without creating a factory you can simply run 
this command: 

    $ zf2rapid create-controller Shop Basket --no-factory

## Create a factory for a controller

If you have a controller without a factory you can create a factory for an 
existing controller. You just need to specify the name of module and controller 
and optionally the project path:

    $ zf2rapid create-controller-factory Shop Basket

The following tasks are executed when creating a new controller factory:

 * Check if module exists
 * Check if controller exists
 * Creating controller factory class
 * Updating controller configuration

## Deleting controllers and controller factories

If you want to delete a controller you need to specify the name of the module, 
the name of the controller and optionally you can specify the project path. If 
a factory exists for this controller, this factory will also be deleted. 
Additionally, the configuration for this controller will also be deleted. 

    $ zf2rapid delete-controller Shop Basket

The following tasks are executed when deleting an existing controller:

 * Check if module exists
 * Check if controller exists
 * Delete controller class
 * Delete controller factory class (if any exists)
 * Remove controller configuration
 * Remove any views for this controller

If you only want to delete the factory of a controller you need to specify the 
name of the module, the name of the controller and optionally you can specify 
the project path. The controller factory will be deleted and the controller 
configuration will be updated.

    $ zf2rapid delete-controller-factory Shop Basket

The following tasks are executed when deleting an existing controller factory:

 * Check if module exists
 * Check if controller exists
 * Delete controller factory class (if any exists)
 * Update controller configuration (from `factories` to `invokables`)

## List controllers

If you want to get an overview about all the controllers in your current project 
you list them with a simple command. Naturally, you can optionally specify the 
project path like in almost any other command.
 
    $ zf2rapid show-controllers
    
If you want to display the controllers of some specific module(s) you can add the
names of these modules to the option `--modules=` and separate them with commas.

    $ zf2rapid show-controllers --modules=Shop,Application

## Create new action

When you want to create a new controller action in a module you need to specify 
the names of the module, the controller and the new action. Optionally, you can 
specify the project path. 
 
    $ zf2rapid create-action Shop Basket show

The following tasks are executed when creating a new action:

 * Check if module exists
 * Check if controller exists
 * Adding new action method for controller
 * Creating action view script

The view script `/module/Shop/view/shop/basket/show.phtml` outputs the names 
of current module, controller and action and can also be simply overwritten.
 
```
<?php
/**
 * ZF2rapid Tutorial
 *
 * @copyright (c) 2015 John Doe
 * @license All rights reserved
 */
?>
<h2>Shop Module</h2>
<h3>Basket Controller</h3>
<h4>Show Action</h4>
```

## Deleting action

If you want to delete a controller action you need to specify the name of 
the module, the name of the controller, the name of the action and optionally 
you can specify the project path.  

    $ zf2rapid delete-action Shop Basket show

The following tasks are executed when deleting an existing controller:

 * Check if module exists
 * Check if controller exists
 * Delete action method form controller class
 * Delete action view script
 
## List actions

If you want to get an overview about all the actions in your current project 
you list them with a simple command. Naturally, you can optionally specify the 
project path like in almost any other command.

    $ zf2rapid show-actions
 
If you want to display the actions of some specific module(s) you can add the
names of these modules to the option `--modules=` and separate them with commas.

    $ zf2rapid show-actions --modules=Shop,Application

If you want to display the actions of some specific controllers(s) you can add the
names of these controllers to the option `--controllers=` and separate them with commas.

    $ zf2rapid show-actions --controllers=Index,Basket

[Contiue to create routing and generate maps](tutorial-create-routing-maps.md)
