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
 * Class ProjectPath
 *
 * @package ZF2rapid\Task\Setup
 */
class ProjectPath extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // set project path if set
        if ($this->route->getMatchedParam('path')) {
            $projectPath = realpath($this->route->getMatchedParam('path'));

            if (!$projectPath) {
                $projectPath = $this->route->getMatchedParam('path');
            }

            $this->params->projectPath = $projectPath;

            $this->params->applicationRootConstant = 'APPLICATION_ROOT';

            // define constant temporarily
            if (!defined($this->params->applicationRootConstant)) {
                define(
                    $this->params->applicationRootConstant,
                    $this->params->projectPath
                );
            }

            if ($projectPath) {
                $this->params->projectModuleDir = $projectPath
                    . DIRECTORY_SEPARATOR . 'module';

                $this->params->projectConfigDir = $projectPath
                    . DIRECTORY_SEPARATOR . 'config';
            }
        }

        return 0;
    }

}