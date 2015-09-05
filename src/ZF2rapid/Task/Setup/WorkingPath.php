<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Setup;

use ZF2rapid\Task\AbstractTask;

/**
 * Class WorkingPath
 *
 * @package ZF2rapid\Task\Setup
 */
class WorkingPath extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // set project path if set
        if ($this->route->getMatchedParam('workingPath')) {
            $workingPath = realpath($this->route->getMatchedParam('workingPath'));

            if (!$workingPath) {
                $workingPath = $this->route->getMatchedParam('workingPath');
            }

            $this->params->workingPath = $workingPath;

            $this->params->applicationRootConstant = 'APPLICATION_ROOT';

            // define constant temporarily
            if (!defined($this->params->applicationRootConstant)) {
                define(
                    $this->params->applicationRootConstant,
                    $this->params->workingPath
                );
            }

        }

        return 0;
    }

}