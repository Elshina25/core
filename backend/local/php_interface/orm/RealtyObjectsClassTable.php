<?php

use \Bitrix\Main\Entity\DataManager;

class RealtyObjectsClassTable extends DataManager
{
    public static function getFilePath()
    {
        return __FILE__;
    }

    public static function getTableName()
    {
        return 'ml_realty_objects';
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
            'PropertyID' => array(
                'column_name' => 'PropertyID',
                'data_type' => 'string',
                'required' => true,
            ),
            'KeyProject' => array(
                'column_name' => 'KeyProject',
                'data_type' => 'integer',
                'required' => true,
            ),
            'PropertyStatus' => array(
                'column_name' => 'PropertyStatus',
                'data_type' => 'integer',
                'required' => false,
            ),
            'X_coordinate' => array(
                'column_name' => 'X_coordinate',
                'data_type' => 'float',
                'required' => false,
            ),
            'Y_coordinate' => array(
                'column_name' => 'Y_coordinate',
                'data_type' => 'float',
                'required' => false,
            ),
            'PropertyNameRus' => array(
                'column_name' => 'PropertyNameRus',
                'data_type' => 'text',
                'required' => false,
            ),
            'PropertyNameEng' => array(
                'column_name' => 'PropertyNameEng',
                'data_type' => 'text',
                'required' => false,
            ),
            'PropertyAddressEng' => array(
                'column_name' => 'PropertyAddressEng',
                'data_type' => 'text',
                'required' => false,
            ),
            'PropertyAddressRus' => array(
                'column_name' => 'PropertyAddressRus',
                'data_type' => 'text',
                'required' => false,
            ),
            'BuildingClass' => array(
                'column_name' => 'BuildingClass',
                'data_type' => 'string',
                'required' => false,
            ),
            'City' => array(
                'column_name' => 'City',
                'data_type' => 'integer',
                'required' => false,
                //connection
            ),
            'Market' => array(
                'column_name' => 'Market',
                'data_type' => 'integer',
                'required' => false,
            ),
            'Submarket' => array(
                'column_name' => 'Submarket',
                'data_type' => 'integer',
                'required' => false,
            ),
            'Direction' => array(
                'column_name' => 'Direction',
                'data_type' => 'integer',
                'required' => false,
                //connection
            ),
            'LocationZone' => array(
                'column_name' => 'LocationZone',
                'data_type' => 'integer',
                'required' => false,
                //connection
            ),
            'DistanceFromCity' => array(
                'column_name' => 'DistanceFromCity',
                'data_type' => 'float',
                'required' => false,
            ),
            'MetroStation' => array(
                'column_name' => 'MetroStation',
                'data_type' => 'integer',
                'required' => false,
                //connection
            ),
            'DistanceFromMetro' => array(
                'column_name' => 'DistanceFromMetro',
                'data_type' => 'float',
                'required' => false,
            ),
            'PropertyType' => array(
                'column_name' => 'PropertyType',
                'data_type' => 'integer',
                'required' => false,
                //connection ??
            ),
            'Orientation' => array(
                'column_name' => 'Orientation',
                'data_type' => 'integer',
                'required' => false,
                //connection
            ),
            'PropertyLease' => array(
                'column_name' => 'PropertyLease',
                'data_type' => 'integer',
                'required' => true,
            ),
            'PropertySale' => array(
                'column_name' => 'PropertySale',
                'data_type' => 'integer',
                'required' => true,
            ),
            'PropertyBTS' => array(
                'column_name' => 'PropertyBTS',
                'data_type' => 'integer',
                'required' => false,
            ),
            'GrossBuildingArea' => array(
                'column_name' => 'GrossBuildingArea',
                'data_type' => 'float',
                'required' => false,
            ),
            'GrossLeasableArea' => array(
                'column_name' => 'GrossLeasableArea',
                'data_type' => 'float',
                'required' => false,
            ),
            'PropertyDescriptionRus' => array(
                'column_name' => 'PropertyDescriptionRus',
                'data_type' => 'text',
                'required' => false,
            ),
            'PropertyDescriptionEng' => array(
                'column_name' => 'PropertyDescriptionEng',
                'data_type' => 'text',
                'required' => false,
            ),
            'LocationDescriptionRus' => array(
                'column_name' => 'LocationDescriptionRus',
                'data_type' => 'text',
                'required' => false,
            ),
            'LocationDescriptionEng' => array(
                'column_name' => 'LocationDescriptionEng',
                'data_type' => 'text',
                'required' => false,
            ),
            'ParkingDescriptionRus' => array(
                'column_name' => 'ParkingDescriptionRus',
                'data_type' => 'text',
                'required' => false,
            ),
            'ParkingDescriptionEng' => array(
                'column_name' => 'ParkingDescriptionEng',
                'data_type' => 'text',
                'required' => false,
            ),
            'DeliveryRus' => array(
                'column_name' => 'DeliveryRus',
                'data_type' => 'text',
                'required' => false,
            ),
            'DeliveryEng' => array(
                'column_name' => 'DeliveryEng',
                'data_type' => 'text',
                'required' => false,
            ),
            'CommercialTermsRus' => array(
                'column_name' => 'CommercialTermsRus',
                'data_type' => 'text',
                'required' => false,
            ),
            'CommercialTermsEng' => array(
                'column_name' => 'CommercialTermsEng',
                'data_type' => 'text',
                'required' => false,
            ),
            'OperatingHeight' => array(
                'column_name' => 'OperatingHeight',
                'data_type' => 'float',
                'required' => false,
            ),
            'FloorType' => array(
                'column_name' => 'FloorType',
                'data_type' => 'integer',
                'required' => false,
                //connection
            ),
            'TemperatureFrom' => array(
                'column_name' => 'TemperatureFrom',
                'data_type' => 'float',
                'required' => false,
            ),
            'TemperatureTo' => array(
                'column_name' => 'TemperatureTo',
                'data_type' => 'float',
                'required' => false,
            ),
            'Railway' => array(
                'column_name' => 'Railway',
                'data_type' => 'integer',
                'required' => false,
            ),
            'ColumnGridMin' => array(
                'column_name' => 'ColumnGridMin',
                'data_type' => 'integer',
                'required' => false,
            ),
            'ColumnGridMax' => array(
                'column_name' => 'ColumnGridMax',
                'data_type' => 'integer',
                'required' => false,
            ),
            'StandardDocs' => array(
                'column_name' => 'StandardDocs',
                'data_type' => 'integer',
                'required' => false,
            ),
            'ZeroLevelDocs' => array(
                'column_name' => 'ZeroLevelDocs',
                'data_type' => 'integer',
                'required' => false,
            ),
            'FloorLoad' => array(
                'column_name' => 'FloorLoad',
                'data_type' => 'float',
                'required' => false,
            ),
            'FiberOpticsComm' => array(
                'column_name' => 'FiberOpticsComm',
                'data_type' => 'integer',
                'required' => false,
            ),
            'BuildingDepthFrom' => array(
                'column_name' => 'BuildingDepthFrom',
                'data_type' => 'float',
                'required' => false,
            ),
            'BuildingDepthTo' => array(
                'column_name' => 'BuildingDepthTo',
                'data_type' => 'float',
                'required' => false,
            ),
            'Lighting' => array(
                'column_name' => 'Lighting',
                'data_type' => 'float',
                'required' => false,
            ),
            'BatteryChargingRoom' => array(
                'column_name' => 'BatteryChargingRoom',
                'data_type' => 'integer',
                'required' => false,
            ),
            'PowerSupply' => array(
                'column_name' => 'PowerSupply',
                'data_type' => 'integer',
                'required' => false,
            ),
            'FlyerRus' => array(
                'column_name' => 'FlyerRus',
                'data_type' => 'text',
                'required' => false,
            ),
            'FlyerEng' => array(
                'column_name' => 'FlyerEng',
                'data_type' => 'text',
                'required' => false,
            ),
            'min_UnitAvailabilityDate' => array(
                'column_name' => 'min_UnitAvailabilityDate',
                'data_type' => 'text',
                'required' => false,
            ),
            'min_lease_UnitDivisibleFrom' => array(
                'column_name' => 'min_lease_UnitDivisibleFrom',
                'data_type' => 'float',
                'required' => false,
            ),
            'min_sell_UnitDivisibleFrom' => array(
                'column_name' => 'min_sell_UnitDivisibleFrom',
                'data_type' => 'float',
                'required' => false,
            ),
            'sum_lease_UnitSize' => array(
                'column_name' => 'sum_lease_UnitSize',
                'data_type' => 'float',
                'required' => false,
            ),
            'sum_sell_UnitSize' => array(
                'column_name' => 'sum_sell_UnitSize',
                'data_type' => 'float',
                'required' => false,
            ),
            'sum_UnitSize' => array(
                'column_name' => 'sum_UnitSize',
                'data_type' => 'float',
                'required' => false,
            ),
            'min_UnitOfferedRent' => array(
                'column_name' => 'min_UnitOfferedRent',
                'data_type' => 'float',
                'required' => false,
            ),
            'max_UnitOfferedRent' => array(
                'column_name' => 'max_UnitOfferedRent',
                'data_type' => 'float',
                'required' => false,
            ),
            'min_UnitOfferedRentUSD' => array(
                'column_name' => 'min_UnitOfferedRentUSD',
                'data_type' => 'float',
                'required' => false,
            ),
            'max_UnitOfferedRentUSD' => array(
                'column_name' => 'max_UnitOfferedRentUSD',
                'data_type' => 'float',
                'required' => false,
            ),
            'min_UnitOfferedRentCurrency' => array(
                'column_name' => 'min_UnitOfferedRentCurrency',
                'data_type' => 'string',
                'required' => false,
            ),
            'max_UnitOfferedRentCurrency' => array(
                'column_name' => 'max_UnitOfferedRentCurrency',
                'data_type' => 'string',
                'required' => false,
            ),
            'min_UnitSalePrice' => array(
                'column_name' => 'min_UnitSalePrice',
                'data_type' => 'float',
                'required' => false,
            ),
            'max_UnitSalePrice' => array(
                'column_name' => 'max_UnitSalePrice',
                'data_type' => 'float',
                'required' => false,
            ),
            'min_UnitSalePriceUSD' => array(
                'column_name' => 'min_UnitSalePriceUSD',
                'data_type' => 'float',
                'required' => false,
            ),
            'max_UnitSalePriceUSD' => array(
                'column_name' => 'max_UnitSalePriceUSD',
                'data_type' => 'float',
                'required' => false,
            ),
            'min_UnitSalePriceCurrency' => array(
                'column_name' => 'min_UnitSalePriceCurrency',
                'data_type' => 'string',
                'required' => false,
            ),
            'max_UnitSalePriceCurrency' => array(
                'column_name' => 'max_UnitSalePriceCurrency',
                'data_type' => 'string',
                'required' => false,
            ),
            'min_rent_price' => array(
                'column_name' => 'min_rent_price',
                'data_type' => 'float',
                'required' => false,
            ),
            'max_rent_price' => array(
                'column_name' => 'max_rent_price',
                'data_type' => 'float',
                'required' => false,
            ),
            'min_sale_price' => array(
                'column_name' => 'min_sale_price',
                'data_type' => 'float',
                'required' => false,
            ),
            'max_sale_price' => array(
                'column_name' => 'max_sale_price',
                'data_type' => 'float',
                'required' => false,
            ),
            'sys_locked' => array(
                'column_name' => 'sys_locked',
                'data_type' => 'integer',
                'required' => false,
            ),
            'active' => array(
                'column_name' => 'active',
                'data_type' => 'integer',
                'required' => false,
            ),
            'code' => array(
                'column_name' => 'code',
                'data_type' => 'text',
                'required' => false,
            ),
            'seoTitle' => array(
                'column_name' => 'seoTitle',
                'data_type' => 'string',
                'required' => false,
            ),
            'seoH1' => array(
                'column_name' => 'seoH1',
                'data_type' => 'string',
                'required' => false,
            ),
            'seoKeywords' => array(
                'column_name' => 'seoKeywords',
                'data_type' => 'string',
                'required' => false,
            ),
            'seoDescription' => array(
                'column_name' => 'seoDescription',
                'data_type' => 'string',
                'required' => false,
            ),
            'seoTitleEn' => array(
                'column_name' => 'seoTitleEn',
                'data_type' => 'string',
                'required' => false,
            ),
            'seoH1En' => array(
                'column_name' => 'seoH1En',
                'data_type' => 'string',
                'required' => false,
            ),
            'seoKeywordsEn' => array(
                'column_name' => 'seoKeywordsEn',
                'data_type' => 'string',
                'required' => false,
            ),
            'seoDescriptionEn' => array(
                'column_name' => 'seoDescriptionEn',
                'data_type' => 'string',
                'required' => false,
            ),
            'sys_top_order' => array(
                'column_name' => 'sys_top_order',
                'data_type' => 'integer',
                'required' => false,
            ),
            'sys_top' => array(
                'column_name' => 'sys_top',
                'data_type' => 'integer',
                'required' => false,
            ),
            'seo_text_ru' => array(
                'column_name' => 'seo_text_ru',
                'data_type' => 'text',
                'required' => false,
            ),
            'seo_text_en' => array(
                'column_name' => 'seo_text_en',
                'data_type' => 'text',
                'required' => false,
            ),
            'top_include' => array(
                'column_name' => 'top_include',
                'data_type' => 'integer',
                'required' => false,
            ),
            'top_exclude' => array(
                'column_name' => 'top_exclude',
                'data_type' => 'integer',
                'required' => false,
            ),
            'top_priority' => array(
                'column_name' => 'top_priority',
                'data_type' => 'integer',
                'required' => false,
            ),
            'top_include_sale' => array(
                'column_name' => 'top_include_sale',
                'data_type' => 'integer',
                'required' => false,
            ),
            'top_exclude_sale' => array(
                'column_name' => 'top_exclude_sale',
                'data_type' => 'integer',
                'required' => false,
            ),
            'top_priority_sale' => array(
                'column_name' => 'top_priority_sale',
                'data_type' => 'integer',
                'required' => false,
            ),
            'RentableArea' => array(
                'column_name' => 'RentableArea',
                'data_type' => 'text',
                'required' => false,
            ),
            'ResearchCheckDate' => array(
                'column_name' => 'ResearchCheckDate',
                'data_type' => 'text',
                'required' => false,
            ),
            'ita_county_id' => array(
                'column_name' => 'ita_county_id',
                'data_type' => 'integer',
                'required' => false,
            ),
            'ita_raion_id' => array(
                'column_name' => 'ita_raion_id',
                'data_type' => 'integer',
                'required' => false,
            ),
            'technicalDescription' => array(
                'column_name' => 'technicalDescription',
                'data_type' => 'text',
                'required' => false,
            ),
        );
    }
}
?>
