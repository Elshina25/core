<?php
class CalculateBuildingHelper
{
    const ACTIVE_PROPERTY = 'active';
    const PRIMARY_KEY_PROPERTY = 'id';
    const OBJECT_ID_PROPERTY = 'object_id';
    const SIZE_PROPERTY = 'UnitSize';
    const DIVISIBLE_FROM_PROPERTY = 'UnitDivisibleFrom';
    const FLOOR_NUMBER_PROPERTY = 'UnitFloorNumber';
    const AVAIL_DATE_PROPERTY = 'UnitAvailabilityDateEng';
    const RENTABLE_AREA_PROPERTY = 'RentableArea';
    const UNIT_LEASE_PROPERTY = 'UnitsLease';
    const UNIT_SALE_PROPERTY = 'UnitSale';
    const OFFERED_RENT_PROPERTY = 'UnitOfferedRent';
    const OFFERED_RENT_CURRENCY_PROPERTY = 'UnitOfferedRentCurrency';
    const OFFERED_RENT_USD_PROPERTY = 'UnitOfferedRentUSD';
    const PRICE_PROPERTY = 'UnitSalePrice';
    const PRICE_CURRENCY_PROPERTY = 'UnitSalePriceCurrency';
    const PRICE_USD_PROPERTY = 'UnitSalePriceUSD';
    const MIN_DATE_PROPERTY = 'min_UnitAvailabilityDate';
    const MIN_LEASE_DIVISIBLE_PROPERTY = 'min_lease_UnitDivisibleFrom';
    const MIN_SELL_DIVISIBLE_PROPERTY = 'min_sell_UnitDivisibleFrom';
    const SUM_LEASE_SIZE_PROPERTY = 'sum_lease_UnitSize';
    const SUM_SELL_SIZE_PROPERTY = 'sum_sell_UnitSize';
    const SUM_SIZE_PROPERTY = 'sum_UnitSize';
    const MIN_OFFER_RENT_PROPERTY = 'min_UnitOfferedRent';
    const MAX_OFFER_RENT_PROPERTY = 'max_UnitOfferedRent';
    const MIN_OFFER_RENT_USD_PROPERTY = 'min_UnitOfferedRentUSD';
    const MAX_OFFER_RENT_USD_PROPERTY = 'max_UnitOfferedRentUSD';
    const MIN_OFFER_RENT_CURRENCY_PROPERTY = 'min_UnitOfferedRentCurrency';
    const MAX_OFFER_RENT_CURRENCY_PROPERTY = 'max_UnitOfferedRentCurrency';
    const MIN_SALE_PRICE_PROPERTY = 'min_UnitSalePrice';
    const MAX_SALE_PRICE_PROPERTY = 'max_UnitSalePrice';
    const MIN_SALE_PRICE_USD_PROPERTY = 'min_UnitSalePriceUSD';
    const MAX_SALE_PRICE_USD_PROPERTY = 'max_UnitSalePriceUSD';
    const MIN_SALE_PRICE_CURRENCY_PROPERTY = 'min_UnitSalePriceCurrency';
    const MAX_SALE_PRICE_CURRENCY_PROPERTY = 'max_UnitSalePriceCurrency';
    const MIN_RENT_PRICE_PROPERTY = 'min_rent_price';
    const MAX_RENT_PRICE_PROPERTY = 'max_rent_price';
    const MIN_SELL_PRICE_PROPERTY = 'min_sale_price';
    const MAX_SELL_PRICE_PROPERTY = 'max_sale_price';
    const ACTIVE_VALUE = 1;
    const NOT_ACTIVE_VALUE = 0;
    const RENTABLE_AREA_TOP_TEXT = 'Корпус А';
    const NOW_DATE_VALUE = 'Now';
    const ON_REQUEST_DATE_VALUE = 'on request';

    protected $startBuildingValues;
    protected $currentYear;
    protected $currentQuart;
    protected $currentMonth;
    protected $currentDay;

    public function __construct()
    {
        $this->startBuildingValues = array(
            static::MIN_DATE_PROPERTY => '',
            static::MIN_LEASE_DIVISIBLE_PROPERTY => 0,
            static::MIN_SELL_DIVISIBLE_PROPERTY => 0,
            static::SUM_LEASE_SIZE_PROPERTY => 0,
            static::SUM_SELL_SIZE_PROPERTY => 0,
            static::SUM_SIZE_PROPERTY => 0,
            static::MIN_OFFER_RENT_PROPERTY => -1,
            static::MAX_OFFER_RENT_PROPERTY => -1,
            static::MIN_OFFER_RENT_USD_PROPERTY => -1,
            static::MAX_OFFER_RENT_USD_PROPERTY => -1,
            static::MIN_OFFER_RENT_CURRENCY_PROPERTY => '',
            static::MAX_OFFER_RENT_CURRENCY_PROPERTY => '',
            static::MIN_SALE_PRICE_PROPERTY => -1,
            static::MAX_SALE_PRICE_PROPERTY => -1,
            static::MIN_SALE_PRICE_USD_PROPERTY => -1,
            static::MAX_SALE_PRICE_USD_PROPERTY => -1,
            static::MIN_SALE_PRICE_CURRENCY_PROPERTY => '',
            static::MAX_SALE_PRICE_CURRENCY_PROPERTY => '',
            static::MIN_RENT_PRICE_PROPERTY => 0,
            static::MAX_RENT_PRICE_PROPERTY => 0,
            static::MIN_SELL_PRICE_PROPERTY => 0,
            static::MAX_SELL_PRICE_PROPERTY => 0,
        );

        $this->currentYear = (int) date('Y');
        $this->currentMonth = (int) date('m');
        $this->currentDay = (int) date('d');
        $this->currentQuart = (int) ceil(
            $currentMonth / 3
        );
    }

    public function calculateValuesForAllBuildings()
    {
        $allBuildingsQuery = RealtyObjectsClassTable::getList(
            array(
                'select' => array('*'),
                'filter' => array(
                    'active' => 1,
                ),
            )
        );

        while ($buildingData = $allBuildingsQuery->fetch()) {
            $this->calculateValuesForBuilding($buildingData);
        }
    }

    public function calculateValuesForBuildingById($buildingId)
    {
        if (empty($buildingId) === true) {
            return false;
        }

        $buildingData = RealtyObjectsClassTable::getById($buildingId)->fetch();

        if ($buildingData !== false) {
            $this->calculateValuesForBuilding($buildingData);
        }

        return true;
    }

    protected function calculateValuesForBuilding($buildingData) 
    {
        if (empty($buildingData) === true) {
            return false;
        }

        $buildingRooms = $this->getRoomsByBuildingId($buildingData[static::PRIMARY_KEY_PROPERTY]);
        $totalValues = $this->calculateTotalValuesForRooms($buildingRooms);

        if (empty($totalValues) === false) {
            foreach ($totalValues as $propertyCode => $propertyValue) {
                $buildingData[$propertyCode] = $propertyValue;
            }

            $buildingData[static::RENTABLE_AREA_PROPERTY] = $this->buildRentableArea($buildingRooms);

            try {
                $updateResult = RealtyObjectsClassTable::update($buildingData[static::PRIMARY_KEY_PROPERTY], $buildingData);

                if ($updateResult->isSuccess() === true) {
                    return true;
                } else {
                    MessageHelper::addMessageTolog('Calculate Helper: error in update building ' . var_export($updateResult->getErrorMessages(), true));
                    return false;
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageTolog('Calculate Helper: catch exception in update building ' . var_export($exception, true));
                return false;
            }
        }

        return true;
    }

    protected function buildRentableArea($buildingRooms)
    {
        if (empty($buildingRooms) === true) {
            return "";
        }

        $rentableArea = array();

        foreach ($buildingRooms as $buildingRoom) {
            if (empty($buildingRoom[static::SIZE_PROPERTY]) === false) {
                $rentableArea[] = number_format($buildingRoom[static::SIZE_PROPERTY], 2, ',', "") . " " . $buildingRoom[static::FLOOR_NUMBER_PROPERTY];
            }
        }

        if (empty($rentableArea) === false) {
            $rentableAreaString = static::RENTABLE_AREA_TOP_TEXT . PHP_EOL . implode(PHP_EOL, $rentableArea);
        }

        return $rentableAreaString;
    }

    protected function calculateTotalValuesForRooms($buildingRooms)
    {
        $totalValues = $this->startBuildingValues;

        $buildingLease = static::NOT_ACTIVE_VALUE;
        $buildingSale = static::NOT_ACTIVE_VALUE;
        $availDate = array(
            'year' => 0,
            'quart' => 0,
            'month' => 0,
            'day' => 1
        );

        foreach ($buildingRooms as $buildingRoom) {
            $divisibleProperty = (float) $buildingRoom[static::DIVISIBLE_FROM_PROPERTY];
            $unitSize = (float) $buildingRoom[static::SIZE_PROPERTY];

            if (empty($buildingRoom[static::AVAIL_DATE_PROPERTY]) === false) {
                $currentAvailDate = $buildingRoom[static::AVAIL_DATE_PROPERTY];

                switch ($currentAvailDate) {
                    case static::NOW_DATE_VALUE:
                        if (
                            empty($totalValues[static::MIN_DATE_PROPERTY]) === true
                            || $totalValues[static::MIN_DATE_PROPERTY] == static::ON_REQUEST_DATE_VALUE
                        ) {
                            $totalValues[static::MIN_DATE_PROPERTY] = $currentAvailDate;
                            $availDate['year'] = $this->currentYear;
                            $availDate['quart'] = $this->currentQuart;
                            $availDate['month'] = $this->currentMonth;
                            $availDate['day'] = $this->currentDay;
                        } else if (empty($availDate['year']) === false) {
                            if (
                                $this->currentYear < $availDate['year']
                                || (
                                    $this->currentYear == $availDate['year']
                                    && $this->currentQuart <= $availDate['quart']
                                )
                                || (
                                    empty($availDate['month']) === false
                                    && $this->currentYear == $availDate['year']
                                    && $this->currentMonth < $availDate['month']
                                )
                                || (
                                    empty($availDate['month']) === false
                                    && empty($availDate['day']) === false
                                    && $this->currentYear == $availDate['year']
                                    && $this->currentMonth == $availDate['month']
                                    && $this->currentDay < $availDate['day']
                                )
                            ) {
                                $totalValues[static::MIN_DATE_PROPERTY] = $currentAvailDate;
                                $availDate['year'] = $this->currentYear;
                                $availDate['quart'] = $this->currentQuart;
                                $availDate['month'] = $this->currentMonth;
                                $availDate['day'] = $this->currentDay;
                            }
                        }
                        break;
                    case 'on request':
                        if (empty($totalValues[static::MIN_DATE_PROPERTY]) === true) {
                            $totalValues[static::MIN_DATE_PROPERTY] = $currentAvailDate;
                        }
                        break;
                    default:
                        $currentAvailDateValues = array(
                            'year' => 0,
                            'quart' => 0,
                            'month' => 0,
                            'day' => 1
                        );

                        if (preg_match("#^(\d{2})\.(\d{2})\.(\d{4})$#iuU", $currentAvailDate, $dateMatches) == true) {
                            $currentAvailDateValues['year'] = (int) $dateMatches[3];
                            $currentAvailDateValues['month'] = (int) $dateMatches[2];
                            $currentAvailDateValues['quart'] = (int) ceil(
                                $currentAvailDateValues['month'] / 3
                            );
                            $currentAvailDateValues['day'] = (int) $dateMatches[1];
                        } else if (preg_match("#^Q(\d)\s+(\d{4})$#iuU", $currentAvailDate, $dateMatches) == 1) {
                            $currentAvailDateValues['year'] = (int) $dateMatches[2];
                            $currentAvailDateValues['quart'] = (int) $dateMatches[1];
                        } else if (preg_match("#^(\d{2})\/(\d{2})\/(\d{4})$#iuU", $currentAvailDate, $dateMatches) == 1) {
                            $currentAvailDateValues['year'] = (int) $dateMatches[3];
                            $currentAvailDateValues['month'] = (int) $dateMatches[1];
                            $currentAvailDateValues['quart'] = (int) ceil(
                                $currentAvailDateValues['month'] / 3
                            );
                            $currentAvailDateValues['day'] = (int) $dateMatches[2];
                        }

                        if (
                            empty($totalValues[static::MIN_DATE_PROPERTY]) === true
                            || $totalValues[static::MIN_DATE_PROPERTY] == static::ON_REQUEST_DATE_VALUE
                        ) {
                            $totalValues[static::MIN_DATE_PROPERTY] = $currentAvailDate;
                            $availDate['year'] = $currentAvailDateValues['year'];
                            $availDate['quart'] = $currentAvailDateValues['quart'];
                            $availDate['month'] = $currentAvailDateValues['month'];
                            $availDate['day'] = $currentAvailDateValues['day'];
                        } else if (
                            empty($availDate['year']) === false
                            && empty($currentAvailDateValues['year']) === false
                        ) {
                            if (
                                $currentAvailDateValues['year'] < $availDate['year']
                                || (
                                    $currentAvailDateValues['year'] == $availDate['year']
                                    && $currentAvailDateValues['quart'] < $availDate['quart']
                                )
                                || (
                                    empty($currentAvailDateValues['month']) === false
                                    && empty($availDate['month']) === false
                                    && $currentAvailDateValues['year'] == $availDate['year']
                                    && $currentAvailDateValues['month'] < $availDate['month']
                                )
                                || (
                                    empty($currentAvailDateValues['month']) === false
                                    && empty($availDate['month']) === false
                                    && $currentAvailDateValues['year'] == $availDate['year']
                                    && $currentAvailDateValues['month'] == $availDate['month']
                                    && $currentAvailDateValues['day'] < $availDate['day']
                                )
                            ) {
                                $totalValues[static::MIN_DATE_PROPERTY] = $currentAvailDate;
                                $availDate['year'] = $currentAvailDateValues['year'];
                                $availDate['quart'] = $currentAvailDateValues['quart'];
                                $availDate['month'] = $currentAvailDateValues['month'];
                                $availDate['day'] = $currentAvailDateValues['day'];
                            }
                        }
                        break;
                }
            }

            if ($unitSize > 0) {
                $totalValues[static::SUM_SIZE_PROPERTY] += $unitSize;
            }

            if ($buildingRoom[static::UNIT_LEASE_PROPERTY] == static::ACTIVE_VALUE) {
                if ($buildingLease == static::NOT_ACTIVE_VALUE) {
                    $buildingLease = static::ACTIVE_VALUE;
                }

                if ($divisibleProperty > 0) {
                    if ($totalValues[static::MIN_LEASE_DIVISIBLE_PROPERTY] <= 0) {
                        $totalValues[static::MIN_LEASE_DIVISIBLE_PROPERTY] = $divisibleProperty;
                    }
                    if ($divisibleProperty < $totalValues[static::MIN_LEASE_DIVISIBLE_PROPERTY]) {
                        $totalValues[static::MIN_LEASE_DIVISIBLE_PROPERTY] = $divisibleProperty;
                    }
                }

                if ($unitSize > 0) {
                    $totalValues[static::SUM_LEASE_SIZE_PROPERTY] += $unitSize;
                }

                $unitOfferedRent = (float) $buildingRoom[static::OFFERED_RENT_PROPERTY];
                $unitOfferedRentUSD = (float) $buildingRoom[static::OFFERED_RENT_USD_PROPERTY];
                $unitOfferedRentCurrency = $buildingRoom[static::OFFERED_RENT_CURRENCY_PROPERTY];

                if ($unitOfferedRent > 0 && $unitOfferedRentUSD > 0) {

                    if ($totalValues[static::MIN_OFFER_RENT_PROPERTY] <= 0) {
                        $totalValues[static::MIN_OFFER_RENT_PROPERTY] = $unitOfferedRent;
                        $totalValues[static::MIN_OFFER_RENT_CURRENCY_PROPERTY] = $unitOfferedRentCurrency;
                        $totalValues[static::MIN_OFFER_RENT_USD_PROPERTY] = $unitOfferedRentUSD;
                    }

                    if ($unitOfferedRentUSD < $totalValues[static::MIN_OFFER_RENT_USD_PROPERTY]) {
                        $totalValues[static::MIN_OFFER_RENT_PROPERTY] = $unitOfferedRent;
                        $totalValues[static::MIN_OFFER_RENT_CURRENCY_PROPERTY] = $unitOfferedRentCurrency;
                        $totalValues[static::MIN_OFFER_RENT_USD_PROPERTY] = $unitOfferedRentUSD;
                    }

                    if ($unitOfferedRentUSD > $totalValues[static::MAX_OFFER_RENT_USD_PROPERTY]) {
                        $totalValues[static::MAX_OFFER_RENT_PROPERTY] = $unitOfferedRent;
                        $totalValues[static::MAX_OFFER_RENT_CURRENCY_PROPERTY] = $unitOfferedRentCurrency;
                        $totalValues[static::MAX_OFFER_RENT_USD_PROPERTY] = $unitOfferedRentUSD;
                    }

                    $unitRentPrice = (
                        $divisibleProperty > 0
                        ? $divisibleProperty * $unitOfferedRentUSD
                        : $unitOfferedRentUSD
                    );

                    if ($totalValues[static::MIN_RENT_PRICE_PROPERTY] <= 0) {
                        $totalValues[static::MIN_RENT_PRICE_PROPERTY] = $unitRentPrice;
                    }

                    if ($unitRentPrice < $totalValues[static::MIN_RENT_PRICE_PROPERTY]) {
                        $totalValues[static::MIN_RENT_PRICE_PROPERTY] = $unitRentPrice;
                    }

                    if ($unitRentPrice > $totalValues[static::MAX_RENT_PRICE_PROPERTY]) {
                        $totalValues[static::MAX_RENT_PRICE_PROPERTY] = $unitRentPrice;
                    }
                }
            }
            
            if ($buildingRoom[static::UNIT_SALE_PROPERTY] == static::ACTIVE_VALUE) {
                if ($buildingSale == static::NOT_ACTIVE_VALUE) {
                    $buildingSale = static::ACTIVE_VALUE;
                }

                if ($divisibleProperty > 0) {
                    if ($totalValues[static::MIN_SELL_DIVISIBLE_PROPERTY] <= 0) {
                        $totalValues[static::MIN_SELL_DIVISIBLE_PROPERTY] = $divisibleProperty;
                    }
                    if ($divisibleProperty < $totalValues[static::MIN_SELL_DIVISIBLE_PROPERTY]) {
                        $totalValues[static::MIN_SELL_DIVISIBLE_PROPERTY] = $divisibleProperty;
                    }
                }

                if ($unitSize > 0) {
                    $totalValues[static::SUM_SELL_SIZE_PROPERTY] += $unitSize;
                }

                $unitSalePrice = (float) $buildingRoom[static::PRICE_PROPERTY];
                $unitSalePriceUSD = (float) $buildingRoom[static::PRICE_USD_PROPERTY];
                $unitSalePriceCurrency = $buildingRoom[static::PRICE_CURRENCY_PROPERTY];

                if ($unitSalePrice > 0 && $unitSalePriceUSD > 0) {

                    if ($totalValues[static::MIN_SALE_PRICE_PROPERTY] <= 0) {
                        $totalValues[static::MIN_SALE_PRICE_PROPERTY] = $unitSalePrice;
                        $totalValues[static::MIN_SALE_PRICE_CURRENCY_PROPERTY] = $unitSalePriceCurrency;
                        $totalValues[static::MIN_SALE_PRICE_USD_PROPERTY] = $unitSalePriceUSD;
                    }

                    if ($unitSalePriceUSD < $totalValues[static::MIN_SALE_PRICE_USD_PROPERTY]) {
                        $totalValues[static::MIN_SALE_PRICE_PROPERTY] = $unitSalePrice;
                        $totalValues[static::MIN_SALE_PRICE_CURRENCY_PROPERTY] = $unitSalePriceCurrency;
                        $totalValues[static::MIN_SALE_PRICE_USD_PROPERTY] = $unitSalePriceUSD;
                    }

                    if ($unitSalePriceUSD > $totalValues[static::MAX_SALE_PRICE_USD_PROPERTY]) {
                        $totalValues[static::MAX_SALE_PRICE_PROPERTY] = $unitSalePrice;
                        $totalValues[static::MAX_SALE_PRICE_CURRENCY_PROPERTY] = $unitSalePriceCurrency;
                        $totalValues[static::MAX_SALE_PRICE_USD_PROPERTY] = $unitSalePriceUSD;
                    }

                    $unitSalePriceTotal = (
                        $divisibleProperty > 0
                        ? $divisibleProperty * $unitSalePriceUSD
                        : $unitSalePriceUSD
                    );

                    if ($totalValues[static::MIN_SELL_PRICE_PROPERTY] <= 0) {
                        $totalValues[static::MIN_SELL_PRICE_PROPERTY] = $unitSalePriceTotal;
                    }

                    if ($unitSalePriceTotal < $totalValues[static::MIN_SELL_PRICE_PROPERTY]) {
                        $totalValues[static::MIN_SELL_PRICE_PROPERTY] = $unitSalePriceTotal;
                    }

                    if ($unitSalePriceTotal > $totalValues[static::MAX_SELL_PRICE_PROPERTY]) {
                        $totalValues[static::MAX_SELL_PRICE_PROPERTY] = $unitSalePriceTotal;
                    }
                }
            }
        }

        if ($buildingSale == static::NOT_ACTIVE_VALUE) {
            $totalValues[static::MIN_SALE_PRICE_PROPERTY] = -2;
            $totalValues[static::MIN_SALE_PRICE_USD_PROPERTY] = -2;
            $totalValues[static::MAX_SALE_PRICE_PROPERTY] = -2;
            $totalValues[static::MAX_SALE_PRICE_USD_PROPERTY] = -2;
            $totalValues[static::MIN_SELL_PRICE_PROPERTY] = -2;
            $totalValues[static::MAX_SELL_PRICE_PROPERTY] = -2;
        } 

        if ($buildingLease == static::NOT_ACTIVE_VALUE) {
            $totalValues[static::MIN_OFFER_RENT_PROPERTY] = -2;
            $totalValues[static::MIN_OFFER_RENT_USD_PROPERTY] = -2;
            $totalValues[static::MAX_OFFER_RENT_PROPERTY] = -2;
            $totalValues[static::MAX_OFFER_RENT_USD_PROPERTY] = -2;
            $totalValues[static::MIN_RENT_PRICE_PROPERTY] = -2;
            $totalValues[static::MAX_RENT_PRICE_PROPERTY] = -2;
        } 

        return $totalValues;
    }

    protected function getRoomsByBuildingId($buildingId)
    {
        if (empty($buildingId) === true) {
            return array();
        }

        $buildingRooms = array();
        $buildingRoomsQuery = RealtyRoomsClassTable::getList(
            array(
                'filter' => array(
                    static::OBJECT_ID_PROPERTY => $buildingId
                )
            )
        );

        while ($buildingRoom = $buildingRoomsQuery->fetch()) {
            $buildingRooms[] = $buildingRoom;
        }

        return $buildingRooms;
    }
}
?>
