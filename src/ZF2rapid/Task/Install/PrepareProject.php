<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Install;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class PrepareProject
 *
 * @package ZF2rapid\Task\Install
 */
class PrepareProject extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine('task_install_prepare_project_preparing');

        // change data file rights
        $directoryIterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $this->params->projectPath . '/data'
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($directoryIterator as $item) {
            chmod($item, 0777);
        }

        // change public assets vendor file rights if exists
        if (file_exists($this->params->projectPath . '/public/assets/vendor')) {
            $directoryIterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $this->params->projectPath . '/public/assets/vendor'
                ),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($directoryIterator as $item) {
                chmod($item, 0777);
            }
        }

        // set ZendDeveloperTools configuration file source and target
        $fileSource = $this->params->projectPath
            . '/vendor/zendframework/zend-developer-tools'
            . '/config/zenddevelopertools.local.php.dist';
        $fileTarget = $this->params->projectPath
            . '/config/autoload/zenddevelopertools.local.php';

        // copy ZendDeveloperTools configuration if exists
        if (file_exists($fileSource)) {
            copy($fileSource, $fileTarget);
        }

        // set autoload local config file source and target
        $fileSource = $this->params->projectPath
            . '/config/autoload/local.php.dist';
        $fileTarget = $this->params->projectPath
            . '/config/autoload/local.php';

        // copy autoload local configuration if exists
        if (file_exists($fileSource)) {
            rename($fileSource, $fileTarget);
        }

        return 0;
    }

}