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
use ZF2rapid\Task\Check\ProjectPathEmpty;

/**
 * Class ProjectPathEmptyTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ProjectPathEmptyTest extends PHPUnit_Framework_TestCase
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
    private $zf2rapidProjectPath;

    /**
     * @var string
     */
    private $zf2rapidAllowedFile;

    /**
     * @var string
     */
    private $zf2rapidNotAllowedFile;

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

        $this->zf2rapidProjectPath    = sys_get_temp_dir() . '/zf2rapid-tests/';
        $this->zf2rapidAllowedFile    = 'zfrapid2.json';
        $this->zf2rapidNotAllowedFile = 'zfrapid2.txt';
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists(
            $this->zf2rapidProjectPath . $this->zf2rapidAllowedFile
        )) {
            unlink($this->zf2rapidProjectPath . $this->zf2rapidAllowedFile);
        }

        if (file_exists(
            $this->zf2rapidProjectPath . $this->zf2rapidNotAllowedFile
        )) {
            unlink($this->zf2rapidProjectPath . $this->zf2rapidNotAllowedFile);
        }

        if (file_exists($this->zf2rapidProjectPath)) {
            rmdir($this->zf2rapidProjectPath);
        }
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new ProjectPathEmpty();

        $this->assertInstanceOf(
            'ZF2rapid\Task\Check\ProjectPathEmpty', $task
        );
    }

    /**
     *  Test with project path not exists
     */
    public function testWithProjectPathNotExists()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_working_path_created')
        );

        $this->console->expects($this->once())->method('colorize');

        $this->parameters->set('workingPath', $this->zf2rapidProjectPath);

        $this->assertFileNotExists($this->zf2rapidProjectPath);

        $task = new ProjectPathEmpty();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);

        $this->assertFileExists($this->zf2rapidProjectPath);
    }

    /**
     *  Test with project path empty
     */
    public function testWithProjectPathEmpty()
    {
        if (!file_exists($this->zf2rapidProjectPath)) {
            mkdir($this->zf2rapidProjectPath);
        }

        $this->console->expects($this->never())->method('writeTaskLine');

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set('workingPath', $this->zf2rapidProjectPath);

        $this->assertFileExists($this->zf2rapidProjectPath);

        $task = new ProjectPathEmpty();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);

        $this->assertFileExists($this->zf2rapidProjectPath);
    }

    /**
     *  Test with project path allowed file
     */
    public function testWithProjectPathAllowedFile()
    {
        if (!file_exists($this->zf2rapidProjectPath)) {
            mkdir($this->zf2rapidProjectPath);
        }

        if (!file_exists(
            $this->zf2rapidProjectPath . $this->zf2rapidAllowedFile
        )
        ) {
            file_put_contents(
                $this->zf2rapidProjectPath . $this->zf2rapidAllowedFile, 'test'
            );
        }

        $this->console->expects($this->never())->method('writeTaskLine');

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set('workingPath', $this->zf2rapidProjectPath);

        $this->assertFileExists($this->zf2rapidProjectPath);

        $task = new ProjectPathEmpty();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);

        $this->assertFileExists($this->zf2rapidProjectPath);
    }


    /**
     *  Test with project path not allowed file
     */
    public function testWithProjectPathNotAllowedFile()
    {
        if (!file_exists($this->zf2rapidProjectPath)) {
            mkdir($this->zf2rapidProjectPath);
        }

        if (!file_exists(
            $this->zf2rapidProjectPath . $this->zf2rapidNotAllowedFile
        )
        ) {
            file_put_contents(
                $this->zf2rapidProjectPath . $this->zf2rapidNotAllowedFile,
                'test'
            );
        }

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_working_path_not_empty')
        );

        $this->console->expects($this->once())->method('colorize');

        $this->parameters->set('workingPath', $this->zf2rapidProjectPath);

        $this->assertFileExists($this->zf2rapidProjectPath);

        $task = new ProjectPathEmpty();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);

        $this->assertFileExists($this->zf2rapidProjectPath);
    }
}
