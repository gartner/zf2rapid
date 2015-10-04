# ZF2rapid

Console application to create ZF2 application prototypes rapidly.

Your feedback is very welcome and needed. Please report any bugs, questions or 
feature enhancements to the 
[issue tracker](https://github.com/ZFrapid/zf2rapid/issues)!

## Table of contents

 * [Features](#features)
 * [Requirements](#requirements)
 * [Documentation](#documentation)
 * [Roadmap](#roadmap)

## Features

 * Create new projects based on Skeleton Applications
 * Create, delete, activate and deactivate modules
 * Create and delete controllers with or without factories
 * Create actions for controllers
 * Create routing for module with or without strict mode
 * Generate class and template maps for a module
 * Create other classes with or without factories
   * Controller plugins
   * View helpers
   * Filters
   * Validators
   * Input filters
   * Forms
   * Hydrators
 * (CRUD) Create model classes for a database table
   * Entities
   * Hydrators
   * Table gateways
   * Repositories
 * Show modules, controllers and actions for a project
 * Show ZF2 version for a project
 * Show other classes for a project
   * Controller plugins
   * View helpers
   * Filters
   * Validators
   * Input filters
   * Forms
   * Hydrators
 * Show ZF2rapid version
 * Show and manipulate ZF2rapid configuration
 * Command line help for each command
 * Autocomplete support (does not work for PHAR file yet)

## Requirements

ZF2rapid is based on [ZF\Console](https://github.com/zfcampus/zf-console) and 
some Zend Framework components like Zend\ServiceManager, Zend\ModuleManager 
or Zend\Code. It also requires PHP 5.5 and the PHP Extension `ext/intl`.  

Please see the [composer.json](composer.json) file for further requirement 
details.

## Documentation

 * For installation instructions please see the [ZF2rapid Installation-Guide](docs/installation.md).
 * For a quick start with ZF2rapid please see the [ZF2rapid Quick-Guide](docs/quick-guide.md).
 * For a more detailed step-by-step guide please see the [ZF2rapid Tutorial](docs/tutorial-create-project.md).
 * See the documentation of all commands in the [ZF2rapid Command-Guide](docs/command-guide.md).

## Roadmap

### Version 0.6.0

* First public release (done)

### Version 0.7.0

* Add simple CRUD commands with DB connection (done)

### Version 0.8.0

* Write tests for ZF2rapid (wip)

### Version 0.9.0

* Add module inspections (todo)

### Version 1.0.0

* First stable release (todo)

### Further plans

* Support ZF3 MVC applications (todo)
* Support ZF3 middleware applications (todo)