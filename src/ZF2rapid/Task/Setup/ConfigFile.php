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
 * Class ConfigFile
 *
 * @package ZF2rapid\Task\Setup
 */
class ConfigFile extends AbstractTask
{
    /**
     * @var string
     */
    const CONFIG_FILE_NAME = 'zfrapid2.json';

    /**
     * @var array
     */
    protected $configFileDefaults
        = array(
            'configFileFormat'          => 'php',
            'flagAddDocBlocks'          => 'true',
            'fileDocBlockText'          => 'ZF2 Application built by ZF2rapid',
            'fileDocBlockCopyright'     => '(c) 2015 John Doe',
            'fileDocBlockLicense'       => 'http://opensource.org/licenses/MIT The MIT License (MIT)',
            'namespaceController'       => 'Controller',
            'namespaceControllerPlugin' => 'Controller\\Plugin',
            'namespaceViewHelper'       => 'View\\Helper',
            'namespaceFilter'           => 'Filter',
            'namespaceValidator'        => 'Validator',
            'namespaceInputFilter'      => 'InputFilter',
            'namespaceForm'             => 'Form',
            'namespaceHydrator'         => 'Hydrator',
            'namespaceEntity'           => 'Entity',
            'namespaceTableGateway'     => 'TableGateway',
            'namespaceRepository'       => 'Repository',
        );

    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // check if file is writable
        if (!isset($this->params->workingPath)) {
            $this->console->writeFailLine(
                'task_setup_config_file_no_working_path'
            );

            return 1;
        }

        // set config file name
        $configFile = $this->params->workingPath . '/' . self::CONFIG_FILE_NAME;

        // check config file existence
        if (file_exists(($configFile))) {
            // load config from file
            $this->params->config = json_decode(
                file_get_contents($configFile), true
            );

            // check if new config keys were added
            if (array_keys($this->params->config) != array_keys(
                    $this->configFileDefaults
                )
            ) {
                // merge config
                $this->params->config = array_merge(
                    $this->configFileDefaults, $this->params->config
                );

                // write merged config data to file
                file_put_contents(
                    $configFile,
                    json_encode(
                        $this->params->config,
                        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                    )
                );
            }

            return 0;
        }

        // get config defaults
        $this->params->set('config', $this->configFileDefaults);

        // check if file is writable
        if (!is_writable($this->params->workingPath)) {
            $this->console->writeFailLine(
                'task_setup_config_file_not_writable'
            );

            return 1;
        }

        // write config data to file
        file_put_contents(
            $configFile,
            json_encode(
                $this->params->config,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            )
        );

        return 0;
    }

}