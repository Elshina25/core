<?php

namespace Ita\Routes\Handlers;
use \Bitrix\Iblock\ {
    IblockTable
};

class Search {
    public static function beforeIndexHandler($arFields)
    {
        if($arFields["MODULE_ID"] == "iblock") {
            $iBlockTable = IblockTable::getList([
                "filter" => ["CODE" => "services"]
            ]);
            if ($iBlock = $iBlockTable->fetch()) {
                $iBlockId = $iBlock["ID"];
            }
            if ($arFields["PARAM2"] == $iBlockId) {
                $elements = \Bitrix\Iblock\Elements\ElementServicesTable::getList([
                    "filter" => ["ID" => $arFields["ITEM_ID"]],
                    "select" => ["ID", "FAST_LINK_XML_ID" => "FAST_LINK.ITEM.XML_ID"],
                ]);
                if ($element = $elements->fetch()) {
                    if ($element["FAST_LINK_XML_ID"] == "true") {
                        $arFields["BODY"]='';
                        $arFields["TITLE"]='';
                    }
                }
            }
        }
        return $arFields;
    }
}