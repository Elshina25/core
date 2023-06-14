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

class Seo extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'getText' => [
				'prefilters' => [],
				'postfilters' => []
			],
		];
	}

	public function getTextAction()
	{
		$request = Context::getCurrent()->getRequest();
		$pageUrl = trim($request->getQuery('url')) ?? '';

		if (strlen($pageUrl) === 0) {
			$this->addError(new Error('Element could not be found, URL is empty.', 404));
			return null;
		}

		$parts = parse_url($pageUrl);
		$noLastSlash = rtrim($parts['path'], '/');
		$hasLastSlash = $noLastSlash . '/';

		$elements = \Bitrix\Iblock\Elements\ElementSeotextsTable::getList([
			'select' => ['ID', 'IBLOCK_ID', 'NAME', 'DETAIL_TEXT'],
			'order' => ['ID' => 'DESC'],
			'filter' => [
				'ACTIVE' => 'Y',
				['LOGIC' => 'OR', ['NAME' => $noLastSlash], ['NAME' => $hasLastSlash]]
			],
			'limit' => 1,
		]);

		if ($element = $elements->fetch()) {
			return [
				'url' => $element['NAME'],
				'text' => $element['DETAIL_TEXT'],
			];
		} else {
			$this->addError(new Error('Element could not be found by URL.', 404));
			return null;
		}
	}
}