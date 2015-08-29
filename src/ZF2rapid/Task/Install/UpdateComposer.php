<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Install;

use ZF2rapid\Task\AbstractTask;

/**
 * Class UpdateComposer
 *
 * @package ZF2rapid\Task\Install
 */
class UpdateComposer extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // start self-update if composer is available
        if (file_exists($this->params->projectPath . '/composer.phar')) {
            // output message
            $this->console->writeTaskLine(
                'task_install_update_composer_self_updating'
            );

            // run composer self-update
            exec(
                'php ' . $this->params->projectPath
                . '/composer.phar self-update -q',
                $output,
                $return
            );
        } else {
            // check for composer in tmp root
            if (!file_exists($this->params->tmpDir . '/composer.phar')) {
                // set installer
                $installer = $this->params->tmpDir . '/composer_installer.php';

                // check for composer installer in tmp root
                if (!file_exists($installer)) {
                    // get latest composer installer
                    file_put_contents(
                        $installer,
                        '?>' . file_get_contents(
                            'https://getcomposer.org/installer'
                        )
                    );
                }

                // output message
                $this->console->writeTaskLine(
                    'task_install_update_composer_installer'
                );

                // run composer installer
                exec(
                    'php ' . $installer . ' --install-dir '
                    . $this->params->tmpDir,
                    $output,
                    $return
                );
            }

            // copy composer.phar from tmp root
            copy(
                $this->params->tmpDir . '/composer.phar',
                $this->params->projectPath . '/composer.phar'
            );
        }

        // change file rights
        chmod($this->params->projectPath . '/composer.phar', 0755);

        return 0;
    }

}