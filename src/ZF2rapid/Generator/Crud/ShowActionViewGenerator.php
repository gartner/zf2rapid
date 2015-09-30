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
use Zend\Code\Generator\BodyGenerator;
use Zend\Code\Reflection\ClassReflection;

/**
 * Class ShowActionViewGenerator
 *
 * @package ZF2rapid\Generator\Crud
 */
class ShowActionViewGenerator extends AbstractActionViewGenerator
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
        $entityName       = $loadedEntity->getShortName();
        $entityParam      = lcfirst($entityName);
        $moduleRoute      = $this->filterCamelCaseToDash($moduleName);

        // set action body
        $body   = array();
        $body[] = 'use ' . $loadedEntity->getName() . ';';
        $body[] = '';
        $body[] = '/** @var ' . $entityName . ' $' . $entityParam . ' */';
        $body[] = '$' . $entityParam . ' = $this->' . $entityParam . ';';
        $body[] = '';
        $body[] = '$this->h1(\'' . $moduleIdentifier . '_title_show\');';
        $body[] = '?>';
        $body[] = '<table class="table table-bordered">';
        $body[] = '    <tbody>';

        foreach ($loadedEntity->getProperties() as $property) {
            $methodName = 'get' . ucfirst($property->getName());

            $body[] = '        <tr>';
            $body[] = '            <th><?php echo $this->translate(\'' . $moduleIdentifier . '_label_'
                . $this->filterCamelCaseToUnderscore($property->getName()) . '\'); ?></th>';
            $body[] = '            <td><?php echo $' . $entityParam . '->' . $methodName . '() ?></td>';
            $body[] = '        </tr>';
        }

        $body[] = '    </tbody>';
        $body[] = '</table>';
        $body[] = '<p>';
        $body[] = '    <a class="btn btn-default" href="<?php echo $this->url(\'' . $moduleRoute. '\'); ?>">';
        $body[] = '        <i class="fa fa-table"></i>';
        $body[] = '        <?php echo $this->translate(\'' . $moduleIdentifier . '_action_index\'); ?>';
        $body[] = '    </a>';
        $body[] = '</p>';

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // add method
        $this->setContent($body);
    }

}