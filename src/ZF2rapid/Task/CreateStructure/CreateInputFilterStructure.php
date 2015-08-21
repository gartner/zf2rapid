<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\CreateStructure;

/**
 * Class CreateInputFilterStructure
 *
 * @package ZF2rapid\Task\InputFilter
 */
class CreateInputFilterStructure extends AbstractCreateStructureTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $result = $this->createDirectory(
            $this->params->inputFilterDir, 'Input filter'
        );

        return $result == true ? 0 : 1;
    }

}