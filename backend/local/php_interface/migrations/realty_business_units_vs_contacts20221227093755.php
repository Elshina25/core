<?php

namespace Sprint\Migration;


class realty_business_units_vs_contacts20221227093755 extends Version
{
    protected $description = "Консультанты для объектов";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
        global $DB;
        $sql1 = "
			DELETE FROM `ml_realty_business_units_vs_contacts`;
			";
        $sql2 = "
            INSERT INTO `ml_realty_business_units_vs_contacts` (`business_unit_id`, `contact_id`, `order`) VALUES 
(4, 50, 0), (4, 72, 10), (4, 85, 20), (4, 7835, 30), (1, 42, 0), (3, 110, 0);
        ";
        $DB->Query($sql1);
        $DB->Query($sql2);
    }

    public function down()
    {
        //your code ...
    }
}
