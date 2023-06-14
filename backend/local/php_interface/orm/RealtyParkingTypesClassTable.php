<?php

use \Bitrix\Main\Entity\DataManager;

class RealtyParkingTypesClassTable extends DataManager
{
	public static function getFilePath()
	{
		return __FILE__;
	}

	public static function getTableName()
	{
		return 'ml_realty_parkings_types';
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
	}
}
?>
