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
class News extends \Bitrix\Main\Engine\Controller
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
        $showImage = false;
        $limit = $request->getQuery('limit') ?? 8;
        $offset = $request->getQuery('offset') ?? 0;
        $image = $request->getQuery('image') ?? 1;
        $topicFilter = $request->getQuery('topic') ?? 'all';
        $year = $request->getQuery("year") ?? "all";
        $exclude = $request->getQuery('exclude');
        if ($image) {
            $showImage = true;
        }
        $result = [];
        $items = [];
        $select = [
            'ID',
            'NAME',
            'PREVIEW_TEXT',
            'ACTIVE_FROM',
            'CODE',
            'TOPIC_CODE' => 'P_DIR.ELEMENT.CODE',
            'TOPIC_NAME' => 'P_DIR.ELEMENT.NAME',
            'DATE_CREATE'
        ];
        if ($showImage) {
            $select[] = 'PREVIEW_PICTURE';
        }
        $filter = [
            '=ACTIVE' => 'Y'
        ];
        if ($topicFilter !== 'all') {
            $filter['TOPIC_CODE'] = $topicFilter;
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

        $ids = [];
        $elements = \Bitrix\Iblock\Elements\ElementNewsTable::getList([
            "select" => ["ID"],
            "filter" => $filter,
            "order" => ["ACTIVE_FROM" => "DESC", "DATE_CREATE" => "DESC"],
            "offset" => $offset,
            "limit" => $limit,
            "count_total" => true,
        ]);
        while ($element = $elements->fetch()) {
            $ids[] = $element["ID"];
        }
        $result["count"] = $elements->getCount();
        $result["limit"] = $limit;
        $result["offset"] = $offset;

        $elements = \Bitrix\Iblock\Elements\ElementNewsTable::getList([
            "select" => $select,
            "filter" => ["ID" => $ids],
            "order" => ["ACTIVE_FROM" => "DESC", "DATE_CREATE" => "DESC"],
            "offset" => $offset,
            "limit" => $limit,
            "count_total" => true,
        ]);
        while ($element = $elements->fetch()) {
            if (!isset($items[$element["ID"]])) {
                $item = [];
                $item["name"] = $element["NAME"];
                $item["id"] = $element["ID"];
                $item["slug"] = $element["CODE"];
                if ($showImage) {
                    if ($element["PREVIEW_PICTURE"]) {
                        $item["image"] = \CFile::GetPath($element["PREVIEW_PICTURE"]);
                    } else {
                        $item["image"] = "";
                    }
                }
                if ($element["ACTIVE_FROM"]) {
                    $item["date"] = $element["ACTIVE_FROM"]->format("d.m.Y");
                } else {
                    $item["date"] = $element["DATE_CREATE"]->format("d.m.Y");
                }
                $item["topic"] = $element["TOPIC_NAME"] ? [$element["TOPIC_NAME"]] : [];
                $item["preview"] = $element["PREVIEW_TEXT"];

                $items[$element["ID"]] = $item;
            } else {
                $items[$element["ID"]]["topic"][] = $element["TOPIC_NAME"];
            }
        }
        $result["items"] = array_values($items);

       	return $result;
    }

    public function sectionsAction()
    {
        Loader::includeModule("iblock");
        $items = [
            "topics" => [],
            "year" => [["code" => "all", "name" => "Все года"]],
        ];
        $elements = \Bitrix\Iblock\Elements\ElementNewstopicTable::getList([
            'select' => [
                'ID',
                'NAME',
                'CODE'
            ],
            "cache" => [
                "ttl" => 86400,
                "cache_joins" => true,
            ],
        ]);
        while ($element = $elements->fetch()) {
            $item = [
                "name" => $element["NAME"],
                "code" => $element["CODE"]
            ];
            $items["topics"][] = $item;
        }
        $elements = \Bitrix\Iblock\Elements\ElementNewsTable::getList([
            "select" => ["ACTIVE_FROM", "DATE_CREATE"],
            "filter" => [
                "=ACTIVE" => "Y"
            ],
            "cache" => [
                "ttl" => 86400,
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
            $items["year"][] = ["code" => (int)$year, "name" => (int)$year];
        }

        return $items;
    }

    public function detailAction(string $code)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $item = [];
        $select = [
            'ID',
            'NAME',
            'PREVIEW_PICTURE',
            'DETAIL_TEXT',
            'ACTIVE_FROM',
            'IBLOCK_ID',
            'CODE',
            'TOPIC_CODE' => 'P_DIR.ELEMENT.CODE',
            'TOPIC_NAME' => 'P_DIR.ELEMENT.NAME',
            'IMAGE_' => 'P_FILE.FILE',
            "AUTHOR.ELEMENT",
            "AUTHOR_EMAIL" => "AUTHOR.ELEMENT.P_EMAIL",
            "AUTHOR_TELEGRAM" => "AUTHOR.ELEMENT.TELEGRAM",
            "AUTHOR_VIBER" => "AUTHOR.ELEMENT.VIBER",
            "AUTHOR_WHATSAPP" => "AUTHOR.ELEMENT.WHATSAPP",
            "AUTHOR_P_PHONE" => "AUTHOR.ELEMENT.P_PHONE",
            "AUTHOR_POST_FULL" => "AUTHOR.ELEMENT.POST_FULL",
        ];
        $elements = \Bitrix\Iblock\Elements\ElementNewsTable::getList([
            'select' => $select,
            'filter' => [
                "CODE" => $code
            ],
            "cache" => [
                "ttl" => 86400,
                "cache_joins" => true,
            ],
        ]);
        if ($element = $elements->fetchObject()) {
            $iBlockId = $element->getIblockId();
            $item['name'] = $element->getName();
            $item['id'] = $element->getId();
            $item['slug'] = $element->getCode();
            if ($element->getPFile()) {
                $item['image'] = \CFile::GetPath($element->getPFile()->getFile()->getId());
            } else {
                $item['image'] = '';
            }

            $item['date'] = '';
            if ($element->getActiveFrom()) {
                $item['date'] = $element->getActiveFrom()->format("d.m.Y");
            }
            $item['topic'] = [];
            foreach ($element->getPDir()->getAll() as $value) {
                $item['topic'] = [
                    "name" => $value->getElement()->getName(),
                    "code" => $value->getElement()->getCode(),
                ];
            }
            $item['detailText'] = $element->getDetailText();
            $authors = [];
            if ($element->getAuthor()) {
                $author = $element->getAuthor();
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
            $item["author"] = $authors;

            $iPropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($iBlockId, $item['id']);
            $metaValues = $iPropValues->getValues();
            $item["seo"]["sectionMetaKeywords"] = "";
            if ($metaValues["SECTION_META_KEYWORDS"]) {
                $item["seo"]["sectionMetaKeywords"] = $metaValues["SECTION_META_KEYWORDS"];
            }
            $item["seo"]["elementMetaKeywords"] = "";
            if ($metaValues["SECTION_META_KEYWORDS"]) {
                $item["seo"]["elementMetaKeywords"] = $metaValues["ELEMENT_META_KEYWORDS"];
            }
            $item["seo"]["elementMetaTitle"] = "";
            if ($metaValues["ELEMENT_META_TITLE"]) {
                $item["seo"]["elementMetaTitle"] = $metaValues["ELEMENT_META_TITLE"];
            }
            $item["seo"]["elementMetaDescription"] = "";
            if ($metaValues["ELEMENT_META_DESCRIPTION"]) {
                $item["seo"]["elementMetaDescription"] = $metaValues["ELEMENT_META_DESCRIPTION"];
            }
        } else {
            $this->addError(new Error('Element could not be found by code.', 404));

            return null;
        }

        return $item;
    }
}
