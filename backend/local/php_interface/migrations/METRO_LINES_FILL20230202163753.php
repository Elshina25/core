<?php

namespace Sprint\Migration;
use \Bitrix\Main\Loader;
use \seregazhuk\HeadHunterApi\Api;
use \Bitrix\Iblock\IblockTable;

class METRO_LINES_FILL20230202163753 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
        require($_SERVER["DOCUMENT_ROOT"].'/local/php_interface/hh/vendor/autoload.php');
        $token = "JAUQKSHA3DU796HDB07J8R4IQL80CQ9UQ8OF3GKN8GFHPNRNIDKBL674146C501I";
        Loader::includeModule("iblock");
        $iBlocks = IblockTable::getList([
            "filter" => [
                "CODE" => "metro_lines",
                "IBLOCK_TYPE_ID" => "metro"
            ]
        ]);
        if ($iBlock = $iBlocks->fetch()) {
            $iBlockId = $iBlock['ID'];
        }
        $api = Api::create($token);
        $metro = $api->metro->forCity(1);
        $obElement = new \CIBlockElement();
        foreach ($metro["lines"] as $line) {
            $fields = [
                "NAME" => $line["name"],
                "ACTIVE" => "Y",
                "IBLOCK_ID" => $iBlockId,
                "PROPERTY_VALUES" => [
                    "COLOR" => $line["hex_color"]
                ],
            ];
            $elementId = $obElement->Add($fields);
            foreach ($line["stations"] as $station) {
                $metroQuery = \RealtyMetrosClassTable::getList(["filter" => ["NameRus" => $station["name"]]]);
                while ($element = $metroQuery->fetch()) {
                    $fields = ["metroLine" => $elementId];
                    \RealtyMetrosClassTable::update($element["id"], $fields);
                }
            }
        }
    }

    public function down()
    {
    }
}
