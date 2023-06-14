<?php

use \Bitrix\Main\Entity\DataManager;

class RealtyDirectionsClassTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
        return 'ml_realty_directions';
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
            'code' => array(
                'column_name' => 'code',
                'data_type' => 'string',
                'required' => false,
            ),
            'NameRus' => array(
                'column_name' => 'NameRus',
                'data_type' => 'string',
                'required' => true,
            ),
            'NameEng' => array(
                'column_name' => 'NameEng',
                'data_type' => 'string',
                'required' => true,
            ),
            'sys_locked' => array(
                'column_name' => 'sys_locked',
                'data_type' => 'integer',
                'required' => false,
            ),
        );
    }
}
?>
