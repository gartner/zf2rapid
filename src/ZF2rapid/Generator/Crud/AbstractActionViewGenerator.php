<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Generator\Crud;

use Zend\Code\Generator\BodyGenerator;
use Zend\Code\Reflection\ClassReflection;
use Zend\Filter\StaticFilter;

/**
 * Abstract Class AbstractActionViewGenerator
 *
 * @package ZF2rapid\Generator\Crud
 */
abstract class AbstractActionViewGenerator extends BodyGenerator
{
    /**
     * @param null|string     $moduleName
     * @param ClassReflection $loadedEntity
     */
    public function __construct($moduleName, ClassReflection $loadedEntity)
    {
        // call parent constructor
        parent::__construct();

        // add methods
        $this->addContent($moduleName, $loadedEntity);
    }

    /**
     * Generate view content
     *
     * @param string          $moduleName
     * @param ClassReflection $loadedEntity
     */
    abstract protected function addContent($moduleName, ClassReflection $loadedEntity);

    /**
     * Filter camel case to underscore
     *
     * @param string $text
     *
     * @return string
     */
    protected function filterCamelCaseToUnderscore($text)
    {
        $text = StaticFilter::execute($text, 'Word\CamelCaseToUnderscore');
        $text = StaticFilter::execute($text, 'StringToLower');

        return $text;
    }

    /**
     * Filter camel case to dash
     *
     * @param string $text
     *
     * @return string
     */
    protected function filterCamelCaseToDash($text)
    {
        $text = StaticFilter::execute($text, 'Word\CamelCaseToDash');
        $text = StaticFilter::execute($text, 'StringToLower');

        return $text;
    }


}