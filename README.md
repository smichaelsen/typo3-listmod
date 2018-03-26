# TYPO3 Extension: List Module+

## Extension Key: listmod

EXT:listmod adds some additional features to the list module.

### addWhere

You can add restrictions to the SQL query for each table to hide certain
records. This is done via TSconfig:

````
    mod.web_list.addWhere {
        tt_content = AND CType = 'text'
    }
````

Will result in the list module only showing text content elements.

### forceColumnVisibility

In the single table view (click the + next to the table name ist list
view) every user can configure the fields that appear directly in the
list view. But sometimes you might want to force the visiblity of a
field.

````
    $GLOBALS['TCA']['fe_users']['columns']['usergroup']['config']['forceColumnVisibility'] = TRUE;
````

Voila!

### enableControls

If the control panel (extended view )is activated in the list module, several
icons are displayed to interact with the records (edit, move, history, etc.).
With the following options you can enable/disable those controls. The key
`_default` will apply to all tables. You can overwrite its configuration
for each table.

````
    mod.web_list {
        enableDisplayBigControlPanel = activated
        enableControls {
            _default {
                edit = 0
                history = 0
            }
            tt_content {
                history = 1
                moveDown = 0
                moveUp = 0
            }
        }
    }
````

This will disable the edit icon for all tables, disable history for all
table except tt_content and disable moveDown and moveUp for
tt_content. The names of all icons are: `view`, `edit`, `move`, `viewBig`,
`history`, `version`, `perms`, `new`, `moveUp`, `moveDown`, `hide`, `delete`, `moveLeft`,
`moveRight`.

## Semantic Versioning

This package uses [semantic versioning](https://semver.org/).

### Breaking Changes from 0.5 to 1.0:

* Only TYPO3 version 8 is supported.
* The filters feature is dropped. Take a look at [EXT:querybuilder](https://extensions.typo3.org/extension/querybuilder/) instead.
