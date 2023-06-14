<?php
class BuildingImport
{
    const SEARCH_KEY_IMPORT = 'PROPERTY_ID';
    const IMPORT_KEY_DATABASE = 'PropertyID';
    const AREA_PROPERTY_TYPE = 'Areas';
    const PRIMARY_PROPERTY = 'id';
    const ACTIVE_PROPERTY = 'active';
    const TYPE_PROPERTY = 'PropertyType';
    const BUILDING_SUBTYPE = 'Orientation';
    const FLYER_PROPERTY = 'FlyerRus';
    const FLYER_PROPERTY_ENG = 'FlyerEng';
    const STATUS_PROPERTY = 'PropertyStatus';
    const BTS_PROPERTY = 'PropertyBTS';
    const CITY_PROPERTY = 'City';
    const LOCATION_PROPERTY = 'LocationZone';
    const DIRECTION_PROPERTY = 'Direction';
    const METRO_PROPERTY = 'MetroStation';
    const FLOOR_PROPERTY = 'FloorType';
    const SUBMARKET_PROPERTY = 'Submarket';
    const MARKET_PROPERTY = 'Market';
    const NAME_RUS_PROPERTY = 'PropertyNameRus';
    const TEMPERATURE_FROM_PROPERTY = 'TemperatureFrom';
    const TEMPERATURE_TO_PROPERTY = 'TemperatureTo';
    const SYS_LOCKED_PROPERTY = 'sys_locked';
    const CLASS_PROPERTY = 'BuildingClass';
    const TYPE_PROPERTY_CRM = 'PROPERTY_TYPE';
    const BUILDING_SUBTYPE_CRM = 'BUILDING_SUBTYPE';
    const FLYER_PROPERTY_CRM = 'FLYER';
    const STATUS_PROPERTY_CRM = 'PROPERTY_STATUS';
    const BTS_PROPERTY_CRM = 'PROPERTY_BTS';
    const CITY_PROPERTY_CRM = 'CITY';
    const LOCATION_PROPERTY_CRM = 'LOCATION_ZONE';
    const DIRECTION_PROPERTY_CRM = 'DIRECTION';
    const METRO_PROPERTY_CRM = 'METRO_STATION';
    const FLOOR_PROPERTY_CRM = 'FLOOR_TYPE';
    const SUBMARKET_PROPERTY_CRM = 'SUBMARKET';
    const IMAGES_PROPERTY_CRM = 'IMAGES';
    const TEMPERATURE_PROPERTY_CRM = 'TEMPERATURE';
    const FIRE_SECURITY_PROPERTY_CRM = 'FIRE_SECURITY';
    const PARKINGS_PROPERTY_CRM = 'PARKINGS';
    const USERS_PROPERTY_CRM = 'ASSIGNED_USER_ID';
    const ROOMS_PROPERTY_CRM = 'ROOMS';
    const CLASS_PROPERTY_CRM = 'BUILDING_CLASS';
    const SYS_LOCKED_NEED_VALUE = 0;
    const DELETED_BUILDINGS_ID_PROPERTY = 'id';

    protected $crmApi;
    protected $cityHelper;
    protected $directionHelper;
    protected $metroHelper;
    protected $floorHelper;
    protected $submarketHelper;
    protected $picturesHelper;
    protected $propertyHelper;
    protected $parkingImport;
    protected $userImport;
    protected $roomImport;
    protected $crmProperties;
    protected $referenceForBuildings = array(
        'PROPERTY_ID' => array(
            'name' => 'PropertyID',
        ),
        'KEY_PROJECT' => array(
            'name' => 'KeyProject',
            'default' => 0,
        ),
        'PROPERTY_NAME_RUS' => array(
            'name' => 'PropertyNameRus',
            'default' => '',
        ),
        'PROPERTY_NAME_ENG' => array(
            'name' => 'PropertyNameEng',
            'default' => '',
        ),
        'PROPERTY_LEASE' => array(
            'name' => 'PropertyLease',
            'default' => 0,
            'defaultAreas' => 0,
        ),
        'PROPERTY_SALE' => array(
            'name' => 'PropertySale',
            'default' => 0,
            'defaultAreas' => 0,
        ),
        'GROSS_BUILDING_AREA' => array(
            'name' => 'GrossBuildingArea',
            'default' => 0,
        ),
        'GROSS_LEASABLE_AREA' => array(
            'name' => 'GrossLeasableArea',
            'default' => 0,
        ),
        'PROPERTY_DESCRIPTION_RUS' => array(
            'name' => 'PropertyDescriptionRus',
            'default' => '',
        ),
        'PROPERTY_DESCRIPTION_ENG' => array(
            'name' => 'PropertyDescriptionEng',
            'default' => '',
        ),
        'DELIVERY_RUS' => array(
            'name' => 'DeliveryRus',
            'default' => '',
        ),
        'DELIVERY_ENG' => array(
            'name' => 'DeliveryEng',
            'default' => '',
        ),
        'COMMERCIAL_TERMS_RUS' => array(
            'name' => 'CommercialTermsRus',
            'default' => '',
        ),
        'COMMERCIAL_TERMS_ENG' => array(
            'name' => 'CommercialTermsEng',
            'default' => '',
        ),
        'X_COORDINATE' => array(
            'name' => 'Y_coordinate',
            'default' => 0,
        ),
        'Y_COORDINATE' => array(
            'name' => 'X_coordinate',
            'default' => 0,
        ),
        'PROPERTY_ADDRESS_RUS' => array(
            'name' => 'PropertyAddressRus',
            'default' => '',
        ),
        'PROPERTY_ADDRESS_ENG' => array(
            'name' => 'PropertyAddressEng',
            'default' => '',
        ),
        'DISTANCE_FROM_CITY' => array(
            'name' => 'DistanceFromCity',
            'default' => 0,
        ),
        'DISTANCE_FROM_METRO' => array(
            'name' => 'DistanceFromMetro',
            'default' => 0,
        ),
        'LOCATION_DESCRIPTION_RUS' => array(
            'name' => 'LocationDescriptionRus',
            'default' => '',
        ),
        'LOCATION_DESCRIPTION_ENG' => array(
            'name' => 'LocationDescriptionEng',
            'default' => '',
        ),
        'OPERATING_HEIGHT' => array(
            'name' => 'OperatingHeight',
            'default' => 0,
        ),
        'FLOOR_LOAD' => array(
            'name' => 'FloorLoad',
            'default' => 0,
        ),
        'RAILWAY' => array(
            'name' => 'Railway',
            'default' => 0,
        ),
        'PARKING_DESCRIPTION_RUS' => array(
            'name' => 'ParkingDescriptionRus',
            'default' => '',
        ),
        'PARKING_DESCRIPTION_ENG' => array(
            'name' => 'ParkingDescriptionEng',
            'default' => '',
        ),
        'COLUMN_GRID_X' => array(
            'name' => 'ColumnGridMin',
            'default' => 0,
            'type' => 'int',
        ),
        'COLUMN_GRID_Y' => array(
            'name' => 'ColumnGridMax',
            'default' => 0,
            'type' => 'int',
        ),
        'RESEARCH_CHECK_DATE' => array(
            'name' => 'ResearchCheckDate',
            'default' => '',
        ),
    );


    public function __construct()
    {
        $this->crmApi = new CrmClass();
        $this->propertyHelper = new BuildingPropertiesHelper($this->crmApi);
        $this->cityHelper = new CityHelper();
        $this->directionHelper = new DirectionHelper();
        $this->metroHelper = new MetroHelper();
        $this->floorHelper = new FloorHelper();
        $this->submarketHelper = new SubmarketHelper();
        $this->picturesHelper = new PicturesHelper($this->propertyHelper);
        $this->parkingImport = new ParkingImport($this->crmApi);
        //$this->userImport = new UserImport($this->crmApi, $this->propertyHelper); //#9171152 stop import users
        $this->roomImport = new RoomImport($this->crmApi);

        $this->crmProperties = array_merge(
            array_keys($this->referenceForBuildings),
            array(
                static::TYPE_PROPERTY_CRM,
                static::BUILDING_SUBTYPE_CRM,
                static::FLYER_PROPERTY_CRM,
                static::STATUS_PROPERTY_CRM,
                static::BTS_PROPERTY_CRM,
                static::CITY_PROPERTY_CRM,
                static::LOCATION_PROPERTY_CRM,
                static::DIRECTION_PROPERTY_CRM,
                static::METRO_PROPERTY_CRM,
                static::FLOOR_PROPERTY_CRM,
                static::SUBMARKET_PROPERTY_CRM,
                static::IMAGES_PROPERTY_CRM,
                static::TEMPERATURE_PROPERTY_CRM,
                static::FIRE_SECURITY_PROPERTY_CRM,
                static::PARKINGS_PROPERTY_CRM,
                static::USERS_PROPERTY_CRM,
                static::ROOMS_PROPERTY_CRM,
            )
        );
    }

    public function importBuildings($date)
    {
        if (empty($date) === true) {
            $date = date(
                "Y-m-d H:i:s",
                strtotime("01.01.2017")
            );
        }

        $buildingsData = $this->crmApi->getAllOffices($date);

        foreach ($buildingsData as $buildingData) {
            echo 'process building ' . $buildingData['PROPERTY_NAME_RUS'] . "\n";
            //echo var_dump($buildingData);
            $this->processBuildingData($buildingData);
        }

        $deletedBuildings = $this->crmApi->getAllOffices($date, true);

        foreach ($deletedBuildings as $deletedBuilding) {
            if (empty($deletedBuilding[static::DELETED_BUILDINGS_ID_PROPERTY]) === false) {
                $this->deactivateBuildingByCrmId($deletedBuilding[static::DELETED_BUILDINGS_ID_PROPERTY]);
            }
        }

        return true;
    }

    protected function deactivateBuildingByCrmId($crmId)
    {
        if (empty($crmId) === true) {
            return false;
        }

        $deletedBuildingData = $this->getBuildingByField(static::IMPORT_KEY_DATABASE, $crmId);
        
        if (
            $deletedBuildingData !== false 
            && $deletedBuildingData[static::SYS_LOCKED_PROPERTY] == static::SYS_LOCKED_NEED_VALUE
        ) {
            $this->deactivateBuilding($deletedBuildingData);
        }
    }

    protected function deactivateBuilding($buildingData)
    {
        if (empty($buildingData) === true) {
            return false;
        }

        $buildingData[static::ACTIVE_PROPERTY] = 0;

        try {
            $updateResult = RealtyObjectsClassTable::update($buildingData[static::PRIMARY_PROPERTY], $buildingData);

            if ($updateResult->isSuccess() === true) {
                return true;
            } else {
                MessageHelper::addMessageToLog('Building Import: error in deactivate object ' . $buildingData[static::PRIMARY_PROPERTY]);
                return false;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('Building Import: catch exception in deactivate object ' . $buildingData[static::PRIMARY_PROPERTY]);
            return false;
        }
    }

    protected function getBuildingByField($fieldName, $fieldValue)
    {
        if (
            empty($fieldName) === true
            || empty($fieldValue) === true
        ) {
            return false;
        }

        $buildingData = RealtyObjectsClassTable::getList(
            array(
                'filter' => array(
                    $fieldName => $fieldValue
                )
            )
        )->fetch();
        
        return $buildingData;
    }

    protected function normalizeBuildingData($buildingData)
    {
        if (empty($buildingData) === true) {
            return array();
        }

        foreach ($this->crmProperties as $crmProperty) {
            if (isset($buildingData[$crmProperty]) === false) {
                $buildingData[$crmProperty] = "";
            }
        }

        return $buildingData;
    }

    protected function processBuildingData($buildingData)
    {
        if (
            empty($buildingData) === true
            || empty($buildingData[static::SEARCH_KEY_IMPORT]) === true
        ) {
            return false;
        }

        $buildingData = $this->normalizeBuildingData($buildingData);
        $currentBuilding = $this->getBuildingByField(static::IMPORT_KEY_DATABASE, $buildingData[static::SEARCH_KEY_IMPORT]);

        if ($currentBuilding !== false) {
            $this->updateBuildingData($buildingData, $currentBuilding);
        } else {
            $this->addBuilding($buildingData);
        }

    }

    protected function updateBuildingData($newBuildingDataFromCrm, $currentBuildingData)
    {
        if (
            empty($newBuildingDataFromCrm) === true
            || empty($currentBuildingData) === true
        ) {
            return false;
        }

        if ($currentBuildingData[static::SYS_LOCKED_PROPERTY] == static::SYS_LOCKED_NEED_VALUE) {
            $newBuildingData = $this->getBuildingDataFromArray($newBuildingDataFromCrm, $currentBuildingData);

            if (
                empty($newBuildingData) === false
                && empty($newBuildingData[static::PRIMARY_PROPERTY]) === false
            ) {
                try {
                    $updateResult = RealtyObjectsClassTable::update($newBuildingData[static::PRIMARY_PROPERTY], $newBuildingData);
                    $updateResultSuccess = $updateResult->isSuccess();
                    if ($updateResultSuccess === false) {
                        MessageHelper::addMessageToLog('ImportBuilding: error in update element with messages ' . var_export($updateResult->getErrorMessages(), true));
                    }
                } catch (\Exception $exception) {
                    $updateResultSuccess = false;
                    MessageHelper::addMessageToLog('ImportBuilding: error in update element ' . var_export($newBuildingData, true));
                }

                if ($updateResultSuccess === true) {
                    $objectId = (int) $newBuildingData[static::PRIMARY_PROPERTY];
                    $this->fillBuildingAdditionalTables($objectId, $newBuildingDataFromCrm);
                }
            }
        }
    }

    protected function addBuilding($buildingDataFromCrm)
    {
        $buildingData = $this->getBuildingDataFromArray($buildingDataFromCrm);

        if (empty($buildingData) === false) {
            $buildingData['code'] = CUtil::translit($buildingData[static::NAME_RUS_PROPERTY], "ru");
            try {
                $addResult = RealtyObjectsClassTable::add($buildingData);
                $addResultSuccess = $addResult->isSuccess();
                if ($addResultSuccess === false) {
                    MessageHelper::addMessageToLog('ImportBuilding: error in add element with messages ' . var_export($addResult->getErrorMessages(), true));
                }
            } catch (\Exception $exception) {
                $addResultSuccess = false;
                MessageHelper::addMessageToLog('ImportBuilding: error in add element ' . var_export($buildingData, true));
            }

            if ($addResultSuccess === true) {
                $objectId = $addResult->getId();
                $this->fillBuildingAdditionalTables($objectId, $buildingDataFromCrm);
            }
        }
    }

    protected function fillBuildingAdditionalTables($objectId, $buildingDataFromCrm)
    {
        if (
            empty($objectId) === true
            || empty($buildingDataFromCrm) === true
        ) {
            return false;
        }

        //fill table for images
        $this->picturesHelper->fillBuildingImages($objectId, $buildingDataFromCrm[static::SEARCH_KEY_IMPORT], $buildingDataFromCrm[static::IMAGES_PROPERTY_CRM]); 

        //business units & fire security
        $this->propertyHelper->fillBusinessUnits($objectId, $buildingDataFromCrm);
        $this->propertyHelper->fillFireSecurity($objectId, $buildingDataFromCrm[static::FIRE_SECURITY_PROPERTY_CRM]);

        //parkings
        $parkingIds = (
            empty($buildingDataFromCrm[static::PARKINGS_PROPERTY_CRM]) === false
            ? explode(",", $buildingDataFromCrm[static::PARKINGS_PROPERTY_CRM])
            : array()
        );
        $this->parkingImport->importParkingsForObject($objectId, $parkingIds);

        //users
        $userIds = (
            empty($buildingDataFromCrm[static::USERS_PROPERTY_CRM]) === false
            ? explode(',', $buildingDataFromCrm[static::USERS_PROPERTY_CRM])
            : array()
        );
        //$this->userImport->importUsersForObject($objectId, $userIds);  //#9171152 stop import users

        $roomIds = (
            empty($buildingDataFromCrm[static::ROOMS_PROPERTY_CRM]) === false
            ? explode(",", $buildingDataFromCrm[static::ROOMS_PROPERTY_CRM])
            : array()
        );
        $this->roomImport->importRoomsForObject($objectId, $roomIds);
    }

    protected function getBuildingDataFromArray($buildingData, $currentBuildingData = array())
    {
        if (empty($buildingData) === true) {
            return array();
        }

        $newBuildingData = $currentBuildingData;
        $newBuildingData = $this->fillSimpleBuildingData($buildingData, $currentBuildingData);
        $newBuildingData[static::ACTIVE_PROPERTY] = 1;
        $newBuildingData = $this->fillComplicatedData($buildingData, $newBuildingData);

        return $newBuildingData;
    }

    protected function fillSimpleBuildingData($buildingData, $currentBuildingData)
    {
        if (empty($buildingData) === true) {
            return $currentBuildingData;
        }

        $newBuildingData = $currentBuildingData;
        foreach ($this->referenceForBuildings as $propertyCode => $propertySettings) {
            if (empty($propertySettings['name']) === false) {
                $propertyValue = false;

                if (
                    isset($buildingData[$propertyCode]) === true
                    && $buildingData[$propertyCode] !== ''
                ) {
                    $propertyValue = $buildingData[$propertyCode];
                } else if (
                    isset($propertySettings['defaultAreas']) === true
                    && $buildingData[static::TYPE_PROPERTY_CRM] == static::AREA_PROPERTY_TYPE
                ) {
                    $propertyValue = $propertySettings['defaultAreas'];
                } else if (isset($propertySettings['default']) === true) {
                    $propertyValue = $propertySettings['default'];
                }

                if ($propertyValue !== false) {
                    if (
                        empty($propertySettings['type']) === false
                        && $propertySettings['type'] == 'int'
                    ) {
                        $propertyValue = (int) $propertyValue;
                    }
                    
                    $newBuildingData[$propertySettings['name']] = $propertyValue;
                }
            }
        }

        return $newBuildingData;
    }

    protected function fillComplicatedData($buildingData, $currentBuildingData)
    {
        if (empty($buildingData) === true) {
            return $currentBuildingData;
        }

        $newBuildingData = $currentBuildingData;

        //type & status & class
        $newBuildingData[static::TYPE_PROPERTY] = $this->propertyHelper->getPropertyTypeId($buildingData[static::TYPE_PROPERTY_CRM]);
        $newBuildingData[static::BUILDING_SUBTYPE] = $this->propertyHelper->getPropertySubTypeId($buildingData[static::BUILDING_SUBTYPE_CRM]);
        $newBuildingData[static::STATUS_PROPERTY] = $this->propertyHelper->getPropertyStatusId($buildingData[static::STATUS_PROPERTY_CRM]);
        $newBuildingData[static::CLASS_PROPERTY] = $this->propertyHelper->getRightClass($buildingData[static::CLASS_PROPERTY_CRM]);
        //flyers
        $buildingFlyers = $this->propertyHelper->getBuildingFiles($buildingData[static::FLYER_PROPERTY_CRM], $buildingData[static::SEARCH_KEY_IMPORT], 'flyers'); 
        $newBuildingData[static::FLYER_PROPERTY] = implode("\n", $buildingFlyers);
        $newBuildingData[static::FLYER_PROPERTY_ENG] = "";
        //bts
        $newBuildingData[static::BTS_PROPERTY] = $this->propertyHelper->getPropertyBts($buildingData[static::BTS_PROPERTY_CRM]);
        //city & location & region & metro
        $newBuildingData[static::CITY_PROPERTY] = $this->cityHelper->getCityIdByName($buildingData[static::CITY_PROPERTY_CRM]);
        $newBuildingData[static::METRO_PROPERTY] = $this->metroHelper->getMetroIdByName($buildingData[static::METRO_PROPERTY_CRM], $newBuildingData[static::CITY_PROPERTY]);
        $newBuildingData[static::LOCATION_PROPERTY] = $this->propertyHelper->getPropertyLocationId($buildingData[static::LOCATION_PROPERTY_CRM]);
        $newBuildingData[static::DIRECTION_PROPERTY] = $this->directionHelper->getDirectionIdByName($buildingData[static::DIRECTION_PROPERTY_CRM]);
        //floor types
        $newBuildingData[static::FLOOR_PROPERTY] = $this->floorHelper->getFloorIdByName($buildingData[static::FLOOR_PROPERTY_CRM]);
        //submarket
        $newBuildingData[static::MARKET_PROPERTY] = $this->submarketHelper->getMarketIdForCity($buildingData[static::CITY_PROPERTY_CRM]);
        $newBuildingData[static::SUBMARKET_PROPERTY] = $this->submarketHelper->getMarketIdByName($buildingData[static::SUBMARKET_PROPERTY_CRM], $newBuildingData[static::MARKET_PROPERTY]);
        //temperature
        $temperatureLimits = $this->propertyHelper->getPropertiesTemperature($buildingData[static::TEMPERATURE_PROPERTY_CRM]);
        $newBuildingData[static::TEMPERATURE_FROM_PROPERTY] = $temperatureLimits['from'];
        $newBuildingData[static::TEMPERATURE_TO_PROPERTY] = $temperatureLimits['to'];
        
        return $newBuildingData;
    }
}
?>
