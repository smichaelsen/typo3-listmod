<?php

namespace T3SEO\Listmod\Hook;

use TYPO3\CMS\Core\Utility\GeneralUtility;

class ListModule implements \TYPO3\CMS\Backend\RecordList\RecordListGetTableHookInterface {

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 * @var string
	 */
	protected $extensionKey = 'listmod';

	/**
	 * @var array
	 */
	protected $filterCriteria = array();

	/**
	 * @var \TYPO3\CMS\Backend\Form\FormEngine
	 */
	protected $formEngine;

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
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];

		// addWhere
		if (is_array($parentObject->modTSconfig['properties']['addWhere.']) && isset($parentObject->modTSconfig['properties']['addWhere.'][$table])) {
			$additionalWhereClause .= $parentObject->modTSconfig['properties']['addWhere.'][$table];
		}

		// forceColumnVisibility
		foreach ($GLOBALS['TCA'][$table]['columns'] as $fieldName => $config) {
			if ($config['config']['forceColumnVisibility']) {
				$selectedFieldsList .= ',' . $fieldName;
				$parentObject->setFields = $selectedFieldsList;
				$parentObject->fieldArray[] = $fieldName;
			}
		}

		// filter
		if (array_key_exists('filter', $GLOBALS['TCA'][$table]['ctrl'])) {
			// check if there are records
			$queryParts = $parentObject->makeQueryArray($table, $pageId, $additionalWhereClause, $selectedFieldsList);
			$itemcount = $this->databaseConnection->exec_SELECTcountRows('*', $queryParts['FROM'], $queryParts['WHERE']);
			if ($itemcount) {
				$posts = GeneralUtility::_POST($this->extensionKey);
				/** @var \TYPO3\CMS\Core\Authentication\BackendUserAuthentication $backendUserAuthentication */
				$backendUserAuthentication = $GLOBALS['BE_USER'];
				if(isset($posts)) {
					$backendUserAuthentication->setAndSaveSessionData($table."_filtercriteria", $posts);
					$this->filterCriteria = $posts;
				} else{
					$this->filterCriteria = $backendUserAuthentication->getSessionData($table."_filtercriteria");
				}
				$this->formEngine = GeneralUtility::makeInstance('TYPO3\\CMS\\Backend\\Form\\FormEngine');
				$this->formEngine->initDefaultBEmode();
				$this->formEngine->backPath = $GLOBALS['BACK_PATH'];
				$parentObject->HTMLcode .= $this->formEngine->printNeededJSFunctions_top();
				$parentObject->HTMLcode .= $this->formEngine->printNeededJSFunctions();
				$itemList = explode(',', $selectedFieldsList);
				$parentObject->HTMLcode .= '<fieldset>';
				$parentObject->HTMLcode .= '<legend>Suchoptionen</legend>';
				foreach ($itemList as $item) {
					if ($conf = $GLOBALS['TCA'][$table]['columns'][$item]['config_filter']) {
						$parentObject->HTMLcode .= $this->makeFormitem($item, $table, $conf);
						$additionalWhereClause .= $this->makeWhereClause($item, $conf, $this->filterCriteria[$item], $table);
					}
				}
				$parentObject->HTMLcode .= '<input type="submit" value="Suche starten" style="margin-top: 15px;" />';
				$parentObject->HTMLcode .= '</fieldset>';
			}
		}
	}

	/**
	 * @param string $item
	 * @param string $table
	 * @param array $conf
	 * @return string
	 */
	protected function makeFormitem($item, $table, $conf) {
		$confarray = array(
			'itemFormElName' => $this->extensionKey.'['.$item.']',
			'itemFormElValue' => '',
			'fieldConf' => array(
				'config' => $conf,
			),
		);
		$labelDef = $GLOBALS['TCA'][$table]['columns'][$item]['label'];
		$labelValue = $this->formEngine->sL($labelDef);
		$formElement = $this->formEngine->getSingleField_SW('','',array(),$confarray);
		$formElement = str_replace($this->extensionKey.'['.$item.']'.'_hr', $this->extensionKey.'['.$item.']', $formElement);
		$formElement = preg_replace('/<input\ type=\"hidden.*?>/s','',$formElement);
		$formElement = str_replace($this->extensionKey.'['.$item.']" value=""', $this->extensionKey.'['.$item.']" value="'.$this->filterCriteria[$item].'"', $formElement);
		$formElement = str_replace('<option value="'.$this->filterCriteria[$item].'">', '<option value="'.$this->filterCriteria[$item].'" selected="selected">', $formElement);
		$formElement = '<div style="float:left; margin: 5px;"><label>'.$labelValue.'</label><br />'.$formElement.'</div>';
		return $formElement;
	}

	/**
	 * @param string $item
	 * @param array $conf
	 * @param string $itemValue
	 * @param string $table
	 * @return mixed
	 */
	protected function makeWhereClause($item, $conf, $itemValue, $table) {
		$whereClause = '';
		if (isset($this->filterCriteria[$item]) && ($this->filterCriteria[$item]!='-1') && ($this->filterCriteria[$item]!='')) {
			switch($conf['type']) {
				case 'select':
					$whereClause = $this->makeQuerySelect($item, $itemValue, $table);
					break;
				case 'input':
					$whereClause = $this->makeQueryInputTrim($item, $itemValue, $table);
					break;
			}
		}
		return $whereClause;
	}

	/**
	 * @param string $item
	 * @param string $itemValue
	 * @param string $table
	 * @return string
	 */
	protected function makeQueryInputTrim($item, $itemValue, $table) {
		$query = ' AND ' . $this->databaseConnection->searchQuery(
			array('searchword' => $itemValue),
			array('field' => $item),
			$table
		);
		return $query;
	}

	/**
	 * @param string $item
	 * @param string $itemValue
	 * @param string $table
	 * @return string
	 */
	protected function makeQuerySelect($item, $itemValue, $table) {
		$query = ' AND (' . $table . '.' . $item . ' = \'' . $itemValue . '\')';
		return $query;
	}

}

?>