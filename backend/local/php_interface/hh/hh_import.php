<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../../..");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require('vendor/autoload.php');

use \Bitrix\Main\Loader;
use \Bitrix\Iblock\IblockTable;
use seregazhuk\HeadHunterApi\Api;
$token = "P9ROMILIQ4331UJKBEPU96CG83TQ4GEHR0S57C9M7BT50LSJL5S7ESUICPPJRIML";
$employerId = 12140;
Loader::includeModule("iblock");
$iBlocks = IblockTable::getList([
    "filter" => [
        "CODE" => "vacant",
        "IBLOCK_TYPE_ID" => "career"
    ]
]);
if ($iBlock = $iBlocks->fetch()) {
    $iBlockId = $iBlock['ID'];
}

$api = Api::create($token);
$params = [
    "employer_id" => $employerId,
    "per_page" => 100,
    "page" => 0
];
$vacancies = $api->vacancies->search($params);
$importIds = [];
$iBlockHHIds = [];
foreach ($vacancies["items"] as $key => $vacancy) {
    $importIds[$key] = $vacancy["id"];
}
$select = [
    'ID',
    "HH_ID" => "HHID.VALUE"
];
$filter = [
    '=ACTIVE' => 'Y',
    "!HH_ID" => false
];

$elements = \Bitrix\Iblock\Elements\ElementVacancyTable::getList([
    'select' => $select,
    'filter' => $filter,
]);
$obElement = new \CIBlockElement();
while ($element = $elements->fetch()) {
    if (!in_array((int)$element["HH_ID"], $importIds)) {
        $obElement::Delete($element["ID"]);
    }
    $iBlockHHIds[(int)$element["HH_ID"]] = $element["ID"];
}
foreach ($vacancies["items"] as $key => $vacancy) {
    $fields = [
        "ACTIVE" => "Y",
        "NAME" => $vacancy["name"],
        "IBLOCK_ID" => $iBlockId,
        "PROPERTY_VALUES" => [
            "HHID" => $vacancy["id"],
            "LINK" => $vacancy["alternate_url"],
            "CITY" => $vacancy["address"]["city"]
        ],
    ];
    if ($vacancy["address"]["metro"]) {
        $fields["PROPERTY_VALUES"]["METRO"] = $vacancy["address"]["metro"];
    }
    if ($vacancy["salary"]) {
        $fields["PROPERTY_VALUES"]["SALARY_FROM"] = $vacancy["salary"]["from"];
        $fields["PROPERTY_VALUES"]["SALARY_TO"] = $vacancy["salary"]["to"];
    }

    if (in_array($vacancy["id"], array_keys($iBlockHHIds))) {
        $obElement->Update($iBlockHHIds[$vacancy["id"]], $fields);
    } else {
        $obElement->Add($fields);
    }
}