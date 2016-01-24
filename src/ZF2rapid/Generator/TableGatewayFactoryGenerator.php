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
use Zend\Db\Metadata\Object\ConstraintObject;
use Zend\Filter\StaticFilter;

/**
 * Class TableGatewayFactoryGenerator
 *
 * @package ZF2rapid\Generator
 */
class TableGatewayFactoryGenerator extends ClassGenerator
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param string $className
     * @param string $moduleName
     * @param string $tableName
     * @param array  $config
     * @param array  $loadedTables
     */
    public function __construct(
        $className, $moduleName, $tableName, array $config = [], array $loadedTables = []
    ) {
        // set config data
        $this->config = $config;

        // call parent constructor
        parent::__construct(
            $className . 'Factory',
            $moduleName . '\\' . $this->config['namespaceTableGateway']
        );

        // add used namespaces and extended classes
        $this->addUse(
            $moduleName . '\\' . $this->config['namespaceEntity'] . '\\'
            . StaticFilter::execute(
                $tableName, 'Word\UnderscoreToCamelCase'
            ) . 'Entity'
        );
        $this->addUse(
            $moduleName . '\\' . $this->config['namespaceHydrator'] . '\\'
            . StaticFilter::execute(
                $tableName, 'Word\UnderscoreToCamelCase'
            ) . 'Hydrator'
        );
        $this->addUse('Zend\Db\Adapter\AdapterInterface');
        $this->addUse('Zend\Db\ResultSet\HydratingResultSet');
        $this->addUse('Zend\ServiceManager\FactoryInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $this->addUse('Zend\Stdlib\Hydrator\HydratorPluginManager');
        $this->setImplementedInterfaces(['FactoryInterface']);

        // add methods
        $this->addCreateServiceMethod($className, $moduleName, $tableName, $loadedTables[$tableName]);
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
     * @param array  $loadedTable
     */
    protected function addCreateServiceMethod(
        $className, $moduleName, $tableName, array $loadedTable = []
    ) {
        /** @var ConstraintObject $primaryKey */
        $primaryKey     = $loadedTable['primaryKey'];
        $primaryColumns = $primaryKey->getColumns();

        $managerName     = 'serviceLocator';
        $hydratorName    = ucfirst($tableName) . 'Hydrator';
        $hydratorService = $moduleName . '\\' . ucfirst($tableName);
        $entityName      = StaticFilter::execute(
            $tableName, 'Word\UnderscoreToCamelCase'
        ) . 'Entity';

        // set action body
        $body   = [];
        $body[] = '/** @var HydratorPluginManager $hydratorManager */';
        $body[] = '$hydratorManager = $serviceLocator->get(\'HydratorManager\');';
        $body[] = '';
        $body[] = '/** @var AdapterInterface $dbAdapter */';
        $body[] = '$dbAdapter = $serviceLocator->get(\'Zend\Db\Adapter\Adapter\');';
        $body[] = '';
        $body[] = '/** @var ' . $hydratorName . ' $hydrator */';
        $body[] = '$hydrator  = $hydratorManager->get(\'' . $hydratorService . '\');';
        $body[] = '$entity    = new ' . $entityName . '();';
        $body[] = '$resultSet = new HydratingResultSet($hydrator, $entity);';
        $body[] = '';
        $body[] = '$instance = new ' . $className . '(';
        $body[] = '    \'' . $tableName . '\', $dbAdapter, null, $resultSet';
        $body[] = ');';
        $body[] = '$instance->setHydrator($hydrator);';
        $body[] = '$instance->setPrimaryKey(\'' . $tableName . '.' . $primaryColumns[0] . '\');';
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
