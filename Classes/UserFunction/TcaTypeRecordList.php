<?php

namespace T3SEO\Listmod\UserFunction;

use TYPO3\CMS\Backend\Form\FormEngine;
use TYPO3\CMS\Backend\Utility\BackendUtility;

class TcaTypeRecordList {

	/**
	 * @var array
	 */
	protected $currentRecord;

	/**
	 * @var string
	 */
	protected $currentTable;

	/**
	 * @var \TYPO3\CMS\Core\Database\DatabaseConnection
	 */
	protected $databaseConnection;

	/**
	 *
	 */
	public function __construct() {
		$this->databaseConnection = $GLOBALS['TYPO3_DB'];
	}

	/**
	 * @param array $parameters
	 * @param FormEngine $parentObject
	 */
	public function render($parameters, FormEngine $parentObject) {
		$this->currentRecord = $parameters['row'];
		$this->currentTable = $parameters['table'];
		$config = $parameters['fieldConf']['config'];
		$content = '';
		$content .= $parentObject->renderWizards(
			array('', ''),
			$config['wizards'],
			$this->currentTable,
			$this->currentRecord,
			$parameters['field'],
			$parameters,
			$parameters['itemFormElName'] . '_hr',
			array()
		);
		$records = $this->getRecordsToList($config);
		foreach ($records as $record) {
			$popupId = \TYPO3\CMS\Core\Utility\GeneralUtility::shortmd5(serialize($record));
			$content .= '<div>';
			$url = 'alt_doc.php?returnUrl=' . rawurlencode('wizard_edit.php?doClose=1') . '&edit[' . $config['foreign_table'] . '][' . $record['uid'] . ']=edit';
			if ($config['editColumnsOnly']) {
				$url .= '&columnsOnly=' . rawurlencode($config['editColumnsOnly']);
			}
			$aOnClick = $parentObject->blur() . 'vHWin=window.open(\'' . $url . '\',\'popUp' . $popupId . '\',\'' . $config['JSopenParams'] . '\');vHWin.focus();return false;';
			$content .= '<a href="#" onclick="' . $aOnClick . '">';
			$content .= '<img src="' . \TYPO3\CMS\Backend\Utility\IconUtility::getIcon($config['foreign_table'], $record) . '" /> ';
			$content .= BackendUtility::getRecordTitle($config['foreign_table'], $record);
			$content .= '</a>';
			$content .= '</div>';
		}
		return $content;
	}

	/**
	 * @param $config
	 */
	protected function getRecordsToList($config) {
		$records = array();
		$res = $this->databaseConnection->exec_SELECTquery('*', $config['foreign_table'], '1=1 ' . BackendUtility::deleteClause($config['foreign_table']) . ' ' . $this->replaceWhereMarkers($config['foreign_table_where']));
		while ($record = $this->databaseConnection->sql_fetch_assoc($res)) {
			$records[] = $record;
		}
		return $records;
	}

	/**
	 * @param $string
	 * @return string
	 */
	protected function replaceWhereMarkers($string) {
		$TSconfig = BackendUtility::getTCEFORM_TSconfig($this->currentTable, $this->currentRecord);
		$string = str_replace(
			array_map(function($key) {
				return '###' . substr($key, 1) . '###';
			}, array_keys($TSconfig)),
			array_values($TSconfig),
			$string
		);
		return $string;
	}

}

?>