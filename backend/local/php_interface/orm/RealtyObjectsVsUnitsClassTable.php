<?php

use \Bitrix\Main\Entity\DataManager;
use \Bitrix\Main\Entity;

class RealtyObjectsVsUnitsClassTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
        return 'ml_realty_objects_vs_business_units';
    }

    public static function getMap()
    {
        return array(
            'object_id' => array(
                'column_name' => 'object_id',
                'data_type' => 'integer',
                'required' => true,
                'primary' => true,
            ),
            'business_unit_id' => array(
                'column_name' => 'business_unit_id',
                'data_type' => 'integer',
                'required' => true,
                'primary' => true,
            ),
        );
    }
}
?>
