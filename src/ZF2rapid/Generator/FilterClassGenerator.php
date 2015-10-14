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
use Zend\Code\Generator\DocBlock\Tag\ParamTag;
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;

/**
 * Class FilterClassGenerator
 *
 * @package ZF2rapid\Generator
 */
class FilterClassGenerator extends ClassGenerator
    implements ClassGeneratorInterface
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
            $moduleName . '\\' . $this->config['namespaceFilter']
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\Filter\AbstractFilter');
        $this->setExtendedClass('AbstractFilter');

        // add methods
        $this->addFilterMethod();
        $this->addClassDocBlock($className, $moduleName);
    }

    /**
     * Add a class doc block
     *
     * @param string $className
     * @param string $moduleName
     */
    protected function addClassDocBlock($className, $moduleName)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->getName(),
                    'Provides the ' . $className . ' filter for the '
                    . $moduleName . ' Module',
                    [
                        new GenericTag('package', $this->getNamespaceName()),
                    ]
                )
            );
        }
    }

    /**
     * Generate a filter method
     */
    protected function addFilterMethod()
    {
        // set action body
        $body = [
            '// add filter code here',
            'return $value;',
        ];
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('filter');
        $method->setBody($body);
        $method->setParameters(
            [
                new ParameterGenerator(
                    'value', 'mixed'
                ),
            ]
        );


        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Called when filter is executed',
                    null,
                    [
                        new ParamTag(
                            'value',
                            [
                                'mixed',
                            ]
                        ),
                        new ReturnTag(['mixed']),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

}