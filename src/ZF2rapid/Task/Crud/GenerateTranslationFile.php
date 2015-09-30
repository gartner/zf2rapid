<?php
/**
 * ZF2rapid - Zend Framework 2 Rapid Development Tool
 *
 * @link      https://github.com/ZFrapid/zf2rapid
 * @copyright Copyright (c) 2014 - 2015 Ralf Eggert
 * @license   http://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace ZF2rapid\Task\Crud;

use Zend\Code\Reflection\ClassReflection;
use Zend\Db\Metadata\Object\ColumnObject;
use ZF2rapid\Generator\ConfigArrayGenerator;
use ZF2rapid\Generator\ConfigFileGenerator;
use ZF2rapid\Task\AbstractTask;

/**
 * Class GenerateModuleConfig
 *
 * @package ZF2rapid\Task\Crud
 */
class GenerateTranslationFile extends AbstractTask
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
            'task_crud_generate_translation_file_writing'
        );

        // prepare identifier
        $entityName       = str_replace('Entity', '', $this->params->paramEntityClass);
        $moduleIdentifier = $this->filterCamelCaseToUnderscore($this->params->paramModule);
        $entityIdentifier = $this->filterCamelCaseToUnderscore($entityName);

        /** @var ClassReflection $loadedEntity */
        $loadedEntity = $this->params->loadedEntity;

        // setup translation data
        $translationData = array(
            $moduleIdentifier . '_message_' . $entityIdentifier . '_not_found' => 'No ' . $entityName . ' found.',
            $moduleIdentifier . '_title_index'                                 => $entityName . ' overview',
            $moduleIdentifier . '_title_show'                                  => $entityName . ' view',
            $moduleIdentifier . '_navigation_index'                            => $entityName,
            $moduleIdentifier . '_navigation_show'                             => $entityName . ' view',
            $moduleIdentifier . '_action_index'                                => $entityName . ' overview',
            $moduleIdentifier . '_action_show'                                 => 'Show ' . $entityName,
        );

        // loop through entity properties
        foreach ($loadedEntity->getProperties() as $property) {
            $key  = $moduleIdentifier . '_label_' . $this->filterCamelCaseToUnderscore($property->getName());
            $text = ucfirst(($property->getName()));

            $translationData[$key] = $text;
        }

        $loadedTable = $this->params->loadedTables[$entityIdentifier];

        /** @var ColumnObject $column */
        foreach ($loadedTable['columns'] as $column) {
            if ($column->getDataType() == 'enum') {
                $key  = $column->getTableName() . '_message_inarray_' . $column->getTableName() . '_' . $column->getName();
                $text = 'Invalid ' . $column->getTableName() . '_' . $column->getName() . '!';

                $translationData[$key] = $text;
            } elseif ($column->getDataType() == 'varchar') {
                $key  = $column->getTableName() . '_message_stringlength_' . $column->getTableName() . '_' . $column->getName();
                $text = 'Wrong length for ' . $column->getTableName() . '_' . $column->getName() . '!';

                $translationData[$key] = $text;
            } elseif ($column->getDataType() == 'char') {
                $key  = $column->getTableName() . '_message_stringlength_' . $column->getTableName() . '_' . $column->getName();
                $text = 'Wrong length for ' . $column->getTableName() . '_' . $column->getName() . '!';

                $translationData[$key] = $text;
            }
        }

        // create config array
        $config = new ConfigArrayGenerator($translationData, $this->params);

        // create file
        $file = new ConfigFileGenerator(
            $config->generate(), $this->params->config
        );

        // write file
        file_put_contents(
            $this->params->applicationLanguageDir . '/en_US.php',
            $file->generate()
        );

        return 0;
    }
}