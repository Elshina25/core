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
    PropertyTable,
    PropertyEnumerationTable,
    Model\Section
};
class Projects extends \Bitrix\Main\Engine\Controller
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
            'listFact' => [
                'prefilters' => [],
                'postfilters' => []
            ],
		];
	}

	public function listAction()
    {
        Loader::includeModule("iblock");
        $request = Context::getCurrent()->getRequest();
        $limit = $request->getQuery('limit') ?? 8;
        $offset = $request->getQuery('offset') ?? 0;
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
            'CODE',
            "PREVIEW_TEXT"
        ];
        $filter = [
            '=ACTIVE' => 'Y'
        ];
        if ($sectionFilter !== 'all') {
            $filter['IBLOCK_SECTION.CODE'] = $sectionFilter;
        }
        if ($typeFilter !== "all") {
            $filter["TYPE.ITEM.XML_ID"] = $typeFilter;
        }
        if ($exclude) {
            $filter["!CODE"] = $exclude;
        }

        $elements = \Bitrix\Iblock\Elements\ElementProjectsTable::getList([
            'select' => $select,
            'filter' => $filter,
            'order' => ['ACTIVE_FROM' => 'DESC'],
            'offset' => $offset,
            'limit' => $limit,
            'count_total' => true,
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($element = $elements->fetch()) {
            $item = [];
            $item['name'] = $element['NAME'];
            $item['id'] = $element['ID'];
            $item["preview"] = "";
            if ($element["PREVIEW_TEXT"]) {
                $item["preview"] = $element["PREVIEW_TEXT"];
            }

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
        $result['count'] = $elements->getCount();
        $result['limit'] = $limit;
        $result['offset'] = $offset;

       	return $result;
    }

    public function sectionsAction()
    {
        Loader::includeModule("iblock");
        $sections = [
            "sections" => [
                ["code" => "all", "name" => "Все предложения"]
            ],
            "types" => [
                ["code" => "all", "name" => "Вся недвижимость"],
            ]
        ];
        $iBlocks = IblockTable::getList([
            "filter" => [
                "CODE" => "case_studies",
                "IBLOCK_TYPE_ID" => "about"
            ]
        ]);
        if ($iblock = $iBlocks->fetch()) {
            $iblockId = $iblock['ID'];
        }
        $entity = Section::compileEntityByIblock($iblockId);
        $rsSection = $entity::getList([
            "filter" => [
                "ACTIVE" => "Y",
                "GLOBAL_ACTIVE" => "Y"
            ],
            "select" => [
                "ID",
                "NAME",
                "CODE",
            ],
            "order" => [
                "SORT" => "ASC"
            ]
        ]);
        while ($section = $rsSection->fetch()) {
            $item = ["name" => $section["NAME"], "code" => $section["CODE"]];
            if ($section["UF_SHORT_NAME"]) {
                $item["name"] = $section["UF_SHORT_NAME"];
            }
            $sections["sections"][] = $item;
        }
        $propertyId = 0;
        $props = PropertyTable::getList([
            "filter" => [
                "CODE" => "TYPE",
                "IBLOCK_ID" => $iblockId
            ],
            "cache" => [
                "ttl" => 86400,
            ],
        ]);
        if ($prop = $props->fetch()) {
            $propertyId = $prop["ID"];
        }
        $props = PropertyEnumerationTable::getList([
            "filter" => ["PROPERTY_ID" => $propertyId],
            "order" => ["SORT" => "ASC"],
            "cache" => [
                "ttl" => 86400,
            ],
        ]);
        while ($prop = $props->fetch()) {
            $item = [
                "name" => $prop["VALUE"],
                "code" => $prop["XML_ID"]
            ];
            $sections["types"][] = $item;
        }

        return $sections;
    }

    public function detailAction(string $code)
    {
        \Bitrix\Main\Loader::includeModule('iblock');
        $item = [];
        $iBlockId = 0;
        $elements = \Bitrix\Iblock\Elements\ElementProjectsTable::getList([
            "select" => [
                "ID",
                "NAME",
                "TYPE_" => "TYPE.ITEM",
                "SECTION_" => "IBLOCK_SECTION",
                "PREVIEW_PICTURE",
                "ACTIVE_FROM",
                "DATE_CREATE",
                "CODE",
                "DETAIL_TEXT",
                "DETAIL_PICTURE",
                "IBLOCK_ID",
                "QUOTETEXT",
                "SUBTITLE",
                "QUOTEAUTHOR",
                "PHOTOS",
                "PERSON.ELEMENT",
                "PERSON_EMAIL" => "PERSON.ELEMENT.P_EMAIL",
                "PERSON_TELEGRAM" => "PERSON.ELEMENT.TELEGRAM",
                "PERSON_VIBER" => "PERSON.ELEMENT.VIBER",
                "PERSON_WHATSAPP" => "PERSON.ELEMENT.WHATSAPP",
                "PERSON_P_PHONE" => "PERSON.ELEMENT.P_PHONE",
                "PERSON_POST_FULL" => "PERSON.ELEMENT.POST_FULL",
                "SERVICES.ELEMENT",
                "FORM_TITLE",
                "FORM_DESC",
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
            $item["name"] = $element->getName();
            $item["id"] = $element->getId();
            $item["date"] = "";
            if ($element->getActiveFrom()) {
                $item["date"] = $element->getActiveFrom()->format("d.m.Y");
            } else {
                $item["date"] = $element->getDateCreate()->format("d.m.Y");
            }
            $item["type"] = [
                "name" => "",
                "code" => ""
            ];
            if ($element->getType()) {
                $item["type"] = [
                    "name" => $element->getType()->getItem()->getValue(),
                    "code" => $element->getType()->getItem()->getXmlId()
                ];
            }
            $item["section"] = [
                "name" => "",
                "code" => ""
            ];
            if ($element->getIblockSection()) {
                $item["section"] = [
                    "name" => $element->getIblockSection()->getName(),
                    "code" => $element->getIblockSection()->getCode()
                ];
            }
            $iBlockId = $element->getIblockId();
            if ($element->getDetailPicture()) {
                $item["image"] = \CFile::GetPath($element->getDetailPicture());
            } else {
                $item["image"] = "";
            }
            $item["detailText"] = $element->getDetailText();
            $persons = [];
            foreach ($element->getPerson()->getAll() as $person) {
                $personItem = [];
                $personItem["name"] = $person->getElement()->getName();
                $personItem["phone"] = "";
                if ($person->getElement()->getPPhone()) {
                    $personItem["phone"] = $person->getElement()->getPPhone()->getValue();
                }
                $personItem["email"] = "";
                if ($person->getElement()->getPEmail()) {
                    $personItem["email"] = $person->getElement()->getPEmail()->getValue();
                }
                $personItem["whatsapp"] = "";
                if ($person->getElement()->getWhatsapp()) {
                    $personItem["whatsapp"] = $person->getElement()->getWhatsapp()->getValue();
                }
                $personItem["viber"] = "";
                if ($person->getElement()->getViber()) {
                    $personItem["viber"] = $person->getElement()->getViber()->getValue();
                }
                $personItem["telegram"] = "";
                if ($person->getElement()->getTelegram()) {
                    $personItem["telegram"] = $person->getElement()->getTelegram()->getValue();
                }
                $personItem["jobTitle"] = "";
                if ($person->getElement()->getPostFull()) {
                    $personItem["jobTitle"] = $person->getElement()->getPostFull()->getValue();
                }
                if ($person->getElement()->getPreviewPicture()) {
                    $personItem["image"] = \CFile::ResizeImageGet(
                        $person->getElement()->getPreviewPicture(),
                        [
                            "width" => 200,
                            "height" => 200
                        ],
                        BX_RESIZE_IMAGE_PROPORTIONAL_ALT
                    )["src"];
                }
                $persons[] = $personItem;
            }
            $item["persons"] = $persons;
            if ($element->getQuotetext()) {
                $quoteText = unserialize($element->getQuotetext()->getValue());
                $item["quoteText"] = $quoteText["TEXT"];
            } else {
                $item["quoteText"] = "";
            }
            if ($element->getQuoteauthor()) {
                $quoteText = unserialize($element->getQuoteauthor()->getValue());
                $item["quoteAuthor"] = $quoteText["TEXT"];
            } else {
                $item["quoteAuthor"] = "";
            }
            $photos = [];
            if ($element->getPhotos()) {
                foreach ($element->getPhotos()->getAll() as $photo) {
                    $photos[] = \CFile::GetPath($photo->getValue());
                }
            }
            $item["photos"] = $photos;
            $item["linkedServices"] = [];
            if ($element->getServices()) {
                foreach ($element->getServices()->getAll() as $linkedService) {
                    $item["linkedServices"][] = [
                        "name" => $linkedService->getElement()->getName(),
                        "code" => $linkedService->getElement()->getCode()
                    ];
                }
            }
            $item["form"] = ["title" => "", "description" => ""];
            if ($element->getFormTitle()) {
                $item["form"]["title"] = $element->getFormTitle()->getValue();
            }
            if ($element->getFormDesc()) {
                $desc = unserialize($element->getFormDesc()->getValue());
                $item["form"]["description"] = $desc["TEXT"];
            }


            $iPropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($iBlockId, $item["id"]);
            $metaValues = $iPropValues->getValues();

            $item["metaTitle"] = $this->prepareMetaString(strval($metaValues["ELEMENT_META_TITLE"]));
            $item["metaKeywords"] = $this->prepareMetaString(strval($metaValues["ELEMENT_META_KEYWORDS"]));
            $item["metaDescription"] = $this->prepareMetaString(strval($metaValues["ELEMENT_META_DESCRIPTION"]));
        } else {
            $this->addError(new Error('Element could not be found by code.', 404));

            return null;
        }

        return $item;
    }

    public function listFactAction()
    {
        Loader::includeModule("iblock");
        $request = Context::getCurrent()->getRequest();
        $sectionFilter = $request->getQuery("section") ?? "all";
        $typeFilter = $request->getQuery("type") ?? "all";
        $exclude = $request->getQuery("exclude");
        $result = [];
        $items = [];
        $select = [
            "ID",
            "NAME",
            "TYPE.ITEM",
            "SECTION_" => "IBLOCK_SECTION",
            "PREVIEW_PICTURE",
            "ACTIVE_FROM",
            "CODE",
            "FACT_TITLE",
            "FACT_DESC",
            "PREVIEW_TEXT",
        ];
        $filter = [
            "=ACTIVE" => "Y"
        ];
        if ($sectionFilter !== "all") {
            $filter["IBLOCK_SECTION.CODE"] = $sectionFilter;
        }
        if ($typeFilter !== "all") {
            $filter["TYPE.ITEM.XML_ID"] = $typeFilter;
        }
        if ($exclude) {
            $filter["!CODE"] = $exclude;
        }
        $elements = \Bitrix\Iblock\Elements\ElementProjectsTable::getList([
            "select" => $select,
            "filter" => $filter,
            "order" => ["ACTIVE_FROM" => "DESC"],
            "count_total" => true,
            "cache" => [
                "ttl" => 3600,
                "cache_joins" => true,
            ],
        ]);
        while ($element = $elements->fetchObject()) {
            $item = [];
            $item["name"] = $element->getName();
            $item["preview"] = "";
            if ($element->getPreviewText()) {
                $item["preview"] = $element->getPreviewText();
            }
            $item["id"] = $element->getId();
            $item["slug"] = $element->getCode();
            if ($element->getPreviewPicture()) {
                $item["image"] = \CFile::GetPath($element->getPreviewPicture());
            } else {
                $item["image"] = "";
            }
            $item["type"] = [];
            if ($element->getType()) {
                $item["type"] = [
                    "name" => $element->getType()->getItem()->getValue(),
                    "code" => $element->getType()->getItem()->getXmlId()
                ];
            }
            $item["date"] = "";
            if ($element->getActiveFrom()) {
                $item["date"] = $element->getActiveFrom()->format("d.m.Y");
            }
            $item["section"] = [];
            if ($element->getIblockSection()) {
                $item["section"] = [
                    "name" => $element->getIblockSection()->getName(),
                    "code" => $element->getIblockSection()->getCode()
                ];
            }
            $item["facts"] = [];
            if ($element->getFactTitle() && $element->getFactDesc()) {
                $facts = $element->getFactTitle()->getAll();
                $descs = $element->getFactDesc()->getAll();
                foreach ($facts as $key => $value) {
                    if ($descs[$key]) {
                        $description = unserialize($descs[$key]->getValue())["TEXT"];
                        $factItem = [
                            "title" => $value->getValue(),
                            "description" => $description,
                        ];
                        $item["facts"][] = $factItem;
                    }
                }
            }

            $items[] = $item;
        }
        $result['items'] = $items;
        $result['count'] = $elements->getCount();

        return $result;
    }

    protected function prepareMetaString(string $value)
    {
        $value = html_entity_decode($value, ENT_QUOTES);
        return strip_tags($value);
    }
}