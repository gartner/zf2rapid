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
 * Class RepositoryFactoryGenerator
 *
 * @package ZF2rapid\Generator
 */
class RepositoryFactoryGenerator extends ClassGenerator
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param string $className
     * @param string $moduleName
     * @param string $namespaceName
     * @param string $tableName
     * @param array  $config
     */
    public function __construct(
        $className, $moduleName, $namespaceName, $tableName,
        array $config = []
    ) {
        // set config data
        $this->config = $config;

        // call parent constructor
        parent::__construct(
            $className . 'Factory',
            $moduleName . '\\' . $namespaceName
        );

        // add used namespaces and extended classes
        $this->addUse(
            $moduleName . '\\' . $this->config['namespaceTableGateway'] . '\\'
            . ucfirst($tableName) . 'TableGateway'
        );
        $this->addUse('Zend\ServiceManager\FactoryInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $this->setImplementedInterfaces(['FactoryInterface']);

        // add methods
        $this->addCreateServiceMethod($className, $moduleName, $tableName);
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
     * @param string $tableName
     */
    protected function addCreateServiceMethod($className, $moduleName, $tableName)
    {
        $managerName      = 'serviceLocator';
        $tableGatewayName = ucfirst($tableName) . 'TableGateway';
        $tableGatewayService = $moduleName . '\\' . $this->config['namespaceTableGateway'] . '\\' . ucfirst($tableName);

        // set action body
        $body = [
            '/** @var ' . $tableGatewayName . ' $tableGateway */',
            '$tableGateway = $serviceLocator->get(\'' . $tableGatewayService . '\');',
            '',
            '$instance = new ' . $className . '($tableGateway);',
            '',
            'return $instance;',
        ];
        $body = implode(AbstractGenerator::LINE_FEED, $body);

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