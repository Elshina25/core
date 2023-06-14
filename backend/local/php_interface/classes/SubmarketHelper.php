<?php
class SubmarketHelper
{
    const DEFAULT_MARKET_PROPERTY_VALUE = 0;
    const DEFAULT_PARENT_ID = 0;
    const PRIMARY_KEY_NAME = 'id';
    const NAME_PROPERTY = 'NameRus';
    const NAME_ENG_PROPERTY = 'NameEng';
    const CODE_PROPERTY = 'code';
    const PARENT_ID_PROPERTY = 'parent_id';

    protected $translitParamsName = array(
        'change_case' => false,
        'replace_space' => ' ',
    );
    protected $translitParamsCode = array(
        'change_case' => 'L',
        'replace_space' => '_',
    );

    public function getMarketIdByName($nameValue, $marketIdForCity)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_MARKET_PROPERTY_VALUE;
        }

        if (empty($marketIdForCity) === true) {
            $marketIdForCity = static::DEFAULT_PARENT_ID;
        }
        $currentMarket = $this->getMarketByName($nameValue, $marketIdForCity);

        if ($currentMarket !== false) {
            return $currentMarket[static::PRIMARY_KEY_NAME];
        } else {
            $addMarketId = $this->addMarketByName($nameValue, $marketIdForCity);
            return $addMarketId;
        }
    }

    public function getMarketIdForCity($cityName) 
    {
        if (empty($cityName) === true) {
            return static::DEFAULT_PARENT_ID;
        }

        $currentCityId = static::DEFAULT_PARENT_ID;
        $currentCity = RealtyMarketsClassTable::getList(
            array(
                'filter' => array(
                    static::NAME_PROPERTY => $cityName
                ),
            )
        )->fetch();

        if ($currentCity !== false) {
            $currentCityId = (int) $currentCity[static::PRIMARY_KEY_NAME];
        }

        return $currentCityId;
    }

    protected function getMarketByName($nameValue, $cityId)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_MARKET_PROPERTY_VALUE;
        }

        $currentMarket = RealtyMarketsClassTable::getList(
            array(
                'filter' => array(
                    static::NAME_PROPERTY => $nameValue,
                    static::PARENT_ID_PROPERTY => $cityId
                ),
            )
        )->fetch();
        
        return $currentMarket;
    }

    protected function addMarketByName($nameValue, $cityId)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_MARKET_PROPERTY_VALUE;
        }

        $addMarketValues = array(
            static::NAME_PROPERTY => $nameValue,
            static::NAME_ENG_PROPERTY => CUtil::translit($nameValue, "ru", $this->translitParamsName),
            static::CODE_PROPERTY => CUtil::translit($nameValue, "ru", $this->translitParamsCode),
            static::PARENT_ID_PROPERTY => $cityId,
        );
        
        try {
            $addResult = RealtyMarketsClassTable::add($addMarketValues);

            if ($addResult->isSuccess() === true) {
                return $addResult->getId();
            } else {
                return static::DEFAULT_MARKET_PROPERTY_VALUE;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('Market Helper: error in add market ' . $nameValue);
            return static::DEFAULT_MARKET_PROPERTY_VALUE;
        }

    }
}
?>
