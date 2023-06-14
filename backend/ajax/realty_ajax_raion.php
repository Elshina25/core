<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
use \Bitrix\Main\ {
    Context,
    Loader
};
$request = Context::getCurrent()->getRequest();
$county = $request->getQuery('county');
$queryRaion = \RealtyRaionClassTable::getList(["select" => ['id', 'name_ru'], 'filter' => ['ita_county_id' => $county == '' ? false : $county]]);
$raions = $queryRaion->fetchAll();
echo json_encode($raions);