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
use ZF2rapid\Task\Setup\ProjectPath;

/**
 * Class ProjectPathTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ProjectPathTest extends PHPUnit_Framework_TestCase
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
            ->setConstructorArgs(array('test', 'test'))
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
        $task = new ProjectPath();

        $this->assertInstanceOf('ZF2rapid\Task\Setup\ProjectPath', $task);
    }

    /**
     *  Test result type of invocation
     */
    public function testInvocation()
    {
        $task = new ProjectPath();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }

    /**
     *  Test path param
     */
    public function testPathParamNonExistingPath()
    {
        $paramValueMap = array(
            array('path', null, '/path/to/project')
        );

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new ProjectPath();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals('/path/to/project', $this->parameters->projectPath);

        $this->assertEquals(
            'APPLICATION_ROOT', $this->parameters->applicationRootConstant
        );

        $this->assertTrue(defined('APPLICATION_ROOT'));
        $this->assertEquals('/path/to/project', constant('APPLICATION_ROOT'));

        $this->assertEquals(
            '/path/to/project/module',
            $this->parameters->projectModuleDir
        );
        $this->assertEquals(
            '/path/to/project/config',
            $this->parameters->projectConfigDir
        );
    }

    /**
     *  Test path param
     */
    public function testPathParamExistingPath()
    {
        $paramValueMap = array(
            array('path', null, '/tmp')
        );

        $this->route->method('getMatchedParam')->will(
            $this->returnValueMap($paramValueMap)
        );

        $task = new ProjectPath();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertEquals('/tmp', $this->parameters->projectPath);

        $this->assertEquals(
            '/tmp/module',
            $this->parameters->projectModuleDir
        );
        $this->assertEquals(
            '/tmp/config',
            $this->parameters->projectConfigDir
        );
    }
}
