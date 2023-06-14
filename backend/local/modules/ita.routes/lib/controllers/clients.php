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

class Clients extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'getClients' => [
				'prefilters' => [],
				'postfilters' => []
			]
		];
	}

	public function getClientsAction()
    {
       	\Bitrix\Main\Loader::includeModule('iblock');
		$clients = \Bitrix\Iblock\Elements\ElementClientsTable::getList(
		[
			'select' => ['ID', 'NAME', "SVG_PICTURE_SRC" => "SVG_PICTURE.VALUE"],
			'filter' => [],
            "cache" => [
                "ttl" => 86400,
            ],
        ]
		)->fetchAll();
		foreach($clients as &$arItem){
			if($arItem["SVG_PICTURE_SRC"]){
				$arItem["SVG_PICTURE_SRC"] = \CFile::GetPath((int)$arItem["SVG_PICTURE_SRC"]);
			}
			$arItem["PICTURE"] = $arItem["SVG_PICTURE_SRC"];
			unset($arItem["SVG_PICTURE_SRC"]);
			$arItem = array_change_key_case($arItem);
		}
		return $clients;
    }
}
