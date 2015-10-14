<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Command\Create;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Command\AbstractCommand;

/**
 * Class CreateProject
 *
 * @package ZF2rapid\Command\Create
 */
class CreateProject extends AbstractCommand
{
    /**
     * @var array
     */
    protected $tasks
        = [
            'ZF2rapid\Task\Setup\WorkingPath',
            'ZF2rapid\Task\Setup\Params',
            'ZF2rapid\Task\Check\ProjectPathMandatory',
            'ZF2rapid\Task\Check\ProjectPathEmpty',
            'ZF2rapid\Task\Install\ChooseSkeletonApplication',
            'ZF2rapid\Task\Install\DownloadSkeletonApplication',
            'ZF2rapid\Task\Install\UnzipSkeletonApplication',
            'ZF2rapid\Task\Install\UpdateComposer',
            'ZF2rapid\Task\Install\RunComposer',
            'ZF2rapid\Task\Install\DownloadGenerators',
            'ZF2rapid\Task\Install\PrepareProject',
        ];

    /**
     * Start the command
     */
    public function startCommand()
    {
        // start output
        $this->console->writeGoLine('command_create_project_start');
    }

    /**
     * Stop the command
     */
    public function stopCommand()
    {
        $this->console->writeOkLine('command_create_project_stop');

        $this->console->writeTodoLine(
            'command_create_project_working_dir',
            [
                $this->console->colorize(
                    $this->params->workingPath, Color::GREEN
                )
            ]
        );
    }
}
