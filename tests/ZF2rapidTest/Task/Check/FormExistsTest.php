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
use ZF2rapid\Task\Check\FormExists;

/**
 * Class FormExistsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class FormExistsTest extends PHPUnit_Framework_TestCase
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
    private $zf2rapidFormDir;

    /**
     * @var string
     */
    private $zf2rapidFormName;

    /**
     * @var string
     */
    private $zf2rapidFormFile;

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

        $this->zf2rapidFormDir = sys_get_temp_dir()
            . '/zf2rapid-tests/';
        $this->zf2rapidFormName = 'TestForm';
        $this->zf2rapidFormFile = 'TestForm.php';

        if (!file_exists($this->zf2rapidFormDir)) {
            mkdir($this->zf2rapidFormDir);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists(
            $this->zf2rapidFormDir
            . $this->zf2rapidFormFile
        )) {
            unlink(
                $this->zf2rapidFormDir
                . $this->zf2rapidFormFile
            );
        }

        rmdir($this->zf2rapidFormDir);
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new FormExists();

        $this->assertInstanceOf(
            'ZF2rapid\Task\Check\FormExists', $task
        );
    }

    /**
     *  Test with form not existing
     */
    public function testWithFormNotExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['form']
        );

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->exactly(2))->method('colorize');

        $this->parameters->set(
            'formDir', $this->zf2rapidFormDir
        );
        $this->parameters->set(
            'paramForm', $this->zf2rapidFormName
        );

        $task = new FormExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with form existing
     */
    public function testWithFormExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['form']
        );

        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set(
            'formDir', $this->zf2rapidFormDir
        );
        $this->parameters->set(
            'paramForm', $this->zf2rapidFormName
        );

        file_put_contents(
            $this->zf2rapidFormDir
            . $this->zf2rapidFormFile,
            'class TestForm {}'
        );

        $task = new FormExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
