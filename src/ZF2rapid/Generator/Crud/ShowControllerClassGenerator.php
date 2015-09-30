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
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;
use Zend\Filter\StaticFilter;
use ZF2rapid\Generator\ClassGeneratorInterface;

/**
 * Class ControllerClassGenerator
 *
 * @package ZF2rapid\Generator\Crud
 */
class ShowControllerClassGenerator extends ClassGenerator implements ClassGeneratorInterface
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
     * @var array
     */
    protected $config = array();

    /**
     * @param null|string $paramModule
     * @param null|string $entityModule
     * @param array       $config
     */
    public function __construct($paramModule, $entityModule, array $config = array())
    {
        // set config data
        $this->paramModule  = $paramModule;
        $this->entityModule = $entityModule;
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
        // prepare some params
        $repositoryClass     = $this->paramModule . 'Repository';
        $repositoryNamespace = $this->entityModule . '\\' . $this->config['namespaceRepository'] . '\\'
            . $repositoryClass;

        // set name and namespace
        $this->setName($className);
        $this->setNamespaceName(
            $moduleName . '\\' . $this->config['namespaceController']
        );

        // add used namespaces and extended classes
        $this->addUse($repositoryNamespace);
        $this->addUse('Zend\Mvc\Controller\AbstractActionController');
        $this->addUse('Zend\View\Model\ViewModel');
        $this->setExtendedClass('AbstractActionController');

        // add doc block
        $this->addClassDocBlock($className, $moduleName);

        // add repository property and setter
        $this->addRepositoryProperty($repositoryClass);
        $this->addRepositorySetter($repositoryClass);

        // add indexAction
        $this->addIndexAction($repositoryClass);
    }

    /**
     * Add a class doc block
     *
     * @param string $controllerName
     * @param string $moduleName
     */
    protected function addClassDocBlock($controllerName, $moduleName)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->getName(),
                    'Handles the ' . $controllerName . ' requests for the '
                    . $moduleName . ' Module',
                    array(
                        new GenericTag('package', $this->getNamespaceName()),
                    )
                )
            );
        }
    }

    /**
     * Add repository property
     *
     * @param $repositoryClass
     */
    protected function addRepositoryProperty($repositoryClass)
    {
        $property = new PropertyGenerator(lcfirst($repositoryClass));
        $property->addFlag(PropertyGenerator::FLAG_PRIVATE);
        $property->setDocBlock(
            new DocBlockGenerator(
                null,
                null,
                array(
                    array(
                        'name'        => 'var',
                        'description' => $repositoryClass,
                    )
                )
            )
        );

        $this->addPropertyFromGenerator($property);
    }

    /**
     * Add repository setter
     *
     * @param $repositoryClass
     */
    protected function addRepositorySetter($repositoryClass)
    {
        $repositoryClass = $this->paramModule . 'Repository';
        $repositoryParam = lcfirst($repositoryClass);

        $body = '$this->' . $repositoryParam . ' = $' . $repositoryParam . ';';

        $parameter = new ParameterGenerator($repositoryParam, $repositoryClass);

        $setMethodName = 'set' . $repositoryClass;

        $setMethod = new MethodGenerator($setMethodName);
        $setMethod->addFlag(MethodGenerator::FLAG_PUBLIC);
        $setMethod->setParameter($parameter);
        $setMethod->setDocBlock(
            new DocBlockGenerator(
                'Set ' . $repositoryClass,
                null,
                array(
                    array(
                        'name'        => 'param',
                        'description' => $repositoryClass . ' $' . $repositoryParam,
                    )
                )
            )
        );

        $setMethod->setBody($body);

        $this->addMethodFromGenerator($setMethod);
    }

    /**
     * Add indexAction() method
     *
     * @param $repositoryClass
     */
    protected function addIndexAction($repositoryClass)
    {
        $entityParam    = lcfirst($this->paramModule) . 'Entity';
        $dashedParam    = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToDash'));
        $noFoundMessage = $dashedParam . '_message_' . $dashedParam . '_not_found';

        $body = array(
            '$id = $this->params()->fromRoute(\'id\');',
            '',
            'if (!$id) {',
            '    $this->flashMessenger()->addErrorMessage(\'' . $noFoundMessage . '\');',
            '    ',
            '    return $this->redirect()->toRoute(\'' . $dashedParam . '\');',
            '}',
            '',
            '$' . $entityParam . ' = $this->' . lcfirst($repositoryClass) . '->getEntityById($id);',
            '',
            'if (!$' . $entityParam . ') {',
            '    $this->flashMessenger()->addErrorMessage(\'' . $noFoundMessage . '\');',
            '    ',
            '    return $this->redirect()->toRoute(\'' . $dashedParam . '\');',
            '}',
            '',
            '$viewModel = new ViewModel(',
            '    array(',
            '        \'' . $entityParam . '\' => $' . $entityParam,
            '    )',
            ');',
            '',
            'return $viewModel;',
        );

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $indexAction = new MethodGenerator('indexAction');
        $indexAction->addFlag(MethodGenerator::FLAG_PUBLIC);
        $indexAction->setDocBlock(
            new DocBlockGenerator(
                'Index action for ShowController',
                null,
                array(
                    new ReturnTag(array('ViewModel')),
                )
            )
        );
        $indexAction->setBody($body);

        $this->addMethodFromGenerator($indexAction);
    }
}