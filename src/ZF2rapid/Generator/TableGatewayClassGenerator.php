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
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Metadata\Object\ConstraintObject;
use Zend\Db\Metadata\Object\TableObject;

/**
 * Class TableGatewayClassGenerator
 *
 * @package ZF2rapid\Generator
 */
class TableGatewayClassGenerator extends ClassGenerator
    implements ClassGeneratorInterface
{
    /**
     * @var array
     */
    protected $config = array();

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var array
     */
    protected $tableObjects;

    /**
     * @param array  $config
     * @param string $tableName
     * @param array  $tableObjects
     */
    public function __construct(
        array $config = array(), $tableName, array $tableObjects = array()
    ) {
        // set config data
        $this->config       = $config;
        $this->tableName    = $tableName;
        $this->tableObjects = $tableObjects;

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
            $moduleName . '\\' . $this->config['namespaceTableGateway']
        );

        // add used namespaces and extended classes
        $this->addUse('ZF2rapidDomain\TableGateway\AbstractTableGateway');
        $this->setExtendedClass('AbstractTableGateway');
        $this->addClassDocBlock($className, $moduleName);

        // add selectWith() method if needed
        $this->addSelectWithMethod();
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
                    'Provides the ' . $className . ' table gateway for the '
                    . $moduleName . ' Module',
                    array(
                        new GenericTag('package', $this->getNamespaceName()),
                    )
                )
            );
        }
    }

    /**
     * Add a selectWith() method if table has an external dependency
     *
     * @return MethodGenerator
     */
    protected function addSelectWithMethod()
    {
        /** @var TableObject $currentTable */
        $currentTable = $this->tableObjects[$this->tableName];

        $foreignKeys = array();

        /** @var $tableConstraint ConstraintObject */
        foreach ($currentTable->getConstraints() as $tableConstraint) {
            if (!$tableConstraint->isForeignKey()) {
                continue;
            }

            $foreignKeys[] = $tableConstraint;
        }

        if (empty($foreignKeys)) {
            return true;
        }

        $body = array();

        /** @var ConstraintObject $foreignKey */
        foreach ($foreignKeys as $foreignKey) {
            $refTableName = $foreignKey->getReferencedTableName();

            /** @var TableObject $refTableObject */
            $refTableObject = $this->tableObjects[$refTableName];

            $body[] = '$select->join(';
            $body[] = '    \'' . $refTableName . '\',';
            $body[] = '    \'' . $this->tableName . '.'
                . $foreignKey->getColumns()[0] . ' = ' . $refTableName . '.'
                . $foreignKey->getReferencedColumns()[0] . '\',';
            $body[] = '    array(';

            
            /** @var ColumnObject $column */
            foreach ($refTableObject->getColumns() as $column) {
                $body[] = '        \'' . $refTableName . '.' . $column->getName(
                    ) . '\' => \'' . $column->getName() . '\',';
            }

            $body[] = '    )';
            $body[] = ');';
            $body[] = '';
        }

        $body[] = 'return parent::selectWith($select);';

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $this->addUse('Zend\Db\ResultSet\ResultSetInterface');
        $this->addUse('Zend\Db\Sql\Select');

        $selectMethod = new MethodGenerator('selectWith');
        $selectMethod->addFlag(MethodGenerator::FLAG_PUBLIC);
        $selectMethod->setParameter(
            new ParameterGenerator('select', 'Select')
        );
        $selectMethod->setDocBlock(
            new DocBlockGenerator(
                'Add join tables',
                null,
                array(
                    array(
                        'name'        => 'param',
                        'description' => 'Select $select',
                    ),
                    array(
                        'name'        => 'return',
                        'description' => 'ResultSetInterface',
                    ),
                )
            )
        );


        $selectMethod->setBody($body);

        $this->addMethodFromGenerator($selectMethod);

        return true;
    }
}