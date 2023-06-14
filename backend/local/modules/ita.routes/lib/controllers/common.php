<?php

namespace Ita\Routes\Controllers;

use \Bitrix\Main\ {
    Context,
    Loader,
    Error,
    Entity,
    ORM\Query
};
use \Bitrix\Iblock\ {
    IblockTable,
    ElementTable
};
class Common extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			"search" => [
				"prefilters" => [],
				"postfilters" => []
			],
            "searchTypes" => [
                "prefilters" => [],
                "postfilters" => []
            ],
		];
	}

	public function searchAction()
    {
        Loader::includeModule("iblock");
        Loader::includeModule("search");
        Loader::includeModule("realty");
        $result = [];

        $request = Context::getCurrent()->getRequest();
        $query = $request->getQuery("query");
        $limit = $request->getQuery("limit") ?? 0;
        $offset = $request->getQuery("offset") ?? 0;
        $type = $request->getQuery("type") ?? "info";
        if ($query) {
            $items = [];
            if ($type == "object") {
                $exludeIds = [];
                $ids = [];
                $count = 0;
                $objects = [];
                $metroLines = [];
                $linesColor = [];
                $filter = ["active" => 1];
                $re = '/(а|А)ренд(у|а)/m';
                if (preg_match_all($re, $query, $matches, PREG_SET_ORDER, 0)) {
                    $query = trim(preg_replace($re, "", $query));
                    $filter["propertyLease"] = 1;
                }
                $re = '/(п|П)родаж(у|а)/m';
                if (preg_match_all($re, $query, $matches, PREG_SET_ORDER, 0)) {
                    $query = trim(preg_replace($re, "", $query));
                    $filter["propertySale"] = 1;
                }
                $realty = \RealtyObjectsClassTable::getList([
                    "filter" => $query ? array_merge($filter, ["%PropertyNameRus" => $query,]) : $filter,
                    "select" => ["id"],
                    "count_total" => true,
                    "cache" => [
                        "ttl" => 43200,
                    ],
                ]);
                while ($object = $realty->fetch()) {
                    $exludeIds[] = $object["id"];
                }
                $count = $count + $realty->getCount();

                $select = [
                    "id",
                    "code",
                    "PropertyNameRus",
                    "PropertyDescriptionRus",
                    "metroName" => "METRO.NameRus",
                    "metroLine" => "METRO.metroLine",
                    "PropertyAddressRus",
                    "PropertyType",
                    "PropertySale",
                    "PropertyLease",
                    "DistanceFromMetro",
                ];
                $realty = \RealtyObjectsClassTable::getList([
                    "filter" => $query ? array_merge($filter, ["%PropertyNameRus" => $query,]) : $filter,
                    "select" => $select,
                    "limit" => $limit,
                    "offset" => $offset,
                    "runtime" => [
                        new Entity\ReferenceField(
                            "METRO",
                            \RealtyMetrosClassTable::class,
                            Query\Join::on('this.MetroStation', 'ref.id'),
                            [
                                "join_type" => Query\Join::TYPE_LEFT,
                            ]
                        ),
                    ],
                    "cache" => [
                        "ttl" => 43200,
                        "cache_joins" => true,
                    ],
                ]);
                while ($object = $realty->fetch()) {
                    $ids[] = $object["id"];
                    $objects[$object["id"]] = [
                        "code" => $object["code"],
                        "propertyNameRus" => $object["PropertyNameRus"],
                        "preview" => $object["PropertyDescriptionRus"],
                        "metroStation" => $object["metroName"],
                        "distanceFromMetro" => $object["DistanceFromMetro"],
                        "propertyLease" => $object["PropertyLease"],
                        "propertySale" => $object["PropertySale"],
                        "propertyType" => $object["PropertyType"],
                        "propertyAddressRus" => $object["PropertyAddressRus"],
                        "metroLine" => $object["metroLine"],
                        "pictures" => [],
                    ];
                    if ($object["metroLine"]) {
                        $metroLines[] = $object["metroLine"];
                    }
                }
                if (($offset + $limit > $count) && $query) {
                    $filter["!id"] = $exludeIds;
                    $descOffset = max($offset - $count, 0);
                    $descLimit = $limit - count($ids);
                    $realty = \RealtyObjectsClassTable::getList([
                        "filter" => $query ? array_merge($filter, ["%PropertyDescriptionRus" => $query,]) : $filter,
                        "select" => $select,
                        "count_total" => true,
                        "limit" => $descLimit,
                        "offset" => $descOffset,
                        "runtime" => [
                            new Entity\ReferenceField(
                                "METRO",
                                \RealtyMetrosClassTable::class,
                                Query\Join::on('this.MetroStation', 'ref.id'),
                                [
                                    "join_type" => Query\Join::TYPE_LEFT,
                                ]
                            ),
                        ],
                        "cache" => [
                            "ttl" => 43200,
                            "cache_joins" => true,
                        ],
                    ]);
                    while ($object = $realty->fetch()) {
                        $ids[] = $object["id"];
                        $objects[$object["id"]] = [
                            "code" => $object["code"],
                            "propertyNameRus" => $object["PropertyNameRus"],
                            "preview" => $object["PropertyDescriptionRus"],
                            "metroStation" => $object["metroName"],
                            "distanceFromMetro" => $object["DistanceFromMetro"],
                            "propertyLease" => $object["PropertyLease"],
                            "propertySale" => $object["PropertySale"],
                            "propertyType" => $object["PropertyType"],
                            "propertyAddressRus" => $object["PropertyAddressRus"],
                            "metroLine" => $object["metroLine"],
                            "pictures" => [],
                        ];
                        if ($object["metroLine"]) {
                            $metroLines[] = $object["metroLine"];
                        }
                    }
                    $count = $count + $realty->getCount();
                }
                if ($ids) {
                    $queryObjectPicture = \RealtyPicturesClassTable::getList(
                        [
                            "filter" => ["object_id" => $ids],
                            "select" => ["file", "object_id"]
                        ]
                    );
                    while ($queryObjectPictureArr = $queryObjectPicture->Fetch()) {
                        if (!file_exists($_SERVER["DOCUMENT_ROOT"] . \CRealty::getWatermarkedPictureFilename($queryObjectPictureArr["file"]))) {
                            \CRealty::putWatermark(
                                $queryObjectPictureArr["file"]
                            );
                        }
                        $objects[$queryObjectPictureArr["object_id"]]["pictures"][] = \CRealty::getWatermarkedPictureFilename($queryObjectPictureArr["file"]);
                    }
                    $queryRooms = \RealtyRoomsClassTable::getList([
                        "filter" => ["object_id" => $ids],
                        "select" => ["UnitSize", "object_id"],
                        "order" => ["UnitSize" => "ASC"]
                    ]);
                    while ($room = $queryRooms->fetch()) {
                        $objects[$room["object_id"]]["rooms"][] = $room["UnitSize"];
                    }
                    if ($metroLines) {
                        $elements = \Bitrix\Iblock\Elements\ElementMetrolinesTable::getList([
                            "filter" => ["ID" => $metroLines],
                            "select" => ["ID", "COLOR_VALUE" => "COLOR.VALUE"]
                        ]);
                        while ($element = $elements->fetch()) {
                            $linesColor[$element["ID"]] = mb_strtolower($element["COLOR_VALUE"]);
                        }
                    }

                    foreach ($objects as $object) {
                        $object["roomCount"] = count($object["rooms"]);
                        $object["minUnitSize"] = min($object["rooms"]);
                        $object["maxUnitSize"] = max($object["rooms"]);
                        $object["metroColor"] = "";
                        if ($linesColor[$object["metroLine"]]) {
                            $object["metroColor"] = $linesColor[$object["metroLine"]];
                        }
                        unset($object["metroLine"]);
                        unset($object["rooms"]);
                        $items[] = $object;
                    }
                }
            } else {
                $types = [
                    "news" => "news",
                    "article" => "blog",
                    "services" => "services",
                    "research" => "analitics",
                    "projects" => "case_studies"
                ];
                if ($type !== "info") {
                    foreach ($types as $k => $v) {
                        if ($v !== $type) {
                            unset($types[$k]);
                        }
                    }
                }
                $iBlocks = [];
                if ($types) {
                    $iBlockTable = IblockTable::getList([
                        "filter" => ["CODE" => \array_values($types)]
                    ]);
                    while ($iBlock = $iBlockTable->fetch()) {
                        foreach ($types as $k => $v) {
                            if ($iBlock["CODE"] == $v) {
                                $iBlocks[$iBlock["ID"]] = $k;
                                break;
                            }
                        }
                    }
                    $obSearch = new \CSearch;
                    $obSearch->Search(
                        [
                            "QUERY" => $query,
                            "SITE_ID" => "s1",
                            "MODULE_ID" => "iblock",
                            "=PARAM2" => \array_keys($iBlocks)
                        ],
                        [
                            "CUSTOM_RANK" => "DESC",
                            "RANK" => "DESC",
                            "DATE_FROM" => "DESC",
                        ],
                    );
                    $iBlockElements = [];
                    $i = 0;
                    $cnt = 0;
                    while ($search = $obSearch->GetNext()) {
                        $i++;
                        if ($i <= $offset) {
                            continue;
                        }
                        $cnt++;
                        $iBlockElements[$search["ITEM_ID"]] = $search["BODY_FORMATED"];
                        if ($cnt == $limit) {
                            break;
                        }
                    }
                    $elements = ElementTable::getList([
                        "filter" => ["ID" => \array_keys($iBlockElements)],
                        "order" => [
                            "ACTIVE_FROM" => "DESC",
                            "DATE_CREATE" => "DESC"
                        ],
                    ]);
                    while ($element = $elements->fetch()) {
                        $items[] = [
                            "code" => $element["CODE"],
                            "name" => $element["NAME"],
                            "type" => $iBlocks[$element["IBLOCK_ID"]],
                            "preview" => $iBlockElements[$element["ID"]]
                        ];
                    }

                    $count = $obSearch->selectedRowsCount();
                } else {
                    $this->addError(new Error("Wrong 'type' param to search", 500));

                    return null;
                }
            }
            $result["items"] = $items;
            $result["limit"] = (int)$limit;
            $result["offset"] = (int)$offset;
            $result["count"] = (int)$count;

            return $result;
        } else {
            $this->addError(new Error("Need 'query' param to search", 404));

            return null;
        }
    }
    
    public function searchTypesAction()
    {
        $result = [
            ["code" => "object", "name" => "Объекты"],
            ["code" => "info", "name" => "Все инфоблоки"],
            ["code" => "news", "name" => "Новости"],
            ["code" => "article", "name" => "Статьи"],
            ["code" => "services", "name" => "Услуги"],
            ["code" => "research", "name" => "Исследования"],
            ["code" => "projects", "name" => "Проекты"],
        ];

        return $result;
    }
}
