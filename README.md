# TYPO3 Extension: List Module+
## Extension Key: listmod

EXT:listmod currently adds one feature to the list module. You can add restrictions to the SQL query for each table to hide certain records. This is done via TSconfig:

	mod.web_list.addWhere {
		tt_content = AND CType = text
	}

Will result in the list module only showing text content elements.