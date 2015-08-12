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
use ZF2rapid\Task\Check\InputFilterExists;

/**
 * Class InputFilterExistsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class InputFilterExistsTest extends PHPUnit_Framework_TestCase
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
    private $zf2rapidInputFilterDir;

    /**
     * @var string
     */
    private $zf2rapidInputFilterName;

    /**
     * @var string
     */
    private $zf2rapidInputFilterFile;

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

        $this->zf2rapidInputFilterDir = sys_get_temp_dir()
            . '/zf2rapid-tests/';
        $this->zf2rapidInputFilterName = 'TestInputFilter';
        $this->zf2rapidInputFilterFile = 'TestInputFilter.php';

        if (!file_exists($this->zf2rapidInputFilterDir)) {
            mkdir($this->zf2rapidInputFilterDir);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists(
            $this->zf2rapidInputFilterDir
            . $this->zf2rapidInputFilterFile
        )) {
            unlink(
                $this->zf2rapidInputFilterDir
                . $this->zf2rapidInputFilterFile
            );
        }

        rmdir($this->zf2rapidInputFilterDir);
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new InputFilterExists();

        $this->assertInstanceOf(
            'ZF2rapid\Task\Check\InputFilterExists', $task
        );
    }

    /**
     *  Test with input filter not existing
     */
    public function testWithInputFilterNotExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            array('input filter')
        );

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->exactly(2))->method('colorize');

        $this->parameters->set(
            'inputFilterDir', $this->zf2rapidInputFilterDir
        );
        $this->parameters->set(
            'paramInputFilter', $this->zf2rapidInputFilterName
        );

        $task = new InputFilterExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with input filter existing
     */
    public function testWithInputFilterExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            array('input filter')
        );

        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set(
            'inputFilterDir', $this->zf2rapidInputFilterDir
        );
        $this->parameters->set(
            'paramInputFilter', $this->zf2rapidInputFilterName
        );

        file_put_contents(
            $this->zf2rapidInputFilterDir
            . $this->zf2rapidInputFilterFile,
            'class TestInputFilter {}'
        );

        $task = new InputFilterExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
