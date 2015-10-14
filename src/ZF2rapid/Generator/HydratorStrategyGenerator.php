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
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Stdlib\Parameters;

/**
 * Class HydratorStrategyGenerator
 *
 * @package ZF2rapid\Generator
 */
class HydratorStrategyGenerator extends ClassGenerator
    implements ClassGeneratorInterface
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $refTableName;

    /**
     * @var array
     */
    protected $loadedTables;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @param Parameters $params
     * @param string     $refTableName
     */
    public function __construct(Parameters $params, $refTableName)
    {
        // set config data
        $this->config       = $params->config;
        $this->refTableName = $refTableName;
        $this->loadedTables = $params->loadedTables;
        $this->entityClass  = $params->tableConfig[$refTableName]['entityClass'];

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
            . '\\Strategy'
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\Stdlib\Hydrator\Strategy\StrategyInterface');
        $this->addUse(
            $moduleName . '\\' . $this->config['namespaceEntity'] . '\\'
            . $this->entityClass
        );
        $this->setImplementedInterfaces(['StrategyInterface']);

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
                    'Provides the ' . ucfirst($this->refTableName)
                    . ' hydrator strategy for the ' . $moduleName . ' Module',
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
            'if (!is_object($value)) {',
            '    return $value;',
            '}',
            '',
            'return $value->getIdentifier();',
        ];
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('extract');
        $method->setBody($body);
        $method->setParameters(
            [
                new ParameterGenerator('value'),
            ]
        );

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Extract identifier from entity',
                    null,
                    [
                        new ParamTag(
                            'value',
                            [
                                $this->entityClass,
                            ]
                        ),
                        new ReturnTag(['string']),
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
        $refTableData = $this->loadedTables[$this->refTableName];

        /** @var ColumnObject $firstColumn */
        $firstColumn = reset($refTableData['columns']);

        // set action body
        $body   = [];
        $body[] = 'if (isset($data[\''
            . $this->refTableName . '.' . $firstColumn->getName() . '\'])) {';

        /** @var ColumnObject $column */
        foreach ($refTableData['columns'] as $column) {
            $body[] = '    $' . $column->getName() . ' = $data[\''
                . $this->refTableName . '.' . $column->getName() . '\'];';
        }

        $body[] = '} else {';

        /** @var ColumnObject $column */
        foreach ($refTableData['columns'] as $column) {
            $body[] = '    $' . $column->getName() . ' = $value;';
        }

        $body[] = '}';
        $body[] = '';

        $body[] = '$' . $this->refTableName . ' = new '
            . $this->entityClass . '();';
        $body[] = '$' . $this->refTableName . '->exchangeArray(';
        $body[] = '    [';

        /** @var ColumnObject $column */
        foreach ($refTableData['columns'] as $column) {
            $body[] = '        \'' . $column->getName() . '\' => $' . $column->getName() . ',';
        }

        $body[] = '    ]';
        $body[] = ');';
        $body[] = '';
        $body[] = 'return $' . $this->refTableName . ';';

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('hydrate');
        $method->setBody($body);
        $method->setParameters(
            [
                new ParameterGenerator(
                    'value'
                ),
                new ParameterGenerator(
                    'data', 'array', []
                ),
            ]
        );

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Hydrate an entity by populating data',
                    null,
                    [
                        new ParamTag(
                            'value'
                        ),
                        new ParamTag(
                            'data',
                            [
                                'array',
                            ]
                        ),
                        new ReturnTag([$this->entityClass]),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

}