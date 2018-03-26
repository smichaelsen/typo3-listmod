<?php
namespace T3SEO\Listmod\Hook;

use TYPO3\CMS\Core\Utility\ArrayUtility;
use TYPO3\CMS\Recordlist\RecordList\RecordListHookInterface;

class EnableControlsHook implements RecordListHookInterface
{
    public function makeClip($table, $row, $cells, &$parentObject)
    {
        return $cells;
    }

    public function makeControl($table, $row, $cells, &$parentObject)
    {
        if (isset($parentObject->modTSconfig['properties']['enableControls.'])) {
            $configuration = $parentObject->modTSconfig['properties']['enableControls.'];
        } else {
            return $cells;
        }
        $settings = isset($configuration['_default.']) ? $configuration['_default.'] : array();
        $tableSettings = isset($configuration[$table . '.']) ? $configuration[$table . '.'] : array();
        ArrayUtility::mergeRecursiveWithOverrule($settings, $tableSettings);
        foreach (array_keys($cells) as $cellName) {
            if (isset($settings[$cellName]) && $settings[$cellName] === "0") {
                $cells[$cellName] = $parentObject->spaceIcon;
            }
        }
        return $cells;
    }

    public function renderListHeader($table, $currentIdList, $headerColumns, &$parentObject)
    {
        return $headerColumns;
    }

    public function renderListHeaderActions($table, $currentIdList, $cells, &$parentObject)
    {
        return $cells;
    }
}
