<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateClass;

use ZF2rapid\Generator\RepositoryClassGenerator;

/**
 * Class GenerateRepositoryClass
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateRepositoryClass extends AbstractGenerateClass
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $result = $this->generateClass(
            $this->params->repositoryDir,
            $this->params->repositoryClassName,
            'repository',
            new RepositoryClassGenerator(
                $this->params->config, $this->params->currentTableObject
            )
        );

        return $result == true ? 0 : 1;
    }

}