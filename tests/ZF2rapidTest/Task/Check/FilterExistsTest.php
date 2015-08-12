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
use ZF2rapid\Task\Check\FilterExists;

/**
 * Class FilterExistsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class FilterExistsTest extends PHPUnit_Framework_TestCase
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
    private $zf2rapidFilterDir;

    /**
     * @var string
     */
    private $zf2rapidFilterName;

    /**
     * @var string
     */
    private $zf2rapidFilterFile;

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

        $this->zf2rapidFilterDir = sys_get_temp_dir()
            . '/zf2rapid-tests/';
        $this->zf2rapidFilterName = 'TestFilter';
        $this->zf2rapidFilterFile = 'TestFilter.php';

        if (!file_exists($this->zf2rapidFilterDir)) {
            mkdir($this->zf2rapidFilterDir);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists(
            $this->zf2rapidFilterDir
            . $this->zf2rapidFilterFile
        )) {
            unlink(
                $this->zf2rapidFilterDir
                . $this->zf2rapidFilterFile
            );
        }

        rmdir($this->zf2rapidFilterDir);
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new FilterExists();

        $this->assertInstanceOf(
            'ZF2rapid\Task\Check\FilterExists', $task
        );
    }

    /**
     *  Test with filter not existing
     */
    public function testWithFilterNotExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            array('filter')
        );

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->exactly(2))->method('colorize');

        $this->parameters->set(
            'filterDir', $this->zf2rapidFilterDir
        );
        $this->parameters->set(
            'paramFilter', $this->zf2rapidFilterName
        );

        $task = new FilterExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with filter existing
     */
    public function testWithFilterExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            array('filter')
        );

        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set(
            'filterDir', $this->zf2rapidFilterDir
        );
        $this->parameters->set(
            'paramFilter', $this->zf2rapidFilterName
        );

        file_put_contents(
            $this->zf2rapidFilterDir
            . $this->zf2rapidFilterFile,
            'class TestFilter {}'
        );

        $task = new FilterExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
