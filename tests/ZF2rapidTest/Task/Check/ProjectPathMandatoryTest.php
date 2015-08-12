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
use ZF2rapid\Task\Check\ProjectPathMandatory;

/**
 * Class ProjectPathMandatoryTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ProjectPathMandatoryTest extends PHPUnit_Framework_TestCase
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
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new ProjectPathMandatory();

        $this->assertInstanceOf(
            'ZF2rapid\Task\Check\ProjectPathMandatory', $task
        );
    }

    /**
     *  Test with no project path set
     */
    public function testWithNoProjectPathSet()
    {
        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_project_path_mandatory')
        );

        $task = new ProjectPathMandatory();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with project path set
     */
    public function testWithProjectPathSet()
    {
        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_project_path_mandatory')
        );

        $this->parameters->set(
            'projectPath', '/path/to/project/'
        );

        $task = new ProjectPathMandatory();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
