<?php
class RoomImport
{
    const PRIMARY_KEY_PROPERTY = 'id';
    const IMPORT_PROPERTY = 'UnitID';
    const OBJECT_ID_PROPERTY = 'object_id';
    const ORDER_PROPERTY = 'order';
    const RENT_CURRENCY_PROPERTY = 'UnitOfferedRentCurrency';
    const SALE_CURRENCY_PROPERTY = 'UnitSalePriceCurrency';
    const ROOM_TYPE_PROPERTY = 'UnitType';
    const NAME_RUS_PROPERTY = 'NameRus';
    const NAME_ENG_PROPERTY = 'NameEng';
    const CODE_PROPERTY = 'code';
    const SYS_LOCKED_PROPERTY = 'sys_locked';
    const AVAIL_NAME_ENG_PROPERTY = 'UnitAvailabilityDateEng';
    const DELIVERY_CONDITION_PROPERTY = 'UnitDeliveryCondition';
    const IMPORT_PROPERTY_CRM = 'ID';
    const RENT_CURRENCY_PROPERTY_CRM = 'UNIT_OFFERED_RENT_CURRENCY';
    const SALE_CURRENCY_PROPERTY_CRM = 'UNIT_SALE_PRICE_CURRENCY';
    const ROOM_TYPE_PROPERTY_CRM = 'UNIT_TYPE';
    const DELIVERY_CONDITION_PROPERTY_CRM = 'UNIT_DELIVERY_CONDITION';
    const AVAIL_NAME_RUS_PROPERTY_CRM = 'UNIT_AVAILABILITY_DATE';
    const ORDER_STEP = 10;
    const ROOM_TYPE_DEFAULT_ID = 0;
    const DELIVERY_CONDITION_DEFAULT_ID = 0;
    const SYS_LOCKED_DEFAULT_VALUE = 0;
    const DELETED_ID_PROPERTY = 'id';

    protected $crmApi;
    protected $referenceForRooms = array(
        'ID' => array(
            'name' => 'UnitID',
        ),
        'UNIT_SIZE' => array(
            'name' => 'UnitSize',
            'default' => 0,
            'type' => 'float',
        ),
        'UNIT_DIVISIBLE_FROM' => array(
            'name' => 'UnitDivisibleFrom',
            'default' => 0,
            'type' => 'float',
        ),
        'UNIT_FLOOR_NUMBER' => array(
            'name' => 'UnitFloorNumber',
            'default' => 0,
        ),
        'UNIT_AVAILABILITY_DATE' => array(
            'name' => 'UnitAvailabilityDateRus',
            'default' => '',
        ),
        'UNIT_LEASE' => array(
            'name' => 'UnitsLease',
            'default' => 0,
        ),
        'UNIT_SALE' => array(
            'name' => 'UnitSale',
            'default' => 0,
        ),
        'UNIT_OFFERED_RENT' => array(
            'name' => 'UnitOfferedRent',
            'default' => -1,
            'type' => 'float',
        ),
        'UNIT_OFFERED_RENT_USD' => array(
            'name' => 'UnitOfferedRentUSD',
            'default' => -2,
        ),
        'UNIT_SALE_PRICE' => array(
            'name' => 'UnitSalePrice',
            'default' => -1,
            'type' => 'float',
        ),
        'UNIT_SALE_PRICE_USD' => array(
            'name' => 'UnitSalePriceUSD',
            'default' => -2,
        ),
        'UNIT_BUILDING' => array(
            'name' => 'UnitBuilding',
            'default' => '',
        ),
    );
    protected $currencyReference = array(
        'RUR' => 'RUB',
    );
    protected $translitParams = array(
        'replace_space' => ' ',
        'safe_chars' => "/.",
    );
    protected $engNameReference = array(
        'сейчас' => 'Now',
        'по запросу' => 'on request',
    );

    public function __construct()
    {
        if (empty($crmApi) === false) {
            $this->crmApi = $crmApi;
        } else {
            $this->crmApi = new CrmClass();
        }
    }

    public function importRooms($date)
    {
        if (empty($date) === true) {
            $date = date(
                "Y-m-d H:i:s",
                strtotime("01.01.2017")
            );
        }

        $roomsData = $this->crmApi->getAllRooms($date);

        foreach ($roomsData as $roomData) {
            $this->processRoomData($roomData);
        }

        $deletedRooms = $this->crmApi->getAllRooms($deleted, true);

        foreach ($deletedRooms as $deletedRoom) {
            if (empty($deletedRoom[static::DELETED_ID_PROPERTY]) === false) {
                $this->deleteRoomByCrmId($deletedRoom[static::DELETED_ID_PROPERTY]);
            }
        }

        return true;
    }

    public function importRoomsForObject($objectId, $roomsIds)
    {
        if (empty($objectId) === true) {
            return false;
        }

        $roomsData = $this->crmApi->getRoomsByIds($roomsIds);
        $newBuildingRoomsIds = array();

        $order = ORDER_STEP;

        foreach ($roomsData as $roomData) {
            $this->processRoomData($roomData, $objectId, $order);
            $newBuildingRoomsIds[] = $roomData[static::IMPORT_PROPERTY_CRM];
            $order += ORDER_STEP;
        }
        $currentBuildingRooms = $this->searchBuildingRooms($objectId);

        foreach ($currentBuildingRooms as $currentBuildingRoom) {
            if (in_array($currentBuildingRoom[static::IMPORT_PROPERTY], $newBuildingRoomsIds) === false) {
                $this->deleteRoom($currentBuildingRoom);
            }
        }

        return true;
    }

    protected function deleteRoomByCrmId($crmId)
    {
        if (empty($crmId) === true) {
            return false;
        }

        $deletedRoom = $this->searchRoomByCrmId($crmId);

        if ($deletedRoom !== false) {
            return $this->deleteRoom($deletedRoom);
        }

        return true;
    }

    protected function deleteRoom($roomData)
    {
        if (empty($roomData) === true) {
            return false;
        }

        if (empty($roomData) === false) {
            try {
                $deleteResult = RealtyRoomsClassTable::delete($roomData[static::PRIMARY_KEY_PROPERTY]);

                if ($deleteResult->isSuccess() === true) {
                    return true;
                } else {
                    MessageHelper::addMessageToLog('Rooms Import: error in delete room ' . var_export($addResult->getErrorMessages(), true));
                    return false;
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageToLog('Rooms Import: catch exception in delete room ' . $roomData[static::IMPORT_PROPERTY]);
                return false;
            }
        }
    }

    protected function processRoomData($roomData, $objectId = 0, $order = 0)
    {
        if (
            empty($roomData) === true
        ) {
            return false;
        }

        $currentRoomData = $this->searchRoomByCrmId($roomData[static::IMPORT_PROPERTY_CRM]);
        $roomData[static::OBJECT_ID_PROPERTY] = $objectId;

        if (
            $objectId == 0
            && empty($currentRoomData[static::OBJECT_ID_PROPERTY]) === false
        ) {
            $roomData[static::OBJECT_ID_PROPERTY] = $currentRoomData[static::OBJECT_ID_PROPERTY];
        }

        if ($currentRoomData !== false) {
            $this->updateRoomData($roomData, $currentRoomData, $order);
        } else {
            $this->addRoomData($roomData, $order);
        }
    }

    protected function updateRoomData($newRoomData, $currentRoomData, $order = 0)
    {
        if (empty($newRoomData) === true) {
            return false;
        }

        $currentRoomData = $this->getRoomDataFromArray($newRoomData, $order, $currentRoomData);
        if (
            empty($currentRoomData) === false
            && empty($currentRoomData[static::PRIMARY_KEY_PROPERTY]) === false
        ) {
            try {
                $updateResult = RealtyRoomsClassTable::update($currentRoomData[static::PRIMARY_KEY_PROPERTY], $currentRoomData);

                if ($updateResult->isSuccess() === true) {
                    return true;
                } else {
                    MessageHelper::addMessageToLog('Rooms Import: error in update room ' . var_export($addResult->getErrorMessages(), true));
                    return false;
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageToLog('Rooms Import: catch exception in update room ' . $currentRoomData[static::IMPORT_PROPERTY]);
                return false;
            }
        }
    }

    protected function addRoomData($roomData, $order = 0)
    {
        if (empty($roomData) === true) {
            return false;
        }

        $currentRoomData = $this->getRoomDataFromArray($roomData, $order);
        if (empty($currentRoomData) === false) {
            try {
                $addResult = RealtyRoomsClassTable::add($currentRoomData);

                if ($addResult->isSuccess() === true) {
                    return true;
                } else {
                    MessageHelper::addMessageToLog('Rooms Import: error in add room ' . var_export($addResult->getErrorMessages(), true));
                    return false;
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageToLog('Rooms Import: catch exception in add room ' . $currentRoomData[static::IMPORT_PROPERTY]);
                return false;
            }
        }
    }

    protected function getRoomDataFromArray($roomData, $order = 0, $currentRoomData = array())
    {
        if (empty($roomData) === true) {
            return false;
        }

        $newRoomData = $this->getSimpleRoomData($roomData, $currentRoomData);
        $newRoomData = $this->getComplicatedRoomData($roomData, $newRoomData, $order);

        return $newRoomData;
    }

    protected function getComplicatedRoomData($roomData, $currentRoomData, $order = 0)
    {
        if (empty($roomData) === true) {
            return $currentRoomData;
        }

        $newRoomData = $currentRoomData;
        $newRoomData[static::OBJECT_ID_PROPERTY] = $roomData[static::OBJECT_ID_PROPERTY];
        $newRoomData[static::RENT_CURRENCY_PROPERTY] = $this->getCurrencyString($roomData[static::RENT_CURRENCY_PROPERTY_CRM]);
        $newRoomData[static::SALE_CURRENCY_PROPERTY] = $this->getCurrencyString($roomData[static::SALE_CURRENCY_PROPERTY_CRM]);
        $newRoomData[static::ROOM_TYPE_PROPERTY] = $this->getRoomTypeIdByName($roomData[static::ROOM_TYPE_PROPERTY_CRM]);
        $newRoomData[static::DELIVERY_CONDITION_PROPERTY] = $this->getDeliveryConditionIdByName($roomData[static::DELIVERY_CONDITION_PROPERTY_CRM]);
        $newRoomData[static::AVAIL_NAME_ENG_PROPERTY] = $this->getEngAvailNameByRussian($roomData[static::AVAIL_NAME_RUS_PROPERTY_CRM]);
        if (
            isset($newRoomData[static::ORDER_PROPERTY]) === false
            || $newRoomData[static::ORDER_PROPERTY] == 0
        ) {
            $newRoomData[static::ORDER_PROPERTY] = $order;
        }

        return $newRoomData;
    }

    protected function getSimpleRoomData($roomData, $currentRoomData)
    {
        if (empty($roomData) === true) {
            return $currentRoomData;
        }

        $newRoomData = $currentRoomData;

        foreach ($this->referenceForRooms as $roomPropertyCrm => $propertySettings) {
            if (empty($propertySettings['name']) === false) {
                if (empty($roomData[$roomPropertyCrm]) === false) {
                    $propertyValue = $roomData[$roomPropertyCrm];
                    if (
                        empty($propertySettings['type']) === false
                        && $propertySettings['type'] == 'float'
                    ) {
                        $propertyValue = (float) $propertyValue;
                    }
                    $newRoomData[$propertySettings['name']] = $propertyValue;
                } else if (isset($propertySettings['default']) === true) {
                    $newRoomData[$propertySettings['name']] = $propertySettings['default'];
                }
            }
        }

        return $newRoomData;
    }

    protected function getEngAvailNameByRussian($russianName) 
    {
        if (empty($russianName) === true) {
            return '';
        }

        $russianName = mb_strtolower($russianName);
        $engName = CUtil::translit($russianName, "ru", $this->translitParams);

        if (array_key_exists($russianName, $this->engNameReference) === true) {
            $engName = $this->engNameReference[$russianName];
        }

        if (preg_match("#^(\d{2})\/(\d{2})\/(\d{4})$#iUU", $russianName, $dateMatches) == 1) {
            $engName = $dateMatches[2] . '.' . $dateMatches[1] . '.' . $dateMatches[3];
        } else if (preg_match("#^(\d)\s+(кв\.|квартал)\s+(\d{4})#iuU", $russianName, $dateMatches) == 1) {
            $engName = 'Q' . $dateMatches[1] . ' ' . $dateMatches[3];
        }

        return $engName;
    }

    protected function getDeliveryConditionIdByName($deliveryConditionName)
    {
        if (empty($deliveryConditionName) === true) {
            return static::DELIVERY_CONDITION_DEFAULT_ID;
        }

        $deliveryConditionName = mb_strtolower($deliveryConditionName);

        $deliveryConditionData = RealtyRoomsConditionsClassTable::getList(
            array(
                'filter' => array(
                    static::NAME_RUS_PROPERTY => $deliveryConditionName,
                ),
            )
        )->fetch();

        if ($deliveryConditionData !== false) {
            return $deliveryConditionData[static::PRIMARY_KEY_PROPERTY];
        } else {
            $roomDeliveryConditionData = array(
                static::NAME_RUS_PROPERTY => $deliveryConditionName,
                static::NAME_ENG_PROPERTY => CUtil::translit($deliveryConditionName, "ru", $this->translitParams),
                static::CODE_PROPERTY => CUtil::translit($deliveryConditionName, "ru"),
                static::SYS_LOCKED_PROPERTY => static::SYS_LOCKED_DEFAULT_VALUE,
            );
            return $this->addRoomDeliveryCondition($roomDeliveryConditionData);
        }
    }

    protected function addRoomDeliveryCondition($roomDeliveryConditionData)
    {
        if (empty($roomDeliveryConditionData) === true) {
            return static::DELIVERY_CONDITION_DEFAULT_ID;
        }

        try {
            $addResult = RealtyRoomsConditionsClassTable::add($roomDeliveryConditionData);

            if ($addResult->isSuccess() === true) {
                return $addResult->getId();
            } else {
                MessageHelper::addMessageToLog('Rooms Import: error in add room delivery condition ' . var_export($addResult->getErrorMessages(), true));
                return static::DELIVERY_CONDITION_DEFAULT_ID;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('Rooms Import: catch exception in add room delivery condition ' . $roomDeliveryConditionData[static::NAME_RUS_PROPERTY]);
            return static::DELIVERY_CONDITION_DEFAULT_ID;
        }
    }

    protected function getRoomTypeIdByName($roomTypeName)
    {
        if (empty($roomTypeName) === true) {
            return static::ROOM_TYPE_DEFAULT_ID;
        }

        $roomTypeName = mb_strtolower($roomTypeName);

        $roomTypeData = RealtyRoomsTypesClassTable::getList(
            array(
                'filter' => array(
                    static::NAME_RUS_PROPERTY => $roomTypeName
                ),
            )
        )->fetch();

        if ($roomTypeData !== false) {
            return $roomTypeData[static::PRIMARY_KEY_PROPERTY];
        } else {
            $roomTypeData = array(
                static::NAME_RUS_PROPERTY => $roomTypeName,
                static::NAME_ENG_PROPERTY => CUtil::translit($roomTypeName, "ru", $this->translitParams),
                static::CODE_PROPERTY => CUtil::translit($roomTypeName, "ru"),
                static::SYS_LOCKED_PROPERTY => static::SYS_LOCKED_DEFAULT_VALUE,
            );
            return $this->addRoomType($roomTypeData);
        }
    }

    protected function addRoomType($roomTypeData)
    {
        if (empty($roomTypeData) === true) {
            return static::ROOM_TYPE_DEFAULT_ID;
        }

        try {
            $addResult = RealtyRoomsTypesClassTable::add($roomTypeData);

            if ($addResult->isSuccess() === true) {
                return $addResult->getId();
            } else {
                MessageHelper::addMessageToLog('Rooms Import: error in add room type ' . var_export($addResult->getErrorMessages(), true));
                return static::ROOM_TYPE_DEFAULT_ID;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('Rooms Import: catch exception in add room type ' . $roomTypeData[static::NAME_RUS_PROPERTY]);
            return static::ROOM_TYPE_DEFAULT_ID;
        }
    }

    protected function getCurrencyString($crmCurrencyString)
    {
        if (empty($crmCurrencyString) === true) {
            return "";
        }

        $currencyString = $crmCurrencyString;
        if (array_key_exists($crmCurrencyString, $this->currencyReference) === true) {
            $currencyString = $this->currencyReference[$crmCurrencyString];
        }

        return $currencyString;
    }

    protected function searchBuildingRooms($objectId)
    {
        if (empty($objectId) === true) {
            return array();
        }

        $roomsQuery = RealtyRoomsClassTable::getList(
            array(
                'filter' => array(
                    static::OBJECT_ID_PROPERTY => $objectId,
                ),
            )
        );
        $currentBuildingRooms = array();

        while ($currentBuildingRoom = $roomsQuery->fetch()) {
            $currentBuildingRooms[] = $currentBuildingRoom;
        }

        return $currentBuildingRooms;
    }

    protected function searchRoomByCrmId($crmId)
    {
        if (empty($crmId) === true) {
            return false;
        }

        $filterArray = array(
            static::IMPORT_PROPERTY => $crmId,
        );

        $searchRoom = RealtyRoomsClassTable::getList(
            array(
                'filter' => $filterArray,
            )
        )->fetch();

        return $searchRoom;
    }
}
?>
