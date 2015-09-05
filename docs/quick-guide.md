# ZF2rapid Quick-Guide

In this Quick-Guide you will learn the basics to create a new application 
with ZF2rapid.

Create a new project to a custom path and switch to the new project path:

    $ zf2rapid create-project --workingPath=/path/to/mynewproject
    $ cd /path/to/mynewproject/

Show installed modules, controllers and actions:

    $ zf2rapid show-modules 
    $ zf2rapid show-controllers 
    $ zf2rapid show-actions

Create a new module, a new controller with a factory and some new actions

    $ zf2rapid create-module Shop
    $ zf2rapid create-controller Shop Basket
    $ zf2rapid create-action Shop basket show
    $ zf2rapid create-action Shop basket send
    $ zf2rapid create-action Shop basket cancel

Show installed modules, controllers and actions again:

    $ zf2rapid show-modules 
    $ zf2rapid show-controllers 
    $ zf2rapid show-actions

Create a new view helper with a factory:

    $ zf2rapid create-view-helper Shop Basket

Create strict routing for new module

    $ zf2rapid create-routing Shop --strict

Generate the class map and the template map for the new module:

    $ zf2rapid generate-classmap Shop
    $ zf2rapid generate-templatemap Shop

Create an Apache 2 virtual host `mynewproject` with the document root 
`mynewproject/public/`, enable it, add it to your `/etc/hosts` file and restart 
Apache 2: 

    <VirtualHost *:80>
        ServerName mynewproject
        DocumentRoot /path/to/mynewproject/public/
        
        SetEnv APPLICATION_ENV development
        
        <Directory "/path/to/mynewproject/public/">
            DirectoryIndex index.php
            AllowOverride All
            Require all granted
        </Directory>
    </VirtualHost>

Run project in your browser:

 * http://mynewproject/
 * http://mynewproject/shop/basket/show

The screen should look like this:

![Screen shot new project](screen_new_project.jpg)

