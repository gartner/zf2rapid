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
use ZF2rapid\Task\Check\ValidatorExists;

/**
 * Class ValidatorExistsTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ValidatorExistsTest extends PHPUnit_Framework_TestCase
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
    private $zf2rapidValidatorDir;

    /**
     * @var string
     */
    private $zf2rapidValidatorName;

    /**
     * @var string
     */
    private $zf2rapidValidatorFile;

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

        $this->zf2rapidValidatorDir = sys_get_temp_dir()
            . '/zf2rapid-tests/';
        $this->zf2rapidValidatorName = 'TestValidator';
        $this->zf2rapidValidatorFile = 'TestValidator.php';

        if (!file_exists($this->zf2rapidValidatorDir)) {
            mkdir($this->zf2rapidValidatorDir);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists(
            $this->zf2rapidValidatorDir
            . $this->zf2rapidValidatorFile
        )) {
            unlink(
                $this->zf2rapidValidatorDir
                . $this->zf2rapidValidatorFile
            );
        }

        rmdir($this->zf2rapidValidatorDir);
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new ValidatorExists();

        $this->assertInstanceOf(
            'ZF2rapid\Task\Check\ValidatorExists', $task
        );
    }

    /**
     *  Test with validator not existing
     */
    public function testWithValidatorNotExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['validator']
        );

        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->exactly(2))->method('colorize');

        $this->parameters->set(
            'validatorDir', $this->zf2rapidValidatorDir
        );
        $this->parameters->set(
            'paramValidator', $this->zf2rapidValidatorName
        );

        $task = new ValidatorExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with validator existing
     */
    public function testWithValidatorExisting()
    {
        $this->console->expects($this->once())->method('writeTaskLine')->with(
            $this->equalTo('task_check_checking_file'),
            ['validator']
        );

        $this->console->expects($this->never())->method('writeFailLine')->with(
            $this->equalTo('task_check_file_exists_not_found')
        );

        $this->console->expects($this->never())->method('colorize');

        $this->parameters->set(
            'validatorDir', $this->zf2rapidValidatorDir
        );
        $this->parameters->set(
            'paramValidator', $this->zf2rapidValidatorName
        );

        file_put_contents(
            $this->zf2rapidValidatorDir
            . $this->zf2rapidValidatorFile,
            'class TestValidator {}'
        );

        $task = new ValidatorExists();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);
    }
}
