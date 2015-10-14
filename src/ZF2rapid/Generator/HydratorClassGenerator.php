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
 * Class HydratorClassGenerator
 *
 * @package ZF2rapid\Generator
 */
class HydratorClassGenerator extends ClassGenerator
    implements ClassGeneratorInterface
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $baseHydrator;

    /**
     * @param array  $config
     * @param string $baseHydrator
     */
    public function __construct(
        array $config = [], $baseHydrator = 'ClassMethods'
    ) {
        // set config data and base hydrator
        $this->config       = $config;
        $this->baseHydrator = $baseHydrator;

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
            $moduleName . '\\' . $this->config['namespaceHydrator']
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\Stdlib\Hydrator\\' . $this->baseHydrator);
        $this->setExtendedClass($this->baseHydrator);

        // add methods
        $this->addExtractMethod();
        $this->addHydrateMethod();
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
                    'Provides the ' . $className . ' hydrator for the '
                    . $moduleName . ' Module',
                    [
                        new GenericTag('package', $this->getNamespaceName()),
                    ]
                )
            );
        }
    }

    /**
     * Generate an extract method
     */
    protected function addExtractMethod()
    {
        // set action body
        $body = [
            '// add extended hydrator logic here',
            'return parent::extract($object);',
        ];
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('extract');
        $method->setBody($body);
        $method->setParameters(
            [
                new ParameterGenerator(
                    'object', 'object'
                ),
            ]
        );

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Extract values from an object',
                    null,
                    [
                        new ParamTag(
                            'object',
                            [
                                'object',
                            ]
                        ),
                        new ReturnTag(['array']),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

    /**
     * Generate an hydrate method
     */
    protected function addHydrateMethod()
    {
        // set action body
        $body = [
            '// add extended hydrator logic here',
            'return parent::hydrate($data, $object);',
        ];
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('hydrate');
        $method->setBody($body);
        $method->setParameters(
            [
                new ParameterGenerator(
                    'data', 'array'
                ),
                new ParameterGenerator(
                    'object', 'object'
                ),
            ]
        );

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Hydrate an object by populating data',
                    null,
                    [
                        new ParamTag(
                            'data',
                            [
                                'array',
                            ]
                        ),
                        new ParamTag(
                            'object',
                            [
                                'object',
                            ]
                        ),
                        new ReturnTag(['object']),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

}