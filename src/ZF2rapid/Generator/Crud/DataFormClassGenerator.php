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
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Metadata\Object\ConstraintObject;
use Zend\Filter\StaticFilter;
use ZF2rapid\Generator\ClassGeneratorInterface;

/**
 * Class FormClassGenerator
 *
 * @package ZF2rapid\Generator\Crud
 */
class DataFormClassGenerator extends ClassGenerator implements ClassGeneratorInterface
{
    /**
     * @var string
     */
    protected $paramModule;

    /**
     * @var string
     */
    protected $entityModule;

    /**
     * @var string
     */
    protected $entityClass;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var array
     */
    protected $loadedTables;

    /**
     * @param null|string $paramModule
     * @param null|string $entityModule
     * @param array       $loadedTables
     * @param array       $config
     */
    public function __construct($paramModule, $entityModule, $entityClass, array $loadedTables = [], array $config = [])
    {
        // set config data
        $this->paramModule  = $paramModule;
        $this->entityModule = $entityModule;
        $this->entityClass  = $entityClass;
        $this->loadedTables = $loadedTables;
        $this->config       = $config;

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
            $moduleName . '\\' . $this->config['namespaceForm']
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\Form\Form');
        $this->setExtendedClass('Form');

        // add methods
        $this->addInitMethod($className, $moduleName);
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
                    'Provides the ' . $className . ' form for the '
                    . $moduleName . ' Module',
                    [
                        new GenericTag('package', $this->getNamespaceName()),
                    ]
                )
            );
        }
    }

    /**
     * Generate an init method
     *
     * @param $className
     * @param $moduleName
     */
    protected function addInitMethod($className, $moduleName)
    {
        $tableName   = $this->filterCamelCaseToUnderscore(str_replace('Entity', '', $this->entityClass));
        $loadedTable = $this->loadedTables[$tableName];
        $labelPrefix = $this->filterCamelCaseToUnderscore($this->paramModule);

        /** @var ConstraintObject $primaryKey */
        $primaryKey     = $loadedTable['primaryKey'];
        $primaryColumns = $primaryKey->getColumns();

        $foreignKeys = [];

        /** @var ConstraintObject $foreignKey */
        foreach ($loadedTable['foreignKeys'] as $foreignKey) {
            foreach ($foreignKey->getColumns() as $column) {
                $foreignKeys[$column] = $foreignKey;
            }
        }

        $body   = [];
        $body[] = '$this->setName(\'' . $moduleName . 'Form\');';
        $body[] = '';

        /** @var ColumnObject $column */
        foreach ($loadedTable['columns'] as $column) {
            if (in_array($column->getName(), $primaryColumns)
                && in_array($column->getDataType(), ['tinyint', 'smallint', 'mediumint', 'int', 'bigint'])
            ) {
                continue;
            }

            // determine type
            if (isset($foreignKeys[$column->getName()])) {
                $type = 'select';
            } else {
                switch ($column->getDataType()) {
                    case 'enum':
                        $type = 'select';
                        break;

                    case 'tinytext':
                    case 'text':
                    case 'mediumtext':
                    case 'longtext':
                        $type = 'textarea';
                        break;

                    default:
                        $type = 'text';
                }
            }

            $options   = [];
            $options[] = '            \'label\' => \'' . $labelPrefix . '_label_' . $column->getName()
                . '\',';

            if (isset($foreignKeys[$column->getName()])) {
                $this->addOptionsProperty($column->getName());
                $this->addOptionsSetter($column->getName());

                $options[] = '            \'value_options\' => $this->' . lcfirst(
                        $this->filterUnderscoreToCamelCase($column->getName()
                        )) . 'Options,';
            } elseif ($column->getDataType() == 'enum') {
                $valueOptions   = [];
                $valueOptions[] = '[';

                foreach ($column->getErrata('permitted_values') as $value) {
                    $valueOptions[] = '                \'' . $value . '\' => \'' . $column->getTableName() . '_option_'
                        . $column->getName() . '_' . $value . '\',';
                }

                $valueOptions[] = '            ]';

                $options[] = '            \'value_options\' => ' . implode(AbstractGenerator::LINE_FEED, $valueOptions)
                    . ',';
            }

            $attributes   = [];
            $attributes[] = '            \'class\' => \'form-control\',';

            if (isset($foreignKeys[$column->getName()])) {
                if ($column->getColumnDefault()) {
                    $attributes[] = '            \'value\' => \'' . $column->getColumnDefault() . '\',';
                }
            } elseif ($column->getDataType() == 'enum') {
                if ($column->getColumnDefault()) {
                    $attributes[] = '            \'value\' => \'' . $column->getColumnDefault() . '\',';
                }
            }

            switch ($column->getDataType()) {
                case 'tinytext':
                    $attributes[] = '            \'rows\' => \'3\',';
                    break;

                case 'text':
                    $attributes[] = '            \'rows\' => \'10\',';
                    break;

                case 'mediumtext':
                case 'longtext':
                    $attributes[] = '            \'rows\' => \'15\',';
                    break;

                default:
            }

            $body[] = '$this->add(';
            $body[] = '    [';
            $body[] = '        \'name\' => \'' . $column->getName() . '\',';
            $body[] = '        \'type\' => \'' . $type . '\',';
            $body[] = '        \'options\' => [';

            $body = array_merge($body, $options);

            $body[] = '        ],';
            $body[] = '        \'attributes\' => [';

            $body = array_merge($body, $attributes);

            $body[] = '        ],';
            $body[] = '    ]';
            $body[] = ');';
            $body[] = '';
        }

        $body[] = '$this->add(';
        $body[] = '    [';
        $body[] = '        \'name\' => \'save_' . $tableName . '\',';
        $body[] = '        \'type\' => \'Submit\',';
        $body[] = '        \'options\' => [';
        $body[] = '        ],';
        $body[] = '        \'attributes\' => [';
        $body[] = '            \'value\' => \'' . $labelPrefix . '_action_save\',';
        $body[] = '            \'id\' => \'save_' . $tableName . '\',';
        $body[] = '            \'class\' => \'btn btn-success\',';
        $body[] = '        ],';
        $body[] = '    ]';
        $body[] = ');';
        $body[] = '';

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $initMethod = new MethodGenerator('init');
        $initMethod->addFlag(MethodGenerator::FLAG_PUBLIC);
        $initMethod->setDocBlock(
            new DocBlockGenerator(
                'Initialize the ' . $className . ' for module ' . $moduleName,
                'Please add any options and attributes you need for each form element'
            )
        );
        $initMethod->setBody($body);

        $this->addMethodFromGenerator($initMethod);
    }

    /**
     * @param string $columnName
     */
    protected function addOptionsProperty($columnName)
    {
        $columnName = lcfirst($this->filterUnderscoreToCamelCase($columnName));
        $property = new PropertyGenerator($columnName . 'Options');
        $property->addFlag(PropertyGenerator::FLAG_PRIVATE);
        $property->setDocBlock(
            new DocBlockGenerator(
                $columnName . ' options',
                null,
                [
                    [
                        'name'        => 'var',
                        'description' => 'array',
                    ]
                ]
            )
        );

        $this->addPropertyFromGenerator($property);
    }

    /**
     * @param string $columnName
     */
    protected function addOptionsSetter($columnName)
    {
        $columnName = lcfirst($this->filterUnderscoreToCamelCase($columnName));
        $body = '$this->' . $columnName . 'Options = $' . $columnName . 'Options;';

        $parameter = new ParameterGenerator($columnName . 'Options', 'array');

        $setMethodName = 'set' . ucfirst($columnName) . 'Options';

        $setMethod = new MethodGenerator($setMethodName);
        $setMethod->addFlag(MethodGenerator::FLAG_PUBLIC);
        $setMethod->setParameter($parameter);
        $setMethod->setDocBlock(
            new DocBlockGenerator(
                'Set ' . $columnName . ' options',
                null,
                [
                    [
                        'name'        => 'param',
                        'description' => 'array $' . $columnName . 'Options',
                    ]
                ]
            )
        );

        $setMethod->setBody($body);

        $this->addMethodFromGenerator($setMethod);
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
     * Filter underscore to camel case
     *
     * @param string $text
     *
     * @return string
     */
    protected function filterUnderscoreToCamelCase($text)
    {
        $text = StaticFilter::execute($text, 'Word\UnderscoreToCamelCase');

        return $text;
    }


}
