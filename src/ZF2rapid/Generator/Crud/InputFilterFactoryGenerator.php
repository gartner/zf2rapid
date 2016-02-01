<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Generator\Crud;

use Zend\Code\Generator\AbstractGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Code\Generator\DocBlock\Tag\ParamTag;
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Db\Metadata\Object\ConstraintObject;
use Zend\Filter\StaticFilter;

/**
 * Class InputFilterFactoryGenerator
 *
 * @package ZF2rapid\Generator\Crud
 */
class InputFilterFactoryGenerator extends ClassGenerator
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $loadedTable;

    /**
     * @var array
     */
    protected $foreignKeys;

    /**
     * @param string $className
     * @param string $moduleName
     * @param string $namespaceName
     * @param string $tableName
     * @param array  $loadedTable
     * @param array  $config
     */
    public function __construct(
        $className, $moduleName, $namespaceName, $tableName, array $loadedTable = [], array $config = []
    ) {
        // set config data
        $this->config      = $config;
        $this->loadedTable = $loadedTable;

        $this->foreignKeys = [];

        /** @var ConstraintObject $foreignKey */
        foreach ($this->loadedTable['foreignKeys'] as $foreignKey) {
            foreach ($foreignKey->getColumns() as $column) {
                $this->foreignKeys[$column] = $foreignKey;
            }
        }

        // call parent constructor
        parent::__construct(
            $className . 'Factory',
            $moduleName . '\\' . $namespaceName
        );

        // add namespaces for foreign key tables
        foreach ($this->foreignKeys as $foreignKey) {
            $this->addUse(
                $moduleName . '\\' . $this->config['namespaceTableGateway'] . '\\'
                . StaticFilter::execute(
                    $foreignKey->getReferencedTableName(), 'Word\UnderscoreToCamelCase'
                ) . 'TableGateway'
            );
        }

        // add used namespaces and extended classes
        $this->addUse('Zend\ServiceManager\FactoryInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorAwareInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $this->setImplementedInterfaces(['FactoryInterface']);

        // add methods
        $this->addCreateServiceMethod($className, $moduleName);
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
     */
    protected function addCreateServiceMethod($className, $moduleName)
    {
        $managerName = 'inputFilterManager';

        // set action body
        $body   = [];
        $body[] = '$serviceLocator = $' . $managerName . '->getServiceLocator();';
        $body[] = '';

        /** @var ConstraintObject $foreignKey */
        foreach ($this->foreignKeys as $foreignKey) {
            $tableGatewayName    = StaticFilter::execute(
                    $foreignKey->getReferencedTableName(), 'Word\UnderscoreToCamelCase'
                ) . 'TableGateway';
            $tableGatewayService = $moduleName . '\\' . $this->config['namespaceTableGateway'] . '\\' . StaticFilter::execute(
                    $foreignKey->getReferencedTableName(), 'Word\UnderscoreToCamelCase'
                );
            $tableGatewayParam   = lcfirst(StaticFilter::execute(
                    $foreignKey->getReferencedTableName(), 'Word\UnderscoreToCamelCase'
                )) . 'TableGateway';

            $body[] = '/** @var ' . $tableGatewayName . ' $' . $tableGatewayParam . ' */';
            $body[] = '$' . $tableGatewayParam . ' = $serviceLocator->get(\'' . $tableGatewayService . '\');';
            $body[] = '';
        }

        $body[] = '$instance = new ' . $className . '();';

        /** @var ConstraintObject $foreignKey */
        foreach ($this->foreignKeys as $foreignKey) {
            $tableGatewayParam = lcfirst(
                    StaticFilter::execute($foreignKey->getReferencedTableName(), 'Word\UnderscoreToCamelCase')
                ) . 'TableGateway';
            $setterOption      = 'set' . StaticFilter::execute(
                    $foreignKey->getReferencedTableName(), 'Word\UnderscoreToCamelCase'
                ) . 'Options';

            $body[] = '$instance->' . $setterOption . '(array_keys($' . $tableGatewayParam . '->getOptions()));';
        }

        $body[] = '';
        $body[] = 'return $instance;';

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
