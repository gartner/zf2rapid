<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Display;

use Zend\Console\ColorInterface as Color;
use Zend\Version\Version;
use ZF2rapid\Task\AbstractTask;

/**
 * Class ZFVersion
 *
 * @package ZF2rapid\Task\Display
 */
class ZFVersion extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $projectPath = $this->params->projectPath;

        if (is_dir(
            $projectPath . '/vendor/zendframework/zendframework/library'
        )) {
            $library = '/vendor/zendframework/zendframework/library';
        } elseif (is_dir(
            $projectPath . '/vendor/zendframework/zend-version'
        )) {
            $library = '/vendor/zendframework/zend-version';
        } elseif (is_dir($projectPath . '/vendor/ZF2/library')) {
            $library = '/vendor/ZF2/library';
        } else {
            $library = false;
        }

        // set version file name
        $versionFile = $projectPath . $library . '/Zend/Version/Version.php';

        // check for individual Zend\Version repository
        if (!file_exists($versionFile)) {
            $versionFile = $projectPath . $library . '/src/Version.php';
        }

        // load version file
        require_once $versionFile;

        // output success message
        $this->console->writeTaskLine(
            'task_display_zfversion_path',
            array(
                $this->console->colorize(
                    $projectPath . $library, Color::GREEN
                )
            )
        );

        $this->console->writeIndentedLine(
            'task_display_zfversion_version',
            array(
                $this->console->colorize(
                    $projectPath, Color::GREEN
                ),
                $this->console->colorize(
                    Version::VERSION, Color::BLUE
                )
            )
        );

        return 0;
    }

}