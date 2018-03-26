<?php
namespace T3SEO\Listmod\Hook;

use TYPO3\CMS\Backend\RecordList\RecordListGetTableHookInterface;

class ForceColumnVisibilityHook implements RecordListGetTableHookInterface
{
    /**
     * @inheritdoc
     */
    public function getDBlistQuery($table, $pageId, &$additionalWhereClause, &$selectedFieldsList, &$parentObject)
    {
        foreach ($GLOBALS['TCA'][$table]['columns'] as $fieldName => $config) {
            if ($config['config']['forceColumnVisibility']) {
                $selectedFieldsList .= ',' . $fieldName;
                $parentObject->fieldArray[] = $fieldName;
            }
        }
    }
}
