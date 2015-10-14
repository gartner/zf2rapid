<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Generator;

use Zend\Code\Generator\AbstractGenerator;
use Zend\Code\Generator\BodyGenerator;

/**
 * Class ActionViewGenerator
 *
 * @package ZF2rapid\Generator
 */
class ActionViewGenerator extends BodyGenerator
{
    /**
     * @param null|string $actionName
     * @param null|string $controllerName
     * @param null|string $moduleName
     */
    public function __construct(
        $actionName, $controllerName, $moduleName
    ) {
        // call parent constructor
        parent::__construct();

        // add methods
        $this->addContent($actionName, $controllerName, $moduleName);
    }

    /**
     * Generate view content
     *
     * @param string $actionName
     * @param string $controllerName
     * @param string $moduleName
     */
    protected function addContent($actionName, $controllerName, $moduleName)
    {
        // set action body
        $body = [
            '?>',
            '<h2>' . $moduleName . ' Module</h2>',
            '<h3>' . $controllerName . ' Controller</h3>',
            '<h4>' . $actionName . ' Action</h4>',
        ];
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // add method
        $this->setContent($body);
    }

}