<?php

namespace Ita\Routes\Controllers;

class Team extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'getTeam' => [
				'prefilters' => [],
				'postfilters' => []
			]
		];
	}

	public function getTeamAction()
    {
		\Bitrix\Main\Loader::includeModule('iblock');
		$iblockIdPersons = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'persons'],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ])->fetch()["ID"];
		$arrWorkers = [];
		$queryPersons = \CIBlockElement::GetList(
			["SORT"=>"ASC"],
			["IBLOCK_ID" => $iblockIdPersons, "PROPERTY_P_ABOUT_COMP_VALUE" => "Да"],//, "PROPERTY_P_ABOUT_COMP_VALUE" => "Да"
			false,
			false,
			["ID", "PREVIEW_PICTURE", "DETAIL_PICTURE", "NAME", "IBLOCK_ID", "PROPERTY_*", "PREVIEW_TEXT"]
		);
		while($queryPersonsRes = $queryPersons->GetNextElement()) {
			$arWorker = $queryPersonsRes->GetFields();
			if($arWorker["PREVIEW_PICTURE"]) {
				$arWorker["PREVIEW_PICTURE"] = \CFile::ResizeImageGet(
					$arWorker["PREVIEW_PICTURE"], 
					[
					  'width' => 220,
					  'height' => 220
					], 
					BX_RESIZE_IMAGE_PROPORTIONAL,
					[
						"name" => "sharpen", 
						"precision" => 0
					]
				);
			}
			if($arWorker["DETAIL_PICTURE"]) {
				$arWorker["DETAIL_PICTURE"] = \CFile::ResizeImageGet(
					$arWorker["DETAIL_PICTURE"], 
					[
					  'width' => 484,
					  'height' => 484
					], 
					BX_RESIZE_IMAGE_PROPORTIONAL,
					[
						"name" => "sharpen", 
						"precision" => 0
					]
				);
			}
			$arWorker["image"] = (string)$arWorker["PREVIEW_PICTURE"]["src"];
			unset($arWorker["PREVIEW_PICTURE"]);
			$arWorker["imageDetail"] = (string)$arWorker["DETAIL_PICTURE"]["src"];
			unset($arWorker["DETAIL_PICTURE"]);
			$arWorker["PROPERTIES"] = $queryPersonsRes->GetProperties();
			$arWorker["jobTitle"] = $arWorker["PROPERTIES"]["POST_FULL"]["VALUE"];
			$arWorker["phone"] = $arWorker["PROPERTIES"]["P_PHONE"]["VALUE"];
			$arWorker["email"] = $arWorker["PROPERTIES"]["P_EMAIL"]["VALUE"];
			$arWorker["whatsapp"] = $arWorker["PROPERTIES"]["WHATSAPP"]["VALUE"];
			$arWorker["telegram"] = $arWorker["PROPERTIES"]["TELEGRAM"]["VALUE"];
			$arWorker["viber"] = $arWorker["PROPERTIES"]["VIBER"]["VALUE"];
			$arWorker["yearExperience"] = $arWorker["PROPERTIES"]["YEAR_EXPERIENCE"]["VALUE"];
			$arWorker["previewText"] = $arWorker["PREVIEW_TEXT"];
			$arWorker["name"] = $arWorker["NAME"];
			$arWorker["experienceHistory"] = "";
			if ($arWorker["PROPERTIES"]["EXPERIENCE_HISTORY"]["VALUE"]) {
                $arWorker["experienceHistory"] = $arWorker["PROPERTIES"]["EXPERIENCE_HISTORY"]["VALUE"]["TEXT"];
            }
			unset($arWorker["PROPERTIES"]);
			unset($arWorker['~PREVIEW_PICTURE']);
			unset($arWorker['~DETAIL_PICTURE']);
			unset($arWorker['NAME']);
			unset($arWorker['~NAME']);
			unset($arWorker["IBLOCK_ID"]);
			unset($arWorker['~IBLOCK_ID']);
			unset($arWorker['SORT']);
			unset($arWorker['~SORT']);
			unset($arWorker['PREVIEW_TEXT']);
			unset($arWorker['~PREVIEW_TEXT']);
			unset($arWorker['PREVIEW_TEXT_TYPE']);
			unset($arWorker['~PREVIEW_TEXT_TYPE']);
			unset($arWorker["ID"]);
			unset($arWorker["~ID"]);
			$arrWorkers[] = $arWorker;
		}
		return $arrWorkers;
    }
}
