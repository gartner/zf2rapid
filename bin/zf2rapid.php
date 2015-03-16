#!/usr/bin/env php
<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */

// set working directory
use ZF2rapid\Console\Application;
use ZF2rapid\Console\Console;

// define application root
define('ZF2RAPID_ROOT', realpath(__DIR__ . '/..'));

// get vendor autoloading
include ZF2RAPID_ROOT . '/vendor/autoload.php';

// configure applications
$application = new Application(
    include ZF2RAPID_ROOT . '/config/routes.php',
    new Console()
);

// run application
$exit = $application->run();
exit($exit);
