<?php

use \Bitrix\Main\Entity\DataManager;

class RealtyParkingsClassTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
        return 'ml_realty_parkings';
    }

    public static function getMap()
    {
        return array(
            'id' => array(
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
            ),
            'object_id' => array(
                'data_type' => 'integer',
                'required' => false,
            ),
            'crm_id' => array(
                'data_type' => 'string',
                'required' => false,
            ),
            'parking_type' => array(
                'data_type' => 'integer',
                'required' => true,
            ),
            'spaces_number' => array(
                'data_type' => 'integer',
                'required' => true,
            ),
            'order' => array(
                'data_type' => 'integer',
                'required' => false,
            ),
            'sys_locked' => array(
                'data_type' => 'integer',
                'required' => false,
            ),
        );
    }
}
?>
