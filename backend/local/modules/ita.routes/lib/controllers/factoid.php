<?php

namespace Ita\Routes\Controllers;

class Factoid extends \Bitrix\Main\Engine\Controller
{
	public function configureActions()
	{
		return [
			'getFactoids' => [
				'prefilters' => [],
				'postfilters' => []
			]
		];
	}

	public function getFactoidsAction()
    {
		$filter = ["ACTIVE" => 'Y'];
       	$factoidsQuery = \Bitrix\Iblock\Elements\ElementFactoidsTable::getList([
			'select' => ['NAME', "PREVIEW_TEXT", "TAG"],
			'filter' => $filter,
            "cache" => [
                "ttl" => 86400,
                "cache_joins" => true,
            ],
        ]);
		$result = [];
		while($factoidsQueryRes = $factoidsQuery->fetchObject()) {
			$prearr = [];
			$prearr["name"] = $factoidsQueryRes->get("NAME");
			$prearr["previewText"] = $factoidsQueryRes->get("PREVIEW_TEXT");
			if($factoidsQueryRes->getTag()){
				$prearr["tag"] = $factoidsQueryRes->getTag()->getValue();
			} else {
				$prearr["tag"] = '';
			}
			$result[] = $prearr;
		}
		return $result;
    }
}
