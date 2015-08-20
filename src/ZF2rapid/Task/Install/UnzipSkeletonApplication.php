<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Install;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Zend\Console\ColorInterface as Color;
use ZF2rapid\Task\AbstractTask;
use ZipArchive;

/**
 * Class UnzipSkeletonApplication
 *
 * @package ZF2rapid\Task\Install
 */
class UnzipSkeletonApplication extends AbstractTask
{
    /**
     * Process the command
     *
     * @return integer
     */
    public function processCommandTask()
    {
        // output message
        $this->console->writeTaskLine(
            'task_install_unzip_skeleton_unzipping',
            array(
                $this->console->colorize(
                    $this->params->skeletonName, Color::GREEN
                )
            )
        );

        // initialize archive
        $zipArchive = new ZipArchive();

        // open archive
        if ($zipArchive->open($this->params->tmpFile)) {
            // check numFiles
            if ($zipArchive->numFiles == 0) {
                // stop with error
                $this->console->writeFailLine(
                    'task_install_unzip_skeleton_file_empty',
                    array(
                        $this->console->colorize(
                            $this->params->skeletonUrl, Color::GREEN
                        )
                    )
                );

                return 1;
            }

            // get top dir
            $topDir = $zipArchive->statIndex(0);
            $this->params->tmpSkeleton = $this->params->tmpDir
                . DIRECTORY_SEPARATOR
                . rtrim($topDir['name'], DIRECTORY_SEPARATOR);

            // try to extract files
            if (!$zipArchive->extractTo($this->params->tmpDir)) {
                // stop with error
                $this->console->writeFailLine(
                    'task_install_unzip_skeleton_unzip_failed',
                    array(
                        $this->console->colorize(
                            $this->params->tmpFile, Color::GREEN
                        )
                    )
                );

                return 1;
            }

            // copy files from tmp to project path
            $result = $this->copyFiles(
                $this->params->tmpSkeleton, $this->params->projectPath
            );

            // close archive
            $zipArchive->close();

            // delete temporary files
            $directoryIterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator(
                    $this->params->tmpSkeleton, RecursiveDirectoryIterator::SKIP_DOTS
                ),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            /** @var \SplFileInfo $item */
            foreach ($directoryIterator as $item) {
                if ($item->isDir()) {
                    rmdir($item);
                } else {
                    unlink($item);
                }
            }

            rmdir($this->params->tmpSkeleton);

            // check for error while copying files
            if (false === $result) {
                $this->console->writeFailLine(
                    'task_install_unzip_skeleton_copy_failed',
                    array(
                        $this->console->colorize(
                            $this->params->tmpFile, Color::GREEN
                        )
                    )
                );

                return 1;
            }
        }

        return 0;
    }

    /**
     * Copy all files recursively from source to destination
     *
     * @param  string $source
     * @param  string $destination
     *
     * @return boolean
     */
    public function copyFiles($source, $destination)
    {
        if (!file_exists($source)) {
            return false;
        }

        if (!file_exists($destination)) {
            mkdir($destination);
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator(
                $source, RecursiveDirectoryIterator::SKIP_DOTS
            ),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $item) {
            $destinationName = $destination . DIRECTORY_SEPARATOR
                . $iterator->getSubPathName();

            if ($item->isDir()) {
                if (!file_exists($destinationName)) {
                    if (!@mkdir($destinationName)) {
                        return false;
                    }
                }
            } else {
                if (!@copy($item, $destinationName)) {
                    return false;
                }
                chmod($destinationName, fileperms($item));
            }
        }

        return true;
    }
}