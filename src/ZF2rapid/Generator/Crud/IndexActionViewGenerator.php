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
 * Class IndexActionViewGenerator
 *
 * @package ZF2rapid\Generator\Crud
 */
class IndexActionViewGenerator extends AbstractActionViewGenerator
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
        $listParam        = lcfirst(str_replace('Entity', '', $entityName)) . 'List';
        $moduleRoute      = $this->filterCamelCaseToDash($moduleName);

        // set action body
        $body   = [];
        $body[] = 'use ' . $loadedEntity->getName() . ';';
        $body[] = '';
        $body[] = '$this->h1(\'' . $moduleIdentifier . '_title_index\');';
        $body[] = '?>';
        $body[] = '<table class="table table-bordered table-striped">';
        $body[] = '    <thead>';
        $body[] = '    <tr>';

        foreach ($loadedEntity->getProperties() as $property) {
            $body[] = '        <th><?php echo $this->translate(\'' . $moduleIdentifier . '_label_'
                . $this->filterCamelCaseToUnderscore($property->getName()) . '\'); ?></th>';
        }

        $body[] = '        <th width="95">';
        $body[] = '            <a class="btn btn-default btn-xs" href="<?php echo $this->url(\'' . $moduleRoute
            . '/create\'); ?>" title="<?php echo $this->translate(\'' . $moduleIdentifier . '_action_create\'); ?>">';
        $body[] = '                <i class="fa fa-plus"></i>';
        $body[] = '            </a>';
        $body[] = '        </th>';
        $body[] = '    </tr>';
        $body[] = '    </thead>';
        $body[] = '    <tbody>';
        $body[] = '    <?php /** @var ' . $entityName . ' $' . $entityParam . ' */ ?>';
        $body[] = '    <?php foreach ($this->' . $listParam . ' as $' . $entityParam . '): ?>';
        $body[] = '        <tr>';

        foreach ($loadedEntity->getProperties() as $property) {
            $methodName = 'get' . ucfirst($property->getName());

            $body[] = '            <td><?php echo $' . $entityParam . '->' . $methodName . '() ?></td>';
        }

        $body[] = '            <td>';
        $body[] = '                <a class="btn btn-default btn-xs" href="<?php echo $this->url(\'' . $moduleRoute
            . '/show\', [\'id\' => $' . $entityParam
            . '->getIdentifier()]); ?>" title="<?php echo $this->translate(\'' . $moduleIdentifier
            . '_action_show\'); ?>">';
        $body[] = '                    <i class="fa fa-search"></i>';
        $body[] = '                </a>';
        $body[] = '                <a class="btn btn-default btn-xs" href="<?php echo $this->url(\'' . $moduleRoute
            . '/update\', [\'id\' => $' . $entityParam
            . '->getIdentifier()]); ?>" title="<?php echo $this->translate(\'' . $moduleIdentifier
            . '_action_update\'); ?>">';
        $body[] = '                    <i class="fa fa-pencil"></i>';
        $body[] = '                </a>';
        $body[] = '                <a class="btn btn-default btn-xs" href="<?php echo $this->url(\'' . $moduleRoute
            . '/delete\', [\'id\' => $' . $entityParam
            . '->getIdentifier()]); ?>" title="<?php echo $this->translate(\'' . $moduleIdentifier
            . '_action_delete\'); ?>">';
        $body[] = '                    <i class="fa fa-trash"></i>';
        $body[] = '                </a>';
        $body[] = '            </td>';
        $body[] = '        </tr>';
        $body[] = '    <?php endforeach; ?>';
        $body[] = '    </tbody>';
        $body[] = '</table>';

        $body = implode(AbstractGenerator::LINE_FEED, $body);

        // add method
        $this->setContent($body);
    }
}