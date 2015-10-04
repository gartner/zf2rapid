<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use ZF2rapid\Generator\Crud\DeleteFormClassGenerator;
use ZF2rapid\Task\GenerateClass\AbstractGenerateClass;

/**
 * Class GenerateDeleteFormClass
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateDeleteFormClass extends AbstractGenerateClass
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $result = $this->generateClass(
            $this->params->applicationFormDir,
            $this->params->paramModule . 'DeleteForm',
            'form',
            new DeleteFormClassGenerator(
                $this->params->paramModule,
                $this->params->paramEntityModule,
                $this->params->loadedTables,
                $this->params->config
            )
        );


        if (!$result) {
            return 1;
        }

        return 0;
    }

}