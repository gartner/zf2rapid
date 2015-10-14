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
use ZF2rapid\Task\Check\ModuleExists;

/**
 * Class ModuleExistsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ModuleExistsTest extends PHPUnit_Framework_TestCase
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
            ->setConstructorArgs(['test', 'test'])
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
        $task = new ModuleExists();

        $this->assertInstanceOf('ZF2rapid\Task\Check\ModuleExists', $task);
    }

    /**
     *  Test with module not existing
     */
    public function testWithModuleNotExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_module')
        );

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_module_not_exists')
        );

        $this->console->expects($this->exactly(2))->method('colorize');

        $this->parameters->set('moduleDir', '/path/to/module/');
        $this->parameters->set('paramModule', 'Module');

        $task = new ModuleExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with module existing
     */
    public function testWithModuleExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_module')
        );

        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_module_not_exists')
        );

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set('moduleDir', $this->zf2rapidModuleDir);
        $this->parameters->set('paramModule', 'Module');

        $task = new ModuleExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
