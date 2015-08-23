#!/usr/bin/env php
<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */

// define application root
define('ZF2RAPID_ROOT', realpath(__DIR__ . '/..'));

// define file and path
$filename  = 'zf2rapid.phar';
$pharPath = ZF2RAPID_ROOT . '/' . $filename;

// unlink phar if exists
if (file_exists($pharPath)) {
    unlink($pharPath);
}

// create phar and add files
$phar = new \Phar($pharPath, 0, $filename);
$phar->startBuffering();
$phar->addFromString(
    ZF2RAPID_ROOT . '/bin/zf2rapid.php',
    substr(php_strip_whitespace(ZF2RAPID_ROOT . '/bin/zf2rapid.php'), 19)
);

addDir($phar, ZF2RAPID_ROOT . '/bin', ZF2RAPID_ROOT);
addDir($phar, ZF2RAPID_ROOT . '/config', ZF2RAPID_ROOT);
addDir($phar, ZF2RAPID_ROOT . '/language', ZF2RAPID_ROOT);
addDir($phar, ZF2RAPID_ROOT . '/src', ZF2RAPID_ROOT);
addDir($phar, ZF2RAPID_ROOT . '/vendor', ZF2RAPID_ROOT);

$phar->setStub($phar->createDefaultStub('bin/zf2rapid.php'));
$phar->stopBuffering();

// stop processing
if (file_exists($pharPath)) {
    echo 'Phar created successfully in ' . $pharPath . "\n";
    chmod($pharPath, 0755);
} else {
    echo 'Error during the compile of the Phar file ' . $pharPath . "\n";
    exit(2);
}

/**
 * Add a directory in phar removing whitespaces from PHP source code
 *
 * @param Phar   $phar
 * @param string $sDir
 * @param null   $baseDir
 */
function addDir($phar, $sDir, $baseDir = null)
{
    $oDir = new RecursiveIteratorIterator (
        new RecursiveDirectoryIterator ($sDir),
        RecursiveIteratorIterator::SELF_FIRST
    );

    foreach ($oDir as $sFile) {
        if (preg_match('/\\.php$/i', $sFile)) {
            addFile(
                $phar,
                $sFile,
                $baseDir
            );
        }
    }
}

/**
 * Add a file in phar removing whitespaces from the file
 *
 * @param Phar   $phar
 * @param string $sFile
 * @param null   $baseDir
 */
function addFile($phar, $sFile, $baseDir = null)
{
    if (null !== $baseDir) {
        $phar->addFromString(
            substr(
                $sFile, strlen($baseDir) + 1
            ),
            php_strip_whitespace($sFile)
        );
    } else {
        $phar->addFromString(
            $sFile,
            php_strip_whitespace($sFile)
        );
    }
}
