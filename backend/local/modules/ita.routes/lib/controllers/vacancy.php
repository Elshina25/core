<?php

namespace Ita\Routes\Controllers;

use \Bitrix\Main\ {
    Context,
    Loader,
    Error
};
use \Bitrix\Iblock\ {
    IblockTable,
    SectionTable,
    Model\Section
};
class Vacancy extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'list' => [
				'prefilters' => [],
				'postfilters' => []
			],
            'sections' => [
                'prefilters' => [],
                'postfilters' => []
            ],
            'detail' => [
                'prefilters' => [],
                'postfilters' => []
            ],
			'workOnUs' => [
                'prefilters' => [],
                'postfilters' => []
            ],
			'workOnUsDetail' => [
                'prefilters' => [],
                'postfilters' => []
            ],
			'workersHistory' => [
                'prefilters' => [],
                'postfilters' => []
            ],
			'getTopics' => [
                'prefilters' => [],
                'postfilters' => []
            ],
			'whatWeOffer' => [
                'prefilters' => [],
                'postfilters' => []
            ],
			'whatWeOfferDetail' => [
                'prefilters' => [],
                'postfilters' => []
            ],
		];
	}

	public function listAction()
    {
        Loader::includeModule("iblock");
        $request = Context::getCurrent()->getRequest();
        $limit = $request->getQuery('limit') ?? 0;
        $offset = $request->getQuery('offset') ?? 0;
        $result = [];
        $items = [];
        $select = [
            'ID',
            'NAME',
            'CITY',
            'SALARY_FROM',
            'SALARY_TO',
            'METRO',
            'LINK'
        ];
        $filter = [
            '=ACTIVE' => 'Y'
        ];

        $elements = \Bitrix\Iblock\Elements\ElementVacancyTable::getList([
            'select' => $select,
            'filter' => $filter,
            'order' => ['ID' => 'DESC'],
            'offset' => $offset,
            'limit' => $limit,
            'count_total' => true,
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($element = $elements->fetchObject()) {
            $item = [];
            $item['name'] = $element->getName();
            $item['id'] = $element->getId();
            $item['city'] = "";
            if ($element->getCity()) {
                $item['city'] = $element->getCity()->getValue();
            }
            $item['metro'] = "";
            if ($element->getMetro()) {
                $item['metro'] = $element->getMetro()->getValue();
            }
            if ($element->getLink()) {
                $item['link'] = $element->getLink()->getValue();
            }
            $item['salaryFrom'] = "";
            $item['salaryTo'] = "";
            $salaryFrom = 0;
            $salaryTo = 0;
            if ($element->getSalaryFrom()) {
                $salaryFrom = $element->getSalaryFrom()->getValue();
            }
            if ($element->getSalaryTo()) {
                $salaryTo = $element->getSalaryTo()->getValue();
            }
            if ($salaryFrom && !$salaryTo) {
                $salaryTo = $salaryFrom;
            } elseif ($salaryTo && !$salaryFrom) {
                $salaryFrom = $salaryTo;
            }
            $item['salaryFrom'] = $salaryFrom;
            $item['salaryTo'] = $salaryTo;
            $items[] = $item;
        }
        $result['items'] = $items;
        $result['count'] = $elements->getCount();
        $result['limit'] = $limit;
        $result['offset'] = $offset;

       	return $result;
    }

    public function detailAction(string $code)
    {
        Loader::includeModule("iblock");
        $request = Context::getCurrent()->getRequest();
        $item = [];
        $select = [
            'ID',
            'NAME',
            'SECTION_' => 'IBLOCK_SECTION',
            'ACTIVE_FROM',
            'CODE',
            'CITY',
            'SALARY_FROM',
            'SALARY_TO',
            'METRO',
            'P_DEPART.ELEMENT',
            'P_RESP',
            'P_REQ',
            'P_COND',
        ];
        $filter = [
            'CODE' => $code
        ];
        $elements = \Bitrix\Iblock\Elements\ElementVacancyTable::getList([
            'select' => $select,
            'filter' => $filter,
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        if ($element = $elements->fetchObject()) {
            $item = [];
            $item['name'] = $element->getName();
            $item['id'] = $element->getId();
            $item['code'] = $element->getCode();
            $item['date'] = "";
            if ($element->getActiveFrom()) {
                $item['date'] = $element->getActiveFrom()->format("d.m.Y");
            }
            $item['section'] = [];
            if ($element->getIblockSection()) {
                $item['section'] = [
                    "name" => $element->getIblockSection()->getName(),
                    "code" => $element->getIblockSection()->getCode(),
                ];
            }
            $item['city'] = "";
            if ($element->getCity()) {
                $item['city'] = $element->getCity()->getValue();
            }
            $item['metro'] = "";
            if ($element->getMetro()) {
                $item['metro'] = $element->getMetro()->getValue();
            }
            $item['salaryFrom'] = "";
            $item['salaryTo'] = "";
            $salaryFrom = 0;
            $salaryTo = 0;
            if ($element->getSalaryFrom()) {
                $salaryFrom = $element->getSalaryFrom()->getValue();
            }
            if ($element->getSalaryTo()) {
                $salaryTo = $element->getSalaryTo()->getValue();
            }
            if ($salaryFrom && !$salaryTo) {
                $salaryTo = $salaryFrom;
            } elseif ($salaryTo && !$salaryFrom) {
                $salaryFrom = $salaryTo;
            }
            $item['salaryFrom'] = $salaryFrom;
            $item['salaryTo'] = $salaryTo;
            $item['department'] = '';
            if ($element->getPDepart()) {
                $item['department'] = $element->getPDepart()->getElement()->getName();
            }
            $item['responsibilities'] = [];
            if ($element->getPResp()) {
                foreach ($element->getPResp()->getAll() as $responsibility) {
                    $item['responsibilities'][] = $responsibility->getValue();
                }
            }
            $item['requirements'] = [];
            if ($element->getPReq()) {
                foreach ($element->getPReq()->getAll() as $requirement) {
                    $item['requirements'][] = $requirement->getValue();
                }
            }
            $item['conditions'] = [];
            if ($element->getPCond()) {
                foreach ($element->getPCond()->getAll() as $condition) {
                    $item['conditions'][] = $condition->getValue();
                }
            }
        } else {
            $this->addError(new Error('Element could not be found by code.', 404));

            return null;
        }

        return $item;
    }

	public function workOnUsAction()
	{
		Loader::includeModule("iblock");
		$request = Context::getCurrent()->getRequest();
		$limit = $request->getQuery('limit') ?? 3;
		$offset = $request->getQuery('offset') ?? 0;
		$elements = \Bitrix\Iblock\Elements\ElementWorkonusTable::getList([
			'select' => ["NAME", "PREVIEW_PICTURE", "CODE", "PREVIEW_TEXT"],
			'filter' => ["ACTIVE" => 'Y'],
			'limit' => $limit,
			'offset' => $offset,
			'count_total' => true,
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
		$count = $elements->getCount();
		$elements = $elements->fetchAll();	
		foreach($elements as &$el) {
			if($el["PREVIEW_PICTURE"]) {
				$el["PREVIEW_PICTURE"] = \CFile::GetPath($el["PREVIEW_PICTURE"]);
			}
			$el["previewPicture"] = $el["PREVIEW_PICTURE"];
			unset($el["PREVIEW_PICTURE"]);
			$el["name"] = $el["NAME"];
			unset($el["NAME"]);
			$el["code"] = $el["CODE"];
			unset($el["CODE"]);
			$el["previewText"] = $el["PREVIEW_TEXT"];
			unset($el["PREVIEW_TEXT"]);
		}
		return ["items" => $elements, "count" => $count,
		 "limit" => $limit, "offset" => $offset];
	}
	public function workOnUsDetailAction(string $code)
	{
		Loader::includeModule("iblock");
		$element = \Bitrix\Iblock\Elements\ElementWorkonusTable::getList([
			'select' => ["NAME", "DETAIL_PICTURE", "DETAIL_TEXT", "CODE"],
			'filter' => ["CODE" => $code, "ACTIVE" => 'Y'],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
		$el = $element->Fetch();
		if(!$el) {
			$this->addError(new Error('Element could not be found by code.', 404));
			return [];
		}
		if($el["DETAIL_PICTURE"]) {
			$el["DETAIL_PICTURE"] = \CFile::GetPath($el["DETAIL_PICTURE"]);
		}
		$el["detailPicture"] = $el["DETAIL_PICTURE"];
		unset($el["DETAIL_PICTURE"]);
		$el["name"] = $el["NAME"];
		unset($el["NAME"]);
		$el["detailText"] = $el["DETAIL_TEXT"];
		unset($el["DETAIL_TEXT"]);
		$el["code"] = $el["CODE"];
		unset($el["CODE"]);

		return $el;
	}

	public function workersHistoryAction()
	{
		Loader::includeModule("iblock");
		$request = Context::getCurrent()->getRequest();
		$topic = $request->getQuery('topic') ?? "all";
		$limit = $request->getQuery('limit') ?? 3;
		$offset = $request->getQuery('offset') ?? 0;
		$iblockIdWorkerHistory = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'workershistory'],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ])->fetch()["ID"];
		$filter = ["IBLOCK_ID" => $iblockIdWorkerHistory, "=ACTIVE" => "Y"];
		if($topic !== "all") {
			$filter["PROPERTY_TOPIC"] = $topic;
		}
		$howMany = [];
		if ($limit && $offset) {
            $howMany["nTopCount"] = $limit;
            $howMany["nOffset"] = $offset;
        } else {
		    $howMany = false;
        }
		$histories = [];
		$historyQuery = \CIBlockElement::GetList(
			["SORT"=>"ASC"],
			$filter,
			false,
			$howMany,
			["NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "ID", "IBLOCK_ID", "PROPERTY_*"]
		);
		$historyQueryCount = \CIBlockElement::GetList(
			["SORT"=>"ASC"],
			$filter,
			false,
			false,
			["NAME", "PREVIEW_TEXT", "PREVIEW_PICTURE", "ID", "IBLOCK_ID", "PROPERTY_*"]
		)->SelectedRowsCount();
		while($historyQueryRes = $historyQuery->GetNextElement()) {
			$prearr = $historyQueryRes->GetFields();
			$prearr["PROPERTIES"] = $historyQueryRes->GetProperties();
			if($prearr["PREVIEW_PICTURE"]) {
				$prearr["PREVIEW_PICTURE"] = \CFile::GetPath($prearr["PREVIEW_PICTURE"]);
			}
			$history["author"]["name"] = $prearr["NAME"];
			$history["previewText"] = $prearr["PREVIEW_TEXT"];
			$history["author"]["image"] = $prearr["PREVIEW_PICTURE"];
			$history["topics"] = $prearr["PROPERTIES"]["TOPIC"]["VALUE"];
			$history["author"]["jobTitle"] = $prearr["PROPERTIES"]["JOB_TITLE"]["VALUE"];
			$histories[] = $history;
		}

		return ["items" => $histories, 'count' => $historyQueryCount, 'limit' => $limit, 'offset' => $offset];
	}

	public function getTopicsAction()
	{
        \Bitrix\Main\Loader::includeModule('iblock');
        $result = [
            [
                "name" => "Все темы",
                "code" => "all",
            ]
        ];
        $elements = \Bitrix\Iblock\Elements\ElementWorkershistoryTable::getList([
            "select" => [
                "CNT",
                "TOPIC_CODE" => "TOPIC.ITEM.XML_ID",
                "TOPIC_NAME" => "TOPIC.ITEM.VALUE",
                "TOPIC_ID" => "TOPIC.ITEM.ID",
            ],
            "filter" => ["ACTIVE" => "Y"],
            "group" => ["TOPIC_ID"],
            "runtime" => [
                new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)'),
            ],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($element = $elements->fetch()) {
            if ($element["TOPIC_ID"]) {
                $result[] = [
                    "name" => $element["TOPIC_NAME"],
                    "code" => $element["TOPIC_ID"]
                ];
            }
        }

		return $result;
	}
	public function whatWeOfferAction()
	{
		\Bitrix\Main\Loader::includeModule('iblock');
		$request = Context::getCurrent()->getRequest();
		$limit = $request->getQuery('limit') ?? 3;
		$offset = $request->getQuery('offset') ?? 0;
		$elements = \Bitrix\Iblock\Elements\ElementWhatweofferTable::getList([
			'select' => ["NAME", "PREVIEW_TEXT"],
			'filter' => ["ACTIVE" => 'Y'],
			'limit' => $limit,
			'offset' => $offset,
            "order" => ["SORT" => "ASC"],
			'count_total' => true,
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
		$count = $elements->getCount();
		$elements = $elements->fetchAll();
		foreach($elements as &$el) {
			$el["name"] = $el["NAME"];
			unset($el["NAME"]);
			$el["previewText"] = $el["PREVIEW_TEXT"];
			unset($el["PREVIEW_TEXT"]);
		}
		return ["items" => $elements, "count" => $count,
		 "limit" => $limit, "offset" => $offset];
	}

	public function whatWeOfferDetailAction(string $code)
	{
		Loader::includeModule("iblock");
		$element = \Bitrix\Iblock\Elements\ElementWhatweofferTable::getList([
			'select' => ["NAME", "DETAIL_PICTURE", "DETAIL_TEXT", "CODE"],
			'filter' => ["CODE" => $code, "ACTIVE" => 'Y'],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
		$el = $element->Fetch();
		if(!$el) {
			$this->addError(new Error('Element could not be found by code.', 404));
			return [];
		}
		if($el["DETAIL_PICTURE"]) {
			$el["DETAIL_PICTURE"] = \CFile::GetPath($el["DETAIL_PICTURE"]);
		}
		$el["detailPicture"] = $el["DETAIL_PICTURE"];
		unset($el["DETAIL_PICTURE"]);
		$el["name"] = $el["NAME"];
		unset($el["NAME"]);
		$el["detailText"] = $el["DETAIL_TEXT"];
		unset($el["DETAIL_TEXT"]);
		$el["code"] = $el["CODE"];
		unset($el["CODE"]);

		return $el;
	}
}
