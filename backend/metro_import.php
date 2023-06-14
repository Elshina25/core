<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
Loader::includeModule("iblock");
Loader::includeModule("realty");
echo("<pre>");
$row = 1;
$districts = [];
$elements = RealtyRaionClassTable::getList([]);
while ($element = $elements->fetch()) {
    $districts[$element["id"]] = $element["name_ru"];
}
if (($handle = fopen("metro.csv", "r")) !== false) {
    while (($data = fgetcsv($handle, null, ";")) !== false) {
        $data = mb_convert_encoding($data, "UTF-8", "windows-1251");

        if ($data[0] && $data[3] && ($row !== 1)) {
            $districtId = array_search($data[3], $districts);
            if ($districtId) {
                RealtyMetrosClassTable::update($data[0], ["ita_raion_id" => $districtId]);
            } else {
                print_r(["Не найден район для записи", $data]);
            }
        }

        $row++;
    }
    fclose($handle);
}

echo("<pre>");