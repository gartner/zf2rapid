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
use ZF2rapid\Task\Check\ControllerExists;

/**
 * Class ControllerExistsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ControllerExistsTest extends PHPUnit_Framework_TestCase
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
    private $zf2rapidControllerDir;

    /**
     * @var string
     */
    private $zf2rapidControllerName;

    /**
     * @var string
     */
    private $zf2rapidControllerFile;

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

        $this->zf2rapidControllerDir = sys_get_temp_dir() . '/zf2rapid-tests/';
        $this->zf2rapidControllerName = 'Test';
        $this->zf2rapidControllerFile = 'TestController.php';

        if (!file_exists($this->zf2rapidControllerDir)) {
            mkdir($this->zf2rapidControllerDir);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists(
            $this->zf2rapidControllerDir . $this->zf2rapidControllerFile
        )) {
            unlink(
                $this->zf2rapidControllerDir . $this->zf2rapidControllerFile
            );
        }

        rmdir($this->zf2rapidControllerDir);
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new ControllerExists();

        $this->assertInstanceOf('ZF2rapid\Task\Check\ControllerExists', $task);
    }

    /**
     *  Test with controller not existing
     */
    public function testWithControllerNotExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['controller']
        );

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->exactly(2))->method('colorize');

        $this->parameters->set('controllerDir', $this->zf2rapidControllerDir);
        $this->parameters->set(
            'paramController', $this->zf2rapidControllerName
        );

        $task = new ControllerExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with controller existing
     */
    public function testWithControllerExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['controller']
        );

        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set('controllerDir', $this->zf2rapidControllerDir);
        $this->parameters->set(
            'paramController', $this->zf2rapidControllerName
        );

        file_put_contents(
            $this->zf2rapidControllerDir . $this->zf2rapidControllerFile,
            'class TestController {}'
        );

        $task = new ControllerExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
