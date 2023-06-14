<?php

namespace Sprint\Migration;


class METRO_LINES_ADD20230202173209 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
        global $DB;
        $sql = 'ALTER TABLE ml_realty_metro
ADD metroLine INT;';
        $DB->Query($sql);
    }

    public function down()
    {
        global $DB;
        $sql ="
			ALTER TABLE ml_realty_metro
			DROP COLUMN metroLine;
		";
        $DB->Query($sql);
    }
}
