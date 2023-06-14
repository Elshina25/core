<?php

namespace Ita\Routes\Controllers;

use \Bitrix\Main\ {
    Context,
    Loader
};

use \Bitrix\Main\{
    Entity,
    ORM\Query
};

class Awards extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'getAwards' => [
				'prefilters' => [],
				'postfilters' => []
			]
		];
	}

	public function getAwardsAction()
    {
       	\Bitrix\Main\Loader::includeModule('iblock');
		$iblockIdAwards = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'awards'],
            "cache" => [
                "ttl" => 3600,
            ],
        ])->fetch()["ID"];
		$awards = [];
		$awardsQuery = \CIBlockElement::GetList(
			["SORT"=>"ASC"],
			["IBLOCK_ID" => $iblockIdAwards, "=ACTIVE" => "Y"],
			false,
			false,
			["ID", "NAME", "IBLOCK_ID", "PREVIEW_TEXT", "PROPERTY_*"]
		);
		while($awardsQueryRes = $awardsQuery->GetNextElement()) {
			$awardsQueryResult = $awardsQueryRes->GetFields();
			$awardsQueryResult["PROPERTIES"] = $awardsQueryRes->GetProperties();
			$awardsQueryResult["tags"] = $awardsQueryResult["PROPERTIES"]["TAGS"]["VALUE"];
			$awardsQueryResult["year"] = $awardsQueryResult["PROPERTIES"]["YEAR"]["VALUE"] === "" ? false : $awardsQueryResult["PROPERTIES"]["YEAR"]["VALUE"];
			$awardsQueryResult["name"] = $awardsQueryResult["NAME"];
			$awardsQueryResult["previewText"] = $awardsQueryResult["PREVIEW_TEXT"];
			unset($awardsQueryResult["PROPERTIES"]);
			unset($awardsQueryResult["~ID"]);
			unset($awardsQueryResult["ID"]);
			unset($awardsQueryResult["IBLOCK_ID"]);
			unset($awardsQueryResult["~IBLOCK_ID"]);
			unset($awardsQueryResult["~SORT"]);
			unset($awardsQueryResult["SORT"]);
			unset($awardsQueryResult["PREVIEW_TEXT"]);
			unset($awardsQueryResult["~PREVIEW_TEXT"]);
			unset($awardsQueryResult["~PREVIEW_TEXT_TYPE"]);
			unset($awardsQueryResult["PREVIEW_TEXT_TYPE"]);
			unset($awardsQueryResult["~NAME"]);
			unset($awardsQueryResult["NAME"]);
			$awards[] = $awardsQueryResult;
		}
		return $awards;
    }
}
