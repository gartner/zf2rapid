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
 * Class DeleteActionViewGenerator
 *
 * @package ZF2rapid\Generator\Crud
 */
class DeleteActionViewGenerator extends AbstractActionViewGenerator
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
        $formParam        = lcfirst($moduleName) . 'DeleteForm';
        $moduleRoute      = $this->filterCamelCaseToDash($moduleName);
        $deleteMessage    = $moduleRoute . '_message_' . $moduleRoute . '_deleting_possible';

        // set action body
        $body   = [];
        $body[] = 'use ' . $loadedEntity->getName() . ';';
        $body[] = '';
        $body[] = '/** @var ' . $entityName . ' $' . $entityParam . ' */';
        $body[] = '$' . $entityParam . ' = $this->' . $entityParam . ';';
        $body[] = '';
        $body[] = '$this->h1(\'' . $moduleIdentifier . '_title_delete\');';
        $body[] = '';
        $body[] = '$this->' . $formParam . '->setAttribute(\'action\', $this->url(\'' . $moduleIdentifier
            . '/delete\', [\'id\' => $' . $entityParam . '->getIdentifier()]));';
        $body[] = '';
        $body[] = '?>';
        $body[] = '<div class="well">';
        $body[] = '    <table class="table">';
        $body[] = '        <tbody>';

        foreach ($loadedEntity->getProperties() as $property) {
            $methodName = 'get' . ucfirst($property->getName());

            $body[] = '            <tr>';
            $body[] = '                <th class="col-sm-2 text-right"><?php echo $this->translate(\'' . $moduleIdentifier . '_label_'
                . $this->filterCamelCaseToUnderscore($property->getName()) . '\'); ?></th>';
            $body[] = '                <td class="col-sm-10"><?php echo $' . $entityParam . '->' . $methodName . '() ?></td>';
            $body[] = '            </tr>';
        }

        $body[] = '            <tr>';
        $body[] = '                <th class="col-sm-2 text-right">&nbsp;</th>';
        $body[] = '                <td class="col-sm-10"><?php echo $this->form($this->' . $formParam . '); ?></td>';
        $body[] = '            </tr>';
        $body[] = '        </tbody>';
        $body[] = '    </table>';
        $body[] = '</div>';
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