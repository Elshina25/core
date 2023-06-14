<?php

namespace Sprint\Migration;


class bu_vs_contacts20230502104726 extends Version
{
    protected $description = "Консультанты для объектов 05|23";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
        global $DB;
        $sql1 = "
			DELETE FROM `ml_realty_business_units_vs_contacts`;
			";
        $sql2 = "
            INSERT INTO `ml_realty_business_units_vs_contacts` (`business_unit_id`, `contact_id`, `order`) VALUES 
(4, 14, 0), (4, 210, 10), (4, 211, 20), (4, 219, 30), (4, 7778, 40), (4, 7781, 50), (4, 7835, 60), (4, 7840, 70), (1, 42, 0), (3, 110, 0);
        ";
        $DB->Query($sql1);
        $DB->Query($sql2);
    }

    public function down()
    {
        //your code ...
    }
}
