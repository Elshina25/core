<?php

namespace Sprint\Migration;


class ADD_technicalDescription_TO_OBJECTS_TABLE_20221216120545 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
		global $DB;
		$sql = 'ALTER TABLE ml_realty_objects
ADD technicalDescription TEXT;';
		$DB->Query($sql);
    }

    public function down()
    {
		 global $DB;
		$sql ="
			ALTER TABLE ml_realty_objects
			DROP COLUMN technicalDescription;
		";
		$DB->Query($sql);
    }
}
