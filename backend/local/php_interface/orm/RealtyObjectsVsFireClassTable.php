<?php

use \Bitrix\Main\Entity\DataManager;
use \Bitrix\Main\Entity;

class RealtyObjectsVsFireClassTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
        return 'ml_realty_objects_vs_fire_security';
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
            'fire_security_id' => array(
                'column_name' => 'fire_security_id',
                'data_type' => 'integer',
                'required' => true,
                'primary' => true,
            ),
        );
    }
}
?>
