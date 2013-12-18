<?php

$GLOBALS['TCA']['fe_users']['ctrl']['filter'] = TRUE;
$GLOBALS['TCA']['fe_users']['columns']['username']['config_filter'] = $GLOBALS['TCA']['fe_users']['columns']['username']['config'];
$GLOBALS['TCA']['fe_users']['columns']['usergroup']['config_filter'] = array(
	'type' => 'select',
	'items' => array(
		array('', ''),
	),
	'foreign_table' => 'fe_groups',
	'foreign_table_where' => 'ORDER BY fe_groups.title ASC',
);
$GLOBALS['TCA']['fe_users']['columns']['usergroup']['config']['forceColumnVisibility'] = TRUE;