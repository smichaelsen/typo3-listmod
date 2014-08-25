TYPO3 Extension: List Module+
=============================

Extension Key: listmod
----------------------

EXT:listmod adds some additional features to the list module.

addWhere
~~~~~~~~

You can add restrictions to the SQL query for each table to hide certain
records. This is done via TSconfig:

::

    mod.web_list.addWhere {
        tt_content = AND CType = 'text'
    }

Will result in the list module only showing text content elements.

Filters
~~~~~~~

This functionality is taken over from `EXT:be\_tablefilter`_. I
integrated it’s code and just slightly modified it to work with TYPO3
6.1.

::

    $GLOBALS['TCA']['fe_users']['ctrl']['filter'] = TRUE;

Activates the usage of filters for fe\_users. This won’t have any effect
yet as we need to configure filter fields.

::

    $GLOBALS['TCA']['fe_users']['columns']['username']['config_filter'] = $GLOBALS['TCA']['fe_users']['columns']['username']['config'];

A filter for the username field. We just want a simple input field for
that, so we just copy over TCA config of this field. When you open the
list view on a page that has frontend users you will have a searchbox to
search for usernames.

::

    $GLOBALS['TCA']['fe_users']['columns']['usergroup']['config_filter'] = array(
        'type' => 'select',
        'items' => array(
            array('', ''),
        ),
        'foreign_table' => 'fe_groups',
        'foreign_table_where' => 'ORDER BY fe_groups.title ASC',
    );

An additional filter for the usergroup. Notice that it won’t appear
unless you have configured usergroup as a visible field in the list
module. The next feature (“forceColumnVisibility”) might also be helpful
for that.

forceColumnVisibility
~~~~~~~~~~~~~~~~~~~~~

In the single table view (click the + next to the table name ist list
view) every user can configure the fields that appear directly in the
list view. But sometimes you might want to force the visiblity of a
field.

::

    $GLOBALS['TCA']['fe_users']['columns']['usergroup']['config']['forceColumnVisibility'] = TRUE;

Voila!

enableControls
~~~~~~~~~~~~~~

If the control panel (extended view )is activated in the list module, several
icons are displayed to interact with the records (edit, move, history, etc.).
With the following options you can enable/disable those controls. The key
\_default will apply to all tables. You can overwrite its configuration
for each table.

::

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

This will disable the edit icon for all tables, disable history for all
table except tt\_content and disable moveDown and moveUp for
tt\_content. The names of all icons are: view, edit, move, viewBig,
history, version, perms, new, moveUp, moveDown, hide, delete, moveLeft,
moveRight

|Flattr Button|

.. _`EXT:be\_tablefilter`: http://typo3.org/extensions/repository/view/be_tablefilter

.. |Flattr Button| image:: http://api.flattr.com/button/button-compact-static-100x17.png
:target: https://flattr.com/thing/1268753/smichaelsentypo3-listmod-on-GitHub