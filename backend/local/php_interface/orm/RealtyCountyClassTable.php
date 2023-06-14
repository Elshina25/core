<?php

use \Bitrix\Main\Entity\DataManager;

class RealtyCountyClassTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
        return 'ita_county';
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
			'name_ru' => array(
                'column_name' => 'name_ru',
                'data_type' => 'string',
                'required' => false,
            ),
			'ml_realty_directions_id' => array(
                'column_name' => 'ml_realty_directions_id',
                'data_type' => 'integer',
                'required' => false,
            ),
			'ml_realty_cities_id' => array(
                'column_name' => 'ml_realty_cities_id',
                'data_type' => 'integer',
                'required' => false,
            ),
		);
    }
}
?>
