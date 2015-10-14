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
use ZF2rapid\Task\Check\ViewHelperExists;

/**
 * Class ViewHelperExistsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ViewHelperExistsTest extends PHPUnit_Framework_TestCase
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
    private $zf2rapidViewHelperDir;

    /**
     * @var string
     */
    private $zf2rapidViewHelperName;

    /**
     * @var string
     */
    private $zf2rapidViewHelperFile;

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

        $this->zf2rapidViewHelperDir = sys_get_temp_dir()
            . '/zf2rapid-tests/';
        $this->zf2rapidViewHelperName = 'TestViewHelper';
        $this->zf2rapidViewHelperFile = 'TestViewHelper.php';

        if (!file_exists($this->zf2rapidViewHelperDir)) {
            mkdir($this->zf2rapidViewHelperDir);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists(
            $this->zf2rapidViewHelperDir
            . $this->zf2rapidViewHelperFile
        )) {
            unlink(
                $this->zf2rapidViewHelperDir
                . $this->zf2rapidViewHelperFile
            );
        }

        rmdir($this->zf2rapidViewHelperDir);
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new ViewHelperExists();

        $this->assertInstanceOf(
            'ZF2rapid\Task\Check\ViewHelperExists', $task
        );
    }

    /**
     *  Test with view helper not existing
     */
    public function testWithViewHelperNotExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['view helper']
        );

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->exactly(2))->method('colorize');

        $this->parameters->set(
            'viewHelperDir', $this->zf2rapidViewHelperDir
        );
        $this->parameters->set(
            'paramViewHelper', $this->zf2rapidViewHelperName
        );

        $task = new ViewHelperExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with view helper existing
     */
    public function testWithViewHelperExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['view helper']
        );

        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set(
            'viewHelperDir', $this->zf2rapidViewHelperDir
        );
        $this->parameters->set(
            'paramViewHelper', $this->zf2rapidViewHelperName
        );

        file_put_contents(
            $this->zf2rapidViewHelperDir
            . $this->zf2rapidViewHelperFile,
            'class TestViewHelper {}'
        );

        $task = new ViewHelperExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
