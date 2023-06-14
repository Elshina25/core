<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
Loader::includeModule("iblock");
Loader::includeModule("realty");
$lines = [];
$elements = \Bitrix\Iblock\Elements\ElementMetrolinesTable::getList([
    "select" => ["ID", "NAME"]
]);
while ($element = $elements->fetch()) {
    $lines[$element["ID"]] = $element["NAME"];
}

$metros = [
    ["ID", "Название", "Линия", "Район"]
];
$metroQuery = RealtyMetrosClassTable::getList([]);
while ($metro = $metroQuery->fetch()) {
    $metros[] = [
        $metro["id"],
        $metro["NameRus"],
        $metro["metroLine"] ? $lines[$metro["metroLine"]] : "",
        ""
    ];
}
$metros = mb_convert_encoding($metros, "windows-1251");
$fp = fopen('metro.csv', 'w');
foreach ($metros as $fields) {
    fputcsv($fp, $fields, ";");
}
fclose($fp);
