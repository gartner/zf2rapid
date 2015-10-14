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
use ZF2rapid\Task\CreateStructure\CreateModuleStructure;

/**
 * Class CreateModuleStructureTest
 *
 * @package ZF2rapidTest\Task\Setupr
 */
class CreateModuleStructureTest extends PHPUnit_Framework_TestCase
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
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists($this->zf2rapidModuleDir . '/config')) {
            rmdir($this->zf2rapidModuleDir . '/config');
        }

        if (file_exists($this->zf2rapidModuleDir . '/src')) {
            rmdir($this->zf2rapidModuleDir . '/src');
        }

        if (file_exists($this->zf2rapidModuleDir . '/view')) {
            rmdir($this->zf2rapidModuleDir . '/view');
        }

        if (file_exists($this->zf2rapidModuleDir)) {
            rmdir($this->zf2rapidModuleDir);
        }
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new CreateModuleStructure();

        $this->assertInstanceOf(
            'ZF2rapid\Task\CreateStructure\CreateModuleStructure', $task
        );
    }

    /**
     *  Test with module dir existing
     */
    public function testWithExistingModuleDir()
    {
        if (!file_exists($this->zf2rapidModuleDir)) {
            mkdir($this->zf2rapidModuleDir);
        }

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_create_structure_module_dir_exists')
        );

        $this->console->expects($this->once())->method('colorize');

        $this->parameters->set(
            'moduleDir', $this->zf2rapidModuleDir
        );

        $task = new CreateModuleStructure();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with module dir creating failed
     */
    public function testWithModuleDirCreatingFailed()
    {
        if (!file_exists($this->zf2rapidModuleDir)) {
            mkdir($this->zf2rapidModuleDir, 0444);
        }

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_create_structure_module_dir_not_created')
        );

        $this->console->expects($this->once())->method('colorize');

        $this->parameters->set(
            'moduleDir', $this->zf2rapidModuleDir . 'test/'
        );

        $task = new CreateModuleStructure();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with module dir creating succeed
     */
    public function testWithModuleDirCreatingSucceed()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo(
                'task_create_structure_module_root_created'
            )
        );

        $this->console->expects($this->once())->method('colorize');

        $this->parameters->set(
            'moduleDir', $this->zf2rapidModuleDir
        );
        $this->parameters->set(
            'moduleConfigDir', $this->zf2rapidModuleDir . 'config/'
        );
        $this->parameters->set(
            'moduleSrcDir', $this->zf2rapidModuleDir . 'src/'
        );
        $this->parameters->set(
            'moduleViewDir', $this->zf2rapidModuleDir . 'view/'
        );

        $task = new CreateModuleStructure();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
        $this->assertFileExists($this->zf2rapidModuleDir);
        $this->assertFileExists($this->zf2rapidModuleDir . 'config/');
        $this->assertFileExists($this->zf2rapidModuleDir . 'src/');
        $this->assertFileExists($this->zf2rapidModuleDir . 'view/');
    }

}
