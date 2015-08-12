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
 * Class BaseHydratorParam
 *
 * @package ZF2rapid\Task\Check
 */
class BaseHydratorParam extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        $result = $this->checkBaseHydrator(
            $this->params->paramBaseHydrator
        );

        return $result == true ? 0 : 1;
    }

    /**
     * @param string $baseHydrator
     *
     * @return bool
     */
    protected function checkBaseHydrator($baseHydrator = 'ClassMethods')
    {
        if (in_array(
            $baseHydrator,
            array(
                'ArraySerializable', 'ClassMethods', 'ObjectProperty',
                'Reflection'
            )
        )) {
            return true;
        }

        $hydratorClass = 'Zend\Stdlib\Hydrator\\' . $baseHydrator;

        $this->console->writeFailLine(
            'task_check_base_hydrator_param_unknown',
            array(
                $this->console->colorize($hydratorClass, Color::GREEN)
            )
        );

        return false;
    }
}