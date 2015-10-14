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
use ZF2rapid\Task\Check\HydratorExists;

/**
 * Class HydratorExistsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class HydratorExistsTest extends PHPUnit_Framework_TestCase
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
    private $zf2rapidHydratorDir;

    /**
     * @var string
     */
    private $zf2rapidHydratorName;

    /**
     * @var string
     */
    private $zf2rapidHydratorFile;

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

        $this->zf2rapidHydratorDir = sys_get_temp_dir()
            . '/zf2rapid-tests/';
        $this->zf2rapidHydratorName = 'TestHydrator';
        $this->zf2rapidHydratorFile = 'TestHydrator.php';

        if (!file_exists($this->zf2rapidHydratorDir)) {
            mkdir($this->zf2rapidHydratorDir);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists(
            $this->zf2rapidHydratorDir
            . $this->zf2rapidHydratorFile
        )) {
            unlink(
                $this->zf2rapidHydratorDir
                . $this->zf2rapidHydratorFile
            );
        }

        rmdir($this->zf2rapidHydratorDir);
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new HydratorExists();

        $this->assertInstanceOf(
            'ZF2rapid\Task\Check\HydratorExists', $task
        );
    }

    /**
     *  Test with hydrator not existing
     */
    public function testWithHydratorNotExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['hydrator']
        );

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->exactly(2))->method('colorize');

        $this->parameters->set(
            'hydratorDir', $this->zf2rapidHydratorDir
        );
        $this->parameters->set(
            'paramHydrator', $this->zf2rapidHydratorName
        );

        $task = new HydratorExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with hydrator existing
     */
    public function testWithHydratorExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['hydrator']
        );

        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set(
            'hydratorDir', $this->zf2rapidHydratorDir
        );
        $this->parameters->set(
            'paramHydrator', $this->zf2rapidHydratorName
        );

        file_put_contents(
            $this->zf2rapidHydratorDir
            . $this->zf2rapidHydratorFile,
            'class TestHydrator {}'
        );

        $task = new HydratorExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
