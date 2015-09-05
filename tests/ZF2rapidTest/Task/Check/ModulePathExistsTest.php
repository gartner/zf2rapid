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
use ZF2rapid\Task\Check\ModulePathExists;

/**
 * Class ModulePathExistsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ModulePathExistsTest extends PHPUnit_Framework_TestCase
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
     * @var string
     */
    private $zf2rapidModuleDir;

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

        $this->zf2rapidModuleDir = sys_get_temp_dir() . '/zf2rapid-tests/';

        if (!file_exists($this->zf2rapidModuleDir)) {
            mkdir($this->zf2rapidModuleDir);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        rmdir($this->zf2rapidModuleDir);
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new ModulePathExists();

        $this->assertInstanceOf('ZF2rapid\Task\Check\ModulePathExists', $task);
    }

    /**
     *  Test with module path not existing
     */
    public function testWithModulePathNotExisting()
    {
        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_working_path_not_exists')
        );

        $this->console->expects($this->once())->method('colorize');

        $this->parameters->set(
            'projectModuleDir', '/path/to/module/'
        );

        $task = new ModulePathExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with module path existing
     */
    public function testWithModulePathExisting()
    {
        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_working_path_not_exists')
        );

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set(
            'projectModuleDir', $this->zf2rapidModuleDir
        );

        $task = new ModulePathExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
