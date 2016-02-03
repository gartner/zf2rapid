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
 * Class DataFormFactoryGenerator
 *
 * @package ZF2rapid\Generator\Crud
 */
class DataFormFactoryGenerator extends ClassGenerator
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $foreignKeys;

    /**
     * @param string $className
     * @param string $moduleName
     * @param string $entityModule
     * @param string $entityClass
     * @param array  $loadedTables
     * @param array  $config
     */
    public function __construct(
        $className, $moduleName, $entityModule, $entityClass, array $loadedTables = [], array $config = []
    ) {
        // set config data
        $this->config = $config;

        $tableName   = $this->filterCamelCaseToUnderscore(str_replace('Entity', '', $entityClass));
        $loadedTable = $loadedTables[$tableName];

        $this->foreignKeys = [];

        /** @var ConstraintObject $foreignKey */
        foreach ($loadedTable['foreignKeys'] as $foreignKey) {
            foreach ($foreignKey->getColumns() as $column) {
                $this->foreignKeys[$column] = $foreignKey;
            }
        }

        // call parent constructor
        parent::__construct(
            $className . 'Factory',
            $moduleName . '\\' . $this->config['namespaceForm']
        );

        // add namespace for hydrator
        $this->addUse(
            $entityModule . '\\' . $this->config['namespaceHydrator'] . '\\'
            . $this->filterUnderscoreToCamelCase($tableName) . 'Hydrator'
        );

        // add namespace for input filter
        $this->addUse(
            $entityModule . '\\' . $this->config['namespaceInputFilter'] . '\\'
            . $this->filterUnderscoreToCamelCase($tableName) . 'InputFilter'
        );

        // add namespaces for foreign key tables
        foreach ($this->foreignKeys as $foreignKey) {
            $this->addUse(
                $entityModule . '\\' . $this->config['namespaceTableGateway'] . '\\'
                . $this->filterUnderscoreToCamelCase($foreignKey->getReferencedTableName()) . 'TableGateway'
            );
        }

        // add used namespaces and extended classes
        $this->addUse('Zend\InputFilter\InputFilterPluginManager');
        $this->addUse('Zend\ServiceManager\FactoryInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorAwareInterface');
        $this->addUse('Zend\ServiceManager\ServiceLocatorInterface');
        $this->addUse('Zend\Stdlib\Hydrator\HydratorPluginManager');
        $this->setImplementedInterfaces(['FactoryInterface']);

        // add methods
        $this->addCreateServiceMethod($className, $moduleName, $entityModule, $entityClass);
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
     * @param string $entityModule
     * @param string $entityClass
     */
    protected function addCreateServiceMethod($className, $moduleName, $entityModule, $entityClass)
    {
        $managerName        = 'formElementManager';
        $entityPrefix       = str_replace('Entity', '', $entityClass);
        $hydratorName       = ucfirst($entityPrefix) . 'Hydrator';
        $hydratorService    = $entityModule . '\\' . ucfirst($entityPrefix);
        $inputFilterName    = ucfirst($entityPrefix) . 'InputFilter';
        $inputFilterService = $entityModule . '\\' . ucfirst($entityPrefix);

        // set action body
        $body   = [];
        $body[] = '$serviceLocator = $' . $managerName . '->getServiceLocator();';
        $body[] = '';
        $body[] = '/** @var HydratorPluginManager $hydratorManager */';
        $body[] = '$hydratorManager = $serviceLocator->get(\'HydratorManager\');';
        $body[] = '';
        $body[] = '/** @var InputFilterPluginManager $inputFilterManager */';
        $body[] = '$inputFilterManager = $serviceLocator->get(\'InputFilterManager\');';
        $body[] = '';

        /** @var ConstraintObject $foreignKey */
        foreach ($this->foreignKeys as $foreignKey) {
            $tableGatewayName    = $this->filterUnderscoreToCamelCase($foreignKey->getReferencedTableName()) . 'TableGateway';
            $tableGatewayService = $entityModule . '\\' . $this->config['namespaceTableGateway'] . '\\' . $this->filterUnderscoreToCamelCase(
                    $foreignKey->getReferencedTableName()
                );
            $tableGatewayParam   = lcfirst($this->filterUnderscoreToCamelCase(
                    $foreignKey->getReferencedTableName()
                )) . 'TableGateway';

            $body[] = '/** @var ' . $tableGatewayName . ' $' . $tableGatewayParam . ' */';
            $body[] = '$' . $tableGatewayParam . ' = $serviceLocator->get(\'' . $tableGatewayService . '\');';
            $body[] = '';
        }

        $body[] = '/** @var ' . $hydratorName . ' $hydrator */';
        $body[] = '$hydrator  = $hydratorManager->get(\'' . $hydratorService . '\');';
        $body[] = '';
        $body[] = '/** @var ' . $inputFilterName . ' $inputFilter */';
        $body[] = '$inputFilter  = $inputFilterManager->get(\'' . $inputFilterService . '\');';
        $body[] = '';
        $body[] = '$instance = new ' . $className . '();';

        /** @var ConstraintObject $foreignKey */
        foreach ($this->foreignKeys as $foreignKey) {
            $tableGatewayParam = lcfirst($this->filterUnderscoreToCamelCase(
                    $foreignKey->getReferencedTableName()
                )) . 'TableGateway';

            $foreignKeyColumns = $foreignKey->getColumns();

            $setterOption      = 'set' . $this->filterUnderscoreToCamelCase(
                        array_pop($foreignKeyColumns)
                ) . 'Options';

            $body[] = '$instance->' . $setterOption . '($' . $tableGatewayParam . '->getOptions());';
        }

        $body[] = '$instance->setHydrator($hydrator);';
        $body[] = '$instance->setInputFilter($inputFilter);';
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
     * Filter camel case to underscore
     *
     * @param string $text
     *
     * @return string
     */
    protected function filterUnderscoreToCamelCase($text)
    {
        $text = StaticFilter::execute($text, 'Word\UnderscoreToCamelCase');
        $text = ucfirst($text);

        return $text;
    }


}
