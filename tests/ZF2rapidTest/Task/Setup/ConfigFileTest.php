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
use ZF2rapid\Task\Setup\ConfigFile;

/**
 * Class ConfigFileTest
 *
 * @package ZF2rapidTest\Task\Setup
 */
class ConfigFileTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Route|PHPUnit_Framework_MockObject_MockObject
     */
    private $route;

    /**
     * @var ConsoleInterface
     */
    private $console;

    /**
     * @var Parameters
     */
    private $parameters;

    /**
     * @var string
     */
    private $zf2rapidFileDir;

    /**
     * @var string
     */
    private $zf2rapidFileName;

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

        $this->zf2rapidFileDir  = sys_get_temp_dir() . '/zf2rapid-tests/';
        $this->zf2rapidFileName = 'zfrapid2.json';

        if (!file_exists($this->zf2rapidFileDir)) {
            mkdir($this->zf2rapidFileDir);
        }
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        if (file_exists($this->zf2rapidFileDir . $this->zf2rapidFileName)) {
            unlink($this->zf2rapidFileDir . $this->zf2rapidFileName);
        }

        rmdir($this->zf2rapidFileDir);
    }

    /**
     *  Test instantiation of class
     */
    public function testInstantiation()
    {
        $task = new ConfigFile();

        $this->assertInstanceOf('ZF2rapid\Task\Setup\ConfigFile', $task);
    }

    /**
     *  Test no project path is set
     */
    public function testNoProjectPathSet()
    {
        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_setup_config_file_no_project_path')
        );

        $task = new ConfigFile();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with unwritable project path t
     */
    public function testUnwritableProjectPathSet()
    {
        $this->console->expects($this->once())->method('writeFailLine')->with(
            $this->equalTo('task_setup_config_file_not_writable')
        );

        $this->parameters->set(
            'projectPath', '/path/to/project/'
        );

        $task = new ConfigFile();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(1, $result);
    }

    /**
     *  Test with non existing file
     */
    public function testWithNonExistingFile()
    {
        $this->parameters->set(
            'projectPath', $this->zf2rapidFileDir
        );

        $task = new ConfigFile();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);

        $expectedConfig = array(
            'configFileFormat'          => 'php',
            'flagAddDocBlocks'          => 'true',
            'fileDocBlockText'          => 'ZF2 Application built by ZF2rapid',
            'fileDocBlockCopyright'     => '(c) 2015 John Doe',
            'fileDocBlockLicense'       => 'http://opensource.org/licenses/MIT The MIT License (MIT)',
            'namespaceController'       => 'Controller',
            'namespaceControllerPlugin' => 'Controller\\Plugin',
            'namespaceViewHelper'       => 'View\\Helper',
            'namespaceFilter'           => 'Filter',
            'namespaceValidator'        => 'Validator',
            'namespaceInputFilter'      => 'InputFilter',
            'namespaceForm'             => 'Form',
            'namespaceHydrator'         => 'Hydrator',
        );

        $this->assertEquals($expectedConfig, $this->parameters->config);

        $this->assertFileExists(
            $this->zf2rapidFileDir . $this->zf2rapidFileName
        );

        $this->assertEquals(
            $expectedConfig,
            json_decode(
                file_get_contents(
                    $this->zf2rapidFileDir . $this->zf2rapidFileName
                ),
                true
            )
        );
    }

    /**
     *  Test with existing file
     */
    public function testWithExistingFile()
    {
        $this->parameters->set(
            'projectPath', $this->zf2rapidFileDir
        );

        $expectedConfig = array(
            'foo' => 'bar',
            'one' => 'two',
        );

        file_put_contents(
            $this->zf2rapidFileDir . $this->zf2rapidFileName,
            json_encode(
                $expectedConfig,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        $task = new ConfigFile();

        $result = $task($this->route, $this->console, $this->parameters);

        $this->assertEquals(0, $result);

        $this->assertEquals($expectedConfig, $this->parameters->config);

        $this->assertFileExists(
            $this->zf2rapidFileDir . $this->zf2rapidFileName
        );

        $this->assertEquals(
            $expectedConfig,
            json_decode(
                file_get_contents(
                    $this->zf2rapidFileDir . $this->zf2rapidFileName
                ),
                true
            )
        );
    }
}
