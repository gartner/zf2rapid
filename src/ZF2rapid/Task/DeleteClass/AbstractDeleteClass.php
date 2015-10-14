<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\DeleteClass;

use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;

/**
 * Class AbstractDeleteClass
 *
 * @package ZF2rapid\Task\DeleteClass
 */
abstract class AbstractDeleteClass extends AbstractTask
{
    /**
     * Delete class
     *
     * @param string $classDir
     * @param string $className
     * @param string $classText
     *
     * @return bool
     */
    public function deleteClass($classDir, $className, $classText)
    {
        // output message
        $this->console->writeTaskLine(
            'Deleting ' . $classText . ' file...'
        );

        // set file
        $file = $classDir . '/' . $className . '.php';

        // check if factory file exists
        if (!file_exists($file)) {
            $this->console->writeFailLine(
                'task_delete_class_not_exists',
                [
                    $classText,
                    $this->console->colorize(
                        $this->params->paramController, Color::GREEN
                    ),
                    $this->console->colorize(
                        $this->params->paramModule, Color::GREEN
                    )
                ]
            );

            return false;
        }

        // delete file
        unlink($file);

        return true;
    }
}
