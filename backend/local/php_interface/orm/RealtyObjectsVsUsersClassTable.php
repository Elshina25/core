<?php

use \Bitrix\Main\Entity;
use \Bitrix\Main\Entity\DataManager;

class RealtyObjectsVsUsersClassTable extends DataManager
{
	public static function getFilePath()
	{
		return __FILE__;
	}

	public static function getTableName()
	{
		return 'ml_realty_objects_vs_contacts';
	}

	public static function getMap()
	{
		return array(
			'object_id' => array(
				'data_type' => 'integer',
                'required' => true,
                'primary' => true,
			),
			'contact_id' => array(
				'data_type' => 'integer',
				'required' => true,
                'primary' => true,
			),
			'order' => array(
				'data_type' => 'integer',
				'required' => false,
			),
		);
	}}
?>
