<?php

namespace Sprint\Migration;


use Bitrix\Main\Loader;

class OBJECTS_REMOVE_COUNTY20230227120535 extends Version
{
    protected $description = "удаление округов для объектов";

    protected $moduleVersion = "4.1.1";

    public function up() {
        Loader::includeModule("realty");
        $limit = 100;

        $realty = \RealtyObjectsClassTable::getList([
            "filter" => [
                "!ita_county_id" => false
            ],
            "select" => [
                "id",
            ],
            "limit" => $limit,
            "count_total" => true
        ]);
        while ($object = $realty->fetch()) {
            \RealtyObjectsClassTable::update(
                $object["id"],
                ["ita_county_id" => null]
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
