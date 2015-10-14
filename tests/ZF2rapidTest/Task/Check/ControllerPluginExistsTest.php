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
use ZF2rapid\Task\Check\ControllerPluginExists;

/**
 * Class ControllerPluginExistsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ControllerPluginExistsTest extends PHPUnit_Framework_TestCase
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
    private $zf2rapidControllerPluginDir;

    /**
     * @var string
     */
    private $zf2rapidControllerPluginName;

    /**
     * @var string
     */
    private $zf2rapidControllerPluginFile;

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

        $this->zf2rapidControllerPluginDir = sys_get_temp_dir()
            . '/zf2rapid-tests/';
        $this->zf2rapidControllerPluginName = 'TestPlugin';
        $this->zf2rapidControllerPluginFile = 'TestPlugin.php';

        if (!file_exists($this->zf2rapidControllerPluginDir)) {
            mkdir($this->zf2rapidControllerPluginDir);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists(
            $this->zf2rapidControllerPluginDir
            . $this->zf2rapidControllerPluginFile
        )) {
            unlink(
                $this->zf2rapidControllerPluginDir
                . $this->zf2rapidControllerPluginFile
            );
        }

        rmdir($this->zf2rapidControllerPluginDir);
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new ControllerPluginExists();

        $this->assertInstanceOf(
            'ZF2rapid\Task\Check\ControllerPluginExists', $task
        );
    }

    /**
     *  Test with controller plugin not existing
     */
    public function testWithControllerPluginNotExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['controller plugin']
        );

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->exactly(2))->method('colorize');

        $this->parameters->set(
            'controllerPluginDir', $this->zf2rapidControllerPluginDir
        );
        $this->parameters->set(
            'paramControllerPlugin', $this->zf2rapidControllerPluginName
        );

        $task = new ControllerPluginExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with controller plugin existing
     */
    public function testWithControllerPluginExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['controller plugin']
        );

        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set(
            'controllerPluginDir', $this->zf2rapidControllerPluginDir
        );
        $this->parameters->set(
            'paramControllerPlugin', $this->zf2rapidControllerPluginName
        );

        file_put_contents(
            $this->zf2rapidControllerPluginDir
            . $this->zf2rapidControllerPluginFile,
            'class TestPlugin {}'
        );

        $task = new ControllerPluginExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
