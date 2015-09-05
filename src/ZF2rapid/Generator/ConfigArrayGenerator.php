<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Generator;

use Zend\Code\Generator\ValueGenerator;
use Zend\Stdlib\Parameters;

/**
 * Class ConfigArrayGenerator
 *
 * @package ZF2rapid\Generator
 */
class ConfigArrayGenerator extends ValueGenerator
{
    /**
     * @var Parameters
     */
    protected $params;

    /**
     * @var array
     */
    protected $ignoreKeys
        = array(
            'controllers',
            'router',
        );

    /**
     * @param array      $configData
     * @param Parameters $params
     */
    public function __construct(array $configData = array(), Parameters $params)
    {
        // set params
        $this->params = $params;

        // reset constant compilation
        $configData = $this->resetConfigDirCompilation($configData);
        $configData = $this->resetTemplateMapCompilation($configData);

        // call parent constructor
        parent::__construct($configData, ValueGenerator::TYPE_ARRAY);

        // init constants
        $this->initEnvironmentConstants();
        $this->addConstant($this->params->moduleRootConstant);
        $this->addConstant($this->params->applicationRootConstant);
    }

    /**
     * @param array $configData
     * @param int   $level
     *
     * @return array
     */
    protected function resetConfigDirCompilation(
        array $configData, $level = 1
    ) {
        // loop config data
        foreach ($configData as $key => $value) {
            // ignore configuration keys on first level
            if ($level == 1 && in_array($key, $this->ignoreKeys)) {
                continue;
            }

            // check for array
            if (is_array($value)) {
                $configData[$key] = $this->resetConfigDirCompilation(
                    $value, $level + 1
                );
            } else {
                if (strpos($value, $this->params->moduleConfigDir) === 0) {
                    $configData[$key] = '__DIR__ . \''
                        . str_replace(
                            $this->params->moduleConfigDir, '', $value
                        )
                        . '\'';
                } elseif (strpos($value, $this->params->moduleDir) === 0) {
                    $configData[$key] = $this->params->moduleRootConstant
                        . ' . \''
                        . str_replace($this->params->moduleDir, '', $value)
                        . '\'';
                } elseif (strpos($value, $this->params->workingPath) === 0) {
                    $configData[$key] = $this->params->applicationRootConstant
                        . ' . \''
                        . str_replace($this->params->workingPath, '', $value)
                        . '\'';
                }
            }
        }

        return $configData;
    }

    /**
     * @param array $configData
     *
     * @return array
     */
    protected function resetTemplateMapCompilation(array $configData)
    {
        if (!isset($configData['view_manager'])) {
            return $configData;
        }

        if (!isset($configData['view_manager']['template_map'])) {
            return $configData;
        }

        $templateMap = $this->params->moduleDir . '/template_map.php';

        if (!file_exists($templateMap)) {
            return $configData;
        }

        $configData['view_manager']['template_map'] = 'include '
            . $this->params->moduleRootConstant . ' . \''
            . '/template_map.php'
            . '\'';;

        return $configData;
    }
}