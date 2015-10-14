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
class ControllerClassGenerator extends ClassGenerator implements ClassGeneratorInterface
{
    /**
     * @var null|string
     */
    protected $controllerName;

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
    protected $config = [];

    /**
     * @param null|string $controllerName
     * @param null|string $paramModule
     * @param null|string $entityModule
     * @param array       $config
     */
    public function __construct($controllerName, $paramModule, $entityModule, array $config = [])
    {
        // set config data
        $this->controllerName = $controllerName;
        $this->paramModule    = $paramModule;
        $this->entityModule   = $entityModule;
        $this->config         = $config;

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
            $moduleName . '\\' . $this->config['namespaceController']
        );

        // prepare repository params
        $repositoryClass     = $this->paramModule . 'Repository';
        $repositoryNamespace = $this->entityModule . '\\' . $this->config['namespaceRepository'] . '\\'
            . $repositoryClass;

        // prepare form params
        if (in_array($this->controllerName, ['Create', 'Update'])) {
            $formClass     = $this->paramModule . 'DataForm';
            $formNamespace = $this->paramModule . '\\' . $this->config['namespaceForm'] . '\\' . $formClass;

            $this->addUse($formNamespace);
        } elseif (in_array($this->controllerName, ['Delete'])) {
            $formClass     = $this->paramModule . 'DeleteForm';
            $formNamespace = $this->paramModule . '\\' . $this->config['namespaceForm'] . '\\' . $formClass;

            $this->addUse($formNamespace);
        } else {
            $formClass = null;
        }
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
        switch ($this->controllerName) {
            case 'Index':
                $this->addIndexControllerAction($repositoryClass);
                break;

            case 'Show':
                $this->addShowControllerAction($repositoryClass);
                break;

            case 'Create':
                $this->addFormProperty($formClass);
                $this->addFormSetter($formClass);
                $this->addCreateControllerAction($repositoryClass);
                break;

            case 'Update':
                $this->addFormProperty($formClass);
                $this->addFormSetter($formClass);
                $this->addUpdateControllerAction($repositoryClass);
                break;

            case 'Delete':
                $this->addFormProperty($formClass);
                $this->addFormSetter($formClass);
                $this->addDeleteControllerAction($repositoryClass);
                break;
        }
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
                    [
                        new GenericTag('package', $this->getNamespaceName()),
                    ]
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
                [
                    [
                        'name'        => 'var',
                        'description' => $repositoryClass,
                    ]
                ]
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
                [
                    [
                        'name'        => 'param',
                        'description' => $repositoryClass . ' $' . $repositoryParam,
                    ]
                ]
            )
        );

        $setMethod->setBody($body);

        $this->addMethodFromGenerator($setMethod);
    }

    /**
     * Add form property
     *
     * @param $formClass
     */
    protected function addFormProperty($formClass)
    {
        $property = new PropertyGenerator(lcfirst($formClass));
        $property->addFlag(PropertyGenerator::FLAG_PRIVATE);
        $property->setDocBlock(
            new DocBlockGenerator(
                null,
                null,
                [
                    [
                        'name'        => 'var',
                        'description' => $formClass,
                    ]
                ]
            )
        );

        $this->addPropertyFromGenerator($property);
    }

    /**
     * Add form setter
     *
     * @param $formClass
     */
    protected function addFormSetter($formClass)
    {
        $formParam = lcfirst($formClass);

        $body = '$this->' . $formParam . ' = $' . $formParam . ';';

        $parameter = new ParameterGenerator($formParam, $formClass);

        $setMethodName = 'set' . $formClass;

        $setMethod = new MethodGenerator($setMethodName);
        $setMethod->addFlag(MethodGenerator::FLAG_PUBLIC);
        $setMethod->setParameter($parameter);
        $setMethod->setDocBlock(
            new DocBlockGenerator(
                'Set ' . $formClass,
                null,
                [
                    [
                        'name'        => 'param',
                        'description' => $formClass . ' $' . $formParam,
                    ]
                ]
            )
        );

        $setMethod->setBody($body);

        $this->addMethodFromGenerator($setMethod);
    }

    /**
     * Add indexAction() method for IndexController
     *
     * @param $repositoryClass
     */
    protected function addIndexControllerAction($repositoryClass)
    {
        $listParam = lcfirst($this->paramModule) . 'List';

        $body = [
            '$' . $listParam . ' = $this->' . lcfirst($repositoryClass) . '->getAllEntities();',
            '',
            '$viewModel = new ViewModel(',
            '    [',
            '        \'' . $listParam . '\' => $' . $listParam . ',',
            '    ]',
            ');',
            '',
            'return $viewModel;',
        ];

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $indexAction = new MethodGenerator('indexAction');
        $indexAction->addFlag(MethodGenerator::FLAG_PUBLIC);
        $indexAction->setDocBlock(
            new DocBlockGenerator(
                'Index action for IndexController',
                null,
                [
                    new ReturnTag(['ViewModel']),
                ]
            )
        );
        $indexAction->setBody($body);

        $this->addMethodFromGenerator($indexAction);
    }

    /**
     * Add indexAction() method for ShowController
     *
     * @param $repositoryClass
     */
    protected function addShowControllerAction($repositoryClass)
    {
        $entityParam    = lcfirst($this->paramModule) . 'Entity';
        $dashedParam    = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToDash'));
        $noFoundMessage = $dashedParam . '_message_' . $dashedParam . '_not_found';

        $body = [
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
            '    [',
            '        \'' . $entityParam . '\' => $' . $entityParam . ',',
            '    ]',
            ');',
            '',
            'return $viewModel;',
        ];

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $indexAction = new MethodGenerator('indexAction');
        $indexAction->addFlag(MethodGenerator::FLAG_PUBLIC);
        $indexAction->setDocBlock(
            new DocBlockGenerator(
                'Index action for ShowController',
                null,
                [
                    new ReturnTag(['ViewModel']),
                ]
            )
        );
        $indexAction->setBody($body);

        $this->addMethodFromGenerator($indexAction);
    }

    /**
     * Add indexAction() method for CreateController
     *
     * @param $repositoryClass
     */
    protected function addCreateControllerAction($repositoryClass)
    {
        // prepare some params
        $formParam        = lcfirst($this->paramModule) . 'DataForm';
        $underscoredParam = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToUnderscore'));
        $dashedParam      = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToDash'));
        $successMessage   = $dashedParam . '_message_' . $dashedParam . '_saving_success';
        $failedMessage    = $dashedParam . '_message_' . $dashedParam . '_saving_failed';
        $invalidMessage   = $dashedParam . '_message_' . $dashedParam . '_data_invalid';

        // prepare entity params
        $entityClass     = $this->paramModule . 'Entity';
        $entityNamespace = $this->entityModule . '\\' . $this->config['namespaceEntity'] . '\\' . $entityClass;
        $entityParam     = lcfirst($entityClass);

        $this->addUse($entityNamespace);

        $body = [
            '$' . $formParam . ' = $this->' . $formParam . ';',
            '',
            'if ($this->params()->fromPost(\'save_' . $underscoredParam . '\')) {',
            '    $' . $entityParam . ' = new ' . $entityClass . '();',
            '    ',
            '    $' . $formParam . '->setData($this->params()->fromPost());',
            '    $' . $formParam . '->bind($' . $entityParam . ');',
            '    ',
            '    if ($' . $formParam . '->isValid()) {',
            '        if ($this->' . lcfirst($repositoryClass) . '->saveEntity($' . $entityParam . ')) {',
            '            $this->flashMessenger()->addSuccessMessage(\'' . $successMessage . '\');',
            '            ',
            '            return $this->redirect()->toRoute(\'' . $dashedParam . '\');',
            '        } else {',
            '            $this->flashMessenger()->addErrorMessage(\'' . $failedMessage . '\');',
            '        }',
            '    } else {',
            '        $this->flashMessenger()->addErrorMessage(\'' . $invalidMessage . '\');',
            '    }',
            '    ',
            '    $' . $formParam . '->setData($this->params()->fromPost());',
            '}',
            '',
            '$viewModel = new ViewModel(',
            '    [',
            '        \'' . $formParam . '\' => $' . $formParam . ',',
            '    ]',
            ');',
            '',
            'return $viewModel;',
        ];

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $indexAction = new MethodGenerator('indexAction');
        $indexAction->addFlag(MethodGenerator::FLAG_PUBLIC);
        $indexAction->setDocBlock(
            new DocBlockGenerator(
                'Index action for CreateController',
                null,
                [
                    new ReturnTag(['ViewModel']),
                ]
            )
        );
        $indexAction->setBody($body);

        $this->addMethodFromGenerator($indexAction);
    }

    /**
     * Add indexAction() method for UpdateController
     *
     * @param $repositoryClass
     */
    protected function addUpdateControllerAction($repositoryClass)
    {
        // prepare some params
        $formParam        = lcfirst($this->paramModule) . 'DataForm';
        $underscoredParam = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToUnderscore'));
        $dashedParam      = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToDash'));
        $noFoundMessage   = $dashedParam . '_message_' . $dashedParam . '_not_found';
        $successMessage   = $dashedParam . '_message_' . $dashedParam . '_saving_success';
        $failedMessage    = $dashedParam . '_message_' . $dashedParam . '_saving_failed';
        $invalidMessage   = $dashedParam . '_message_' . $dashedParam . '_data_invalid';

        // prepare entity params
        $entityClass     = $this->paramModule . 'Entity';
        $entityNamespace = $this->entityModule . '\\' . $this->config['namespaceEntity'] . '\\' . $entityClass;
        $entityParam     = lcfirst($entityClass);

        $this->addUse($entityNamespace);

        $body = [
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
            '$' . $formParam . ' = $this->' . $formParam . ';',
            '$' . $formParam . '->bind($' . $entityParam . ');',
            '',
            'if ($this->params()->fromPost(\'save_' . $underscoredParam . '\')) {',
            '    $' . $formParam . '->setData($this->params()->fromPost());',
            '    ',
            '    if ($' . $formParam . '->isValid()) {',
            '        if ($this->' . lcfirst($repositoryClass) . '->saveEntity($' . $entityParam . ')) {',
            '            $this->flashMessenger()->addSuccessMessage(\'' . $successMessage . '\');',
            '            ',
            '            return $this->redirect()->toRoute(\'' . $dashedParam . '\');',
            '        } else {',
            '            $this->flashMessenger()->addErrorMessage(\'' . $failedMessage . '\');',
            '        }',
            '    } else {',
            '        $this->flashMessenger()->addErrorMessage(\'' . $invalidMessage . '\');',
            '    }',
            '    ',
            '    $' . $formParam . '->setData($this->params()->fromPost());',
            '}',
            '',
            '$viewModel = new ViewModel(',
            '    [',
            '        \'' . $entityParam . '\' => $' . $entityParam . ',',
            '        \'' . $formParam . '\' => $' . $formParam . ',',
            '    ]',
            ');',
            '',
            'return $viewModel;',
        ];

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $indexAction = new MethodGenerator('indexAction');
        $indexAction->addFlag(MethodGenerator::FLAG_PUBLIC);
        $indexAction->setDocBlock(
            new DocBlockGenerator(
                'Index action for UpdateController',
                null,
                [
                    new ReturnTag(['ViewModel']),
                ]
            )
        );
        $indexAction->setBody($body);

        $this->addMethodFromGenerator($indexAction);
    }

    /**
     * Add indexAction() method for DeleteController
     *
     * @param $repositoryClass
     */
    protected function addDeleteControllerAction($repositoryClass)
    {
        // prepare some params
        $formParam        = lcfirst($this->paramModule) . 'DeleteForm';
        $underscoredParam = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToUnderscore'));
        $dashedParam      = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToDash'));
        $noFoundMessage   = $dashedParam . '_message_' . $dashedParam . '_not_found';
        $deleteMessage    = $dashedParam . '_message_' . $dashedParam . '_deleting_possible';
        $successMessage   = $dashedParam . '_message_' . $dashedParam . '_deleting_success';
        $failedMessage    = $dashedParam . '_message_' . $dashedParam . '_deleting_failed';

        // prepare entity params
        $entityClass     = $this->paramModule . 'Entity';
        $entityParam     = lcfirst($entityClass);

        $body = [
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
            '$' . $formParam . ' = $this->' . $formParam . ';',
            '',
            'if ($this->params()->fromPost(\'delete_' . $underscoredParam . '\')) {',
            '    if ($this->' . lcfirst($repositoryClass) . '->removeEntity($' . $entityParam . ')) {',
            '        $this->flashMessenger()->addSuccessMessage(\'' . $successMessage . '\');',
            '        ',
            '        return $this->redirect()->toRoute(\'' . $dashedParam . '\');',
            '    } else {',
            '        $this->flashMessenger()->addErrorMessage(\'' . $failedMessage . '\');',
            '    }',
            '} else {',
            '    $this->flashMessenger()->addInfoMessage(\'' . $deleteMessage . '\');',
            '}',
            '',
            '$viewModel = new ViewModel(',
            '    [',
            '        \'' . $entityParam . '\' => $' . $entityParam . ',',
            '        \'' . $formParam . '\' => $' . $formParam . ',',
            '    ]',
            ');',
            '',
            'return $viewModel;',
        ];

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        $indexAction = new MethodGenerator('indexAction');
        $indexAction->addFlag(MethodGenerator::FLAG_PUBLIC);
        $indexAction->setDocBlock(
            new DocBlockGenerator(
                'Index action for DeleteController',
                null,
                [
                    new ReturnTag(['ViewModel']),
                ]
            )
        );
        $indexAction->setBody($body);

        $this->addMethodFromGenerator($indexAction);
    }


}