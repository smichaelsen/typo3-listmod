<?php

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['getTable'][] = \T3SEO\Listmod\Hook\AddWhereHook::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['getTable'][] = \T3SEO\Listmod\Hook\ForceColumnVisibilityHook::class;
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/class.db_list_extra.inc']['actions'][] = \T3SEO\Listmod\Hook\EnableControlsHook::class;
