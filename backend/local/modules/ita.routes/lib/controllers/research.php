<?php

namespace Ita\Routes\Controllers;

use \Bitrix\Main\ {
    Context,
    Loader,
    Error,
    Entity
};
use \Bitrix\Iblock\ {
    IblockTable,
    SectionTable,
    Model\Section
};
class Research extends \Bitrix\Main\Engine\Controller
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
		];
	}

	public function listAction()
    {
        Loader::includeModule("iblock");
        $request = Context::getCurrent()->getRequest();
        $limit = $request->getQuery("limit") ?? 8;
        $offset = $request->getQuery("offset") ?? 0;
        $sectionFilter = $request->getQuery("section") ?? "all";
        $typeFilter = $request->getQuery("type") ?? "all";
        $exclude = $request->getQuery("exclude");
        $year = $request->getQuery("year") ?? "all";
        $result = [];
        $items = [];
        $select = [
            "ID",
            "NAME",
            "TYPE_CODE" => "TYPE.ELEMENT.CODE",
            "TYPE_NAME" => "TYPE.ELEMENT.NAME",
            "TYPE_SHORT_NAME" => "TYPE.ELEMENT.SHORT_NAME.VALUE",
            "SECTION_" => "IBLOCK_SECTION",
            "PREVIEW_PICTURE",
            "P_CLOSE_ITEM_" => "P_CLOSE.ITEM",
            "SHORT_RESEARCH_" => "SHORT_RESEARCH.ITEM",
            "ACTIVE_FROM",
            "CODE",
            "DATE_CREATE"
        ];
        $filter = [
            "=ACTIVE" => "Y"
        ];
        if ($sectionFilter !== "all") {
            $filter["IBLOCK_SECTION.CODE"] = $sectionFilter;
        }
        if ($typeFilter !== "all") {
            $filter["TYPE_CODE"] = $typeFilter;
        }
        if ($exclude) {
            $filter["!CODE"] = $exclude;
        }
        if ($year != "all") {
            $dateStart = new \Bitrix\Main\Type\DateTime("01.01.".$year." 00:00:00");
            $dateEnd = new \Bitrix\Main\Type\DateTime("31.12.".$year." 23:59:59");
            $dateFilter = [
                "LOGIC" => "OR"
            ];
            $dateFilter[] = [
                ">=ACTIVE_FROM" => $dateStart,
                "<=ACTIVE_FROM" => $dateEnd,
            ];
            $dateFilter[] = [
                "ACTIVE_FROM" => false,
                ">=DATE_CREATE" => $dateStart,
                "<=DATE_CREATE" => $dateEnd,
            ];
            $filter[] = $dateFilter;
        }

        $elements = \Bitrix\Iblock\Elements\ElementResearchTable::getList([
            "select" => $select,
            "filter" => $filter,
            "order" => ["ACTIVE_FROM" => "DESC", "DATE_CREATE" => "DESC"],
            "offset" => $offset,
            "limit" => $limit,
            "count_total" => true,
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($element = $elements->fetch()) {
            $item = [];
            $item["name"] = $element["NAME"];
            $item["id"] = $element["ID"];
            if ($element["PREVIEW_PICTURE"]) {
                $item["image"] = \CFile::ResizeImageGet(
                    $element["PREVIEW_PICTURE"],
                    [
                        "width" => 1000,
                        "height" => 1000
                    ]
                )["src"];
            } else {
                $item["image"] = "";
            }
            $item["type"] = "";
            if ($element["TYPE_NAME"]) {
                $item["type"] = $element["TYPE_NAME"];
            }
            if ($element["P_CLOSE_ITEM_XML_ID"] == "1") {
                $item["closed"] = 1;
            } else {
                $item["closed"] = 0;
                $item["slug"] = $element["CODE"];
            }
            $item["short"] = 0;
            if ($element["SHORT_RESEARCH_XML_ID"] == "Y") {
                $item["short"] = 1;
            }
            if ($element["ACTIVE_FROM"]) {
                $item["date"] = $element["ACTIVE_FROM"]->format("d.m.Y");
            } else {
                $item["date"] = $element["DATE_CREATE"]->format("d.m.Y");
            }
            $item["section"] = $element["SECTION_NAME"];

            $items[] = $item;
        }
        $result["items"] = $items;
        $result["count"] = $elements->getCount();
        $result["limit"] = $limit;
        $result["offset"] = $offset;
        
        return $result;
    }

    public function sectionsAction()
    {
        Loader::includeModule("iblock");
        $result = [
            "sections" => [
                ["code" => "all", "name" => "Вся недвижимость"]
            ],
            "types" => [
                ["code" => "all", "name" => "Все отчёты"],
            ],
            "year" => [
                ["code" => "all", "name" => "Все года"]
            ]
        ];
        $sections = [];
        $elements = \Bitrix\Iblock\Elements\ElementResearchTable::getList([
            "select" => ["CNT", "IBLOCK_SECTION_ID"],
            "group" => ["IBLOCK_SECTION_ID"],
            "filter" => ["ACTIVE" => "Y"],
            "runtime" => [
                new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)'),
            ],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($element = $elements->fetch()) {
            if ($element["IBLOCK_SECTION_ID"]) {
                $sections[] = $element["IBLOCK_SECTION_ID"];
            }
        }
        $iBlocks = IblockTable::getList([
            "filter" => [
                "CODE" => "analitics",
                "IBLOCK_TYPE_ID" => "analytics"
            ],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        if ($iblock = $iBlocks->fetch()) {
            $iblockId = $iblock['ID'];
        }
        $entity = Section::compileEntityByIblock($iblockId);
        $rsSection = $entity::getList([
            "filter" => [
                "ACTIVE" => "Y",
                "GLOBAL_ACTIVE" => "Y",
                "ID" => $sections
            ],
            "select" => [
                "ID",
                "NAME",
                "CODE",
                "UF_SHORT_NAME",
            ],
            "order" => [
                "SORT" => "ASC"
            ],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($section = $rsSection->fetch()) {
            $item = ["name" => $section["NAME"], "code" => $section["CODE"]];
            if ($section["UF_SHORT_NAME"]) {
                $item["name"] = $section["UF_SHORT_NAME"];
            }
            $result["sections"][] = $item;
        }

        $elements = \Bitrix\Iblock\Elements\ElementResearchTable::getList([
            "select" => [
                "CNT",
                "TYPE_CODE" => "TYPE.ELEMENT.CODE",
                "TYPE_NAME" => "TYPE.ELEMENT.NAME",
                "TYPE_SHORT_NAME" => "TYPE.ELEMENT.SHORT_NAME.VALUE"
            ],
            "filter" => ["ACTIVE" => "Y"],
            "group" => ["TYPE.ELEMENT.CODE"],
            "runtime" => [
                new \Bitrix\Main\Entity\ExpressionField('CNT', 'COUNT(*)'),
            ],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($element = $elements->fetch()) {
            if ($element["TYPE_CODE"]) {
                $result["types"][] = [
                    "name" => $element["TYPE_SHORT_NAME"] ?: $element["TYPE_NAME"],
                    "code" => $element["TYPE_CODE"]
                ];
            }
        }
        $elements = \Bitrix\Iblock\Elements\ElementResearchTable::getList([
            "select" => ["ACTIVE_FROM", "DATE_CREATE"],
            "filter" => [
                "=ACTIVE" => "Y"
            ],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        $years = [];
        while ($element = $elements->fetch()) {
            if ($element["ACTIVE_FROM"]) {
                $year = $element["ACTIVE_FROM"]->format("Y");
            } else {
                $year = $element["DATE_CREATE"]->format("Y");
            }
            $years[$year] = $year;
        }
        arsort($years);
        foreach ($years as $year) {
            $result["year"][] = ["code" => (int)$year, "name" => (int)$year];
        }

        return $result;
    }

    public function detailAction(string $code)
    {
        Loader::includeModule("iblock");
        $item = [];
        $id = 0;
        $re = '/-full-(\d+)/m';
        if (preg_match_all($re, $code, $matches, PREG_SET_ORDER, 0)) {
            $id = $matches[0][1];
            $code = mb_substr($code, 0, mb_strlen($code) - 6 - mb_strlen($id));
        }
        $elements = \Bitrix\Iblock\Elements\ElementResearchTable::getList([
            "select" => [
                "ID",
                "NAME",
                "IBLOCK_ID",
                "TYPE_CODE" => "TYPE.ELEMENT.CODE",
                "TYPE_NAME" => "TYPE.ELEMENT.NAME",
                "TYPE_SHORT_NAME" => "TYPE.ELEMENT.SHORT_NAME.VALUE",
                "SECTION_" => "IBLOCK_SECTION",
                "PREVIEW_PICTURE",
                "P_CLOSE_ITEM_" => "P_CLOSE.ITEM",
                "ACTIVE_FROM",
                "DETAIL_TEXT",
                "PREVIEW_TEXT",
                "SHORT_RESEARCH.ITEM",
                "AUTHOR.ELEMENT",
                "AUTHOR_EMAIL" => "AUTHOR.ELEMENT.P_EMAIL",
                "AUTHOR_TELEGRAM" => "AUTHOR.ELEMENT.TELEGRAM",
                "AUTHOR_VIBER" => "AUTHOR.ELEMENT.VIBER",
                "AUTHOR_WHATSAPP" => "AUTHOR.ELEMENT.WHATSAPP",
                "AUTHOR_P_PHONE" => "AUTHOR.ELEMENT.P_PHONE",
                "AUTHOR_POST_FULL" => "AUTHOR.ELEMENT.POST_FULL",
            ],
            "filter" => [
                "CODE" => $code
            ],
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        if ($element = $elements->fetchObject()) {
            $iBlockId = $element->getIblockId();
            $item["name"] = $element->getName();
            $item["id"] = $element->getId();
            if ($element->getPreviewPicture()) {
                $item["image"] = \CFile::GetPath($element->getPreviewPicture());
            } else {
                $item["image"] = "";
            }
            $item["type"] = [];
            if ($element->getType()) {
                if ($element->getType()->getElement()->getCode()) {
                    $item["type"] = [
                        "name" => $element->getType()->getElement()->getName(),
                        "code" => $element->getType()->getElement()->getCode()
                    ];
                }
            }
            $item["closed"] = 0;
            if ($element->getP_close()) {
                if ($element->getP_close()->getItem()->getXmlId() == "1") {
                    $item["closed"] = "1";
                }
            }
            $item["short"] = 0;
			if ($element->getShortResearch()) {
                if ($element->getShortResearch()->getItem()->getXmlId() == "Y") {
                    $item["short"] = "1";
                }
            }
            $item["section"] = [
                "name" => $element->getIblockSection()->getName(),
                "code" => $element->getIblockSection()->getCode()
            ];
            $item["date"] = $element->getActiveFrom()->format("d.m.Y");
            $item["previewText"] = $element->getPreviewText();
            $item["detailText"] = $element->getDetailText();
            $item["facts"] = [];
            $facts = \Bitrix\Iblock\Elements\ElementResearchfactTable::getList([
                "filter" => ["RESEARCH.ELEMENT.ID" => $element["ID"]],
                "select" => [
                    "ID",
                    "NAME",
                    "PREVIEW_TEXT",
                    "DETAIL_TEXT",
                    "ARROW_TYPE" => "ARROW.ITEM.XML_ID",
                    "ARROW_NAME" => "ARROW.ITEM.VALUE",
                ],
                "order" => ["SORT" => "ASC"],
                "cache" => [
                    "ttl" => 3600,
                    "cache_joins" => true,
                ]
            ]);
            while ($fact = $facts->fetch()) {
                $item["facts"][] = [
                    "title" => $fact["NAME"],
                    "subTitle" => $fact["PREVIEW_TEXT"],
                    "arrow" => $fact["ARROW_TYPE"],
                    "description" => $fact["DETAIL_TEXT"],
                ];
            }
            $authors = [];
            foreach ($element->getAuthor()->getAll() as $author) {
                $authorItem = [];
                $authorItem["name"] = $author->getElement()->getName();
                $authorItem["phone"] = "";
                if ($author->getElement()->getPPhone()) {
                    $authorItem["phone"] = $author->getElement()->getPPhone()->getValue();
                }
                $authorItem["email"] = "";
                if ($author->getElement()->getPEmail()) {
                    $authorItem["email"] = $author->getElement()->getPEmail()->getValue();
                }
                $authorItem["whatsapp"] = "";
                if ($author->getElement()->getWhatsapp()) {
                    $authorItem["whatsapp"] = $author->getElement()->getWhatsapp()->getValue();
                }
                $authorItem["viber"] = "";
                if ($author->getElement()->getViber()) {
                    $authorItem["viber"] = $author->getElement()->getViber()->getValue();
                }
                $authorItem["telegram"] = "";
                if ($author->getElement()->getTelegram()) {
                    $authorItem["telegram"] = $author->getElement()->getTelegram()->getValue();
                }
                $authorItem["jobTitle"] = "";
                if ($author->getElement()->getPostFull()) {
                    $authorItem["jobTitle"] = $author->getElement()->getPostFull()->getValue();
                }
                if ($author->getElement()->getPreviewPicture()) {
                    $authorItem["image"] = \CFile::ResizeImageGet(
                        $author->getElement()->getPreviewPicture(),
                        [
                            "width" => 200,
                            "height" => 200
                        ],
                        BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                    )["src"];
                }
                $authors[] = $authorItem;
            }
            $item["authors"] = $authors;

            $iPropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($iBlockId, $item["id"]);
            $metaValues = $iPropValues->getValues();

            $item["metaTitle"] = $this->prepareMetaString(strval($metaValues["ELEMENT_META_TITLE"]));
            $item["metaKeywords"] = $this->prepareMetaString(strval($metaValues["ELEMENT_META_KEYWORDS"]));
            $item["metaDescription"] = $this->prepareMetaString(strval($metaValues["ELEMENT_META_DESCRIPTION"]));
        } else {
            $this->addError(new Error("Element could not be found by code.", 404));

            return null;
        }

        return $item;
    }

    protected function prepareMetaString(string $value)
    {
        $value = html_entity_decode($value, ENT_QUOTES);
        return strip_tags($value);
    }
}
