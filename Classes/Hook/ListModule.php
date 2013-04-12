<?php

namespace T3SEO\Listmod\Hook;

class ListModule implements \TYPO3\CMS\Backend\RecordList\RecordListGetTableHookInterface {

	/**
	 * modifies the DB list query
	 *
	 * @param string $table The current database table
	 * @param integer $pageId The record's page ID
	 * @param string $additionalWhereClause An additional WHERE clause
	 * @param string $selectedFieldsList Comma separated list of selected fields
	 * @param \TYPO3\CMS\Recordlist\RecordList\DatabaseRecordList $parentObject Parent localRecordList object
	 * @return void
	 */
	public function getDBlistQuery($table, $pageId, &$additionalWhereClause, &$selectedFieldsList, &$parentObject) {
		if (is_array($parentObject->modTSconfig['properties']['addWhere.']) && isset($parentObject->modTSconfig['properties']['addWhere.'][$table])) {
			$additionalWhereClause .= $parentObject->modTSconfig['properties']['addWhere.'][$table];
		}
	}
}

?>