<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Generator;

use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Code\Generator\DocBlock\Tag\LicenseTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\FileGenerator as ZendFileGenerator;

/**
 * Class ClassFileGenerator
 *
 * @package ZF2rapid\Generator
 */
abstract class AbstractFileGenerator extends ZendFileGenerator
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * Add file doc block
     */
    public function addFileDocBlock()
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->config['fileDocBlockText'],
                    null,
                    [
                        new GenericTag(
                            'copyright', $this->config['fileDocBlockCopyright']
                        ),
                        new LicenseTag(
                            $this->config['fileDocBlockLicense']
                        ),
                    ]
                )
            );
        }
    }
}