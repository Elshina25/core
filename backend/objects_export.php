<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\{
    Loader,
    Entity,
    ORM\Query
};
Loader::includeModule("iblock");
Loader::includeModule("realty");
echo "<pre>";

$objects = [
    ["ID", "Название", "Адрес"]
];
$queryObjects = \RealtyObjectsClassTable::getList([
    "select" => [
        "id",
        "PropertyNameRus",
        "PropertyAddressRus",
    ],
    "filter" => [
        "METRO.ita_raion_id" => false,
        "City" => [6, 112],
        "active" => 1,
    ],
    "runtime" => [
        new Entity\ReferenceField(
            "METRO",
            \RealtyMetrosClassTable::class,
            Query\Join::on('this.MetroStation', 'ref.id'),
            [
                "join_type" => Query\Join::TYPE_LEFT,
            ]
        ),
    ],
]);
while ($object = $queryObjects->fetch()) {
    $objects[] = [
        $object["id"],
        $object["PropertyNameRus"],
        $object["PropertyAddressRus"]
    ];
}

$objects = mb_convert_encoding($objects, "windows-1251");
$fp = fopen('objects.csv', 'w');
foreach ($objects as $fields) {
    fputcsv($fp, $fields, ";");
}
fclose($fp);
