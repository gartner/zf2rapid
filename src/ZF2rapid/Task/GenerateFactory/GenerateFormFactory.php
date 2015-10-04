<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\GenerateFactory;

/**
 * Class GenerateDataFormFactory
 *
 * @package ZF2rapid\Task\GenerateFactory
 */
class GenerateFormFactory extends AbstractGenerateFactory
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $result = $this->generateFactory(
            $this->params->formDir,
            $this->params->paramForm,
            'form',
            $this->params->config['namespaceForm'],
            'formElementManager'
        );

        return $result == true ? 0 : 1;
    }
}