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
use ZF2rapid\Task\Check\BaseHydratorParam;

/**
 * Class BaseHydratorParamTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class BaseHydratorParamTest extends PHPUnit_Framework_TestCase
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
        $task = new BaseHydratorParam();

        $this->assertInstanceOf('ZF2rapid\Task\Check\BaseHydratorParam', $task);
    }

    /**
     *  Test with unknown hydrators
     */
    public function testWithUnknownHydrator()
    {
        $this->console->expects($this->exactly(4))->method('writeFailLine')->with(
            $this->equalTo('task_check_base_hydrator_param_unknown')
        );

        $this->console->expects($this->exactly(4))->method('colorize');

        $unknownHydrators = array(
            'Unknown', 'ClassObjects', 'ObjectMethod', 'Whatever'
        );

        foreach ($unknownHydrators as $hydrator) {
            $this->parameters->set('paramBaseHydrator', $hydrator);

            $task = new BaseHydratorParam();

            $result = $task($this->route, $this->console, $this->parameters);

            $this->assertEquals(1, $result);
        }
    }

    /**
     *  Test with controller existing
     */
    public function testWithKnownHydrators()
    {
        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_base_hydrator_param_unknown')
        );

        $this->console->expects($this->never())->method('colorize');

        $knownHydrators = array(
            'ArraySerializable', 'ClassMethods', 'ObjectProperty', 'Reflection'
        );

        foreach ($knownHydrators as $hydrator) {
            $this->parameters->set('paramBaseHydrator', $hydrator);

            $task = new BaseHydratorParam();

            $result = $task($this->route, $this->console, $this->parameters);

            $this->assertEquals(0, $result);
        }
    }
}
