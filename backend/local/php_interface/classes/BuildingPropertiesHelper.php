<?php

use \Bitrix\Main\Loader;

class BuildingPropertiesHelper
{
    const DEFAULT_STATUS_PROPERTY_ID = 0;
    const DEFAULT_BTS_PROPERTY_VALUE = 0;
    const YES_BTS_PROPERTY_VALUE = 1;
    const DEFAULT_LOCATION_PROPERTY_ID = 0;
    const DEFAULT_TYPE_PROPERTY_ID = 0;
    const DEFAULT_SUBTYPE_PROPERTY_ID = 0;
    const OBJECT_FOR_INVESTMENT_UNIT_CODE = 'investment_sales';
    const REGIONAL_UNIT_CODE = 'regional';
    const FILES_PATH = '/property/';
    const TYPE_PROPERTY_CRM = 'PROPERTY_TYPE';
    const CODE_PROPERTY = 'code';
    const PRIMARY_KEY_PROPERTY = 'id';
    const NAME_RUS_PROPERTY = 'NameRus';
    const NAME_ENG_PROPERTY = 'NameEng';
    const SYS_LOCKED_PROPERTY = 'sys_locked';
    const CITY_ID_PROPERTY = 'city_id';
    const OBJECT_FOR_INVESTMENT_CRM = 'OBJECT_FOR_INVESTMENT';
    const ADDRESS_PROPERTY_CRM = "PROPERTY_ADDRESS_RUS";
    const OBJECT_FOR_INVESTMENT_VALUE = 1;
    const CITY_PROPERTY_CRM = 'CITY';
    const SYS_LOCKED_NEED_VALUE = 0;
    const CITY_ID_DEFAULT_VALUE = 0;
    const MOSKOW_REGION = "Московская область";

    protected $crmApi;
    protected $buildingEntity;
    protected $businessUnits;
    protected $unitsHelper;
    protected $fireSecuritiesHelper;

    protected $cityPropertyNotRegions = array(
        'Москва',
    );
    protected $defaultTemperatures = array(
        'from' => 0,
        'to' => 0,
    );
    protected $propertyTypesReference = array(
        'BuildingWarehouses' => 1,
        'BuildingRetails' => 2,
        'BuildingOffices' => 3,
        'Areas' => 4,
    );
    protected $propertySubTypesReference = array(
        'Отдельно стоящее' => 1,
        'Часть комплекса' => 2,
        'Особняк' => 3,
    );
    protected $businessUnitsReference = array(
        'industrial_logistics' => 'BuildingWarehouses',
        'retail' => 'BuildingRetails',
        'office' => 'BuildingOffices',
    );
    protected $statusesReference = array(
        'существующий' => 2,
        'проект' => 3,
    );
    protected $locationsReference = array(
        'Внутри СК' => 12,
        'За МКАД' => 11,
        'Между СК и ТТК' => 13,
        'Между ТТК и МКАД' => 14,
    );
    protected $fireSecuritiesReference = array(
        1 => 1,
        0 => 3,
    );
    protected $temperaturesReference = array(
        'Сухой склад' => array(
            'from' => '16',
            'to' => '25',
        ),
        'Мультитемпературный склад' => array(
            'from' => '-25',
            'to' => '-10',
        ),
        'Морозильные помещения' => array(
            'from' => '-25',
            'to' => '7',
        ),
    );
    protected $btsYesValues = array(
        "Да", "Возможно"
    );
    protected $classesReference = array(
        'В+' => 'B+',
        'В-' => 'B',
    );
    protected $translitParams = array(
        'change_case' => false,
        'replace_space' => ' ',
    );

    public function __construct($crmApi)
    {
        if (empty($crmApi) === false) {
            $this->crmApi = $crmApi;
        } else {
            $this->crmApi = new CrmClass();
        }
        $this->unitsHelper = new BusinessUnitsHelper();
        $this->fireSecuritiesHelper = new FireSecurityHelper();
        $this->businessUnits = array();
        
        if (Loader::includeModule('realty') === true) {
            $businessUnitsQuery = CRealtyBusinessUnits::GetList();

            while ($businessUnit = $businessUnitsQuery->Fetch()) {
                $this->businessUnits[] = $businessUnit;
            }
        }
    }

    public function getRightClass($buildingClass)
    {
        if (empty($buildingClass) === true) {
            return '';
        }

        $rightBuildingClass = (
            array_key_exists($buildingClass, $this->classesReference) === true
            ? $this->classesReference[$buildingClass]
            : $buildingClass
        );

        return $rightBuildingClass;
    }

    public function getPropertyTypeId($buildingPropertyType)
    {
        if (empty($buildingPropertyType) === true) {
            return static::DEFAULT_TYPE_PROPERTY_ID;
        }

        $propertyTypeId = static::DEFAULT_TYPE_PROPERTY_ID;
        if (array_key_exists($buildingPropertyType, $this->propertyTypesReference) === true) {
            $propertyTypeId = $this->propertyTypesReference[$buildingPropertyType];
        }

        return $propertyTypeId;
    }

    public function getPropertySubTypeId($buildingSubPropertyType)
    {
        if (empty($buildingSubPropertyType) === true) {
            return static::DEFAULT_SUBTYPE_PROPERTY_ID;
        }

        $propertySubTypeId = static::DEFAULT_SUBTYPE_PROPERTY_ID;
        if (array_key_exists($buildingSubPropertyType, $this->propertySubTypesReference) === true) {
            $propertySubTypeId = $this->propertySubTypesReference[$buildingSubPropertyType];
        }

        return $propertySubTypeId;
    }

    public function getPropertyStatusId($buildingPropertyStatus)
    {
        if (empty($buildingPropertyStatus) === true) {
            return static::DEFAULT_STATUS_PROPERTY_ID;
        }

        $buildingPropertyStatus = mb_strtolower($buildingPropertyStatus);
        $propertyStatusId = false;
            
        if (array_key_exists($buildingPropertyStatus, $this->statusesReference) === true) {
            $propertyStatusId = $this->statusesReference[$buildingPropertyStatus];
        } else {
            $currentStatus = RealtyStatusesClassTable::getList(
                array(
                    'filter' => array(
                        static::NAME_RUS_PROPERTY => $buildingPropertyStatus
                    ),
                )
            )->fetch();

            if ($currentStatus === false) {
                $propertyStatusId = static::DEFAULT_STATUS_PROPERTY_ID;
                $propertyStatusData = array(
                    static::NAME_RUS_PROPERTY => $buildingPropertyStatus,
                    static::NAME_ENG_PROPERTY => CUtil::translit($buildingPropertyStatus, "ru", $this->translitParams),
                    static::CODE_PROPERTY => CUtil::translit($buildingPropertyStatus, "ru"),
                    static::SYS_LOCKED_PROPERTY => static::SYS_LOCKED_NEED_VALUE,
                );

                try {
                    $addResult = RealtyStatusesClassTable::add($propertyStatusData);

                    if ($addResult->isSuccess() === true) {
                        $propertyStatusId = $addResult->getId();
                    } else {
                        MessageHelper::addMessageToLog('Property Helper: error in add object status ' . var_export($addResult->getErrorMessages(), true));
                    }
                } catch (\Exception $exception) {
                    MessageHelper::addMessageToLog('Property Helper: catch exception in add object status ' . $buildingPropertyStatus);
                }
            } else {
                $propertyStatusId = $currentStatus[static::PRIMARY_KEY_PROPERTY];
            }
        }

        return $propertyStatusId;
    }

    public function getPropertyBts($buildingPropertyBts)
    {
        $propertyBts = static::DEFAULT_BTS_PROPERTY_VALUE;

        if (
            empty($buildingPropertyBts) === false
            && in_array($buildingPropertyBts, $this->btsYesValues) === true
        ) {
            $propertyBts = static::YES_BTS_PROPERTY_VALUE;
        } 

        return $propertyBts;
    }

    public function getPropertyLocationId($buildingLocation)
    {
        if (empty($buildingLocation) === true) {
            return static::DEFAULT_LOCATION_PROPERTY_ID;
        }

        $propertyLocationId = static::DEFAULT_LOCATION_PROPERTY_ID;
        if (array_key_exists($buildingLocation, $this->locationsReference) === true) {
            $propertyLocationId = $this->locationsReference[$buildingLocation];
        } else {
            $currentLocation = RealtyLocationsClassTable::getList(
                array(
                    'filter' => array(
                        static::NAME_RUS_PROPERTY => $buildingLocation
                    ),
                )
            )->fetch();

            if ($currentLocation === false) {
                $locationData = array(
                    static::NAME_RUS_PROPERTY => $buildingLocation,
                    static::NAME_ENG_PROPERTY => CUtil::translit($buildingLocation, "ru", $this->translitParams),
                    static::CODE_PROPERTY => CUtil::translit($buildingLocation, "ru"),
                    static::SYS_LOCKED_PROPERTY => static::SYS_LOCKED_NEED_VALUE,
                    static::CITY_ID_PROPERTY => static::CITY_ID_DEFAULT_VALUE,
                );

                try {
                    $addResult = RealtyLocationsClassTable::add($locationData);

                    if ($addResult->isSuccess() === true) {
                        $propertyLocationId = $addResult->getId();
                    } else {
                        MessageHelper::addMessageToLog('Property Helper: error in add object location ' . var_export($addResult->getErrorMessages(), true));
                    }
                } catch (\Exception $exception) {
                    MessageHelper::addMessageToLog('Property Helper: catch exception in add object location ' . $buildingLocation);
                }
            } else {
                $propertyLocationId = $currentLocation[static::PRIMARY_KEY_PROPERTY];
            }
        }

        return $propertyLocationId;
    }

    public function getPropertiesTemperature($temperatureName)
    {
        if (empty($temperatureName) === true) {
            return $this->defaultTemperatures;
        }

        $temperatureLimits = $this->defaultTemperatures;

        if (array_key_exists($temperatureName, $this->temperaturesReference) === true) {
            $temperatureLimits = $this->temperaturesReference[$temperatureName];
        }

        return $temperatureLimits;
    }

    public function fillBusinessUnits($buildingId, $buildingDataFromCrm)
    {
        if (
            empty($buildingId) === true
            || empty($buildingDataFromCrm) === true
        ) {
            return false;
        }

        $businessUnits = 0;

        $isRegional = (
            strpos($buildingDataFromCrm[static::ADDRESS_PROPERTY_CRM], static::MOSKOW_REGION) === false
            && in_array($buildingDataFromCrm[static::CITY_PROPERTY_CRM], $this->cityPropertyNotRegions) === false
        );

        foreach ($this->businessUnits as $businessUnit) {
            $businessUnitCode = $businessUnit[static::CODE_PROPERTY];
            if (
                array_key_exists($businessUnitCode, $this->businessUnitsReference) === true
                && $this->businessUnitsReference[$businessUnitCode] == $buildingDataFromCrm[static::TYPE_PROPERTY_CRM]
                && $businessUnits == 0
            ) {
                $businessUnits = $businessUnit[static::PRIMARY_KEY_PROPERTY];
            } else if ($businessUnitCode == static::OBJECT_FOR_INVESTMENT_UNIT_CODE) {
                if ($buildingDataFromCrm[static::OBJECT_FOR_INVESTMENT_CRM] == static::OBJECT_FOR_INVESTMENT_VALUE) {
                    $businessUnits = $businessUnit[static::PRIMARY_KEY_PROPERTY];
                }
            } /*else if ($businessUnitCode == static::REGIONAL_UNIT_CODE) {
                if ($isRegional === true) {
                    $businessUnits = $businessUnit[static::PRIMARY_KEY_PROPERTY];
                }
            }*/ //exclude regional business unit
        }
    
        $businessUnits = (
            $businessUnits != 0
            ? array($businessUnits)
            : array()
        );

        $updateUnits = $this->unitsHelper->processBuildingUnits($buildingId, $businessUnits);
        return $updateUnits;
    }

    public function fillFireSecurity($buildingId, $buildingFireSecurityBool)
    {
        if (empty($buildingId) === true) {
            return false;
        }

        $fireSecurityId = 0;

        if (array_key_exists($buildingFireSecurityBool, $this->fireSecuritiesReference) === true) {
            $fireSecurityId = $this->fireSecuritiesReference[$buildingFireSecurityBool];
        }
    
        $this->fireSecuritiesHelper->processBuildingFireSecurity($buildingId, $fireSecurityId);
        return true;
    }

    public function getBuildingFiles($buildingDataFiles, $buildingDataName, $fileType = 'images')
    {
        $returnFileNames = array();
        if (empty($buildingDataFiles) === false) {
            $buildingFiles = explode(',', $buildingDataFiles);
            if ($fileType == 'images') {
                $files = $this->crmApi->getImagesByIds($buildingFiles);
            } else if ($fileType == 'flyers') {
                $files = $this->crmApi->getDocumentsByIds($buildingFiles);
            } else {
                $files = array();
            }

            foreach ($files as $fileCount => $file) {
                $fileName = static::FILES_PATH . $buildingDataName . '/' . $file['name'] . "." . $file['extension'];
                $isWriteFile = $this->saveFiles($fileName, $file['content']);

                if ($isWriteFile === true) {
                    if ($fileType == 'images') {
                        $watermark = CRealty::putWatermark($fileName);
                    }
                    $returnFileNames[] = $fileName;
                }
            }
        }

        return $returnFileNames;
    }

    public function saveFiles($fileName, $fileContent)
    {
        if (
            empty($fileName) === true
            || empty($fileContent) === true
        ) {
            return false;
        }

        $fileName = $_SERVER['DOCUMENT_ROOT'] . $fileName;

        if (file_exists($fileName) === true) {
            return true;
        }

        $isSaveFile = RewriteFile($fileName, $fileContent);
        return $isSaveFile;
    }
}
?>
