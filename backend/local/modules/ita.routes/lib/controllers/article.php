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

use \Bitrix\Main\Type\DateTime;

class Article extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'getArticles' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'getImmovables' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'getOffers' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'getDetail' => [
				'prefilters' => [],
				'postfilters' => []
			],
			'serviceSubjectBlock' => [
				'prefilters' => [],
				'postfilters' => []
			]
		];
	}

	public function getArticlesAction()
    {
		\Bitrix\Main\Loader::includeModule('iblock');
		$request = Context::getCurrent()->getRequest();
		$limit = $request->getQuery('limit') ?? 8;
		$offset = $request->getQuery('offset') ?? 0;
		$immovables = $request->getQuery('immovables');
		$offers = $request->getQuery('offers');
		$filter = [
			'=ACTIVE' => "Y"
		];
		if(!empty($immovables)) {
			$filter["IMMOVABLES.VALUE"] = $immovables;
		}
		if(!empty($offers)) {
			$filter["OFFERS.VALUE"] = $offers;
		}
        $articlesQuery = \Bitrix\Iblock\Elements\ElementBlogTable::getList([
            'select' => ['CODE', 'ID', 'NAME', 'PREVIEW_TEXT', 'IMMOVABLES', 'OFFERS', 'PREVIEW_PICTURE', 'ACTIVE_FROM', 'DATE_CREATE'],
            'filter' => $filter,
            'limit' => $limit,
            'offset' => $offset,
            'order' => [
                'ACTIVE_FROM' => 'DESC',
                'DATE_CREATE' => 'DESC',
            ],
            "cache" => [
                "ttl" => 86400,
                "cache_joins" => true,
            ],
            'count_total' => true
        ]);
        $articles = [];
        while($articlesQueryRes = $articlesQuery->fetchObject()) {
            $articles[] = $articlesQueryRes;
        }
        $result = [];
        foreach($articles as &$article) {
            $prearr = [];
            if($article->get("PREVIEW_PICTURE")){
                $prearr["picture"] = \CFile::GetPath($article->get("PREVIEW_PICTURE"));
            } else {
                $prearr["picture"] = '';
            }
            if($article->getImmovables()) {
                $prearr["immovablesValue"] = $article->getImmovables()->getValue();
            } else {
                $prearr["immovablesValue"] = null;
            }
            if($article->getOffers()){
                $prearr["offersValue"] = $article->getOffers()->getValue();
            } else {
                $prearr["offersValue"] = null;
            }
            $prearr["code"] = $article->get("CODE");
            $prearr["name"] = $article->get("NAME");
            $prearr["previewText"] = $article->get("PREVIEW_TEXT");
            if($article->get("ACTIVE_FROM")){
                $prearr["activeFrom"] = $article->get("ACTIVE_FROM")->format("d.m.Y");
            } else {
                $prearr["activeFrom"] = $article->get("DATE_CREATE")->format("d.m.Y");
            }
            $result[] = $prearr;
        }

        return ["items" => $result, "count" => $articlesQuery->getCount(), "limit" => $limit, "offset" => $offset];
    }
	public function getImmovablesAction() {
		\Bitrix\Main\Loader::includeModule('iblock');
		$iblockId = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" => 'blog']
		])->fetch()["ID"];
		$queryEnum = \CIBlockPropertyEnum::GetList(
 			["ID"=>"ASC"],
			["IBLOCK_ID" => $iblockId, "CODE" => "IMMOVABLES"]
		);
		$result = [];
		while($queryEnumArr = $queryEnum->Fetch()) {
			$result[] = ['name' => $queryEnumArr["VALUE"], 'code' => $queryEnumArr["ID"]];
		}
		return $result;
	}
	public function getOffersAction() {
		\Bitrix\Main\Loader::includeModule('iblock');
		$iblockId = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" => 'blog']
		])->fetch()["ID"];
		$queryEnum = \CIBlockPropertyEnum::GetList(
 			["ID"=>"ASC"],
			["IBLOCK_ID" => $iblockId, "CODE" => "OFFERS"]
		);
		$result = [];
		while($queryEnumArr = $queryEnum->Fetch()) {
			$result[] = ['name' => $queryEnumArr["VALUE"], 'code' => $queryEnumArr["ID"]];
		}
		return $result;
	}
	public function getDetailAction(string $code)
	{
		\Bitrix\Main\Loader::includeModule('iblock');
		$filter["=ACTIVE"] = 'Y';
		$filter["=CODE"] = $code;
		$article = \Bitrix\Iblock\Elements\ElementBlogTable::getList([
			'select' => ["CODE", 'ID', 'NAME', "DETAIL_TEXT",
			 'DETAIL_PICTURE', 'AUTHOR_TITLE' => 'AUTHOR.VALUE', 'AUTHOR_PHOTO_PATH' => 'AUTHOR_PHOTO.VALUE', 'MAIN_THOUGHT_TEXT' => 'MAIN_THOUGHT.VALUE', 'AUTHOR_POSITION_TEXT' => 'AUTHOR_POSITION.VALUE', 'AUTHOR_PHONE_TEXT' => 'AUTHOR_PHONE.VALUE', 'AUTHOR_EMAIL_TEXT' => 'AUTHOR_EMAIL.VALUE', 'AUTHOR_TELEGRAM_TEXT' => 'AUTHOR_TELEGRAM.VALUE', 'AUTHOR_VIBER_TEXT' => 'AUTHOR_VIBER.VALUE', 'AUTHOR_WHATS_APP_TEXT' => 'AUTHOR_WHATS_APP.VALUE', "NAVIGATION_TEXT" => "NAVIGATION.VALUE"],
			'filter' => $filter
		])->fetch();
		if(!$article) {
			$this->addError(new Error('Element could not be found by code.', 404));
			return [];
		}
		if($article["DETAIL_PICTURE"]) {
			$article["DETAIL_PICTURE"] = \CFile::GetPath($article["DETAIL_PICTURE"]);
		}
		if($article["AUTHOR_PHOTO_PATH"]) {
			$article["AUTHOR_PHOTO_PATH"] = \CFile::GetPath((int)$article["AUTHOR_PHOTO_PATH"]);
		}
		$arWorker = [];
		$arWorker["name"] = $article["AUTHOR_TITLE"];
		$arWorker["image"] = (string)$article["AUTHOR_PHOTO_PATH"];
		$arWorker["jobTitle"] = $article["AUTHOR_POSITION_TEXT"];
		$arWorker["phone"] = $article["AUTHOR_PHONE_TEXT"];
		$arWorker["email"] = $article["AUTHOR_EMAIL_TEXT"];
		$arWorker["telegram"] = $article["AUTHOR_TELEGRAM_TEXT"];
		$arWorker["viber"] = $article["AUTHOR_VIBER_TEXT"];
		$arWorker["whatsapp"] = $article["AUTHOR_WHATS_APP_TEXT"];
		unset($article["AUTHOR_TITLE"]);
		unset($article["AUTHOR_PHOTO_PATH"]);
		unset($article["AUTHOR_POSITION_TEXT"]);
		unset($article["AUTHOR_PHONE_TEXT"]);
		unset($article["AUTHOR_EMAIL_TEXT"]);
		unset($article["AUTHOR_TELEGRAM_TEXT"]);
		unset($article["AUTHOR_VIBER_TEXT"]);
		unset($article["AUTHOR_WHATS_APP_TEXT"]);
		$article["author"] = $arWorker;
		$article['mainThougtText'] = unserialize($article['MAIN_THOUGHT_TEXT']);
		$article['mainThougtText']["text"] = $article['mainThougtText']["TEXT"];
		$article['mainThougtText']["type"] = $article['mainThougtText']["TYPE"];
		unset($article['mainThougtText']["TEXT"]);
		unset($article['mainThougtText']["TYPE"]);
		unset($article['MAIN_THOUGHT_TEXT']);
		$article["navigationText"] = unserialize($article["NAVIGATION_TEXT"]);
		$article['navigationText']["text"] = $article['navigationText']["TEXT"];
		$article['navigationText']["type"] = $article['navigationText']["TYPE"];
		unset($article['navigationText']["TEXT"]);
		unset($article['navigationText']["TYPE"]);
		unset($article['NAVIGATION_TEXT']);
		$filterOther["=ACTIVE"] = 'Y';
		$filterOther["!CODE"] = $code;
		$articlesOtherQuery = \Bitrix\Iblock\Elements\ElementBlogTable::getList([
			'select' => ["CODE", 'ID', 'NAME', "PREVIEW_TEXT", "IMMOVABLES", "OFFERS", 'PREVIEW_PICTURE', 'ACTIVE_FROM'],
			'filter' => $filterOther
		]);
		$articlesOther = [];
		while($articlesOtherQueryRes = $articlesOtherQuery->fetchObject()) {
			$articlesOther[] = $articlesOtherQueryRes;
		}
		$otherResult = [];
		foreach($articlesOther as &$art) {
			$prearr = [];
			if($art->get("PREVIEW_PICTURE")){
				$prearr["picture"] = \CFile::GetPath($art->get("PREVIEW_PICTURE"));
			} else {
				$prearr["picture"] = '';
			}
			if($art->getImmovables()) {
				$prearr["immovablesValue"] = $art->getImmovables()->getValue();
			} else {
				$prearr["immovablesValue"] = null;
			}
			if($art->getOffers()){
				$prearr["offersValue"] = $art->getOffers()->getValue();
			} else {
				$prearr["offersValue"] = null;
			}
			$prearr["code"] = $art->get("CODE");
			$prearr["name"] = $art->get("NAME");
			$prearr["previewText"] = $art->get("PREVIEW_TEXT");
			if($art->get("ACTIVE_FROM")) {
				$prearr["activeFrom"] = (new DateTime($art->get("ACTIVE_FROM")))->format("d.m.Y");
			} else {
				$prearr["activeFrom"] = null;
			}
			$otherResult[] = $prearr;
		}
		$article["otherArticles"] = $otherResult;
		$iblockIdArticle = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" => 'blog']
		])->fetch()["ID"];
		$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($iblockIdArticle, $article['ID']);
        $metaValues = $ipropValues->getValues();
        $article["seo"]["sectionMetaKeywords"] = "";
        if ($metaValues["SECTION_META_KEYWORDS"]) {
            $article["seo"]["sectionMetaKeywords"] = $metaValues["SECTION_META_KEYWORDS"];
        }
        $article["seo"]["elementMetaKeywords"] = "";
        if ($metaValues["SECTION_META_KEYWORDS"]) {
            $article["seo"]["elementMetaKeywords"] = $metaValues["ELEMENT_META_KEYWORDS"];
        }
        $article["seo"]["elementMetaTitle"] = "";
        if ($metaValues["ELEMENT_META_TITLE"]) {
            $article["seo"]["elementMetaTitle"] = $metaValues["ELEMENT_META_TITLE"];
        }
        $article["seo"]["elementMetaDescription"] = "";
        if ($metaValues["ELEMENT_META_DESCRIPTION"]) {
            $article["seo"]["elementMetaDescription"] = $metaValues["ELEMENT_META_DESCRIPTION"];
        }
        $article["code"] = $article["CODE"];
		unset($article["CODE"]);
		$article["name"] = $article["NAME"]; 
		unset($article["NAME"]);
		$article["detailText"] = $article["DETAIL_TEXT"]; 
		unset($article["DETAIL_TEXT"]);
		$article["detailPicture"] = (string)$article["DETAIL_PICTURE"]; 
		unset($article["DETAIL_PICTURE"]);
		unset($article["ID"]);
		return $article;
	}

	public function serviceSubjectBlockAction()
	{
		$iblockIdService = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'services']
		])->fetch()["ID"];
		$rsSection = \Bitrix\Iblock\SectionTable::getList(
			[
				'filter' => ['IBLOCK_ID' => $iblockIdService],
				'select' => ['NAME', "ID", "CODE", "DESCRIPTION"],
                "cache" => [
                    "ttl" => 86400,
                ],
            ]
		)->fetchAll();
		foreach($rsSection as &$section) {
			$section["name"] = $section["NAME"];
			unset($section["NAME"]);
			$section["code"] = $section["CODE"];
			unset($section["CODE"]);
			$section["description"] = $section["DESCRIPTION"];
			unset($section["DESCRIPTION"]);
		}
		$result = $rsSection;
		return $result;
	}
}
