<?php

use \Bitrix\Main\Entity\DataManager;

class RealtyPicturesClassTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
        return 'ml_realty_pictures';
    }

    public static function getMap()
    {
        return array(
            'id' => array(
                'column_name' => 'id',
                'data_type' => 'integer',
                'primary' => true,
                'autocomplete' => true,
            ),
            'object_id' => array(
                'column_name' => 'object_id',
                'data_type' => 'integer',
                'required' => true,
            ),
            'file' => array(
                'column_name' => 'file',
                'data_type' => 'string',
                'required' => true,
            ),
            'order' => array(
                'column_name' => 'order',
                'data_type' => 'integer',
                'required' => false,
            ),
            'main' => array(
                'column_name' => 'main',
                'data_type' => 'integer',
                'required' => true,
            ),
        );
    }
}
?>
