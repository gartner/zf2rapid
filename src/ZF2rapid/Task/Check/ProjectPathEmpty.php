<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Check;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class ProjectPathEmpty
 *
 * @package ZF2rapid\Task\Check
 */
class ProjectPathEmpty extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check if project path exists
        if (is_dir($this->params->workingPath)) {

            // scan directory
            $dir = scandir($this->params->workingPath);

            // ignore current and parent path
            unset($dir[array_search('.', $dir)]);
            unset($dir[array_search('..', $dir)]);
            unset($dir[array_search('zfrapid2.json', $dir)]);

            // check empty path
            if (count($dir) > 0) {
                // stop with error
                $this->console->writeFailLine(
                    'task_check_working_path_not_empty',
                    [
                        $this->console->colorize(
                            $this->params->workingPath, Color::GREEN
                        )
                    ]
                );

                return 1;
            }
        } else {
            // create new project path if it does not exists
            mkdir($this->params->workingPath, 0777, true);

            $this->console->writeTaskLine(
                'task_check_working_path_created',
                [
                    $this->console->colorize(
                        realpath($this->params->workingPath), Color::GREEN
                    )
                ]
            );
        }

        return 0;
    }

}