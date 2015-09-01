<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Module;

use ZF2rapid\Console\Console;
use ZF2rapid\Task\AbstractTask;

/**
 * Class ChooseApplicationConfigFile
 *
 * @package ZF2rapid\Task\Module
 */
class ChooseApplicationConfigFile extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        if (!$this->params->paramActivation) {
            return 0;
        }

        // set filter dirs
        $filterDirs = array('..', '.', 'autoload');

        // get existing config files
        $configFiles = array_values(
            array_diff(scandir($this->params->projectConfigDir), $filterDirs)
        );

        // set indention
        $spaces = Console::INDENTION_PROMPT_OPTIONS;

        // add option keys
        foreach ($configFiles as $key => $file) {
            $configFiles[$spaces . chr(ord('a') + $key)] = $file;
            unset($configFiles[$key]);
        }

        $chosenConfigFile = $this->console->writeSelectPrompt(
            'task_module_choose_config_file_prompt',
            $configFiles
        );

        // set skeleton application name
        $this->params->configFile = $configFiles[$spaces . $chosenConfigFile];

        return 0;
    }
}