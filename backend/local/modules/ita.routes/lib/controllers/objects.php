<?php

namespace Ita\Routes\Controllers;

use \Bitrix\Main\ {
    Context,
    Loader
};

use Bitrix\Main\{
    Entity,
    Error,
    ORM\Fields\IntegerField,
    ORM\Query};

class Objects extends \Bitrix\Main\Engine\Controller
{
	/*
	 * Типы объекта (propertyType)
	 */
	const PROPERTY_TYPE_WAREHOUSE = 1;	//склады
	const PROPERTY_TYPE_PREMISE = 2;		//торговые помещения
	const PROPERTY_TYPE_OFFICE = 3;		//офисы

	public function configureActions()
	{
		return [
			'objectsMap' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'getCities' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'getTypes' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'getMetros' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'getCounty' => [
				'prefilters' => [],
				'postfilters' => []
			],
            'detail' => [
                'prefilters' => [],
                'postfilters' => []
            ],
			'getCountiesAndRaions' => [
				'prefilters' => [],
				'postfilters' => []
            ],
			'getCoords' => [
				'prefilters' => [],
				'postfilters' => []
            ],
			'getObjectPreview' => [
				'prefilters' => [],
				'postfilters' => []
            ],
			'getObjectFavorite' => [
				'prefilters' => [],
				'postfilters' => []
            ],
            'searchNamePreview' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'searchAddressPreview' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'getDirections' => [
                'prefilters' => [],
                'postfilters' => []
            ],
		];
	}

	public function objectsMapAction()
    {
		$request = Context::getCurrent()->getRequest();
		$excludeCode = $request->getQuery('excludeCode');
        $byTab = $request->getQuery('byTab');

        $tabType = !empty($byTab) ? $byTab['type'] : false;
        if ('rent' === $tabType) {
            $grossLeasableAreaMin = $byTab['grossAreaMin']; //площадь от
            $grossLeasableAreaMax = $byTab['grossAreaMax']; //площадь до
            $minRentPrice = $byTab['minPrice']; //минимальная цена аренды
            $maxRentPrice = $byTab['maxPrice']; //максимальная цена аренды
            $topInclude = $byTab['topInclude']; //выгодные предложения аренда
        } elseif ('sale' === $tabType) {
            $grossBuildingAreaMin = $byTab['grossAreaMin']; //площадь от
            $grossBuildingAreaMax = $byTab['grossAreaMax']; //площадь до
            $minSalePrice = $byTab['minPrice']; //минимальная цена продажи
            $maxSalePrice = $byTab['maxPrice'];; //максимальная цена продажи
            $topIncludeSale = $byTab['topInclude']; //выгодные предложения продажа
        }

        if (!empty($byTab) && $byTab['topInclude']) {
            $topIncludeSys = 1;
        }

        /*
		$grossLeasableAreaMin = $request->getQuery('grossLeasableAreaMin');
		$grossLeasableAreaMax = $request->getQuery('grossLeasableAreaMax');
        $minRentPrice = $request->getQuery('minRentPrice'); //минимальная цена аренды
        $maxRentPrice = $request->getQuery('maxRentPrice'); //максимальная цена аренды
        $topInclude = $request->getQuery('topInclude'); //выгодные предложения аренда

		$grossBuildingAreaMin = $request->getQuery('grossBuildingAreaMin'); //площадь от
		$grossBuildingAreaMax = $request->getQuery('grossBuildingAreaMax'); //площадь до
        $minSalePrice = $request->getQuery('minSalePrice'); //минимальная цена продажи
        $maxSalePrice = $request->getQuery('maxSalePrice'); //максимальная цена продажи
        $topIncludeSale = $request->getQuery('topIncludeSale'); //выгодные предложения продажа
        */

		$name = $request->getQuery('name'); //название
        $propertyAddressRus = $request->getQuery('propertyAddressRus'); //адрес
		$buildingClass = $request->getQuery('buildingClass');//Класс объекта
		$propertyLease = $request->getQuery('propertyLease'); //В аренду
		$propertySale = $request->getQuery('propertySale'); //На продажу
		$city = $request->getQuery('city'); // id города
		$propertyType = $request->getQuery('propertyType'); //Тип объекта
		$distanceFromMetro = $request->getQuery('distanceFromMetro'); //расстояние до метро
		$metroStation = $request->getQuery('metroStation'); // code станции метро
		$limit = $request->getQuery('limit') ?? 8;
        $offset = $request->getQuery('offset') ?? 0;
		$minUnitFloorNumber = $request->getQuery('minUnitFloorNumber'); //минимальный этаж
		$maxUnitFloorNumber = $request->getQuery('maxUnitFloorNumber'); //максимальный этаж
		$hasParking = $request->getQuery('hasParking'); //наличие парковки

        $distanceFromCity = $request->getQuery('distanceFromCity'); //расстояние до города
        $direction = $request->getQuery('direction'); //направление

		$county = $request->getQuery('cityCounty');//округ города
		$raion = $request->getQuery('cityArea');//район города

        $orderBy = $request->getQuery('orderBy'); // параметры сортировки
        $sortNew = $orderBy['newest'];  //сортировка по новизне
        $sortMinRentPrice = ('rent' === $tabType) ? $orderBy['minPrice'] : null; //сортировка по цене аренды
        $sortMinSalePrice = ('sale' === $tabType) ? $orderBy['minPrice'] : null; //сортировка по цене продажи
        $sortBuildingClass = $orderBy['buildingClass']; //сортировка по классу здания
        $sortGrossLeasableArea = ('rent' === $tabType) ? $orderBy['grossArea'] : null; //сортировка по площади в аренду
        $sortGrossBuildingArea = ('sale' === $tabType) ? $orderBy['grossArea'] : null; //сортировка по площади
        $sortRand = $orderBy["rand"] ?: null; //Сортировка в произвольном порядке

		//фильтровать по площади помещений или всего объекта
		$findAreaByRooms = in_array($propertyType, [
			self::PROPERTY_TYPE_OFFICE,
			self::PROPERTY_TYPE_PREMISE,
		]);

		$sort = [];
		if(!empty($sortMinRentPrice)) {
			$sort["min_rent_price"] = $sortMinRentPrice;
		}
		if(!empty($sortMinSalePrice)) {
			$sort["min_sale_price"] = $sortMinSalePrice;
		}
		if(!empty($sortBuildingClass)) {
			$sort["BuildingClass"] = $sortBuildingClass;
		}
		if(!empty($sortGrossLeasableArea)) {
			$sort["GrossLeasableArea"] = $sortGrossLeasableArea;
		}
		if(!empty($sortGrossBuildingArea)) {
			$sort["GrossBuildingArea"] = $sortGrossBuildingArea;
		}
		if(!empty($sortNew)) {
			$sort["id"] = $sortNew;
		}
        if(!empty($sortRand)) {
            $sort["RAND"] = $sortRand;
        }

		$filter = [];
		$filter["active"] = 1;
		if (!empty($name)) {
		    $filter["%PropertyNameRus"] = $name;
        }

		$filterRooms = [];
		if ($findAreaByRooms) {
			if (!empty($grossLeasableAreaMin) && !empty($grossLeasableAreaMax)) {
				$filter["><FLOORS.UnitSize"] = [$grossLeasableAreaMin, $grossLeasableAreaMax];
				$filterRooms["><UnitSize"] = $filter["><FLOORS.UnitSize"];
			} elseif (!empty($grossLeasableAreaMin)) {
				$filter[">=FLOORS.UnitSize"] = $grossLeasableAreaMin;
				$filterRooms[">=UnitSize"] = $filter[">=FLOORS.UnitSize"];
			} elseif (!empty($grossLeasableAreaMax)) {
				$filter["<=FLOORS.UnitSize"] = $grossLeasableAreaMax;
				$filterRooms["<=UnitSize"] = $filter["<=FLOORS.UnitSize"];
			}
		} else {
			if (!empty($grossLeasableAreaMin)) {
				$filter[">=GrossLeasableArea"] = $grossLeasableAreaMin;
			}
			if (!empty($grossLeasableAreaMax)) {
				$filter["<=GrossLeasableArea"] = $grossLeasableAreaMax;
			}
		}
		if(!empty($grossBuildingAreaMin)) {
			$filter[">=GrossBuildingArea"] = $grossBuildingAreaMin;
		}
		if(!empty($grossBuildingAreaMax)) {
			$filter["<=GrossBuildingArea"] = $grossBuildingAreaMax;
		}
		if(!empty($propertyAddressRus)) {
			$filter["%PropertyAddressRus"] = $propertyAddressRus;
		}
		if(!empty($buildingClass)) {
			$filter["BuildingClass"] = $buildingClass;
		}
		if(!empty($propertyLease)) {
			$filter["PropertyLease"] = $propertyLease;
		}
		if(!empty($propertySale)) {
			$filter["PropertySale"] = $propertySale;
		}
		if(!empty($city)) {
			$filter["City"] = $city;
		}
		if(!empty($propertyType)) {
			$filter["PropertyType"] = $propertyType;
		}
		if(!empty($distanceFromMetro)) {
			$filter['<=DistanceFromMetro'] = $distanceFromMetro;
            $filter['!MetroStation'] = false;
		}
		if(!empty($metroStation)) {
			$filter['METRO.code'] = $metroStation;
		}
        if(!empty($distanceFromCity)) {
            $filter['<=DistanceFromCity'] = $distanceFromCity;
            $filter['>DistanceFromCity'] = 0;
        }
        if(!empty($direction)) {
            $filter['Direction'] = $direction;
        }

		if(!empty($minRentPrice) && !empty($maxRentPrice)){
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_rent_price' => [$minRentPrice, $maxRentPrice]],
				['><max_rent_price' => [$minRentPrice, $maxRentPrice]],
				[
					['<min_rent_price' => $minRentPrice],
					['>max_rent_price' => $maxRentPrice]
				]
			];
		} else if(!empty($minRentPrice)) {
			$maxRentPrice = 2147483647;
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_rent_price' => [$minRentPrice, $maxRentPrice]],
				['><max_rent_price' => [$minRentPrice, $maxRentPrice]],
				[
					['<min_rent_price' => $minRentPrice],
					['>max_rent_price' => $maxRentPrice]
				]
			];
		} else if(!empty($maxRentPrice)) {
			$minRentPrice = 0;
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_rent_price' => [$minRentPrice, $maxRentPrice]],
				['><max_rent_price' => [$minRentPrice, $maxRentPrice]],
				[
					['<min_rent_price' => $minRentPrice],
					['>max_rent_price' => $maxRentPrice]
				]
			];
		}

		if(!empty($minSalePrice) && !empty($maxSalePrice)) {
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_sale_price' => [$minSalePrice, $maxSalePrice]],
				['><max_sale_price' => [$minSalePrice, $maxSalePrice]],
				[
					['<min_sale_price' => $minSalePrice],
					['>max_sale_price' => $maxSalePrice]
				]
			];
		} else if(!empty($minSalePrice)) {
			$maxSalePrice = 2147483647;
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_sale_price' => [$minSalePrice, $maxSalePrice]],
				['><max_sale_price' => [$minSalePrice, $maxSalePrice]],
				[
					['<min_sale_price' => $minSalePrice],
					['>max_sale_price' => $maxSalePrice]
				]
			];
		} else if(!empty($maxSalePrice)) {
			$minSalePrice = 0;
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_sale_price' => [$minSalePrice, $maxSalePrice]],
				['><max_sale_price' => [$minSalePrice, $maxSalePrice]],
				[
					['<min_sale_price' => $minSalePrice],
					['>max_sale_price' => $maxSalePrice]
				]
			];
		}
		if(!empty($minUnitFloorNumber) && !empty($maxUnitFloorNumber)){
			$filter["><FLOORS.UnitFloorNumber"] = [$minUnitFloorNumber, $maxUnitFloorNumber];
		} else if(!empty($minUnitFloorNumber)) {
			$filter[">=FLOORS.UnitFloorNumber"] = $minUnitFloorNumber;
		} else if(!empty($maxUnitFloorNumber)) {
			$filter["<=FLOORS.UnitFloorNumber"] = $maxUnitFloorNumber;
		}
		if(!empty($hasParking)) {
			$filter["!PARKING.id"] = null;
		}

        if(!empty($topIncludeSys)) {
            $filter["sys_top"] = $topIncludeSys;
        }
		if(!empty($topInclude)) {
			$filter["top_include"] = $topInclude;
		}
		if(!empty($topIncludeSale)) {
			$filter["top_include_sale"] = $topIncludeSale;
		}
        if(!empty($raion)) {
            $raionId = 0;
            $zones = \RealtyRaionClassTable::getList([
                "select" => ["id"],
                "filter" => ["code" => $raion],
                "cache" => [
                    "ttl" => 3600,
                ],
            ]);
            if ($zone = $zones->fetch()) {
                $raionId = $zone["id"];
            }
            if ($raionId == 0) {
                $raionId = (int)$raion;
            }

            $filterRaion = ["LOGIC" => "OR"];
            $filterRaion[] = ["ita_raion_id" => $raionId];
            $filterRaion[] = [
                "ita_raion_id" => false,
                "METRO.ita_raion_id" => $raionId,
            ];
            $filter[] = $filterRaion;
        }
        if(!empty($county)) {
            if(!empty($raion)) {
                $filter["COUNTY.code"] = $county;
            } else {
                $filterCounty = ["LOGIC" => "OR"];
                $filterCounty[] = [
                    "COUNTY.code" => $county,
                ];
                $zones = \RealtyRaionClassTable::getList([
                    "select" => ["id"],
                    "filter" => ["COUNTY.code" => $county,],
                    "runtime" => [
                        new Entity\ReferenceField(
                            "COUNTY",
                            \RealtyCountyClassTable::class,
                            Query\Join::on('this.ita_county_id', 'ref.id'),
                            [
                                "join_type" => Query\Join::TYPE_LEFT,
                            ]
                        ),
                    ],
                    "cache" => [
                        "ttl" => 3600,
                        "cache_joins" => true,
                    ],
                ]);
                while ($zone = $zones->fetch()) {
                    $filterCounty[] = [
                        "COUNTY.code" => false,
                        "METRO.ita_raion_id" => $zone["id"],
                    ];
                }
                $filter[] = $filterCounty;
            }
        }

		if(!empty($excludeCode)) {
			$filter['!code'] = $excludeCode;
		}

		$runtime = [
            new Entity\ReferenceField(
                "FLOORS",
                \RealtyRoomsClassTable::class,
                Query\Join::on('this.id', 'ref.object_id'),
                ["join_type" => Query\Join::TYPE_LEFT]
            ),
            new Entity\ReferenceField(
                "PARKING",
                \RealtyParkingsClassTable::class,
                Query\Join::on('this.id', 'ref.object_id'),
                [
                    "join_type" => Query\Join::TYPE_LEFT,
                ]
            ),
            new Entity\ReferenceField(
                "METRO",
                \RealtyMetrosClassTable::class,
                Query\Join::on('this.MetroStation', 'ref.id'),
                [
                    "join_type" => Query\Join::TYPE_LEFT,
                ]
            ),
            new Entity\ReferenceField(
                "COUNTY",
                \RealtyCountyClassTable::class,
                Query\Join::on('this.ita_county_id', 'ref.id'),
                [
                    "join_type" => Query\Join::TYPE_LEFT,
                ]
            ),
        ];
		if (!empty($sortRand)) {
		    $runtime[] = new Entity\ExpressionField(
                "RAND",
                "RAND()"
            );
        }

		$params = [
            'select'  => ["PropertyType", "PropertySale", "PropertyLease", "BuildingClass", "COUNTY.name_ru", "METRO.NameRus", "METRO.code", "metroLine" => "METRO.metroLine", 'id', 'min_rent_price', 'max_rent_price', 'min_sale_price', 'max_sale_price', 'X_coordinate', 'Y_coordinate', 'PropertyAddressRus', 'PropertyDescriptionRus', 'LocationDescriptionRus', 'PropertyNameRus', 'code', 'MetroStation', 'DistanceFromMetro', 'LocationZone', 'Direction'], // имена полей, которые необходимо получить в результате
            'filter'  => $filter, // описание фильтра для WHERE и HAVING
            'limit'   => $limit, // количество записей
            'offset' => $offset,
            'group' => ["id"],
            "runtime" => $runtime,
            "order" => $sort,
            'count_total' => true,
        ];
		if (empty($sortRand)) {
		    $params["cache"] = [
                "ttl" => 3600,
                "cache_joins" => true,
            ];
        }

		$queryObjects = \RealtyObjectsClassTable::getList($params);
        $arrObjects = [];
        $ids = [];
        $images = [];
        $rooms = [];
        \Bitrix\Main\Loader::includeModule('realty');
        $metroLines = [];
        while($qObjects = $queryObjects->Fetch()) {
            $ids[] = $qObjects['id'];
            // Приведем наименования к единому стилю
            $queryObjectsArr = [
                'id' => $qObjects['id'],
                'minRentPrice' => $qObjects['min_rent_price'],
                'maxRentPrice' => $qObjects['max_rent_price'],
                'minSalePrice' => $qObjects['min_sale_price'],
                'maxSalePrice' => $qObjects['max_sale_price'],
                'xCoordinate' => $qObjects['X_coordinate'],
                'yCoordinate' => $qObjects['Y_coordinate'],
                'propertyAddressRus' => $qObjects['PropertyAddressRus'],
                'propertyDescriptionRus' => $qObjects['PropertyDescriptionRus'],
                'locationDescriptionRus' => $qObjects['LocationDescriptionRus'],
                'propertyNameRus' => $qObjects['PropertyNameRus'],
                'code' => $qObjects['code'],
                'metroStation' => $qObjects['REALTY_OBJECTS_CLASS_METRO_NameRus'],
                'metroStationCode' => $qObjects['REALTY_OBJECTS_CLASS_METRO_code'],
                'distanceFromMetro' => $qObjects['DistanceFromMetro'],
                'locationZone' => $qObjects['LocationZone'],
                'direction' => $qObjects['Direction'],
                'county' => $qObjects['REALTY_OBJECTS_CLASS_COUNTY_name_ru'],
                'buildingClass' => $qObjects['BuildingClass'],
                'propertyLease' => $qObjects['PropertyLease'],
                'propertySale' => $qObjects['PropertySale'],
                'propertyType' => $qObjects['PropertyType'],
                "metroLine" => $qObjects["metroLine"],
            ];
            // **
            $queryObjectsArr["pictures"] = [];
            $queryObjectsArr["rooms"] = [];

            if ($qObjects["metroLine"]) {
                $metroLines[] = $qObjects["metroLine"];
            }

            $arrObjects[] = $queryObjectsArr;
        }
        $queryObjectPicture = \RealtyPicturesClassTable::getList(
            [
                'filter' => ['object_id' => $ids],
                'select' => ['file', 'object_id'],
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ],
            ]
        );
        while($queryObjectPictureArr = $queryObjectPicture->Fetch()) {
            //TODO str_pos() _wm
            If(!file_exists($_SERVER['DOCUMENT_ROOT'] . \CRealty::getWatermarkedPictureFilename($queryObjectPictureArr["file"]))) {
                \CRealty::putWatermark(
                    $queryObjectPictureArr["file"]
                );
            }
            $images[$queryObjectPictureArr["object_id"]]["pictures"][] = \CRealty::getWatermarkedPictureFilename($queryObjectPictureArr["file"]);
        }

        $filterRooms['object_id'] = $ids;

        $queryRooms = \RealtyRoomsClassTable::getList([
            'filter' => $filterRooms,
            'select' => ['UnitOfferedRent', 'UnitSalePrice', 'UnitFloorNumber', 'UnitSize', 'object_id'],
            'order' => ['UnitSize' => 'ASC'],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($room = $queryRooms->fetch()) {
            $rooms[$room["object_id"]]["rooms"][] = $room;
        }

        foreach ($arrObjects as $k => $object) {
            if ($images[$object["id"]]) {
                foreach ($images[$object["id"]]["pictures"] as $image) {
                    $arrObjects[$k]["pictures"][] = $image;
                    if ($object["propertyType"] == 1) {
                        break;
                    }
                }
            }
            if ($rooms[$object["id"]]) {
                $cnt = 0;
                $min = PHP_INT_MAX;
                $max = PHP_INT_MIN;
                foreach ($rooms[$object["id"]]["rooms"] as $room) {
                    $cnt++;
                    if ($room["UnitSize"] > $max) {
                        $max = $room["UnitSize"];
                    }
                    if ($room["UnitSize"] < $min) {
                        $min = $room["UnitSize"];
                    }
                    if ($cnt <= 4) {
                        $arrObjects[$k]["rooms"][] = [
                            'unitOfferedRent' => $room['UnitOfferedRent'],
                            'unitFloorNumber' => $room['UnitFloorNumber'],
                            'unitSalePrice' => $room['UnitSalePrice'],
                            'unitSize' => $room['UnitSize']
                        ];
                    }
                }
                $arrObjects[$k]["roomCount"] = $cnt;
                $arrObjects[$k]["minUnitSize"] = $min;
                $arrObjects[$k]["maxUnitSize"] = $max;
            } else {
                $arrObjects[$k]["roomCount"] = 0;
                $arrObjects[$k]["minUnitSize"] = 0;
                $arrObjects[$k]["maxUnitSize"] = 0;
            }
        }

        $linesColor = $this->getMetrosColor($metroLines);
        foreach ($arrObjects as $k => $object) {
            $arrObjects[$k]["metroColor"] = "";
            if ($linesColor[$object["metroLine"]]) {
                $arrObjects[$k]["metroColor"] = $linesColor[$object["metroLine"]];
            }
            unset($arrObjects[$k]["metroLine"]);
        }

        return ["objects" => $arrObjects, "count" => $queryObjects->getCount(), "limit" => $limit, 'offset' => $offset];
    }

	public function getCitiesAction()
	{
		$queryCities = \RealtyCitiesClassTable::getList([
			'select' => ['id', 'NameRus'],
			'order' => [
				'id' => 'DESC'
			],
			'filter' => ["!OBJECT.id" => null, "OBJECT.active" => 1],
			'runtime' => [
				new Entity\ReferenceField(
					"OBJECT",
					\RealtyObjectsClassTable::class,
					Query\Join::on('this.id', 'ref.City'),
					["join_type" => Query\Join::TYPE_LEFT]
				),
			],
			'group' => ["id", "NameRus"],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
		$res = [];
		while($queryCitiesArr = $queryCities->Fetch()) {
			$res[] = ['id' => $queryCitiesArr["id"], 'name' => $queryCitiesArr["NameRus"]];
		}
		return $res;
	}

	public function getTypesAction()
	{
		Loader::includeModule("realty");
		$queryTypes = \CRealtyPropertyTypes::GetList();
		$res = [];
		while($queryTypesArr = $queryTypes->Fetch()) {
			$res[] = ["id" => $queryTypesArr["id"], "name" => $queryTypesArr["NameRus"]];
		}
		return $res;
	}

	public function getMetrosAction()
	{
		$request = Context::getCurrent()->getRequest();
		$cityId = $request->getQuery("cityId") ?? 6;
		$queryMetros = \RealtyMetrosClassTable::getList([
			"select" => ["id", "NameRus", "code", "metroLine"],
			"filter" => ["city_id" => $cityId, "!OBJECT.id" => null, "OBJECT.active" => 1],
			"runtime" => [
				new Entity\ReferenceField(
					"OBJECT",
					\RealtyObjectsClassTable::class,
					Query\Join::on("this.id", "ref.MetroStation"),
					["join_type" => Query\Join::TYPE_LEFT]
				),
			],
			"group" => ["id", "NameRus", "code"],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
		$res = [];
		$metroLines = [];
		while($queryMetrosArr = $queryMetros->Fetch()) {
		    $metroLines[] = $queryMetrosArr["metroLine"];
			$res[] = [
			    "id" => $queryMetrosArr["id"],
                "name" => $queryMetrosArr["NameRus"],
                "slug" => $queryMetrosArr["code"],
                "metroLine" => $queryMetrosArr["metroLine"]
            ];
		}
        $linesColor = $this->getMetrosColor($metroLines);
        foreach ($res as $k => $metro) {
            $res[$k]["color"] = "";
            if ($linesColor[$metro["metroLine"]]) {
                $res[$k]["color"] = $linesColor[$metro["metroLine"]];
            }
            unset($res[$k]["metroLine"]);
        }


        return $res;
	}

	public function getCountyAction()
	{
		$request = Context::getCurrent()->getRequest();
		$cityId = $request->getQuery('cityId');
		$countyQuery = \RealtyCountyClassTable::getList([
			'select' => ['id', 'name_ru', 'code'],
			'filter' => ["ml_realty_cities_id" => $cityId],
            "cache" => [
                "ttl" => 3600,
            ],
		]);
		$county = [];
		while($countyQueryArr = $countyQuery->Fetch()) {
			$prearr["id"] = $countyQueryArr["id"];
			$prearr["nameRu"] = $countyQueryArr["name_ru"];
			$prearr["slug"] = $countyQueryArr["code"];
			$county[] = $prearr;
		}
		return $county;
	}

	public function detailAction(string $code)
    {
        Loader::includeModule("realty");
        $item = [];
        $objectId = 0;
        $realty = \RealtyObjectsClassTable::getList([
            "filter" => ["code" => $code, "active" => 1],
            "select" => [
                "*",
                "CITY_NAME" => "CITY_LINKED.NameRus",
                "PARKING_TYPE" => "PARKINGS_LINKED.parking_type",
                "BUSINESS_UNIT_ID" => "BUSINESS_LINKED.business_unit_id"
            ],
            "runtime" => [
                new Entity\ReferenceField(
                    "CITY_LINKED",
                    \RealtyCitiesClassTable::class,
                    Query\Join::on('this.City', 'ref.id'),
                    ["join_type" => Query\Join::TYPE_INNER]
                ),
                new Entity\ReferenceField(
                    "PARKINGS_LINKED",
                    \RealtyParkingsClassTable::class,
                    Query\Join::on('this.id', 'ref.object_id'),
                    ["join_type" => Query\Join::TYPE_LEFT]
                ),
                new Entity\ReferenceField(
                    "BUSINESS_LINKED",
                    \RealtyObjectsVsUnitsClassTable::class,
                    Query\Join::on('this.id', 'ref.object_id'),
                    ["join_type" => Query\Join::TYPE_LEFT]
                ),
            ],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        if ($object = $realty->fetch()) {
            $type = "office";
            switch ($object["BUSINESS_UNIT_ID"]) {
                case 1:
                    $type = "industrial";
                    break;
                case 2:
                    switch ($object["PropertyType"]) {
                        case 1:
                            $type = "industrial";
                            break;
                        case 2:
                            $type = "retail";
                            break;
                        case 3:
                            $type = "office";
                            break;
                        default:
                            $type = "office";
                            break;
                    }
                    break;
                case 3:
                    $type = "retail";
                    break;
                case 4:
                    $type = "office";
                    break;
                case 5:
                    $type = "office";
                    break;
            }
            $objectId = $object["id"];
            $item["id"] = $object["id"];
            $item["title"] = $object["PropertyNameRus"];
            if ($object["PropertyLease"] && $object["PropertySale"]) {
                $item["for"] = "Аренда/продажа";
            } elseif ($object["PropertyLease"]) {
                $item["for"] = "Аренда";
            } elseif ($object["PropertySale"]) {
                $item["for"] = "Продажа";
            }
            $item["address"] = $object["PropertyAddressRus"];
            $metroDistrict = 0;
            if ($object["MetroStation"]) {
                $metros = \RealtyMetrosClassTable::getList([
                    "filter" => [
                        "id" => $object["MetroStation"]
                    ],
                    "cache" => [
                        "ttl" => 3600,
                    ],
                ]);
                if ($metro = $metros->fetch()) {
                    $color = "";
                    if ($metro["metroLine"]) {
                        $color = $this->getMetroColor($metro["metroLine"]);
                    }
                    if ($metro["ita_raion_id"]) {
                        $metroDistrict = $metro["ita_raion_id"];
                    }
                    $item["metro"] = [
                        "name" => $metro["NameRus"],
                        "code" => $metro["code"],
                        "distance" => $object["DistanceFromMetro"],
                        "color" => $color,
                    ];
                }
            }
            if ($type == "office") {
                $item["type"] = "office";
            } elseif ($type == "retail") {
                $item["type"] = "retail";
            } elseif ($type == "industrial") {
                $item["type"] = "industrial";
                $item["operationHeight"] = $object["OperatingHeight"];
                $item["columnGridMin"] = $object["ColumnGridMin"];
                $item["columnGridMax"] = $object["ColumnGridMax"];
                $item["temperatureFrom"] = $object["TemperatureFrom"];
                $item["temperatureTo"] = $object["TemperatureTo"];
                $item["distanceFromCity"] = $object["DistanceFromCity"];
                if ($object["FloorType"]) {
                    $floorType = \CRealtyFloorTypes::GetByID($object["FloorType"]);
                    $item["floorType"] = $floorType["NameRus"];
                }
                if ($object["FloorLoad"]) {
                    $item["floorLoad"] = $object["FloorLoad"];
                }
                $rsVentilationSystems = \CRealtyObjects::getVentilationSystems($objectId);
                if ($ventilationSystem = $rsVentilationSystems->Fetch()) {
                    $item["ventilationSystem"] = $ventilationSystem["NameRus"];
                }
                $rsSecuritySystems = \CRealtyObjects::getSecuritySystems($objectId);
                if ($securitySystem = $rsSecuritySystems->Fetch()) {
                    $item["securitySystem"] = $securitySystem["NameRus"];
                }
                $rsFireSecurity = \CRealtyObjects::getFireSecurity($objectId);
                if ($fireSecurity = $rsFireSecurity->Fetch()) {
                    $item["fireSecurity"] = $fireSecurity["NameRus"];
                }
            }
            $item["propertyType"] = "";
            if ($object["PropertyType"]) {
                $arPropertyType = \CRealtyPropertyTypes::GetByID(
                    $object['PropertyType']
                );
				$item["propertyType"] = ['id' => $arPropertyType["id"], 'name' => $arPropertyType["NameRus"]];//NameRus
            }
            $item["city"] = [
                "name" => "",
                "id" => "",
            ];
            if ($object["City"]) {
                $item["city"]["id"] = $object["City"];
            }
            if ($object["CITY_NAME"]) {
                $item["city"]["name"] = $object["CITY_NAME"];
            }
            $item["class"] = "Класс " . $object["BuildingClass"];
            $item["buildingTotal"] = $object["GrossBuildingArea"];
            $item["leaseTotal"] = $object["GrossLeasableArea"];
            if ($object["min_lease_UnitDivisibleFrom"] || $object["sum_lease_UnitSize"]) {
                $item["leaseSquare"] = $object["min_lease_UnitDivisibleFrom"] . "-" . $object["sum_lease_UnitSize"];
            } else {
                $item["leaseSquare"] = "";
            }
            $item["leasePrice"] = "";
            if ($object["min_UnitOfferedRent"] == "-1" && $object["max_UnitOfferedRent"] == "-1") {
                $item["leasePrice"] = "-1";
            } elseif ($object["min_UnitOfferedRent"] == "-2" && $object["max_UnitOfferedRent"] == "-2") {
                $item["leasePrice"] = "-2";
            } elseif ($object["min_UnitOfferedRent"] == $object["max_UnitOfferedRent"]) {
                $item["leasePrice"] = $object["min_UnitOfferedRent"];
            } else {
                $item["leasePrice"] = $object["min_UnitOfferedRent"] . "-" . $object["max_UnitOfferedRent"];
            }
            if ($object["min_UnitSalePrice"] == "-1" && $object["max_UnitSalePrice"] == "-1") {
                $item["salePrice"] = "-1";
            } elseif ($object["min_UnitSalePrice"] == "-2" && $object["max_UnitSalePrice"] == "-2") {
                $item["salePrice"] = "-2";
            } elseif ($object["min_UnitSalePrice"] == $object["max_UnitSalePrice"]) {
                $item["salePrice"] = $object["min_UnitSalePrice"];
            } else {
                $item["salePrice"] = $object["min_UnitSalePrice"] . "-" . $object["max_UnitSalePrice"];
            }

            $item["parkingType"] = "";
            if ($object["PARKING_TYPE"]) {
                $parking = \RealtyParkingTypesClassTable::getById($object["PARKING_TYPE"]);
                $item["parkingType"] = $parking->fetch()["NameRus"];
            }
            $item["contacts"] = [];
            $contacts = \RealtyObjectsVsUsersClassTable::getList([
                "filter" => [
                    "object_id" => $objectId,
                    "ACTIVE" => 1
                ],
                "select" => [
                    "*",
                    "CONTACT_NAME" => "CONTACT.NameRus",
                    "CONTACT_POST" => "CONTACT.PostRus",
                    "PHONE" => "CONTACT.ContactMobilePhone",
                    "EMAIL" => "CONTACT.ContactEmail",
                    "IMAGE" => "CONTACT.ContactFolder",
                    "ACTIVE" => "CONTACT.active"
                ],
                "runtime" => [
                    new Entity\ReferenceField(
                        "CONTACT",
                        \RealtyUsersClassTable::class,
                        Query\Join::on('this.contact_id', 'ref.id'),
                        ["join_type" => Query\Join::TYPE_INNER]
                    ),
                ],
                "order" => ["order" => "ASC"],
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ],
            ]);
            while ($contact = $contacts->fetch()) {
                $contactItem = [
                    "name" => $contact["CONTACT_NAME"],
                    "jobTitle" => $contact["CONTACT_POST"],
                    "phone" => $contact["PHONE"],
                    "email" => $contact["EMAIL"],
                    "image" => $contact["IMAGE"],
                ];
                $item["contacts"][] = $contactItem;
            }
            if (empty($item["contacts"])) {
                $contacts = \CRealtyBusinessUnits::getContacts($object["BUSINESS_UNIT_ID"]);
                while ($contact = $contacts->Fetch()) {
                    $contactItem = [
                        "name" => $contact["NameRus"],
                        "jobTitle" => $contact["PostRus"],
                        "phone" => $contact["ContactMobilePhone"],
                        "email" => $contact["ContactEmail"],
                        "image" => $contact["ContactFolder"],
						"miniImage" => $this->resiseThisImage($contact["ContactFolder"])
                    ];
                    $item["contacts"][] = $contactItem;
                }
            }
            $item["images"] = [];
            $images = \RealtyPicturesClassTable::getList([
                "filter" => ["object_id" => $objectId],
                "order" => ["order" => "ASC"],
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ],
            ]);
            while ($image = $images->fetch()) {
                $fileName = \CRealty::tryGetWatermarkedPictureFilename($image["file"]);
                if ($fileName == $image["file"]) {
                    \CRealty::putWatermark(
                        $image["file"]
                    );
                    $fileName = \CRealty::getWatermarkedPictureFilename($image["file"]);
                }
                $item["images"][] = $fileName;
				$item["miniImages"][] = $this->resiseThisImage($fileName);
                if ($type == "industrial") {
                    break;
                }
            }
            $item["xCoordinate"] = $object["X_coordinate"];
            $item["yCoordinate"] = $object["Y_coordinate"];
            $item["description"] = $object["PropertyDescriptionRus"];
            $item["commercialDescription"] = $object["CommercialTermsRus"];
            $item["geoDescription"] = $object["LocationDescriptionRus"];
            $item["technicalDescription"] = $object["technicalDescription"];
            $item["crmId"] = $object["PropertyID"];
            $item["rooms"] = [];
            $rooms = \RealtyRoomsClassTable::getList([
                "select" => [
                    "*",
                    "ROOM_CONDITION" => "CONDITION.NameRus",
                    "ROOM_TYPE" => "TYPE.NameRus"
                ],
                "filter" => ["object_id" => $objectId],
                "runtime" => [
                    new Entity\ReferenceField(
                        "CONDITION",
                        \RealtyRoomsConditionsClassTable::class,
                        Query\Join::on('this.UnitDeliveryCondition', 'ref.id'),
                        ["join_type" => Query\Join::TYPE_INNER]
                    ),
                    new Entity\ReferenceField(
                        "TYPE",
                        \RealtyRoomsTypesClassTable::class,
                        Query\Join::on('this.UnitType', 'ref.id'),
                        ["join_type" => Query\Join::TYPE_INNER]
                    ),
                ],
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ],
            ]);
			$item["rooms"]["rent"] = [];
			$item["rooms"]["sale"] = [];
            while ($room = $rooms->fetch()) {
                $roomItem = [];
                $roomItem["floorNumber"] = $room["UnitFloorNumber"];
                $roomItem["condition"] = $room["ROOM_CONDITION"];
                $roomItem["type"] = $room["ROOM_TYPE"];
                $roomItem["size"] = $room["UnitSize"];
                $roomItem["crmId"] = $room["UnitID"];
                if ($room["UnitsLease"]) {
                    $item["rooms"]["rent"][] = $roomItem;
                }
                if ($room["UnitSale"]) {
                    $item["rooms"]["sale"][] = $roomItem;
                }
            }
            if ($object["ita_county_id"]) {
                $districts = \RealtyCountyClassTable::getById($object["ita_county_id"]);
                if ($district = $districts->fetch()) {
                    $item["district"] = [
                        "name" => $district["name_ru"],
                        "code" => $district["code"],
                    ];
                }
            } elseif ($metroDistrict) {
                $districtId = 0;
                $raionQuery = \RealtyRaionClassTable::getById($metroDistrict);
                if ($raion = $raionQuery->fetch()) {
                    $districtId = $raion["ita_county_id"];
                }
                if ($districtId) {
                    $districts = \RealtyCountyClassTable::getById($districtId);
                    if ($district = $districts->fetch()) {
                        $item["district"] = [
                            "name" => $district["name_ru"],
                            "code" => $district["code"],
                        ];
                    }
                }
            } elseif ($object["Direction"] || $object["LocationZone"]) {
                foreach (\CRealtySeoPages::$districts as $dNum => $dParams) {
                    if (in_array($object['LocationZone'], $dParams['filter']['LocationZone'])
                        && (!isset($dParams['filter']['Direction'])
                            || isset($dParams['filter']['Direction'])
                            && $object['Direction'] == $dParams['filter']['Direction'])
                    ) {
                        $district = $dParams;
                    }
                }
                $item["district"] = [
                    "name" => $district["ru"],
                    "code" => mb_strtolower($district["en"])
                ];
            }

            $h1 = $object["PropertyNameRus"];
            $offerType = [];
            if ($object["PropertyBTS"])
                $offerType[] = 'Built-to-suit';
            if ($object["PropertyLease"])
                $offerType[] = "Аренда";
            if ($object["PropertySale"])
                $offerType[] = "Продажа";
            $offerTypeText = implode( ', ', $offerType );


            $arSubmarket = \CRealtyMarkets::GetByID(
                (int)$object["Submarket"]
            );

            $arStatus = \CRealtyStatuses::GetByID(
                (int)$object["PropertyStatus"]
            );

            $types1 = array(
                '1' => 'складских помещений',
                '2' => 'торговых помещений',
                '3' => 'офисных помещений',
                '4' => 'земельных участков',
                '5' => 'жилых помещений',
                '6' => 'дата-центров',
                '7' => 'гостиниц',
            );
            $types2 = array(
                '1' => 'складские помещения',
                '2' => 'торговые помещения',
                '3' => 'офисные помещения',
                '4' => 'земельные участки',
                '5' => 'жилые помещения',
                '6' => 'дата-центры',
                '7' => 'гостиницы',
            );

            $categories = array(
                'office' => 'офисы в москве',
                'industrial_logistics' => 'складские комплексы',
                'retail' => 'аренда в торговых центрах',
                'regional' => 'аренда и продажа в регионах',
                'investment_sales' => 'объекты для инвестирования',
            );

            $categoriesUp = array(
                'office' => 'Офисы в москве',
                'industrial_logistics' => 'Складские комплексы',
                'retail' => 'Аренда в торговых центрах',
                'regional' => 'Аренда и продажа в регионах',
                'investment_sales' => 'Объекты для инвестирования',
            );

            $keys = array(
                '[<h1>]' => trim($h1),
                '[класс]' => $object["BuildingClass"],
                '[услуга 1]' => strtolower($offerTypeText),
                '[тип помещения]' => $types2[$object["PropertyType"]],
                '[типа помещения]' => $types1[$object["PropertyType"]],
                '[адрес]' => trim($object["PropertyAddressRus"]),
                '[город]' => trim($object["CITY_NAME"]),
                '[значение]' => number_format_ext($object["GrossLeasableArea"]) . ' ' . 'м' . '2',
                '[название шоссе]' => trim($arSubmarket["NameRu"]),
                '[название станции]' => trim($item["metro"]["name"]),
                '[статус]' => trim($arStatus["NameRus"]),
            );

            if ($object["BUSINESS_UNIT_ID"]) {
                $businessUnitCode = \CRealtyBusinessUnits::GetByID($object["BUSINESS_UNIT_ID"])["code"];
            } else {
                $businessUnitCode = "office";
            }
            $keys['[услугу 1]'] = str_replace(array('аренда', 'продажа'), array('аренду', 'продажу'), $keys['[услуга 1]']);
            $keys['[услуге 1]'] = str_replace(array('аренда', 'продажа'), array('арендe', 'продажe'), $keys['[услуга 1]']);
            $keys['[услуга 2]'] = str_replace(array('аренда', 'продажа', 'built-to-suit'), array('снять', 'купить', 'построить под ключ'), $keys['[услуга 1]']);
            $keys['[Услуга 1]'] = str_replace(array('аренда', 'продажа', 'built-to-suit'), array('Аренда', 'Продажа', 'Built-to-suit'), $keys['[услуга 1]']);
            $keys['[Услуга 1]'] = str_replace(', Продажа', ', продажа', $keys['[Услуга 1]']);
            $keys['[Услуга 1]'] = str_replace(', Аренда', ', аренда', $keys['[Услуга 1]']);
            $keys['[Услуга 1]'] = str_replace(', Built-to-suit', ', built-to-suit', $keys['[Услуга 1]']);
            $keys['[Услугу 1]'] = str_replace(array('аренду', 'продажу'), array('Аренду', 'Продажу'), $keys['[услугу 1]']);
            $keys['[Услугу 1]'] = str_replace('Аренду, Продажу', 'Аренду, ародажу', $keys['[Услугу 1]']);
            $keys['[Услуга 2]'] = str_replace(array('снять', 'купить', 'построить под ключ'), array('Снять', 'Купить', 'Построить под ключ'), $keys['[услуга 2]']);
            $keys['[Услуга 2]'] = str_replace(', Купить', ', купить', $keys['[Услуга 2]']);
            $keys['[Услуга 2]'] = str_replace(', Построить под ключ', ', построить под ключ', $keys['[Услуга 2]']);
            $keys['[Услуга 2]'] = str_replace(', Снять', ', снять', $keys['[Услуга 2]']);
            $keys['[категория]'] = $categories[$businessUnitCode];
            $keys['[Категория]'] = $categoriesUp[$businessUnitCode];

            $descriptions = array(
                'office' => 'Предлагаем [услугу 1] офисов в [<h1>] по адресу [адрес]. [<h1>] – [тип помещения] класса [класс], расположенные около метро [название станции]. Общая арендуемая площадь [значение]',
                'industrial_logistics' => 'Предлагаем [услугу 1] склада [<h1>] в городе [город]. [<h1>] – [тип помещения] класса [класс], [название шоссе]. Общая арендуемая площадь [значение]',
                'retail' => 'Предлагаем [услугу 1] площадей в [<h1>],  город [город]. [<h1>] – [статус] объект, общей арендуемой площадью [значение]. Звоните +7 (495) 258 3990',
                'regional' => '[Услуга 1] [типа помещения] [<h1>], в городе [город] по адресу [адрес]. [<h1>] – [тип помещения] класса [класс]. Общая арендуемая площадь [значение]. Звоните +7 (495) 258 3990',
                'investment_sales' => '[Услуга 1] [типа помещения] [<h1>], в городе [город] по адресу [адрес]. [<h1>] – [тип помещения], общей арендуемой площадью [значение]. Звоните +7 (495) 258 3990',
            );

            $titles = array(
                'office' => '[<h1>] – [услуга 1] офисов в Москве. [Услуга 2] офис в [<h1>], метро [название станции] - CORE.XP',
                'industrial_logistics' => '[<h1>] – [услуга 1] склада в г. [город]. [Услуга 2] складское помещение в [<h1>], [название шоссе] - CORE.XP',
                'retail' => '[<h1>] – [услуга 1] площадей в торговом центре. [Услуга 2] помещение в [<h1>], город [город] - CORE.XP',
                'regional' => '[<h1>] – [услуга 1] [тип помещения] в городе [город] – [категория] CORE.XP',
                'investment_sales' => '[<h1>] – [услуга 1] [тип помещения] в городе [город] – [категория] CORE.XP',
            );

            $admTemplates = \CRealtySeoPagesTemplates::GetByCode('object_' . $businessUnitCode);

            $titles[$businessUnitCode] = $admTemplates['title_ru'];
            $descriptions[$businessUnitCode] = $admTemplates['description_ru'];

            $seoTitle = $object["seoTitle"];
            $seoDescription = $object["seoDescription"];
            $seoKeywords = $object["seoKeywords"];

            $description = strlen($seoDescription) > 0 ? $seoDescription : $descriptions[$businessUnitCode];
            $description = str_replace(array_keys($keys), array_values($keys), $description);
            $title = strlen($seoTitle) > 0 ? $seoTitle : $titles[$businessUnitCode];
            $title = str_replace(array_keys($keys), array_values($keys), $title);
            $keywords = strlen($seoKeywords) > 0 ? $seoKeywords : $h1;

            $item["seo"]["elementMetaKeywords"] = $keywords;
            $item["seo"]["elementMetaTitle"] = $title;
            $item["seo"]["elementMetaDescription"] = $description;

            return $item;
        } else {
            $this->addError(new Error('Element could not be found by code.', 404));

            return null;
        }
    }

	public function getCountiesAndRaionsAction()
	{
		$request = Context::getCurrent()->getRequest();
		$slugsCounty = $request->getQuery('slugs');
		$city = $request->getQuery('cities') ?? 6;
		$filter = [];
		if(!empty($slugsCounty)) {
			$filter["=code"] = $slugsCounty;
		}
		$filter["ml_realty_cities_id"] = $city;
		$queryCounty = \RealtyCountyClassTable::getList([
			'select'  => ['RAION.id', 'RAION.name_ru', 'RAION.code', 'code', 'ml_realty_cities_id'], // имена полей, которые необходимо получить в результате
			'filter'  => $filter,
			'runtime' => [
				new Entity\ReferenceField(
					"RAION",
					\RealtyRaionClassTable::class,
					Query\Join::on('this.id', 'ref.ita_county_id'),
					["join_type" => Query\Join::TYPE_LEFT]
				),
			],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
		$res = [];
		while($queryCountyArr = $queryCounty->Fetch()) {
			$prearr = [];
			$prearr["areaId"] = $queryCountyArr["REALTY_COUNTY_CLASS_RAION_id"];
			$prearr["areaNameRu"] = $queryCountyArr["REALTY_COUNTY_CLASS_RAION_name_ru"];
			$prearr["countySlug"] = $queryCountyArr["code"];
			$prearr["areaSlug"] = $queryCountyArr["REALTY_COUNTY_CLASS_RAION_code"];
			$prearr["cityId"] = (int)$queryCountyArr["ml_realty_cities_id"];
			$queryMetro = \RealtyMetrosClassTable::getList([
				'select' => ['code'],
				'filter' => ['ita_raion_id' => $prearr["areaId"]],
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ],
            ]);
			$queryMetroRes = $queryMetro->fetchAll();
			foreach($queryMetroRes as &$metro) {
				$metro = $metro["code"];
			}
			$prearr['metroSlugs'] = $queryMetroRes;
			$res[] = $prearr;
		}
		return $res;
	}

	public function resiseThisImage($path)
    {
		$file = \CFile::MakeFileArray($path);
		$thumb = '/upload/resize_objects_image/'.$file['name'];
		$img_path = $_SERVER['DOCUMENT_ROOT'].$path;
		$thumb_path = $_SERVER['DOCUMENT_ROOT'].$thumb;
		if(is_file($img_path) && !is_file($thumb_path)){
			\CFile::ResizeImageFile($img_path, $thumb_path, array('width'=>200,'height'=>200), BX_RESIZE_IMAGE_PROPORTIONAL);
		}
		return $thumb;
	}

	public function getCoordsAction()
	{
		$request = Context::getCurrent()->getRequest();
		$excludeCode = $request->getQuery('excludeCode');
        $byTab = $request->getQuery('byTab');
        $tabType = !empty($byTab) ? $byTab['type'] : false;
        if ('rent' === $tabType) {
            $grossLeasableAreaMin = $byTab['grossAreaMin']; //площадь от
            $grossLeasableAreaMax = $byTab['grossAreaMax']; //площадь до
            $minRentPrice = $byTab['minPrice']; //минимальная цена аренды
            $maxRentPrice = $byTab['maxPrice']; //максимальная цена аренды
            $topInclude = $byTab['topInclude']; //выгодные предложения аренда
        } elseif ('sale' === $tabType) {
            $grossBuildingAreaMin = $byTab['grossAreaMin']; //площадь от
            $grossBuildingAreaMax = $byTab['grossAreaMax']; //площадь до
            $minSalePrice = $byTab['minPrice']; //минимальная цена продажи
            $maxSalePrice = $byTab['maxPrice'];; //максимальная цена продажи
            $topIncludeSale = $byTab['topInclude']; //выгодные предложения продажа
        }

        /*
		$grossLeasableAreaMin = $request->getQuery('grossLeasableAreaMin');
		$grossLeasableAreaMax = $request->getQuery('grossLeasableAreaMax');
        $minRentPrice = $request->getQuery('minRentPrice'); //минимальная цена аренды
        $maxRentPrice = $request->getQuery('maxRentPrice'); //максимальная цена аренды
        $topInclude = $request->getQuery('topInclude'); //выгодные предложения аренда

		$grossBuildingAreaMin = $request->getQuery('grossBuildingAreaMin'); //площадь от
		$grossBuildingAreaMax = $request->getQuery('grossBuildingAreaMax'); //площадь до
        $minSalePrice = $request->getQuery('minSalePrice'); //минимальная цена продажи
        $maxSalePrice = $request->getQuery('maxSalePrice'); //максимальная цена продажи
        $topIncludeSale = $request->getQuery('topIncludeSale'); //выгодные предложения продажа
        */

		$propertyAddressRus = $request->getQuery('propertyAddressRus'); //адрес
		$buildingClass = $request->getQuery('buildingClass');//Класс объекта
		$propertyLease = $request->getQuery('propertyLease'); //В аренду
		$propertySale = $request->getQuery('propertySale'); //На продажу
		$city = $request->getQuery('city'); // id города
		$propertyType = $request->getQuery('propertyType'); //Тип объекта
		$distanceFromMetro = $request->getQuery('distanceFromMetro'); //расстояние до метро
		$metroStation = $request->getQuery('metroStation'); // code станции метро
		$limit = $request->getQuery('limit') ?? 8;
        $offset = $request->getQuery('offset') ?? 0;
		$minUnitFloorNumber = $request->getQuery('minUnitFloorNumber'); //минимальный этаж
		$maxUnitFloorNumber = $request->getQuery('maxUnitFloorNumber'); //максимальный этаж
		$hasParking = $request->getQuery('hasParking'); //наличие парковки

        $distanceFromCity = $request->getQuery('distanceFromCity'); //расстояние до города
        $direction = $request->getQuery('direction'); //направление

        $county = $request->getQuery('cityCounty');//округ города
		$raion = $request->getQuery('cityArea');//район города

        $orderBy = $request->getQuery('orderBy'); // параметры сортировки
        $sortNew = $orderBy['newest'];  //сортировка по новизне
        $sortMinRentPrice = ('rent' === $tabType) ? $orderBy['minPrice'] : null; //сортировка по цене аренды
        $sortMinSalePrice = ('sale' === $tabType) ? $orderBy['minPrice'] : null; //сортировка по цене продажи
        $sortBuildingClass = $orderBy['buildingClass']; //сортировка по классу здания
        $sortGrossLeasableArea = ('rent' === $tabType) ? $orderBy['grossArea'] : null; //сортировка по площади в аренду
        $sortGrossBuildingArea = ('sale' === $tabType) ? $orderBy['grossArea'] : null; //сортировка по площади

        /*
		$sortNew = $request->getQuery('sortNew');//сортировка по новизне
		$sortMinRentPrice = $request->getQuery('sortMinRentPrice');//сортировка по цене аренды
		$sortMinSalePrice = $request->getQuery('sortMinSalePrice');//сортировка по цене продажи
		$sortBuildingClass = $request->getQuery('sortBuildingClass');//сортировка по классу здания
		$sortGrossLeasableArea = $request->getQuery('sortGrossLeasableArea');//сортировка по площади в аренду
		$sortGrossBuildingArea = $request->getQuery('sortGrossBuildingArea');//сортировка по площади
		$order = $request->getQuery('order') ?? 'DESC';
        */

		$sort = [];
		if(!empty($sortMinRentPrice)) {
			$sort["min_rent_price"] = $sortMinRentPrice;
		}
		if(!empty($sortMinSalePrice)) {
			$sort["min_sale_price"] = $sortMinSalePrice;
		}
		if(!empty($sortBuildingClass)) {
			$sort["BuildingClass"] = $sortBuildingClass;
		}
		if(!empty($sortGrossLeasableArea)) {
			$sort["GrossLeasableArea"] = $sortGrossLeasableArea;
		}
		if(!empty($sortGrossBuildingArea)) {
			$sort["GrossBuildingArea"] = $sortGrossBuildingArea;
		}
		if(!empty($sortNew)) {
			$sort["id"] = $sortNew;
		}

		$filter = [];
		$filter["active"] = 1;
		if(!empty($grossLeasableAreaMin)) {
			$filter[">=GrossLeasableArea"] = $grossLeasableAreaMin;
		}
		if(!empty($grossLeasableAreaMax)) {
			$filter["<=GrossLeasableArea"] = $grossLeasableAreaMax;
		}
		if(!empty($grossBuildingAreaMin)) {
			$filter[">=GrossBuildingArea"] = $grossBuildingAreaMin;
		}
		if(!empty($grossBuildingAreaMax)) {
			$filter["<=GrossBuildingArea"] = $grossBuildingAreaMax;
		}
		if(!empty($propertyAddressRus)) {
			$filter["%PropertyAddressRus"] = $propertyAddressRus;
		}
		if(!empty($buildingClass)) {
			$filter["BuildingClass"] = $buildingClass;
		}
		if(!empty($propertyLease)) {
			$filter["PropertyLease"] = $propertyLease;
		}
		if(!empty($propertySale)) {
			$filter["PropertySale"] = $propertySale;
		}
		if(!empty($city)) {
			$filter["City"] = $city;
		}
		if(!empty($propertyType)) {
			$filter["PropertyType"] = $propertyType;
		}
		if(!empty($distanceFromMetro)) {
			$filter['<=DistanceFromMetro'] = $distanceFromMetro;
            $filter['!MetroStation'] = false;
		}
		if(!empty($metroStation)) {
			$filter['METRO.code'] = $metroStation;
		}
        if(!empty($distanceFromCity)) {
            $filter['<=DistanceFromCity'] = $distanceFromCity;
            $filter['>DistanceFromCity'] = 0;
        }
        if(!empty($direction)) {
            $filter['Direction'] = $direction;
        }

		if(!empty($minRentPrice) && !empty($maxRentPrice)){
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_rent_price' => [$minRentPrice, $maxRentPrice]],
				['><max_rent_price' => [$minRentPrice, $maxRentPrice]],
				[
					['<min_rent_price' => $minRentPrice],
					['>max_rent_price' => $maxRentPrice]
				]
			];
		} else if(!empty($minRentPrice)) {
			$maxRentPrice = 2147483647;
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_rent_price' => [$minRentPrice, $maxRentPrice]],
				['><max_rent_price' => [$minRentPrice, $maxRentPrice]],
				[
					['<min_rent_price' => $minRentPrice],
					['>max_rent_price' => $maxRentPrice]
				]
			];
		} else if(!empty($maxRentPrice)) {
			$minRentPrice = 0;
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_rent_price' => [$minRentPrice, $maxRentPrice]],
				['><max_rent_price' => [$minRentPrice, $maxRentPrice]],
				[
					['<min_rent_price' => $minRentPrice],
					['>max_rent_price' => $maxRentPrice]
				]
			];
		}

		if(!empty($minSalePrice) && !empty($maxSalePrice)) {
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_sale_price' => [$minSalePrice, $maxSalePrice]],
				['><max_sale_price' => [$minSalePrice, $maxSalePrice]],
				[
					['<min_sale_price' => $minSalePrice],
					['>max_sale_price' => $maxSalePrice]
				]
			];
		} else if(!empty($minSalePrice)) {
			$maxSalePrice = 2147483647;
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_sale_price' => [$minSalePrice, $maxSalePrice]],
				['><max_sale_price' => [$minSalePrice, $maxSalePrice]],
				[
					['<min_sale_price' => $minSalePrice],
					['>max_sale_price' => $maxSalePrice]
				]
			];
		} else if(!empty($maxSalePrice)) {
			$minSalePrice = 0;
			$filter[] = [
				'LOGIC' => 'OR',
				['><min_sale_price' => [$minSalePrice, $maxSalePrice]],
				['><max_sale_price' => [$minSalePrice, $maxSalePrice]],
				[
					['<min_sale_price' => $minSalePrice],
					['>max_sale_price' => $maxSalePrice]
				]
			];
		}
		if(!empty($minUnitFloorNumber) && !empty($maxUnitFloorNumber)){
			$filter["><FLOORS.UnitFloorNumber"] = [$minUnitFloorNumber, $maxUnitFloorNumber];
		} else if(!empty($minUnitFloorNumber)) {
			$filter[">=FLOORS.UnitFloorNumber"] = $minUnitFloorNumber;
		} else if(!empty($maxUnitFloorNumber)) {
			$filter["<=FLOORS.UnitFloorNumber"] = $maxUnitFloorNumber;
		}
		if(!empty($hasParking)) {
			$filter["!PARKING.id"] = null;
		}
		if(!empty($topInclude)) {
			$filter["top_include"] = $topInclude;
		}
		if(!empty($topIncludeSale)) {
			$filter["top_include_sale"] = $topIncludeSale;
		}

        if(!empty($raion)) {
            $raionId = 0;
            $zones = \RealtyRaionClassTable::getList([
                "select" => ["id"],
                "filter" => ["code" => $raion],
                "cache" => [
                    "ttl" => 3600,
                ],
            ]);
            if ($zone = $zones->fetch()) {
                $raionId = $zone["id"];
            }
            if ($raionId == 0) {
                $raionId = (int)$raion;
            }

            $filterRaion = ["LOGIC" => "OR"];
            $filterRaion[] = ["ita_raion_id" => $raionId];
            $filterRaion[] = [
                "ita_raion_id" => false,
                "METRO.ita_raion_id" => $raionId,
            ];
            $filter[] = $filterRaion;
        }
        if(!empty($county)) {
            if(!empty($raion)) {
                $filter["COUNTY.code"] = $county;
            } else {
                $filterCounty = ["LOGIC" => "OR"];
                $filterCounty[] = [
                    "COUNTY.code" => $county,
                ];
                $zones = \RealtyRaionClassTable::getList([
                    "select" => ["id"],
                    "filter" => ["COUNTY.code" => $county,],
                    "runtime" => [
                        new Entity\ReferenceField(
                            "COUNTY",
                            \RealtyCountyClassTable::class,
                            Query\Join::on('this.ita_county_id', 'ref.id'),
                            [
                                "join_type" => Query\Join::TYPE_LEFT,
                            ]
                        ),
                    ],
                    "cache" => [
                        "ttl" => 3600,
                        "cache_joins" => true,
                    ],
                ]);
                while ($zone = $zones->fetch()) {
                    $filterCounty[] = [
                        "COUNTY.code" => false,
                        "METRO.ita_raion_id" => $zone["id"],
                    ];
                }
                $filter[] = $filterCounty;
            }
        }

		if(!empty($excludeCode)) {
			$filter['!code'] = $excludeCode;
		}

		$queryObjects = \RealtyObjectsClassTable::getList([
			'select'  => ['id', 'X_coordinate', 'Y_coordinate', 'PropertyNameRus', 'code'], // имена полей, которые необходимо получить в результате
			'filter'  => $filter, // описание фильтра для WHERE и HAVING
			'limit'   => $limit, // количество записей
			'offset' => $offset,
			'group' => ["id"],
			"runtime" => [
				new Entity\ReferenceField(
					"FLOORS",
					\RealtyRoomsClassTable::class,
					Query\Join::on('this.id', 'ref.object_id'),
					["join_type" => Query\Join::TYPE_LEFT]
				),
				new Entity\ReferenceField(
					"PARKING",
					\RealtyParkingsClassTable::class,
					Query\Join::on('this.id', 'ref.object_id'),
					[
						"join_type" => Query\Join::TYPE_LEFT,
					]
				),
				new Entity\ReferenceField(
					"COUNTY",
					\RealtyCountyClassTable::class,
					Query\Join::on('this.ita_county_id', 'ref.id'),
					[
						"join_type" => Query\Join::TYPE_LEFT,
					]
				),
                new Entity\ReferenceField(
                    "METRO",
                    \RealtyMetrosClassTable::class,
                    Query\Join::on('this.MetroStation', 'ref.id'),
                    [
                        "join_type" => Query\Join::TYPE_LEFT,
                    ]
                ),
			],
			"order" => $sort,
			'count_total' => true,
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
		$arrObjects = [];
		while($qObjects = $queryObjects->Fetch()) {
            // Приведем наименования к единому стилю
            $queryObjectsArr = [
                'id' => $qObjects['id'],
                'latitude' => $qObjects['X_coordinate'],
                'longitude' => $qObjects['Y_coordinate'],
            ];
            // **
			$arrObjects[] = $queryObjectsArr;
		}
		return ["objects" => $arrObjects, "count" => $queryObjects->getCount(), "limit" => $limit, 'offset' => $offset];
	}

	public function getObjectPreviewAction(int $id)
    {
        Loader::includeModule("realty");
        $item = [];
        $realty = \RealtyObjectsClassTable::getList([
            "filter" => ["id" => $id],
            "select" => [
                "*",
                "id",
                "code",
                "PropertyLease",
                "PropertySale",
                "PropertyAddressRus",
                "DistanceFromMetro",
                "MetroStation",
                "PropertyNameRus",
                "BUSINESS_UNIT_ID" => "BUSINESS_LINKED.business_unit_id"
            ],
            "runtime" => [
                new Entity\ReferenceField(
                    "BUSINESS_LINKED",
                    \RealtyObjectsVsUnitsClassTable::class,
                    Query\Join::on('this.id', 'ref.object_id'),
                    ["join_type" => Query\Join::TYPE_LEFT]
                ),
            ],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        if ($object = $realty->fetch()) {
            $item["id"] = $object["id"];
            $item["propertyNameRus"] = $object["PropertyNameRus"];
            $item["code"] = $object["code"];
            $item["propertyLease"] = $object["PropertyLease"];
            $item["propertySale"] = $object["PropertySale"];
            if ($object["PropertyLease"] && $object["PropertySale"]) {
                $item["for"] = "Аренда/продажа";
            } elseif ($object["PropertyLease"]) {
                $item["for"] = "Аренда";
            } elseif ($object["PropertySale"]) {
                $item["for"] = "Продажа";
            }
            $item["propertyAddressRus"] = $object["PropertyAddressRus"];
            if ($object["MetroStation"]) {
                $metros = \RealtyMetrosClassTable::getList([
                    "filter" => [
                        "id" => $object["MetroStation"]
                    ],
                    "cache" => [
                        "ttl" => 3600,
                        "cache_joins" => true,
                    ],
                ]);
                if ($metro = $metros->fetch()) {
                    $item["metroStation"] = $metro["NameRus"];
                    $item["metroStationCode"] = $metro["code"];
                    $item["distanceFromMetro"] = $object["DistanceFromMetro"];
                    $color = "";
                    if ($metro["metroLine"]) {
                        $color = $this->getMetroColor($metro["metroLine"]);
                    }
                    $item["metro"] = [
                        "name" => $metro["NameRus"],
                        "code" => $metro["code"],
                        "distance" => $object["DistanceFromMetro"],
                        "color" => $color,
                    ];
                }
            }
            $item["propertyType"] = "";
            if ($object["PropertyType"]) {
                $arPropertyType = \CRealtyPropertyTypes::GetByID(
                    $object['PropertyType']
                );
                $item["propertyType"] = $arPropertyType["NameRus"];//NameRus
                $item["propertyTypeId"] = $arPropertyType["id"];//NameRus
            }
            $item["picture"] = "";
            $item["resizedPicture"] = "";
            $images = \RealtyPicturesClassTable::getList([
                "filter" => ["object_id" => $id],
                "order" => ["order" => "ASC"],
                "limit" => 1,
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ],
            ]);
            if ($image = $images->fetch()) {
                $fileName = \CRealty::tryGetWatermarkedPictureFilename($image["file"]);
                if ($fileName == $image["file"]) {
                    \CRealty::putWatermark(
                        $image["file"]
                    );
                    $fileName = \CRealty::getWatermarkedPictureFilename($image["file"]);
                }
                $item["picture"] = $fileName;
                $item["resizedPicture"] = $this->resiseThisImage($fileName);
            }
            $item["roomCount"] = 0;
            $item["square"] = 0;
            $rooms = \RealtyRoomsClassTable::getList([
                "select" => [
                    "*",
                    "ROOM_CONDITION" => "CONDITION.NameRus",
                    "ROOM_TYPE" => "TYPE.NameRus"
                ],
                "filter" => ["object_id" => $id],
                "runtime" => [
                    new Entity\ReferenceField(
                        "CONDITION",
                        \RealtyRoomsConditionsClassTable::class,
                        Query\Join::on('this.UnitDeliveryCondition', 'ref.id'),
                        ["join_type" => Query\Join::TYPE_INNER]
                    ),
                    new Entity\ReferenceField(
                        "TYPE",
                        \RealtyRoomsTypesClassTable::class,
                        Query\Join::on('this.UnitType', 'ref.id'),
                        ["join_type" => Query\Join::TYPE_INNER]
                    ),
                ],
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ],
            ]);
            while ($room = $rooms->fetch()) {
                $item["roomCount"]++;
                $item["square"] += $room["UnitSize"];
            }
            return $item;
        } else {
            $this->addError(new Error('Element could not be found by code.', 404));

            return null;
        }
    }

    public function getObjectFavoriteAction()
    {
        $request = Context::getCurrent()->getRequest();
        $id = $request->getQuery('id');

        $sort = [];

        $filter = ["id" => $id];
        $filter["active"] = 1;
        $queryObjects = \RealtyObjectsClassTable::getList([
            'select'  => ["PropertyType", "PropertySale", "PropertyLease", "BuildingClass", "COUNTY.name_ru", "METRO.NameRus", "METRO.code", "metroLine" => "METRO.metroLine", 'id', 'min_rent_price', 'max_rent_price', 'min_sale_price', 'max_sale_price', 'X_coordinate', 'Y_coordinate', 'PropertyAddressRus', 'PropertyDescriptionRus', 'LocationDescriptionRus', 'PropertyNameRus', 'code', 'MetroStation', 'DistanceFromMetro', 'LocationZone', 'Direction'], // имена полей, которые необходимо получить в результате
            'filter'  => $filter, // описание фильтра для WHERE и HAVING
            'group' => ["id"],
            "runtime" => [
                new Entity\ReferenceField(
                    "FLOORS",
                    \RealtyRoomsClassTable::class,
                    Query\Join::on('this.id', 'ref.object_id'),
                    ["join_type" => Query\Join::TYPE_LEFT]
                ),
                new Entity\ReferenceField(
                    "PARKING",
                    \RealtyParkingsClassTable::class,
                    Query\Join::on('this.id', 'ref.object_id'),
                    [
                        "join_type" => Query\Join::TYPE_LEFT,
                    ]
                ),
                new Entity\ReferenceField(
                    "METRO",
                    \RealtyMetrosClassTable::class,
                    Query\Join::on('this.MetroStation', 'ref.id'),
                    [
                        "join_type" => Query\Join::TYPE_LEFT,
                    ]
                ),
                new Entity\ReferenceField(
                    "COUNTY",
                    \RealtyCountyClassTable::class,
                    Query\Join::on('this.ita_county_id', 'ref.id'),
                    [
                        "join_type" => Query\Join::TYPE_LEFT,
                    ]
                ),
            ],
            //"order" => $sort,
            'count_total' => true,
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        $arrObjects = [];
        $metroLines = [];
        \Bitrix\Main\Loader::includeModule('realty');
        while($qObjects = $queryObjects->Fetch()) {
            // Приведем наименования к единому стилю
            $queryObjectsArr = [
                'id' => $qObjects['id'],
                'minRentPrice' => $qObjects['min_rent_price'],
                'maxRentPrice' => $qObjects['max_rent_price'],
                'minSalePrice' => $qObjects['min_sale_price'],
                'maxSalePrice' => $qObjects['max_sale_price'],
                'xCoordinate' => $qObjects['X_coordinate'],
                'yCoordinate' => $qObjects['Y_coordinate'],
                'propertyAddressRus' => $qObjects['PropertyAddressRus'],
                'propertyDescriptionRus' => $qObjects['PropertyDescriptionRus'],
                'locationDescriptionRus' => $qObjects['LocationDescriptionRus'],
                'propertyNameRus' => $qObjects['PropertyNameRus'],
                'code' => $qObjects['code'],
                'metroStation' => $qObjects['REALTY_OBJECTS_CLASS_METRO_NameRus'],
                'metroStationCode' => $qObjects['REALTY_OBJECTS_CLASS_METRO_code'],
                'distanceFromMetro' => $qObjects['DistanceFromMetro'],
                'locationZone' => $qObjects['LocationZone'],
                'direction' => $qObjects['Direction'],
                'county' => $qObjects['REALTY_OBJECTS_CLASS_COUNTY_name_ru'],
                'buildingClass' => $qObjects['BuildingClass'],
                'propertyLease' => $qObjects['PropertyLease'],
                'propertySale' => $qObjects['PropertySale'],
                'propertyType' => $qObjects['PropertyType'],
                "metroLine" => $qObjects["metroLine"],
            ];
            // **

            $queryObjectPicture = \RealtyPicturesClassTable::getList(
                [
                    'filter' => ['object_id' => $queryObjectsArr['id']],
                    'select' => ['file'],
                    "cache" => [
                        "ttl" => 3600,
                        "cache_joins" => true,
                    ],
                ]
            );
            $queryObjectsArr["pictures"] = [];
            while($queryObjectPictureArr = $queryObjectPicture->Fetch()) {
                If(!file_exists($_SERVER['DOCUMENT_ROOT'] . \CRealty::getWatermarkedPictureFilename($queryObjectPictureArr["file"]))) {
                    \CRealty::putWatermark(
                        $queryObjectPictureArr["file"]
                    );
                }
                $queryObjectsArr["pictures"][] = \CRealty::getWatermarkedPictureFilename($queryObjectPictureArr["file"]);
            }
            $queryRooms = \RealtyRoomsClassTable::getList(
                [
                    'filter' => ['object_id' => $queryObjectsArr['id']],
                    'select' => ['UnitOfferedRent', 'UnitFloorNumber', 'UnitSize'],
                    'limit' => 4,
                    'order' => ['UnitSize' => 'ASC'],
                    "cache" => [
                        "ttl" => 3600,
                        "cache_joins" => true,
                    ],
                ]
            );
            $queryObjectsArr["rooms"] = [];
            while($queryRoomsArr = $queryRooms->Fetch()) {
                $queryObjectsArr["rooms"][] = [
                    'unitOfferedRent' => $queryRoomsArr['UnitOfferedRent'],
                    'unitFloorNumber' => $queryRoomsArr['UnitFloorNumber'],
                    'unitSize' => $queryRoomsArr['UnitSize']
                ];
            }
            $queryObjectsArr["minUnitSize"] = (int)$queryObjectsArr["rooms"][0]["unitSize"];
            $queryRoomsMax = \RealtyRoomsClassTable::getList(
                [
                    'filter' => ['object_id' => $queryObjectsArr['id']],
                    'select' => ['UnitOfferedRent', 'UnitSalePrice', 'UnitFloorNumber', 'UnitSize'],
                    'order' => ['UnitSize' => 'ASC'],
                    "cache" => [
                        "ttl" => 3600,
                        "cache_joins" => true,
                    ],
                ]
            )->fetchAll();
            $queryObjectsArr["roomCount"] = count($queryRoomsMax);
            $queryObjectsArr["maxUnitSize"] = (int)array_pop($queryRoomsMax)["UnitSize"];

            if ($qObjects["metroLine"]) {
                $metroLines[] = $qObjects["metroLine"];
            }
            $arrObjects[] = $queryObjectsArr;
        }
        $linesColor = $this->getMetrosColor($metroLines);
        foreach ($arrObjects as $k => $object) {
            $arrObjects[$k]["metroColor"] = "";
            if ($linesColor[$object["metroLine"]]) {
                $arrObjects[$k]["metroColor"] = $linesColor[$object["metroLine"]];
            }
            unset($arrObjects[$k]["metroLine"]);
        }

        return $arrObjects;
    }

    public function searchNamePreviewAction()
    {
        $request = Context::getCurrent()->getRequest();
        $name = $request->getQuery("name");
        $for = $request->getQuery("for") ?? "lease";
        $filter = [];
        $filter["active"] = 1;
        if ($for == "lease") {
            $filter["PropertyLease"] = 1;
        } elseif ($for == "sale") {
            $filter["PropertySale"] = 1;
        }
        if (!empty($name)) {
            $result = ["items" => []];
            $filter["%PropertyNameRus"] = $name;
            $realty = \RealtyObjectsClassTable::getList([
                "filter" => $filter,
                "select" => [
                    "id",
                    "code",
                    "PropertyNameRus",
                ],
                "count_total" => true,
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ],
            ]);
            while ($object = $realty->fetch()) {
                $result["items"][] = [
                    "name" => $object["PropertyNameRus"],
                    "id" => $object["id"],
                    "code" => $object["code"],
                ];
            }
            $result["count"] = $realty->getCount();

            return $result;
        } else {
            $this->addError(new Error("Required parameter 'name' missed", 404));

            return null;
        }
    }

    public function getMetroColor($line)
    {
        Loader::includeModule("iblock");
        if ($line) {
            $elements = \Bitrix\Iblock\Elements\ElementMetrolinesTable::getList([
                "filter" => ["ID" => $line],
                "select" => ["ID", "COLOR_VALUE" => "COLOR.VALUE"],
                "cache" => [
                    "ttl" => 86400,
                    "cache_joins" => true,
                ],
            ]);
            if ($element = $elements->fetch()) {
                return mb_strtolower($element["COLOR_VALUE"]);
            }
        }

        return "";
    }

    public function getMetrosColor($lines)
    {
        Loader::includeModule("iblock");
        $linesColor = [];
        if ($lines) {
            $elements = \Bitrix\Iblock\Elements\ElementMetrolinesTable::getList([
                "filter" => ["ID" => $lines],
                "select" => ["ID", "COLOR_VALUE" => "COLOR.VALUE"],
                "cache" => [
                    "ttl" => 86400,
                    "cache_joins" => true,
                ],
            ]);
            while ($element = $elements->fetch()) {
                $linesColor[$element["ID"]] = mb_strtolower($element["COLOR_VALUE"]);
            }
        }

        return $linesColor;
    }

    public function searchAddressPreviewAction()
    {
        $request = Context::getCurrent()->getRequest();
        $query = $request->getQuery("address");
        $type = $request->getQuery("type") ?? "db";
        $for = $request->getQuery("for") ?? "lease";
        $result = ["items" => []];
        if ($type == "db" && !empty($query) && (mb_strlen($query) > 2)) {
            $filter = [];
            $filter["active"] = 1;
            if ($for == "lease") {
                $filter["PropertyLease"] = 1;
            } elseif ($for == "sale") {
                $filter["PropertySale"] = 1;
            }
            $filter["%PropertyAddressRus"] = $query;
            $realty = \RealtyObjectsClassTable::getList([
                "filter" => $filter,
                "select" => [
                    "id",
                    "code",
                    "PropertyAddressRus",
                ],
                "count_total" => true,
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ],
            ]);
            while ($object = $realty->fetch()) {
                $result["items"][] = [
                    "address" => $object["PropertyAddressRus"],
                ];
            }
            $result["count"] = $realty->getCount();
        } elseif ($type == "kladr" && !empty($query) && (mb_strlen($query) > 2)) {
            $uri = "api.php";
            $baseUrl = "https://kladr-api.ru/";
            $reqestParams = [
                "cityId" => 7700000000000,
                "contentType" => "street",
                "query" => $query
            ];

            $client = new Client([
                'base_uri'    => $baseUrl,
                'http_errors' => false,
            ]);
            $uri .= "?" . \http_build_query($reqestParams);
            $request = new GuzzleRequest("GET", $uri, []);

            $response = $client->send($request, []);
            $data = \json_decode($response->getBody(), true);
            foreach ($data["result"] as $address) {
                if ($address["id"] == "Free") {
                    continue;
                }
                if ($address["typeShort"] == "проезд") {
                    $name = $address["name"]." "."пр-д";
                } else {
                    $name = $address["name"]." ".$address["typeShort"];
                }
                $item = [
                    "address" => $name
                ];
                $result["items"][] = $item;
            }
            $result["count"] = count($result["items"]);
        } else {
            $this->addError(new Error("Required parameter 'address' is missed or less then 3 symbols", 404));

            return null;
        }

        return $result;
    }

    public function getDirectionsAction()
    {
        $result = [];
        $directions = \RealtyDirectionsClassTable::getList([
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ]
        ]);
        while ($direction = $directions->fetch()) {
            $result["items"][] = [
                "id" => $direction["id"],
                "name" => $direction["NameRus"]
            ];
        }

        return $result;
    }
}
