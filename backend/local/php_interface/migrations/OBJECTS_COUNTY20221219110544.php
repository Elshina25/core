<?php
namespace Sprint\Migration;
use \Bitrix\Main\Loader;

class OBJECTS_COUNTY20221219110544 extends Version
{
    protected $description = "Заполнение округов для объектов";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
        Loader::includeModule("realty");
        $offset = 0;
        $limit = 100;

        $realty = \RealtyObjectsClassTable::getList([
            "filter" => [
                [
                    "LOGIC" => "AND",
                    [">=LocationZone" => 11,],
                    ["<=LocationZone" => 14,],
                ],
                "ita_county_id" => false
            ],
            "select" => [
                "id",
                "Direction",
                "LocationZone",
                "ita_county_id"
            ],
            "limit" => $limit,
            "count_total" => true
        ]);
        while ($object = $realty->fetch()) {
            foreach (\CRealtySeoPages::$districts as $dNum => $dParams) {
                if (in_array($object["LocationZone"], $dParams["filter"]["LocationZone"])
                    && (!isset($dParams["filter"]["Direction"])
                        || isset($dParams["filter"]["Direction"])
                        && $object["Direction"] == $dParams["filter"]["Direction"])
                ) {
                    $district = $dNum;
                }
            }
            
            \RealtyObjectsClassTable::update(
                $object["id"], 
                ["ita_county_id" => $dNum]
            );
        }
        if ($realty->getCount() > 0) {
            $this->restart();
        }
    }

    public function down()
    {
        //your code ...
    }
}
