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
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;

/**
 * Class ActionMethodGenerator
 *
 * @package ZF2rapid\Generator
 */
class ActionMethodGenerator extends MethodGenerator
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param null|string $actionName
     * @param null|string $controllerName
     * @param array       $config
     */
    public function __construct(
        $actionName, $controllerName, array $config = []
    ) {
        // set config data
        $this->config = $config;

        // call parent constructor
        parent::__construct(lcfirst($actionName) . 'Action');

        // set action body
        $body = [
            '$viewModel = new ViewModel();',
            '',
            'return $viewModel;',
        ];
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $this->setBody($body);

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $actionName . ' action for ' . $controllerName
                    . 'Controller',
                    null,
                    [
                        new ReturnTag(['ViewModel']),
                    ]
                )
            );
        }
    }
}