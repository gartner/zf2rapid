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
use Zend\Db\Metadata\Object\ConstraintObject;
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
            $moduleIdentifier . '_title_index'       => $entityName . ' overview',
            $moduleIdentifier . '_title_show'        => $entityName . ' view',
            $moduleIdentifier . '_title_create'      => $entityName . ' create',
            $moduleIdentifier . '_title_update'      => $entityName . ' update',
            $moduleIdentifier . '_title_delete'      => $entityName . ' delete',
            $moduleIdentifier . '_navigation_index'  => $entityName,
            $moduleIdentifier . '_navigation_show'   => $entityName . ' view',
            $moduleIdentifier . '_navigation_create' => $entityName . ' create',
            $moduleIdentifier . '_navigation_update' => $entityName . ' update',
            $moduleIdentifier . '_navigation_delete' => $entityName . ' delete',
            $moduleIdentifier . '_action_index'      => $entityName . ' overview',
            $moduleIdentifier . '_action_show'       => 'Show ' . $entityName,
            $moduleIdentifier . '_action_save'       => 'Save ' . $entityName,
            $moduleIdentifier . '_action_create'     => 'Create ' . $entityName,
            $moduleIdentifier . '_action_update'     => 'Update ' . $entityName,
            $moduleIdentifier . '_action_delete'     => 'Delete ' . $entityName,
        );

        // loop through entity properties
        foreach ($loadedEntity->getProperties() as $property) {
            $key  = $moduleIdentifier . '_label_' . $this->filterCamelCaseToUnderscore($property->getName());
            $text = ucfirst(($property->getName()));

            $translationData[$key] = $text;
        }

        $loadedTable = $this->params->loadedTables[$entityIdentifier];

        /** @var ConstraintObject $primaryKey */
        $primaryKey     = $loadedTable['primaryKey'];
        $primaryColumns = $primaryKey->getColumns();

        /** @var ColumnObject $column */
        foreach ($loadedTable['columns'] as $column) {
            if ($column->getDataType() == 'enum') {
                foreach ($column->getErrata('permitted_values') as $value) {
                    $key  = $column->getTableName() . '_option_' . $column->getName() . '_' . $value;
                    $text = $value;

                    $translationData[$key] = $text;
                }
            }
        }

        $key  = $moduleIdentifier . '_message_' . $entityIdentifier . '_not_found';
        $text = 'No ' . $entityName . ' found.';

        $translationData[$key] = $text;

        $key  = $moduleIdentifier . '_message_' . $entityIdentifier . '_data_invalid';
        $text = $entityName . ' data invalid. Please check your input.';

        $translationData[$key] = $text;

        $key  = $moduleIdentifier . '_message_' . $entityIdentifier . '_saving_success';
        $text = $entityName . ' was saved.';

        $translationData[$key] = $text;

        $key  = $moduleIdentifier . '_message_' . $entityIdentifier . '_saving_failed';
        $text = $entityName . ' could not be saved.';

        $translationData[$key] = $text;

        $key  = $moduleIdentifier . '_message_' . $entityIdentifier . '_deleting_possible';
        $text = 'You can delete the ' . $entityName . ' now.';

        $translationData[$key] = $text;

        $key  = $moduleIdentifier . '_message_' . $entityIdentifier . '_deleting_success';
        $text = $entityName . ' was deleted.';

        $translationData[$key] = $text;

        $key  = $moduleIdentifier . '_message_' . $entityIdentifier . '_deleting_failed';
        $text = $entityName . ' could not be deleted.';

        $translationData[$key] = $text;

        /** @var ColumnObject $column */
        foreach ($loadedTable['columns'] as $column) {
            $columnText = str_replace('_', ' ', $column->getName());

            if ($column->getDataType() == 'enum') {
                $key  = $column->getTableName() . '_message_' . $column->getTableName() . '_' . $column->getName()
                    . '_inarray';
                $text = 'Invalid ' . $column->getTableName() . ' ' . $columnText . '!';

                $translationData[$key] = $text;
            } elseif ($column->getDataType() == 'varchar') {
                $key  = $column->getTableName() . '_message_' . $column->getTableName() . '_' . $column->getName()
                    . '_stringlength';
                $text = 'Wrong length for ' . $column->getTableName() . ' ' . $columnText . '!';

                $translationData[$key] = $text;
            } elseif ($column->getDataType() == 'char') {
                $key  = $column->getTableName() . '_message_' . $column->getTableName() . '_' . $column->getName()
                    . '_stringlength';
                $text = 'Wrong length for ' . $column->getTableName() . ' ' . $columnText . '!';

                $translationData[$key] = $text;
            }

            if (in_array($column->getName(), $primaryColumns)) {
                $required = 'false';
            } elseif ($column->isNullable() === false) {
                $required = 'true';
            } else {
                $required = 'false';
            }

            if ($required) {
                $key  = $column->getTableName() . '_message_' . $column->getTableName() . '_' . $column->getName()
                    . '_notempty';
                $text = ucfirst($columnText) . ' should not be empty!';

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