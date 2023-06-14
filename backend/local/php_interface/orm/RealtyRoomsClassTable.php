<?php

use \Bitrix\Main\Entity\DataManager;

class RealtyRoomsClassTable extends DataManager
{
	public static function getFilePath()
	{
		return __FILE__;
	}

	public static function getTableName()
	{
		return 'ml_realty_units';
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
			'order' => array(
				'data_type' => 'integer',
				'required' => false,
			),
			'UnitID' => array(
				'data_type' => 'string',
				'required' => true,
			),
			'UnitType' => array(
				'data_type' => 'integer',
				'required' => true,
			),
			'UnitSize' => array(
				'data_type' => 'float',
				'required' => false,
			),
			'UnitDivisibleFrom' => array(
				'data_type' => 'float',
				'required' => false,
			),
			'UnitFloorNumber' => array(
				'data_type' => 'integer',
				'required' => false,
			),
			'UnitDeliveryCondition' => array(
				'data_type' => 'integer',
				'required' => false,
			),
			'UnitAvailabilityDateRus' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'UnitAvailabilityDateEng' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'UnitsLease' => array(
				'data_type' => 'integer',
				'required' => true,
			),
			'UnitSale' => array(
				'data_type' => 'integer',
				'required' => true,
			),
			'UnitOfferedRent' => array(
				'data_type' => 'float',
				'required' => false,
			),
			'UnitOfferedRentCurrency' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'UnitOfferedRentUSD' => array(
				'data_type' => 'float',
				'required' => false,
			),
			'UnitSalePrice' => array(
				'data_type' => 'float',
				'required' => false,
			),
			'UnitSalePriceCurrency' => array(
				'data_type' => 'string',
				'required' => false,
			),
			'UnitSalePriceUSD' => array(
				'data_type' => 'float',
				'required' => false,
			),
			'UnitBuilding' => array(
				'data_type' => 'string',
				'required' => false,
			),
		);
	}
}
?>
