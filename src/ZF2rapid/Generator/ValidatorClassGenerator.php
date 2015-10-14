<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Generator;

use Zend\Code\Generator\AbstractGenerator;
use Zend\Code\Generator\ClassGenerator;
use Zend\Code\Generator\DocBlock\Tag\GenericTag;
use Zend\Code\Generator\DocBlock\Tag\ParamTag;
use Zend\Code\Generator\DocBlock\Tag\PropertyTag;
use Zend\Code\Generator\DocBlock\Tag\ReturnTag;
use Zend\Code\Generator\DocBlockGenerator;
use Zend\Code\Generator\MethodGenerator;
use Zend\Code\Generator\ParameterGenerator;
use Zend\Code\Generator\PropertyGenerator;

/**
 * Class ValidatorClassGenerator
 *
 * @package ZF2rapid\Generator
 */
class ValidatorClassGenerator extends ClassGenerator
    implements ClassGeneratorInterface
{
    /**
     * @var array
     */
    protected $config = [];

    /**
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        // set config data
        $this->config = $config;

        // call parent constructor
        parent::__construct();
    }

    /**
     * Build the class
     *
     * @param string $className
     * @param string $moduleName
     */
    public function build($className, $moduleName)
    {
        // set name and namespace
        $this->setName($className);
        $this->setNamespaceName(
            $moduleName . '\\' . $this->config['namespaceValidator']
        );

        // add used namespaces and extended classes
        $this->addUse('Zend\Validator\AbstractValidator');
        $this->setExtendedClass('AbstractValidator');

        // add properties
        $this->addMessageConstant($className);
        $this->addMessageTemplates($className);

        // add methods
        $this->addIsValidMethod();
        $this->addClassDocBlock($className, $moduleName);
    }

    /**
     * Add a class doc block
     *
     * @param string $className
     * @param string $moduleName
     */
    protected function addClassDocBlock($className, $moduleName)
    {
        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $this->setDocBlock(
                new DocBlockGenerator(
                    $this->getName(),
                    'Provides the ' . $className . ' validator for the '
                    . $moduleName . ' Module',
                    [
                        new GenericTag('package', $this->getNamespaceName()),
                    ]
                )
            );
        }
    }

    /**
     * Generate a isValid method
     */
    protected function addIsValidMethod()
    {
        // set action body
        $body = [
            '$this->setValue((string) $value);',
            '',
            '// add validation code here',
            '$isValid = true;',
            '',
            'if (!$isValid) {',
            '    $this->error(self::INVALID);',
            '    return false;',
            '}',
            '',
            'return true;',
        ];
        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // create method
        $method = new MethodGenerator();
        $method->setName('isValid');
        $method->setBody($body);
        $method->setParameters(
            [
                new ParameterGenerator(
                    'value', 'mixed'
                ),
            ]
        );


        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $method->setDocBlock(
                new DocBlockGenerator(
                    'Called when validator is executed',
                    null,
                    [
                        new ParamTag(
                            'value',
                            [
                                'mixed',
                            ]
                        ),
                        new ReturnTag(['mixed']),
                    ]
                )
            );
        }

        // add method
        $this->addMethodFromGenerator($method);
    }

    /**
     * @param $className
     */
    protected function addMessageConstant($className)
    {
        // generate property
        $property = new PropertyGenerator();
        $property->setName('INVALID');
        $property->setDefaultValue('invalid' . $className);
        $property->setConst(true);

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $property->setDocBlock(
                new DocBlockGenerator(
                    '',
                    '',
                    [
                        new GenericTag(
                            'const'
                        ),
                    ]
                )
            );
        }

        // add property
        $this->addPropertyFromGenerator($property);
    }

    /**
     * @param $className
     */
    protected function addMessageTemplates($className)
    {
        // generate property
        $property = new PropertyGenerator();
        $property->setName('messageTemplates');
        $property->setDefaultValue(
            [
                'invalid' . $className => 'Value "%value%" is not valid',
            ]
        );
        $property->setVisibility(PropertyGenerator::FLAG_PROTECTED);

        // check for api docs
        if ($this->config['flagAddDocBlocks']) {
            $property->setDocBlock(
                new DocBlockGenerator(
                    'Validation failure message template definitions',
                    '',
                    [
                        new GenericTag(
                            'var',
                            'array'
                        ),
                    ]
                )
            );
        }

        // add property
        $this->addPropertyFromGenerator($property);
    }

}