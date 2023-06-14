<?php

use \Bitrix\Main\Entity\DataManager;

class RealtyLocationsClassTable extends DataManager
{
	public static function getFilePath()
	{
		return __FILE__;
	}

	public static function getTableName()
	{
		return 'ml_realty_location_zones';
	}

	public static function getMap()
	{
		return array(
			'id' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
			),
			'code' => array(
				'data_type' => 'string',
				'required' => true,
			),
			'city_id' => array(
				'data_type' => 'integer',
				'required' => false,
			),
			'NameRus' => array(
				'data_type' => 'string',
				'required' => true,
			),
			'NameEng' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'sys_locked' => array(
				'data_type' => 'integer',
				'required' => false,
			),
		);
	}}
?>
