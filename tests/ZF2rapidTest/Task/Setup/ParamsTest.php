<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapidTest\Task\Setup;

use PHPUnit_Framework_MockObject_MockObject;
use PHPUnit_Framework_TestCase;
use Zend\Stdlib\Parameters;
use ZF\Console\Route;
use ZF2rapid\Console\ConsoleInterface;
use ZF2rapid\Task\Setup\Params;

/**
 * Class ParamsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ParamsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Route|PHPUnit_Framework_MockObject_MockObject
     */
    private $route;

    /**
     * @var ConsoleInterface|PHPUnit_Framework_MockObject_MockObject
     */
    private $console;

    /**
     * @var Parameters
     */
    private $parameters;

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->route = $this->getMockBuilder('ZF\Console\Route')
            ->setConstructorArgs(['test', 'test'])
            ->getMock();

        $this->console = $this->getMockBuilder(
            'ZF2rapid\Console\ConsoleInterface'
        )->getMock();

        $this->parameters = new Parameters();
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new Params();

        $this->assertInstanceOf('ZF2rapid\Task\Setup\Params', $task);
    }

    /**
     *  Test result type of invocation
     */
    public function testInvocation()
    {
        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }

    /**
     *  Test module param
     */
    public function testModuleParam()
    {
        $this->parameters->set('workingPath', '/path/to/module/dir');

        $paramValueMap = [
            ['module', null, 'testModule']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals('testModule', $this->parameters->paramModule);
        $this->assertEquals(
            'TEST_MODULE_MODULE_ROOT', $this->parameters->moduleRootConstant
        );

        $this->assertEquals(
            '/path/to/module/dir/module/testModule', $this->parameters->moduleDir
        );
        $this->assertTrue(defined('TEST_MODULE_MODULE_ROOT'));
        $this->assertEquals(
            '/path/to/module/dir/module/testModule',
            constant('TEST_MODULE_MODULE_ROOT')
        );

        $this->assertEquals(
            '/path/to/module/dir/module/testModule/config',
            $this->parameters->moduleConfigDir
        );
        $this->assertEquals(
            '/path/to/module/dir/module/testModule/src/' . 'testModule',
            $this->parameters->moduleSrcDir
        );
        $this->assertEquals(
            '/path/to/module/dir/module/testModule/view/test-module',
            $this->parameters->moduleViewDir
        );
    }

    /**
     *  Test modules param
     */
    public function testModulesParam()
    {
        $paramValueMap = [
            ['modules', null, 'Application,Test']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'Application,Test', $this->parameters->paramModuleList
        );
    }

    /**
     *  Test controller param
     */
    public function testControllerParam()
    {
        $this->parameters->set(
            'moduleSrcDir', '/path/to/module/dir/testModule/src'
        );
        $this->parameters->set(
            'moduleViewDir', '/path/to/module/dir/testModule/view'
        );
        $this->parameters->set(
            'config', ['namespaceController' => 'Application\\Controller']
        );

        $paramValueMap = [
            ['controller', null, 'TestController']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'TestController', $this->parameters->paramController
        );

        $this->assertEquals(
            '/path/to/module/dir/testModule/src/Application/Controller',
            $this->parameters->controllerDir
        );
        $this->assertEquals(
            '/path/to/module/dir/testModule/view/test-controller',
            $this->parameters->controllerViewDir
        );
    }

    /**
     *  Test controllers param
     */
    public function testControllersParam()
    {
        $paramValueMap = [
            ['controllers', null, 'Test,Index']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'Test,Index', $this->parameters->paramControllerList
        );
    }

    /**
     *  Test action param
     */
    public function testActionParam()
    {
        $paramValueMap = [
            ['action', null, 'create']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'create', $this->parameters->paramAction
        );
    }

    /**
     *  Test controllerPlugin param
     */
    public function testControllerPluginParam()
    {
        $this->parameters->set(
            'moduleSrcDir', '/path/to/module/dir/testModule/src'
        );
        $this->parameters->set(
            'config',
            [
                'namespaceControllerPlugin' => 'Application\\Controller\\Plugin'
            ]
        );

        $paramValueMap = [
            ['controllerPlugin', null, 'PluginName']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'PluginName', $this->parameters->paramControllerPlugin
        );

        $this->assertEquals(
            '/path/to/module/dir/testModule/src/Application/Controller/Plugin',
            $this->parameters->controllerPluginDir
        );
    }

    /**
     *  Test viewHelper param
     */
    public function testViewHelperParam()
    {
        $this->parameters->set(
            'moduleSrcDir', '/path/to/module/dir/testModule/src'
        );
        $this->parameters->set(
            'config',
            [
                'namespaceViewHelper' => 'View\\Helper'
            ]
        );

        $paramValueMap = [
            ['viewHelper', null, 'HelperName']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'HelperName', $this->parameters->paramViewHelper
        );

        $this->assertEquals(
            '/path/to/module/dir/testModule/src/View/Helper',
            $this->parameters->viewHelperDir
        );
    }

    /**
     *  Test filter param
     */
    public function testFilterParam()
    {
        $this->parameters->set(
            'moduleSrcDir', '/path/to/module/dir/testModule/src'
        );
        $this->parameters->set(
            'config',
            [
                'namespaceFilter' => 'Model\\Filter'
            ]
        );

        $paramValueMap = [
            ['filter', null, 'FilterName']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'FilterName', $this->parameters->paramFilter
        );

        $this->assertEquals(
            '/path/to/module/dir/testModule/src/Model/Filter',
            $this->parameters->filterDir
        );
    }

    /**
     *  Test validator param
     */
    public function testValidatorParam()
    {
        $this->parameters->set(
            'moduleSrcDir', '/path/to/module/dir/testModule/src'
        );
        $this->parameters->set(
            'config',
            [
                'namespaceValidator' => 'Model\\Validator'
            ]
        );

        $paramValueMap = [
            ['validator', null, 'ValidatorName']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'ValidatorName', $this->parameters->paramValidator
        );

        $this->assertEquals(
            '/path/to/module/dir/testModule/src/Model/Validator',
            $this->parameters->validatorDir
        );
    }

    /**
     *  Test inputFilter param
     */
    public function testInputFilterParam()
    {
        $this->parameters->set(
            'moduleSrcDir', '/path/to/module/dir/testModule/src'
        );
        $this->parameters->set(
            'config',
            [
                'namespaceInputFilter' => 'Model\\InputFilter'
            ]
        );

        $paramValueMap = [
            ['inputFilter', null, 'InputFilterName']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'InputFilterName', $this->parameters->paramInputFilter
        );

        $this->assertEquals(
            '/path/to/module/dir/testModule/src/Model/InputFilter',
            $this->parameters->inputFilterDir
        );
    }

    /**
     *  Test form param
     */
    public function testFormParam()
    {
        $this->parameters->set(
            'moduleSrcDir', '/path/to/module/dir/testModule/src'
        );
        $this->parameters->set(
            'config',
            [
                'namespaceForm' => 'Application\\Form'
            ]
        );

        $paramValueMap = [
            ['form', null, 'FormName']
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'FormName', $this->parameters->paramForm
        );

        $this->assertEquals(
            '/path/to/module/dir/testModule/src/Application/Form',
            $this->parameters->formDir
        );
    }

    /**
     *  Test hydrator param
     */
    public function testHydratorParam()
    {
        $this->parameters->set(
            'moduleSrcDir', '/path/to/module/dir/testModule/src'
        );
        $this->parameters->set(
            'config',
            [
                'namespaceHydrator' => 'Model\\Hydrator'
            ]
        );

        $paramValueMap = [
            ['hydrator', null, 'HydratorName'],
            ['baseHydrator', null, 'BaseHydrator'],
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals(
            'HydratorName', $this->parameters->paramHydrator
        );

        $this->assertEquals(
            'BaseHydrator', $this->parameters->paramBaseHydrator
        );

        $this->assertEquals(
            '/path/to/module/dir/testModule/src/Model/Hydrator',
            $this->parameters->hydratorDir
        );
    }

    /**
     *  Test no-factory param
     */
    public function testNoFactoryParamIsSet()
    {
        $paramValueMap = [
            ['no-factory', null, true]
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertFalse($this->parameters->paramFactory);
    }

    /**
     *  Test no-factory param
     */
    public function testNoFactoryParamNotSet()
    {
        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertTrue($this->parameters->paramFactory);
    }

    /**
     *  Test strict param
     */
    public function testStrictParam()
    {
        $paramValueMap = [
            ['strict', null, true]
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertTrue($this->parameters->paramStrict);
    }

    /**
     *  Test removeFactory param
     */
    public function testRemoveFactoryParam()
    {
        $paramValueMap = [
            ['removeFactory', null, true]
        ];

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new Params();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertTrue($this->parameters->paramRemoveFactory);
    }

}
