<?php

use \Bitrix\Main\Entity;
use \Bitrix\Main\Entity\DataManager;

class RealtyUsersClassTable extends DataManager
{
	public static function getFilePath()
	{
		return __FILE__;
	}

	public static function getTableName()
	{
		return 'ml_realty_contacts';
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
				'required' => true,
			),
			'crm_id' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'order' => array(
				'data_type' => 'integer',
				'required' => false,
			),
			'ContactBusinessUnit' => array(
				'data_type' => 'integer',
				'required' => true,
			),
			'NameRus' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'PostRus' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'PostEng' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'NameEng' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'ContactMobilePhone' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'ContactEmail' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'ContactFolder' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'sys_locked' => array(
				'data_type' => 'integer',
				'required' => false,
			),
			'active' => array(
				'data_type' => 'integer',
				'required' => false,
			),
		);
	}
}
?>
