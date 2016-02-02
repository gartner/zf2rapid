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
 * Class InputFilterClassGenerator
 *
 * @package ZF2rapid\Generator\Crud
 */
class InputFilterClassGenerator extends ClassGenerator
    implements ClassGeneratorInterface
{
    /**
     * @var
     */
    protected $tableName;

    /**
     * @var array
     */
    protected $loadedTable;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param null|string $tableName
     * @param array       $loadedTable
     * @param array       $config
     */
    public function __construct($tableName, array $loadedTable = [], array $config = [])
    {
        // set config data
        $this->tableName   = $tableName;
        $this->loadedTable = $loadedTable;
        $this->config      = $config;

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
            $moduleName . '\\' . $this->config['namespaceInputFilter']
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\InputFilter\InputFilter');
        $this->setExtendedClass('InputFilter');
        $this->addClassDocBlock($className, $moduleName);

        // add init method
        $this->addInitMethod($className, $moduleName);
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
                    'Provides the ' . $className . ' input filter for the '
                    . $moduleName . ' Module',
                    [
                        new GenericTag('package', $this->getNamespaceName()),
                    ]
                )
            );
        }
    }

    /**
     * Add the init() method to setup input elements
     *
     * @param $className
     * @param $moduleName
     */
    protected function addInitMethod($className, $moduleName)
    {
        /** @var ConstraintObject $primaryKey */
        $primaryKey     = $this->loadedTable['primaryKey'];
        $primaryColumns = $primaryKey->getColumns();

        $foreignKeys = [];

        /** @var ConstraintObject $foreignKey */
        foreach ($this->loadedTable['foreignKeys'] as $foreignKey) {
            foreach ($foreignKey->getColumns() as $column) {
                $foreignKeys[$column] = $foreignKey;
            }
        }

        $body = [];

        /** @var ColumnObject $column */
        foreach ($this->loadedTable['columns'] as $column) {
            if (in_array($column->getName(), $primaryColumns)
                && in_array($column->getDataType(), ['tinyint', 'smallint', 'mediumint', 'int', 'bigint'])
            ) {
                $required = 'false';
            } elseif ($column->isNullable() === false) {
                $required = 'true';
            } else {
                $required = 'false';
            }

            $filters = [];

            if (in_array(
                $column->getDataType(), ['varchar', 'char', 'text', 'enum', 'set', 'datetime', 'timestamp']
            )) {
                $filters[] = '            [';
                $filters[] = '                \'name\' => \'StringTrim\',';
                $filters[] = '            ],';
            }

            $validators = [];

            if ($column->getDataType() == 'enum') {
                $message = $column->getTableName() . '_message_' . $column->getTableName() . '_' . $column->getName()
                    . '_inarray';
                $options = '[\'' . implode('\', \'', $column->getErrata('permitted_values')) . '\']';

                $validators[] = '            [';
                $validators[] = '                \'name\' => \'InArray\',';
                $validators[] = '                \'options\' => [';
                $validators[] = '                     \'haystack\' => ' . $options . ',';
                $validators[] = '                     \'message\' => \'' . $message . '\',';
                $validators[] = '                ],';
                $validators[] = '            ],';

            } elseif ($column->getDataType() == 'varchar') {
                $message = $column->getTableName() . '_message_' . $column->getTableName() . '_' . $column->getName()
                    . '_stringlength';
                $max     = $column->getCharacterMaximumLength();

                $validators[] = '            [';
                $validators[] = '                \'name\' => \'StringLength\',';
                $validators[] = '                \'options\' => [';
                $validators[] = '                     \'max\' => ' . $max . ',';
                $validators[] = '                     \'message\' => \'' . $message . '\',';
                $validators[] = '                ],';
                $validators[] = '            ],';

            } elseif ($column->getDataType() == 'char') {
                $message = $column->getTableName() . '_message_' . $column->getTableName() . '_' . $column->getName()
                    . '_stringlength';
                $min     = $column->getCharacterMaximumLength();
                $max     = $column->getCharacterMaximumLength();

                $validators[] = '            [';
                $validators[] = '                \'name\' => \'StringLength\',';
                $validators[] = '                \'options\' => [';
                $validators[] = '                     \'min\' => ' . $min . ',';
                $validators[] = '                     \'max\' => ' . $max . ',';
                $validators[] = '                     \'message\' => \'' . $message . '\',';
                $validators[] = '                ],';
                $validators[] = '            ],';
            }

            if (isset($foreignKeys[$column->getName()])) {
                $this->addOptionsProperty($column->getName(), $foreignKeys[$column->getName()]);
                $this->addOptionsSetter($column->getName(), $foreignKeys[$column->getName()]);

                $message = $column->getTableName() . '_message_' . $column->getTableName() . '_' . $column->getName()
                    . '_inarray';

                $validators[] = '            [';
                $validators[] = '                \'name\' => \'InArray\',';
                $validators[] = '                \'options\' => [';
                $validators[] = '                     \'haystack\' => $this->' . $column->getName() . 'Options,';
                $validators[] = '                     \'message\' => \'' . $message . '\',';
                $validators[] = '                ],';
                $validators[] = '            ],';
            }

            if ($required) {
                $message = $column->getTableName() . '_message_' . $column->getTableName() . '_' . $column->getName()
                    . '_notempty';

                $validators[] = '            [';
                $validators[] = '                \'name\' => \'NotEmpty\',';
                $validators[] = '                \'options\' => [';
                $validators[] = '                     \'message\' => \'' . $message . '\',';
                $validators[] = '                ],';
                $validators[] = '            ],';
            }

            $body[] = '$this->add(';
            $body[] = '    [';
            $body[] = '        \'name\' => \'' . $column->getName() . '\',';
            $body[] = '        \'required\' => ' . $required . ',';
            $body[] = '        \'filters\' => [';

            $body = array_merge($body, $filters);

            $body[] = '        ],';
            $body[] = '        \'validators\' => [';

            $body = array_merge($body, $validators);

            $body[] = '        ],';
            $body[] = '    ]';
            $body[] = ');';
            $body[] = '';
        }

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $initMethod = new MethodGenerator('init');
        $initMethod->addFlag(MethodGenerator::FLAG_PUBLIC);
        $initMethod->setDocBlock(
            new DocBlockGenerator(
                'Initialize the ' . $className . ' for module ' . $moduleName,
                'Please add any filter and validator you need for each input element'
            )
        );
        $initMethod->setBody($body);

        $this->addMethodFromGenerator($initMethod);
    }

    /**
     * @param string           $columnName
     * @param ConstraintObject $foreignKey
     */
    protected function addOptionsProperty($columnName, ConstraintObject $foreignKey)
    {
        $columnName = StaticFilter::execute($columnName, 'Word\UnderscoreToCamelCase');
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
     * @param string           $columnName
     * @param ConstraintObject $foreignKey
     */
    protected function addOptionsSetter($columnName, ConstraintObject $foreignKey)
    {
        $columnName = StaticFilter::execute($columnName, 'Word\UnderscoreToCamelCase');
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
}
