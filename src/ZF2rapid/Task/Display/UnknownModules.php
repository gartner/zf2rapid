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
use ZF2rapid\Task\AbstractTask;

/**
 * Class UnknownModules
 *
 * @package ZF2rapid\Task\Display
 */
class UnknownModules extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check for unknown modules
        if (empty($this->params->paramModuleList)) {
            return 0;
        }

        // get unknown modules
        $unknownModules = array_diff(
            $this->params->paramModuleList,
            array_keys($this->params->loadedModules)
        );

        // sort
        sort($unknownModules);

        // check for unknown modules
        if (count($unknownModules) > 0) {
            // output done message
            $this->console->writeWarnLine(
                'task_display_unknown_modules_not_exist',
                [
                    $this->console->colorize(
                        $this->params->workingPath, Color::GREEN
                    ),
                ]
            );

            foreach ($unknownModules as $moduleName) {
                $this->console->writeListItemLine(
                    'task_display_unknown_modules_module',
                    [
                        $this->console->colorize(
                            $moduleName, Color::GREEN
                        ),
                    ]
                );
            }

            $this->console->writeLine();
        }

        return 0;
    }

}