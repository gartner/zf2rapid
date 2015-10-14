<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Install;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Console\Console;
use ZF2rapid\Task\AbstractTask;

/**
 * Class ChooseSkeletonApplication
 *
 * @package ZF2rapid\Task\Install
 */
class ChooseSkeletonApplication extends AbstractTask
{
    /**
     * URL to Zend Framework 2 Skeleton Application
     */
    const ZF2_SKELETON_URL = 'https://github.com/zendframework/ZendSkeletonApplication/archive/master.zip';

    /**
     * URL to ZF2rapid Skeleton Application
     */
    const ZF2RAPID_SKELETON_URL = 'https://github.com/ZFrapid/zf2rapid-skeleton/archive/master.zip';

    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // set indention
        $spaces = Console::INDENTION_PROMPT_OPTIONS;

        // define options for select prompt
        $options = [
            $spaces . 'a' => 'task_install_choose_skeleton_official',
            $spaces . 'b' => 'task_install_choose_skeleton_zf2rapid',
            $spaces . 'c' => 'task_install_choose_skeleton_custom',
        ];

        $skeletonAnswer = $this->console->writeSelectPrompt(
            'task_install_choose_skeleton_prompt',
            $options
        );

        // set url depending on select prompt answer
        switch ($skeletonAnswer) {
            case 'b':
                $this->params->skeletonUrl = self::ZF2RAPID_SKELETON_URL;
                break;

            case 'c':
                $this->params->skeletonUrl = $this->console->writeLinePrompt(
                    'task_install_choose_skeleton_enter_url'
                );
                break;

            default:
                $this->params->skeletonUrl = self::ZF2_SKELETON_URL;
        }

        // set skeleton application name
        $this->params->skeletonName = $options[$spaces . $skeletonAnswer];

        // write which skeleton application was chosen
        $this->console->writeTaskLine(
            'task_install_choose_skeleton_installation',
            [
                $this->console->colorize(
                    $this->params->skeletonName, Color::GREEN
                )
            ]
        );

        return 0;
    }

}