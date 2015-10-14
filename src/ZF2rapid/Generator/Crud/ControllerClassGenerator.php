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
     * @var string
     */
    protected $entityClass;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param null|string $controllerName
     * @param null|string $paramModule
     * @param null|string $entityModule
     * @param null|string $entityClass
     * @param array       $config
     */
    public function __construct($controllerName, $paramModule, $entityModule, $entityClass, array $config = [])
    {
        // set config data
        $this->controllerName = $controllerName;
        $this->paramModule    = $paramModule;
        $this->entityModule   = $entityModule;
        $this->entityClass    = $entityClass;
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
        $repositoryClass     = str_replace('Entity', '', $this->entityClass) . 'Repository';
        $repositoryNamespace = $this->entityModule . '\\' . $this->config['namespaceRepository'] . '\\'
            . $repositoryClass;

        // prepare form params
        if (in_array($this->controllerName, ['Create', 'Update'])) {
            $formClass     = str_replace('Entity', '', $this->entityClass) . 'DataForm';
            $formNamespace = $this->paramModule . '\\' . $this->config['namespaceForm'] . '\\' . $formClass;

            $this->addUse($formNamespace);
        } elseif (in_array($this->controllerName, ['Delete'])) {
            $formClass     = str_replace('Entity', '', $this->entityClass) . 'DeleteForm';
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
                $this->addCreateControllerAction($repositoryClass, $formClass);
                break;

            case 'Update':
                $this->addFormProperty($formClass);
                $this->addFormSetter($formClass);
                $this->addUpdateControllerAction($repositoryClass, $formClass);
                break;

            case 'Delete':
                $this->addFormProperty($formClass);
                $this->addFormSetter($formClass);
                $this->addDeleteControllerAction($repositoryClass, $formClass);
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
        $listParam = lcfirst(str_replace('Entity', '', $this->entityClass)) . 'List';

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
        $entityParam    = lcfirst($this->entityClass);
        $dashedParam    = strtolower(StaticFilter::execute(str_replace('Entity', '', $this->entityClass), 'WordCamelCaseToDash'));
        $underscoredModule     = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToUnderscore'));
        $dashedModule     = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToDash'));
        $noFoundMessage = $underscoredModule . '_message_' . $dashedParam . '_not_found';

        $body = [
            '$id = $this->params()->fromRoute(\'id\');',
            '',
            'if (!$id) {',
            '    $this->flashMessenger()->addErrorMessage(\'' . $noFoundMessage . '\');',
            '    ',
            '    return $this->redirect()->toRoute(\'' . $dashedModule . '\');',
            '}',
            '',
            '$' . $entityParam . ' = $this->' . lcfirst($repositoryClass) . '->getEntityById($id);',
            '',
            'if (!$' . $entityParam . ') {',
            '    $this->flashMessenger()->addErrorMessage(\'' . $noFoundMessage . '\');',
            '    ',
            '    return $this->redirect()->toRoute(\'' . $dashedModule . '\');',
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
     * @param $formClass
     */
    protected function addCreateControllerAction($repositoryClass, $formClass)
    {
        // prepare some params
        $formParam        = lcfirst($formClass);
        $underscoredParam = strtolower(StaticFilter::execute(str_replace('Entity', '', $this->entityClass), 'WordCamelCaseToUnderscore'));
        $dashedParam      = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToDash'));
        $dashedModule     = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToUnderscore'));
        $successMessage   = $dashedModule . '_message_' . $underscoredParam . '_saving_success';
        $failedMessage    = $dashedModule . '_message_' . $underscoredParam . '_saving_failed';
        $invalidMessage   = $dashedModule . '_message_' . $underscoredParam . '_data_invalid';

        // prepare entity params
        $entityNamespace = $this->entityModule . '\\' . $this->config['namespaceEntity'] . '\\' . $this->entityClass;
        $entityParam     = lcfirst($this->entityClass);

        $this->addUse($entityNamespace);

        $body = [
            '$' . $formParam . ' = $this->' . $formParam . ';',
            '',
            'if ($this->params()->fromPost(\'save_' . $underscoredParam . '\')) {',
            '    $' . $entityParam . ' = new ' . $this->entityClass . '();',
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
     * @param $formClass
     */
    protected function addUpdateControllerAction($repositoryClass, $formClass)
    {
        // prepare some params
        $formParam        = lcfirst($formClass);
        $underscoredParam = strtolower(StaticFilter::execute(str_replace('Entity', '', $this->entityClass), 'WordCamelCaseToUnderscore'));
        $dashedParam      = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToDash'));
        $dashedModule     = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToUnderscore'));
        $noFoundMessage   = $dashedModule . '_message_' . $underscoredParam . '_not_found';
        $successMessage   = $dashedModule . '_message_' . $underscoredParam . '_saving_success';
        $failedMessage    = $dashedModule . '_message_' . $underscoredParam . '_saving_failed';
        $invalidMessage   = $dashedModule . '_message_' . $underscoredParam . '_data_invalid';

        // prepare entity params
        $entityNamespace = $this->entityModule . '\\' . $this->config['namespaceEntity'] . '\\' . $this->entityClass;
        $entityParam     = lcfirst($this->entityClass);

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
     * @param $formClass
     */
    protected function addDeleteControllerAction($repositoryClass, $formClass)
    {
        // prepare some params
        $formParam        = lcfirst($formClass);
        $underscoredParam = strtolower(StaticFilter::execute(str_replace('Entity', '', $this->entityClass), 'WordCamelCaseToUnderscore'));
        $dashedParam      = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToDash'));
        $dashedModule     = strtolower(StaticFilter::execute($this->paramModule, 'WordCamelCaseToUnderscore'));
        $noFoundMessage   = $dashedModule . '_message_' . $underscoredParam . '_not_found';
        $deleteMessage    = $dashedModule . '_message_' . $underscoredParam . '_deleting_possible';
        $successMessage   = $dashedModule . '_message_' . $underscoredParam . '_deleting_success';
        $failedMessage    = $dashedModule . '_message_' . $underscoredParam . '_deleting_failed';

        // prepare entity params
        $entityParam = lcfirst($this->entityClass);

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