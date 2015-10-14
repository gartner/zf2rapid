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
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;

/**
 * Class ViewHelperClassGenerator
 *
 * @package ZF2rapid\Generator
 */
class ViewHelperClassGenerator extends ClassGenerator implements ClassGeneratorInterface
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // set config data
        $this->config = $config;

        // call parent constructor
        parent::__construct();
    }

    /**
     * Build the class
     *
     * @param string $className
     * @param string $moduleName
     */
    public function build($className, $moduleName)
    {
        // set name and namespace
        $this->setName($className);
        $this->setNamespaceName(
            $moduleName . '\\' . $this->config['namespaceViewHelper']
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\View\Helper\AbstractHelper');
        $this->setExtendedClass('AbstractHelper');

        // add methods
        $this->addInvokeMethod();
        $this->addClassDocBlock($className, $moduleName);
    }

    /**
     * Add a class doc block
     *
     * @param string $viewHelperName
     * @param string $moduleName
     */
    protected function addClassDocBlock($viewHelperName, $moduleName)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->getName(),
                    'Provides the ' . $viewHelperName . ' view helper for the '
                    . $moduleName . ' Module',
                    [
                        new GenericTag('package', $this->getNamespaceName()),
                    ]
                )
            );
        }
    }

    /**
     * Generate an __invoke method
     */
    protected function addInvokeMethod()
    {
        // set action body
        $body = [
            '// add view helper code here',
            '$output = \'\';',
            '',
            'return $output;',
        ];
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('__invoke');
        $method->setBody($body);

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Called when view helper is executed',
                    null,
                    [
                        new ReturnTag(['string']),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

}