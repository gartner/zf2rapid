<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateClass;

use ZF2rapid\Generator\FormClassGenerator;

/**
 * Class GenerateDataFormClass
 *
 * @package ZF2rapid\Task\GenerateClass
 */
class GenerateFormClass extends AbstractGenerateClass
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $result = $this->generateClass(
            $this->params->formDir,
            $this->params->paramForm,
            'form',
            new FormClassGenerator($this->params->config)
        );

        return $result == true ? 0 : 1;
    }
}