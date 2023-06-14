<?php

use \Bitrix\Main\Loader;

class ParkingImport
{
    const PRIMARY_KEY_PROPERTY = 'id';
    const OBJECT_ID_PROPERTY = 'object_id';
    const IMPORT_PROPERTY = 'crm_id';
    const PARKING_TYPE_PROPERTY = 'parking_type';
    const SPACES_NUMBER_PROPERTY = 'spaces_number';
    const ORDER_PROPERTY = 'order';
    const SYS_LOCKED_PROPERTY = 'sys_locked';
    const NAME_RUS_PROPERTY = 'NameRus';
    const NAME_ENG_PROPERTY = 'NameEng';
    const IMPORT_PROPERTY_CRM = 'ID';
    const PARKING_TYPE_PROPERTY_CRM = 'PARKING_TYPE';
    const SPACES_NUMBER_PROPERTY_CRM = 'PARKING_SPACES_NUMBER';
    const SYS_LOCKED_NEED_VALUE = 0;
    const DEFAULT_PARKING_TYPE = 0;
    const CODE_PROPERTY = 'code';
    const ID_PROPERTY = 'id';
    const ORDER_STEP = 10;
    const DELETED_PARKINGS_ID_PROPERTY = 'id';

    protected $crmApi;
    protected $parkingTypesReference = array(
        'Наземная' => 17,
        'Подземная' => 18,
        'Многоуровневая' => 19,
    );
    protected $translitParams = array(
        'replace_space' => ' ',
        'change_case' => false,
    );

    public function __construct($crmApi)
    {
        if (empty($crmApi) === false) {
            $this->crmApi = $crmApi;
        } else {
            $this->crmApi = new CrmClass();
        }
    }

    public function importParkings($date)
    {
        if (empty($date) === true) {
            $date = date(
                "Y-m-d H:i:s",
                strtotime("01.01.2017")
            );
        }

        $parkingsData = $this->crmApi->getAllParkings($date);

        foreach ($parkingsData as $parkingData) {
            $this->processParkingData($parkingData);
        }

        $deletedParkings = $this->crmApi->getAllParkings($date, true);

        foreach ($deletedParkings as $deletedParking) {
            if (empty($deletedParking[static::DELETED_PARKINGS_ID_PROPERTY]) === false) {
                $this->deleteParkingByCrmId($deletedParking[static::DELETED_PARKINGS_ID_PROPERTY]);
            }
        }

        return true;
    }

    public function importParkingsForObject($objectId, $parkingIds)
    {
        if (empty($objectId) === true) {
            return false;
        }

        $parkingsData = $this->crmApi->getParkingsByIds($parkingIds);
        $newBuildingParkingsIds = array();

        $order = static::ORDER_STEP;
        foreach ($parkingsData as $parkingData) {
            $newBuildingParkingsIds[] = $parkingData[static::IMPORT_PROPERTY_CRM];
            $this->processParkingData($parkingData, $objectId, $order);
            $order += static::ORDER_STEP;
        }

        $currentBuildingParkings = $this->searchBuildingParkings($objectId);

        foreach ($currentBuildingParkings as $currentBuildingParking) {
            if (
                in_array($currentBuildingParking[static::IMPORT_PROPERTY], $newBuildingParkingsIds) === false
                && $currentBuildingParking[static::SYS_LOCKED_PROPERTY] == static::SYS_LOCKED_NEED_VALUE
            ) {
                $this->deleteParking($currentBuildingParking);
            }
        }

        return true;
    }

    protected function deleteParkingByCrmId($crmId)
    {
        if (empty($crmId) === true) {
            return false;
        }

        $deletedParking = $this->searchParkingByCrmId($crmId);

        if (
            $deletedParking !== false
            && $deletedParking[static::SYS_LOCKED_PROPERTY] == static::SYS_LOCKED_NEED_VALUE
        ) {
            return $this->deleteParking($deletedParking);
        }

        return true;
    }

    protected function deleteParking($deletedParking)
    {
        if (
            empty($deletedParking[static::PRIMARY_KEY_PROPERTY]) === true
        ) {
            return false;
        }

        try {
            $deleteResult = RealtyParkingsClassTable::delete($deletedParking[static::PRIMARY_KEY_PROPERTY]);

            if ($deleteResult->isSuccess() === false) {
                MessageHelper::addMessageToLog('Parking Import: error in delete parking ' . var_export($deleteResult->getErrorMessages(), true));
                return false;
            } else {
                return true;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('Parking Import: catch error in delete parking ' . $currentParkingData[static::PRIMARY_KEY_PROPERTY]);
            return false;
        }
    }

    protected function processParkingData($parkingData, $objectId = 0, $order = 0)
    {
        if (empty($parkingData) === true) {
            return false;
        }

        $currentParking = $this->searchParkingByCrmId($parkingData[static::IMPORT_PROPERTY_CRM]);
        $parkingData[static::OBJECT_ID_PROPERTY] = $objectId;

        if (
            $objectId == 0
            && empty($currentParking[static::OBJECT_ID_PROPERTY]) === false
        ) {
            $parkingData[static::OBJECT_ID_PROPERTY] = $currentParking[static::OBJECT_ID_PROPERTY];
        }

        if ($currentParking !== false) {
            $this->updateParkingData($parkingData, $currentParking, $order);
        } else {
            $this->addParkingData($parkingData, $order);
        }

        return true;
    }

    protected function updateParkingData($newParkingData, $currentParking, $order = 0)
    {
        if (
            empty($newParkingData) === true
            || empty($currentParking) === true
        ) {
            return false;
        }
        
        if ($currentParking[static::SYS_LOCKED_PROPERTY] == static::SYS_LOCKED_NEED_VALUE) {
            $currentParkingData = $this->getParkingDataFromArray($newParkingData, $order, $currentParking);

            if (empty($currentParkingData) === false) {
                try {
                    $updateResult = RealtyParkingsClassTable::update($currentParkingData[static::PRIMARY_KEY_PROPERTY], $currentParkingData);

                    if ($updateResult->isSuccess() === false) {
                        MessageHelper::addMessageToLog('Parking Import: error in update parking ' . var_export($updateResult->getErrorMessages(), true));
                        return false;
                    } else {
                        return $currentParkingData[static::PRIMARY_KEY_PROPERTY];
                    }
                } catch (\Exception $exception) {
                    MessageHelper::addMessageToLog('Parking Import: catch error in update parking ' . $currentParkingData[static::IMPORT_PROPERTY]);
                    return false;
                }
            }
        }
    }

    protected function addParkingData($parkingData, $order = 0)
    {
        if (empty($parkingData) === true) {
            return false;
        }

        $currentParkingData = $this->getParkingDataFromArray($parkingData, $order);

        if (empty($currentParkingData) === false) {
            try {
                $addResult = RealtyParkingsClassTable::add($currentParkingData);

                if ($addResult->isSuccess() === false) {
                    MessageHelper::addMessageToLog('Parking Import: error in add parking ' . var_export($addResult->getErrorMessages(), true));
                    return false;
                } else {
                    return $addResult->getId();
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageToLog('Parking Import: catch error in add parking ' . $currentParkingData[static::IMPORT_PROPERTY]);
                return false;
            }
        }
    }

    protected function getParkingDataFromArray($parkingData, $order = 0, $currentParking = array())
    {
        if (empty($parkingData) === true) {
            return array();
        }

        $currentParkingData = $currentParking;
        $currentParkingData[static::IMPORT_PROPERTY] = $parkingData[static::IMPORT_PROPERTY_CRM];
        $currentParkingData[static::OBJECT_ID_PROPERTY] = $parkingData[static::OBJECT_ID_PROPERTY];
        $currentParkingData[static::SPACES_NUMBER_PROPERTY] = $parkingData[static::SPACES_NUMBER_PROPERTY_CRM];
        $currentParkingData[static::PARKING_TYPE_PROPERTY] = $this->getParkingTypeIdByName($parkingData[static::PARKING_TYPE_PROPERTY_CRM]);
        if (
            isset($currentParkingData[static::ORDER_PROPERTY]) === false
            || $currentParkingData[static::ORDER_PROPERTY] == 0
        ) {
            $currentParkingData[static::ORDER_PROPERTY] = $order;
        }

        if (isset($currentParkingData[static::SYS_LOCKED_PROPERTY]) === false) {
            $currentParkingData[static::SYS_LOCKED_PROPERTY] = static::SYS_LOCKED_NEED_VALUE;
        }
        
        return $currentParkingData;
    }

    protected function getParkingTypeIdByName($parkingTypeString)
    {
        if (empty($parkingTypeString) === true) {
            return static::DEFAULT_PARKING_TYPE;
        }

        $parkingTypeId = static::DEFAULT_PARKING_TYPE;

        if (array_key_exists($parkingTypeString, $this->parkingTypesReference) === true) {
            $parkingTypeId = $this->parkingTypesReference[$parkingTypeString];
        } else {
            $currentParkingType = RealtyParkingTypesClassTable::getList(
                array(
                    'filter' => array(
                        static::NAME_RUS_PROPERTY => $parkingTypeString,
                    ),
                )
            )->fetch();

            if ($currentParkingType !== false) {
                $parkingTypeId = $currentParkingType[static::PRIMARY_KEY_PROPERTY];
            } else {
                $parkingTypeData = array(
                    static::NAME_RUS_PROPERTY => $parkingTypeString,
                    static::NAME_ENG_PROPERTY => CUtil::translit($parkingTypeString, "ru", $this->translitParams),
                    static::CODE_PROPERTY => CUtil::translit($parkingTypeString, "ru"),
                    static::SYS_LOCKED_PROPERTY => static::SYS_LOCKED_NEED_VALUE,
                );

                try {
                    $addResult = RealtyParkingTypesClassTable::add($parkingTypeData);

                    if ($addResult->isSuccess() === true) {
                        $parkingTypeId = $addResult->getId();
                    } else {
                        MessageHelper::addMessageToLog('Parking import: error in add parking type ' . var_export($addResult->getErrorMessages(), true));
                    }
                } catch(\Exception $exception) {
                    MessageHelper::addMessageToLog('Parking import: catch exception in add parking type ' . $parkingTypeString);
                }
            }
        }

        return $parkingTypeId;
    }

    protected function searchParkingByCrmId($crmId)
    {
        if (empty($crmId) === true) {
            return array();
        }

        $filterArray = array(
            static::IMPORT_PROPERTY => $crmId,
        );

        $searchParking = RealtyParkingsClassTable::getList(
            array(
                'filter' => $filterArray     
            )
        )->fetch();

        return $searchParking;
    }

    protected function searchBuildingParkings($objectId)
    {
        if (empty($objectId) === true) {
            return array();
        }

        $buildingParkingsQuery = RealtyParkingsClassTable::getList(
            array(
                'filter' => array(
                    static::OBJECT_ID_PROPERTY => $objectId,
                    static::SYS_LOCKED_PROPERTY => static::SYS_LOCKED_NEED_VALUE,
                ),
            )
        );

        $buildingParkings = array();
        while ($buildingParking = $buildingParkingsQuery->fetch()) {
            $buildingParkings[] = $buildingParking;
        }

        return $buildingParkings;
    }
}
?>
