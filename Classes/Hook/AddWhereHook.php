<?php
namespace T3SEO\Listmod\Hook;

use TYPO3\CMS\Backend\RecordList\RecordListGetTableHookInterface;

class AddWhereHook implements RecordListGetTableHookInterface
{
    /**
     * @inheritdoc
     */
    public function getDBlistQuery($table, $pageId, &$additionalWhereClause, &$selectedFieldsList, &$parentObject)
    {
        if (is_array($parentObject->modTSconfig['properties']['addWhere.']) && isset($parentObject->modTSconfig['properties']['addWhere.'][$table])) {
            $additionalWhereClause .= $parentObject->modTSconfig['properties']['addWhere.'][$table];
        }
    }
}
