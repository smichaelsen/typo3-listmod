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

This functionality is taken over from [EXT:be_tablefilter](http://typo3.org/extensions/repository/view/be_tablefilter).

### forceColumnVisibility

TCA option that makes a certain field always visible in list view.