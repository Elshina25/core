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

use Bitrix\Main\Error;

class Service extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'list' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'getForWhom' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'getTypes' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'detail' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'doneProjects' => [
				'prefilters' => [],
				'postfilters' => []
			]
		];
	}

	public function listAction()
    {
		\Bitrix\Main\Loader::includeModule('iblock');
		$iblockId = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'services']
		])->fetch()["ID"];
		$request = Context::getCurrent()->getRequest();
		$forWhom = $request->getQuery('forWhom');
		$type = $request->getQuery('type');
		$rsSection = \Bitrix\Iblock\SectionTable::getList(
			[
				'filter' => ['IBLOCK_ID' => $iblockId],
				'select' => ['NAME', "ID", "PICTURE", "CODE", "DESCRIPTION"]
			]
		);
		$result = [];
		$filter = [];
		$filter['=ACTIVE'] = "Y";
		if(!empty($forWhom)) {
			$filter['FOR_WHOM_SERVICES_'] = $forWhom;
		}
		if(!empty($type)) {
			$filter['TYPE_'] = $type;
		}
		while($arSection = $rsSection->Fetch())
		{
			$arSection["items"] = [];
			if($arSection["PICTURE"]) {
				$arSection["PICTURE"] = \CFile::GetPath($arSection["PICTURE"]);
			}
			$sectprearr["name"] = $arSection["NAME"];
			$sectprearr["id"] = $arSection["ID"];
			$sectprearr["picture"] = (string)$arSection["PICTURE"];
			$sectprearr["code"] = $arSection["CODE"];
			$sectprearr["description"] = $arSection["DESCRIPTION"];
			$result[] = $sectprearr;
		}
        $servicesQuery = \Bitrix\Iblock\Elements\ElementServicesTable::getList([
            "select" => [
                "CODE",
                "ID",
                "NAME",
                "FOR_WHOM_SERVICES_" =>"FOR_WHOM_SERVICES.VALUE",
                "TYPE_" => "TYPE.VALUE",
                "IBLOCK_SECTION_ID",
                "FAST_LINK_VALUE" => "FAST_LINK.ITEM.VALUE",
                "ACTIVE_FROM",
                "DATE_CREATE"
            ],
            "filter" => $filter,
            "order" => ["ACTIVE_FROM" => "DESC"],
        ]);
		$services = [];
		while($servicesQueryObject = $servicesQuery->fetchObject()) {
			$services[] = $servicesQueryObject;
		}
		foreach($result as &$section){
			$section['items'] = [];
			foreach($services as $service){
				if($section["id"] == $service->get("IBLOCK_SECTION_ID")){
					$serviceprearr["code"] = $service->get("CODE");
					$serviceprearr["id"] = $service->get("ID");
					$serviceprearr["name"] = $service->get("NAME");
					$serviceprearr["iblockSectionId"] = $service->get("IBLOCK_SECTION_ID");
					if($service->getFastLink()) {
						$serviceprearr["fastLink"] = $service->getFastLink()->getItem()->getValue();
					} else {
						$serviceprearr["fastLink"] = false;
					}
                    if ($service->getActiveFrom()) {
                        $serviceprearr["date"] = $service->getActiveFrom()->format("d.m.Y");
                    } else {
                        $serviceprearr["date"] = $service->getDateCreate()->format("d.m.Y");
                    }
                    $section['items'][] = $serviceprearr;
				}
			}
		}
       	return $result;
    }

	public function getForWhomAction()
	{
		\Bitrix\Main\Loader::includeModule('iblock');
		$iblockId = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'services']
		])->fetch()["ID"];
		$queryEnum = \CIBlockPropertyEnum::GetList(
 			["ID"=>"ASC"],
			["IBLOCK_ID" => $iblockId, "CODE" => "FOR_WHOM_SERVICES"]
		);
		$result = [['name' => 'Все услуги', 'code' => 0]];
		while($queryEnumArr = $queryEnum->Fetch()) {
			$result[] = ['name' => $queryEnumArr["VALUE"], 'code' => $queryEnumArr["ID"]];
		}
		return $result;
	}

	public function getTypesAction()
	{
		\Bitrix\Main\Loader::includeModule('iblock');
		$iblockId = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'services']
		])->fetch()["ID"];
		$queryEnum = \CIBlockPropertyEnum::GetList(
 			["SORT"=>"ASC"],
			["IBLOCK_ID" => $iblockId, "CODE" => "TYPE"]
		);
		$result = [['name' => 'Все виды', 'code' => 0]];
		while($queryEnumArr = $queryEnum->Fetch()) {
			$result[] = ['name' => $queryEnumArr["VALUE"], 'code' => $queryEnumArr["ID"]];
		}
		return $result;
	}

	public function detailAction(string $code)
	{
		\Bitrix\Main\Loader::includeModule('iblock');
		$iblockIdService = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'services'],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ])->fetch()["ID"];
		$iblockIdPersons = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'persons'],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ])->fetch()["ID"];

		$serviceQuery = \CIBlockElement::GetList(
			["SORT"=>"ASC"],
			["IBLOCK_ID" => $iblockIdService, "=CODE" => $code, "=ACTIVE" => "Y"],
			false,
			false,
			["ID", "NAME", "IBLOCK_ID", "DETAIL_TEXT", "CODE", "PROPERTY_*", "IBLOCK_SECTION_ID"]
		);
		$serviceQueryRes = $serviceQuery->GetNextElement();
		if(!$serviceQueryRes) {
			$this->addError(new Error('Element could not be found by code.', 404));
			return [];
		}
		$service = $serviceQueryRes->GetFields();
		$service["PROPERTIES"] = $serviceQueryRes->GetProperties();
        $service["utp"] = [];
        foreach ($service["PROPERTIES"]["UTP_TITLE"]["VALUE"] as $key => $title) {
            if ($service["PROPERTIES"]["UTP_DESC"]["VALUE"][$key]) {
                $service["utp"][] = [
                    "title" => $title,
                    "desc" => $service["PROPERTIES"]["UTP_DESC"]["VALUE"][$key]["TEXT"]
                ];
            }
        }
		$arrWorkers = [];
		$queryPersons = \CIBlockElement::GetList(
			["SORT"=>"ASC"],
			["IBLOCK_ID" => $iblockIdPersons, "ID" => $service["PROPERTIES"]["WORKERS"]["VALUE"]],
			false,
			false,
			["ID", "PREVIEW_PICTURE", "NAME", "IBLOCK_ID", "PROPERTY_*"]
		);
		while($queryPersonsRes = $queryPersons->GetNextElement()) {
			$arWorker = $queryPersonsRes->GetFields();
			if($arWorker["PREVIEW_PICTURE"]) {
				$arWorker["PREVIEW_PICTURE"] = \CFile::ResizeImageGet(
						$arWorker["PREVIEW_PICTURE"], 
						[
						  'width' => 200,
						  'height' => 200
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
			$arWorker["id"] = $arWorker["ID"];
			unset($arWorker["ID"]);
			$arWorker["name"] = $arWorker["NAME"];
			unset($arWorker["NAME"]);
			$arWorker["PROPERTIES"] = $queryPersonsRes->GetProperties();
			$arWorker["jobTitle"] = $arWorker["PROPERTIES"]["POST_FULL"]["VALUE"];
			$arWorker["phone"] = $arWorker["PROPERTIES"]["P_PHONE"]["VALUE"];
			$arWorker["email"] = $arWorker["PROPERTIES"]["P_EMAIL"]["VALUE"];
			$arWorker["whatsapp"] = $arWorker["PROPERTIES"]["WHATSAPP"]["VALUE"];
			$arWorker["telegram"] = $arWorker["PROPERTIES"]["TELEGRAM"]["VALUE"];
			$arWorker["viber"] = $arWorker["PROPERTIES"]["VIBER"]["VALUE"];
			unset($arWorker["PROPERTIES"]);
			unset($arWorker["~ID"]);
			unset($arWorker["~PREVIEW_PICTURE"]);
			unset($arWorker["~NAME"]);
			unset($arWorker["~IBLOCK_ID"]);
			unset($arWorker["IBLOCK_ID"]);
			unset($arWorker["~SORT"]);
			unset($arWorker["SORT"]);
			$arrWorkers[] = $arWorker;
		}
		$service["workers"] = $arrWorkers;
		/* Проекты начало */
        $items = [];
        $selectProj = [
            'ID',
            'NAME',
            'TYPE_' => 'TYPE.ITEM',
            'SECTION_' => 'IBLOCK_SECTION',
            'PREVIEW_PICTURE',
            'ACTIVE_FROM',
            'CODE'
        ];
        $filterProj = [
            '=ACTIVE' => 'Y',
			'SERVICES.VALUE' => $service["ID"]
        ];

        $elements = \Bitrix\Iblock\Elements\ElementProjectsTable::getList([
            'select' => $selectProj,
            'filter' => $filterProj,
            'order' => ['ACTIVE_FROM' => 'DESC'],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($element = $elements->fetch()) {
            $item = [];
            $item['name'] = $element['NAME'];
            $item['id'] = $element['ID'];
            $item['slug'] = $element['CODE'];
            if ($element['PREVIEW_PICTURE']) {
                $item['image'] = \CFile::GetPath($element['PREVIEW_PICTURE']);
            } else {
                $item['image'] = '';
            }
            $item['type'] = '';
            if ($element['TYPE_VALUE']) {
                $item['type'] = $element['TYPE_VALUE'];
            }
            $item['date'] = '';
            if ($element['ACTIVE_FROM']) {
                $item['date'] = $element['ACTIVE_FROM']->format("d.m.Y");
            }
            $item['section'] = $element['SECTION_NAME'];

            $items[] = $item;
        }
		/* Проекты Конец*/
		$service["doneProjects"] = $items;

		$rsSection = \Bitrix\Iblock\SectionTable::getList(
			[
				'filter' => ['IBLOCK_ID' => $iblockIdService],
				'select' => ['NAME', "ID", "PICTURE", "CODE"],
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ],
            ]
		);
		$resultOtherService = [];
		$filterServ = [];
		$filterServ['=ACTIVE'] = "Y";
		$filterServ["!CODE"] = $code;
		while($arSection = $rsSection->Fetch())
		{
			if ($arSection["ID"] == $service["IBLOCK_SECTION_ID"]) {
			    $service["section"] = [
			        "code" => $arSection["CODE"],
                    "id" => $arSection["ID"]
                ];
			    unset($service["IBLOCK_SECTION_ID"]);
			    unset($service["~IBLOCK_SECTION_ID"]);
            }
		    $arSection["ITEMS"] = [];
			if($arSection["PICTURE"]) {
				$arSection["PICTURE"] = \CFile::GetPath($arSection["PICTURE"]);
			}
			$sectprearr["name"] = $arSection["NAME"];
			$sectprearr["id"] = $arSection["ID"];
			$sectprearr["picture"] = (string)$arSection["PICTURE"];
			$sectprearr["code"] = $arSection["CODE"];
			$sectprearr["description"] = $arSection["DESCRIPTION"];
			$resultOtherService[] = $sectprearr;
		}
		if(!empty($service["PROPERTIES"]["TYPE"]["VALUE_ENUM_ID"]) && empty($service["PROPERTIES"]["FOR_WHOM_SERVICES"]["VALUE_ENUM_ID"])) {
			$filterServ["TYPE_"] = $service["PROPERTIES"]["TYPE"]["VALUE_ENUM_ID"];
		}
		if(!empty($service["PROPERTIES"]["FOR_WHOM_SERVICES"]["VALUE_ENUM_ID"]) && empty($service["PROPERTIES"]["TYPE"]["VALUE_ENUM_ID"])) {
			$filterServ["FOR_WHOM_SERVICES_"] = $service["PROPERTIES"]["FOR_WHOM_SERVICES"]["VALUE_ENUM_ID"];
		}
		if(!empty($service["PROPERTIES"]["TYPE"]["VALUE_ENUM_ID"]) && !empty($service["PROPERTIES"]["FOR_WHOM_SERVICES"]["VALUE_ENUM_ID"])) {
            $filterServ["TYPE_"] = $service["PROPERTIES"]["TYPE"]["VALUE_ENUM_ID"];
            $filterServ["FOR_WHOM_SERVICES_"] = $service["PROPERTIES"]["FOR_WHOM_SERVICES"]["VALUE_ENUM_ID"];
		}
		$servicesAllQuery = \Bitrix\Iblock\Elements\ElementServicesTable::getList([
			'select' => ["CODE", 'ID', 'NAME', 'FOR_WHOM_SERVICES_' =>'FOR_WHOM_SERVICES.VALUE', 'TYPE_' => 'TYPE.VALUE', "IBLOCK_SECTION_ID", "FAST_LINK_VALUE" => "FAST_LINK.ITEM.VALUE"],
			'filter' => $filterServ,
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ]
        ]);
		$servicesAll = [];
		while($servicesAllQueryObject = $servicesAllQuery->fetchObject()) {
			$servicesAll[] = $servicesAllQueryObject;
		}
		foreach($resultOtherService as &$section){
			$section['items'] = [];
			foreach($servicesAll as $service2){
				if($section["id"] == $service2->get("IBLOCK_SECTION_ID")){
					$serviceprearr["code"] = $service2->get("CODE");
					$serviceprearr["id"] = $service2->get("ID");
					$serviceprearr["name"] = $service2->get("NAME");
					$serviceprearr["iblockSectionId"] = $service2->get("IBLOCK_SECTION_ID");
					if($service2->getFastLink()) {
						$serviceprearr["fastLink"] = $service2->getFastLink()->getItem()->getValue();
					} else {
						$serviceprearr["fastLink"] = false;
					}
					$section['items'][] = $serviceprearr;
				}
			}
		}
		$service["otherService"] = $resultOtherService;
        $service["forWhom"] = [];
		if ($service["PROPERTIES"]["FOR_WHOM_SERVICES"]) {
		    $service["forWhom"] = [
		        "name" => $service["PROPERTIES"]["FOR_WHOM_SERVICES"]["VALUE"],
		        "code" => $service["PROPERTIES"]["FOR_WHOM_SERVICES"]["VALUE_ENUM_ID"],
            ];
        }
        $service["type"] = [];
        if ($service["PROPERTIES"]["TYPE"]) {
            $service["type"] = [
                "name" => $service["PROPERTIES"]["TYPE"]["VALUE"],
                "code" => $service["PROPERTIES"]["TYPE"]["VALUE_ENUM_ID"],
            ];
        }
		unset($service["PROPERTIES"]);
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($iblockIdService, $service['ID']);
		$service["seo"] = $ipropValues->getValues();
		$service["seo"]["elementMetaKeywords"] = $service["seo"]["ELEMENT_META_KEYWORDS"];
		unset($service["seo"]["ELEMENT_META_KEYWORDS"]);
		$service["seo"]["elementMetaDescription"] = $service["seo"]["ELEMENT_META_DESCRIPTION"];
		unset($service["seo"]["ELEMENT_META_DESCRIPTION"]);
		$service["seo"]["elementMetaTitle"] = $service["seo"]["ELEMENT_META_TITLE"];
		unset($service["seo"]["ELEMENT_META_TITLE"]);
		unset($service["~PREVIEW_TEXT"]);
		unset($service["~DETAIL_TEXT"]);
		unset($service["SORT"]);
		unset($service["~SORT"]);
		unset($service["~IBLOCK_ID"]);
		unset($service["IBLOCK_ID"]);
		unset($service["~ID"]);
		unset($service["~NAME"]);
		unset($service["DETAIL_TEXT_TYPE"]);
		unset($service["PREVIEW_TEXT_TYPE"]);
		unset($service["~DETAIL_TEXT_TYPE"]);
		unset($service["~PREVIEW_TEXT_TYPE"]);
		unset($service["~CODE"]);
		$service["id"] = $service["ID"];
		unset($service["ID"]);
		$service["name"] = $service["NAME"];
		unset($service["NAME"]);
		$service["detailText"] = $service["DETAIL_TEXT"];
		unset($service["DETAIL_TEXT"]);
		$service["code"] = $service["CODE"];
		unset($service["CODE"]);

		return $service;
	}
	public function doneProjectsAction()
	{
		\Bitrix\Main\Loader::includeModule('iblock');
		$request = Context::getCurrent()->getRequest();
        $sectionFilter = $request->getQuery('section') ?? 'all';
        $typeFilter = $request->getQuery('type') ?? 'all';
        $exclude = $request->getQuery('exclude');
        $result = [];
        $items = [];
        $select = [
            'ID',
            'NAME',
            'TYPE_' => 'TYPE.ITEM',
            'SECTION_' => 'IBLOCK_SECTION',
            'PREVIEW_PICTURE',
            'ACTIVE_FROM',
            'CODE'
        ];
        $filter = [
            '=ACTIVE' => 'Y',
			'SHOW_ON_ALL_SERVICE.ITEM.XML_ID' => 'true'
        ];
        if ($sectionFilter !== 'all') {
            $filter['IBLOCK_SECTION.CODE'] = $sectionFilter;
        }
        if ($typeFilter !== 'all') {
            $filter['SPECIAL.ITEM.XML_ID'] = $typeFilter;
        }
        if ($exclude) {
            $filter["!CODE"] = $exclude;
        }

        $elements = \Bitrix\Iblock\Elements\ElementProjectsTable::getList([
            'select' => $select,
            'filter' => $filter,
            'order' => ['ACTIVE_FROM' => 'DESC'],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($element = $elements->fetch()) {
            $item = [];
            $item['name'] = $element['NAME'];
            $item['id'] = $element['ID'];
            $item['slug'] = $element['CODE'];
            if ($element['PREVIEW_PICTURE']) {
                $item['image'] = \CFile::GetPath($element['PREVIEW_PICTURE']);
            } else {
                $item['image'] = '';
            }
            $item['type'] = '';
            if ($element['TYPE_VALUE']) {
                $item['type'] = $element['TYPE_VALUE'];
            }
            $item['date'] = '';
            if ($element['ACTIVE_FROM']) {
                $item['date'] = $element['ACTIVE_FROM']->format("d.m.Y");
            }
            $item['section'] = $element['SECTION_NAME'];

            $items[] = $item;
        }
        $result['items'] = $items;

       	return $result;
	}
}
