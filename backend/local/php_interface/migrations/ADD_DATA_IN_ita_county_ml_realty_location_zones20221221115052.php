<?php

namespace Sprint\Migration;


class ADD_DATA_IN_ita_county_ml_realty_location_zones20221221115052 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
		global $DB;
		$sql = '
			INSERT INTO ita_county_ml_realty_location_zones (ml_realty_location_zones_id, ita_county_id) VALUES
			(12, 1),(13,1),
			(14,2),(11,2),
			(14,3),(11,3),
			(14,4),(11,4),
			(14,5),(11,5),
			(14,6),(11,6),
			(14,7),(11,7),
			(14,8),(11,8),
			(14,9),(11,9);
		';
        $DB->Query($sql);
    }

    public function down()
    {
        //your code ...
    }
}
