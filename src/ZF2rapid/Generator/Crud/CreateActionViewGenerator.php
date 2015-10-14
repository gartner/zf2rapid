<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Generator\Crud;

use Zend\Code\Generator\AbstractGenerator;
use Zend\Code\Reflection\ClassReflection;

/**
 * Class CreateActionViewGenerator
 *
 * @package ZF2rapid\Generator\Crud
 */
class CreateActionViewGenerator extends AbstractActionViewGenerator
{
    /**
     * Generate view content
     *
     * @param string          $moduleName
     * @param ClassReflection $loadedEntity
     */
    protected function addContent($moduleName, ClassReflection $loadedEntity)
    {
        // prepare some params
        $moduleIdentifier = $this->filterCamelCaseToUnderscore($moduleName);
        $formParam        = lcfirst($moduleName) . 'DataForm';
        $moduleRoute      = $this->filterCamelCaseToDash($moduleName);

        // set action body
        $body   = [];
        $body[] = '$this->h1(\'' . $moduleIdentifier . '_title_create\');';
        $body[] = '';
        $body[] = '$this->' . $formParam . '->setAttribute(\'action\', $this->url(\'' . $moduleIdentifier
            . '/create\'));';
        $body[] = '';
        $body[] = 'echo $this->bootstrapForm($this->' . $formParam . ');';
        $body[] = '?>';
        $body[] = '<p>';
        $body[] = '    <a class="btn btn-primary" href="<?php echo $this->url(\'' . $moduleRoute. '\'); ?>">';
        $body[] = '        <i class="fa fa-table"></i>';
        $body[] = '        <?php echo $this->translate(\'' . $moduleIdentifier . '_action_index\'); ?>';
        $body[] = '    </a>';
        $body[] = '</p>';

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // add method
        $this->setContent($body);
    }
}