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
use Zend\Code\Generator\BodyGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Code\Generator\DocBlock\Tag\ParamTag;
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\ValueGenerator;

/**
 * Class ModuleClassGenerator
 *
 * @package ZF2rapid\Generator
 */
class ModuleClassGenerator extends ClassGenerator
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var string
     */
    protected $moduleRootConstant;

    /**
     * @param null|string $moduleName
     * @param null|string $moduleRootConstant
     * @param array       $config
     */
    public function __construct(
        $moduleName, $moduleRootConstant, array $config = []
    ) {
        // set config data
        $this->config             = $config;
        $this->moduleRootConstant = $moduleRootConstant;

        // call parent constructor
        parent::__construct('Module', $moduleName);

        // add methods
        $this->addInitMethod();
        $this->addGetConfigMethod();
        $this->addGetAutoloaderConfigMethod();
        $this->addClassDocBlock($moduleName);
    }

    /**
     * Add a class doc block
     *
     * @param $moduleName
     */
    protected function addClassDocBlock($moduleName)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    'Module ' . $moduleName,
                    'Sets up and configures the ' . $moduleName . ' module',
                    [
                        new GenericTag('package', $moduleName),
                    ]
                )
            );
        }
    }

    /**
     * Generate the init() method
     */
    protected function addInitMethod()
    {
        // set action body
        $bodyContent = [
            'if (!defined(\'' . $this->moduleRootConstant . '\')) {',
            '    define(\'' . $this->moduleRootConstant . '\', realpath(__DIR__));',
            '}',
        ];
        $bodyContent = implode(AbstractGenerator::LINE_FEED, $bodyContent);

        // create method body
        $body = new BodyGenerator();
        $body->setContent($bodyContent);

        // create method
        $method = new MethodGenerator();
        $method->setName('init');
        $method->setBody($body->generate());
        $method->setParameter(
            new ParameterGenerator('manager', 'ModuleManagerInterface')
        );

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Init module',
                    'Initialize module on loading',
                    [
                        new ParamTag(
                            'manager', ['ModuleManagerInterface']
                        ),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
        $this->addUse('Zend\ModuleManager\Feature\InitProviderInterface');
        $this->addUse('Zend\ModuleManager\ModuleManagerInterface');
        $this->setImplementedInterfaces(
            array_merge(
                $this->getImplementedInterfaces(),
                ['InitProviderInterface']
            )
        );
    }

    /**
     * Generate the getConfig() method
     *
     * @return void
     */
    protected function addGetConfigMethod()
    {
        // create method body
        $body = new BodyGenerator();
        $body->setContent(
            'include __DIR__ . \'/config/module.config.php\''
        );

        // create method
        $method = new MethodGenerator();
        $method->setName('getConfig');
        $method->setBody(
            'return ' . $body->generate() . ';'
            . AbstractGenerator::LINE_FEED
        );

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Get module configuration',
                    'Reads the module configuration from the config/ directory',
                    [
                        new ReturnTag(
                            ['array'], 'module configuration data'
                        ),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
        $this->addUse('Zend\ModuleManager\Feature\ConfigProviderInterface');
        $this->setImplementedInterfaces(
            array_merge(
                $this->getImplementedInterfaces(),
                ['ConfigProviderInterface']
            )
        );
    }

    /**
     * Generate the getAutoloaderConfig() method
     *
     * @return void
     */
    protected function addGetAutoloaderConfigMethod()
    {
        // set array data
        $array = [
            'Zend\Loader\ClassMapAutoloader' => [
                '__NAMESPACE__ => __DIR__ . \'/autoload_classmap.php\'',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    '__NAMESPACE__ => __DIR__ . \'/src/\' . __NAMESPACE__',
                ],
            ],
        ];

        // create method body
        $body = new ValueGenerator();
        $body->initEnvironmentConstants();
        $body->setValue($array);

        // create method
        $method = new MethodGenerator();
        $method->setName('getAutoloaderConfig');
        $method->setBody(
            'return ' . $body->generate() . ';'
            . AbstractGenerator::LINE_FEED
        );

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Get module autoloader configuration',
                    'Sets up the module autoloader configuration',
                    [
                        new ReturnTag(
                            ['array'], 'module autoloader configuration'
                        ),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
        $this->addUse(
            'Zend\ModuleManager\Feature\AutoloaderProviderInterface'
        );
        $this->setImplementedInterfaces(
            array_merge(
                $this->getImplementedInterfaces(),
                ['AutoloaderProviderInterface']
            )
        );
    }


}