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
 * Class FactoryGenerator
 *
 * @package ZF2rapid\Generator
 */
class FactoryGenerator extends ClassGenerator
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $hydratorStrategies;

    /**
     * @param string $className
     * @param string $moduleName
     * @param string $namespaceName
     * @param string $managerName
     * @param array  $config
     * @param array  $currentHydratorStrategies
     */
    public function __construct(
        $className, $moduleName, $namespaceName, $managerName,
        array $config = [], array $currentHydratorStrategies = []
    ) {
        // set config data
        $this->config             = $config;
        $this->hydratorStrategies = $currentHydratorStrategies;

        // call parent constructor
        parent::__construct(
            $className . 'Factory',
            $moduleName . '\\' . $namespaceName
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\ServiceManager\FactoryInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorAwareInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $this->setImplementedInterfaces(['FactoryInterface']);

        // add methods
        $this->addCreateServiceMethod($className, $moduleName, $managerName);
        $this->addClassDocBlock($className);
    }

    /**
     * Add a class doc block
     *
     * @param string $className
     */
    protected function addClassDocBlock($className)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->getName(),
                    'Creates an instance of ' . $className,
                    [
                        new GenericTag('package', $this->getNamespaceName()),
                    ]
                )
            );
        }
    }

    /**
     * Generate the create service method
     *
     * @param string $className
     * @param string $moduleName
     * @param string $managerName
     */
    protected function addCreateServiceMethod($className, $moduleName, $managerName)
    {
        // set action body
        $body = [];
        $body[] = '$serviceLocator = $' . $managerName . '->getServiceLocator();';
        $body[] = '';
        $body[] = '$instance = new ' . $className . '();';

        foreach ($this->hydratorStrategies as $table => $strategy) {
            $body[] = '$instance->addStrategy(\'' . $strategy['column'] . '\', new ' . $strategy['class'] . '());';
        }

        $body[] = '';
        $body[] = 'return $instance;';

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        foreach ($this->hydratorStrategies as $table => $strategy) {
            $this->addUse($moduleName . '\\' . $this->config['namespaceHydrator'] . '\\Strategy\\' . $strategy['class']);
        }

        // create method
        $method = new MethodGenerator();
        $method->setName('createService');
        $method->setBody($body);
        $method->setParameters(
            [
                new ParameterGenerator(
                    $managerName, 'ServiceLocatorInterface'
                ),
            ]
        );

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Create service',
                    null,
                    [
                        new ParamTag(
                            $managerName,
                            [
                                'ServiceLocatorInterface',
                                'ServiceLocatorAwareInterface',
                            ]
                        ),
                        new ReturnTag([$className]),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

}
