<?php
/**
 * zf2rapid
 *
 * @package    MODULENAME
 * @copyright  Copyright (c) 2014 ralf
 * @license    All rights reserved
 */

/**
 * namespace definition and usage
 */
namespace ZF2rapid\Generator;

use Zend\Code\Generator\GeneratorInterface;

/**
 * Class ControllerPluginClassGenerator
 *
 * @package ZF2rapid\Generator
 */
interface ClassGeneratorInterface extends GeneratorInterface
{
    /**
     * Build the class
     *
     * @param string $className
     * @param string $moduleName
     */
    public function build($className, $moduleName);
}