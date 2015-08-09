<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapidTest\Task\Setup;

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
     * @var Route
     */
    private $route;

    /**
     * @var ConsoleInterface
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
        $this->route      = $this->getMockBuilder('ZF\Console\Route')
            ->setConstructorArgs(array('test', 'test'))
            ->getMock();
        $this->console    = $this->getMockBuilder(
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
        $this->parameters->set('projectModuleDir', '/path/to/module/dir/');

        $paramValueMap = array(
            array('module', null, 'testModule')
        );

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
            '/path/to/module/dir/testModule', $this->parameters->moduleDir
        );
        $this->assertTrue(defined('TEST_MODULE_MODULE_ROOT'));
        $this->assertEquals(
            '/path/to/module/dir/testModule', TEST_MODULE_MODULE_ROOT
        );

        $this->assertEquals(
            '/path/to/module/dir/testModule/config',
            $this->parameters->moduleConfigDir
        );
        $this->assertEquals(
            '/path/to/module/dir/testModule/src/' . 'testModule',
            $this->parameters->moduleSrcDir
        );
        $this->assertEquals(
            '/path/to/module/dir/testModule/view/test-module',
            $this->parameters->moduleViewDir
        );
    }

    /**
     *  Test modules param
     */
    public function testModulesParam()
    {
        $paramValueMap = array(
            array('modules', null, 'Application,Test')
        );

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
            'config', array('namespaceController' => 'Application\\Controller')
        );

        $paramValueMap = array(
            array('controller', null, 'TestController')
        );

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
        $paramValueMap = array(
            array('controllers', null, 'Test,Index')
        );

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

}
