<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use Prophecy\Doubler\ClassPatch\SplFileInfoPatch;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class DeleteModule
 *
 * @package ZF2rapid\Task\Module
 */
class DeleteModule extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine(
            'task_module_delete_module_deleting',
            array(
                $this->console->colorize(
                    $this->params->paramModule, Color::GREEN
                )
            )
        );

        // output confirm prompt
        $deleteConfirmation = $this->console->writeConfirmPrompt(
            'task_module_delete_module_prompt_1',
            'task_module_delete_module_yes_answer',
            'task_module_delete_module_no_answer'
        );

        if (!$deleteConfirmation) {
            // output success message
            $this->console->writeOkLine(
                'task_module_delete_module_not_deleted',
                array(
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->projectModuleDir, Color::GREEN
                    )
                )
            );

            return 1;
        }

        // output confirm prompt
        $deleteConfirmation = $this->console->writeConfirmPrompt(
            'task_module_delete_module_prompt_2',
            'task_module_delete_module_yes_answer',
            'task_module_delete_module_no_answer'
        );

        if (!$deleteConfirmation) {
            // output success message
            $this->console->writeOkLine(
                'task_module_delete_module_not_deleted',
                array(
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->projectModuleDir, Color::GREEN
                    )
                )
            );

            return 1;
        }

        $this->console->writeLine();

        // delete module directory and its content
        $directoryIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->params->moduleDir, RecursiveDirectoryIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::CHILD_FIRST
        );

        /** @var \SplFileInfo $item */
        foreach ($directoryIterator as $item) {
            if ($item->isDir()) {
                rmdir($item);
            } else {
                unlink($item);
            }
        }

        rmdir($this->params->moduleDir);

        return 0;
    }
}