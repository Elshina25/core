<?php
class CityHelper
{
    const DEFAULT_CITY_PROPERTY_VALUE = 6;
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

    public function getCityIdByName($nameValue)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_CITY_PROPERTY_VALUE;
        }

        $currentCity = $this->getCityByName($nameValue);

        if ($currentCity !== false) {
            return $currentCity[static::PRIMARY_KEY_NAME];
        } else {
            $addCityId = $this->addCityByName($nameValue);
            return $addCityId;
        }
    }

    protected function getCityByName($nameValue)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_CITY_PROPERTY_VALUE;
        }

        $currentCity = RealtyCitiesClassTable::getList(
            array(
                'filter' => array(
                    static::NAME_PROPERTY => $nameValue
                ),
            )
        )->fetch();
        
        return $currentCity;
    }

    protected function addCityByName($nameValue)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_CITY_PROPERTY_VALUE;
        }

        $addCityValues = array(
            static::NAME_PROPERTY => $nameValue,
            static::NAME_ENG_PROPERTY => CUtil::translit($nameValue, "ru", $this->translitParamsName),
            static::CODE_PROPERTY => CUtil::translit($nameValue, "ru", $this->translitParamsCode) . "_" . static::DEFAULT_PARENT_ID,
            static::PARENT_ID_PROPERTY => static::DEFAULT_PARENT_ID,
        );

        try {
            $addResult = RealtyCitiesClassTable::add($addCityValues);

            if ($addResult->isSuccess() === true) {
                return $addResult->getId();
            } else {
                MessageHelper::addMessageToLog('City Helper: error in add city ' . $nameValue);
                return static::DEFAULT_CITY_PROPERTY_VALUE;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('City Helper: catch error in add city ' . $nameValue);
            return static::DEFAULT_CITY_PROPERTY_VALUE;
        }

    }
}
?>
