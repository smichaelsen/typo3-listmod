# TYPO3 Extension: List Module+
## Extension Key: listmod

EXT:listmod adds some additionaly features to the list module.

### addWhere

You can add restrictions to the SQL query for each table to hide certain records. This is done via TSconfig:

	mod.web_list.addWhere {
		tt_content = AND CType = text
	}

Will result in the list module only showing text content elements.

### Filters

This functionality is taken over from [EXT:be_tablefilter](http://typo3.org/extensions/repository/view/be_tablefilter). I integrated it's code and just slightly modified it to work with TYPO3 6.1.

	$GLOBALS['TCA']['fe_users']['ctrl']['filter'] = TRUE;

Activates the usage of filters for fe_users. This won't have any effect yet as we need to configure filter fields.

	$GLOBALS['TCA']['fe_users']['columns']['username']['config_filter'] = $GLOBALS['TCA']['fe_users']['columns']['username']['config'];

A filter for the username field. We just want a simple input field for that, so we just copy over TCA config of this field. When you open the list view on a page that has frontend users you will have a searchbox to search for usernames.

	$GLOBALS['TCA']['fe_users']['columns']['usergroup']['config_filter'] = array(
		'type' => 'select',
		'items' => array(
			array('', ''),
		),
		'foreign_table' => 'fe_groups',
		'foreign_table_where' => 'ORDER BY fe_groups.title ASC',
	);

An additional filter for the usergroup. Notice that it won't appear unless you have configured usergroup as a visible field in the list module. The next feature ("forceColumnVisibility") might also be helpful for that.

### forceColumnVisibility

In the single table view (click the + next to the table name ist list view) every user can configure the fields that appear directly in the list view. But sometimes you might want to force the visiblity of a field.

	$GLOBALS['TCA']['fe_users']['columns']['usergroup']['config']['forceColumnVisibility'] = TRUE;

Voila!