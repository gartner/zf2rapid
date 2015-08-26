#!/usr/bin/env php
<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use Zend\I18n\Translator\Translator;
use ZF2rapid\Console\Application;
use ZF2rapid\Console\Console;

// define application root
define('ZF2RAPID_ROOT', __DIR__ . '/..');

// get vendor autoloading
if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    // Local install
    require __DIR__ . '/../vendor/autoload.php';
} elseif (file_exists(getcwd() . '/vendor/autoload.php')) {
    // Root project is current working directory
    require getcwd() . '/vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../../autoload.php')) {
    // Relative to composer install
    require __DIR__ . '/../../../autoload.php';
} else {
    fwrite(STDERR, "Unable to setup autoloading; aborting\n");
    exit(2);
}

// set locale
Locale::setDefault('en_US');

// setup translator
$translator = new Translator();
$translator->addTranslationFilePattern(
    'PhpArray',
    ZF2RAPID_ROOT . '/language',
    '%s.php',
    'default'
);

// setup console
$console = new Console();
$console->setTranslator($translator);

// configure applications
$application = new Application(
    include ZF2RAPID_ROOT . '/config/routes.php',
    $console,
    $translator
);

// run application
$exit = $application->run();
exit($exit);
