<?php
class MetroHelper
{
    const DEFAULT_METRO_PROPERTY_VALUE = 0;
    const DEFAULT_CITY_ID = 0;
    const PRIMARY_KEY_NAME = 'id';
    const NAME_PROPERTY = 'NameRus';
    const NAME_ENG_PROPERTY = 'NameEng';
    const CODE_PROPERTY = 'code';
    const CITY_PROPERTY = 'city_id';

    protected $translitParamsName = array(
        'change_case' => false,
        'replace_space' => ' ',
    );
    protected $translitParamsCode = array(
        'change_case' => 'L',
        'replace_space' => '_',
    );

    public function getMetroIdByName($nameValue, $cityId)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_METRO_PROPERTY_VALUE;
        }

        if (empty($cityId) === true) {
            $cityId = static::DEFAULT_CITY_ID;
        }

        $currentMetro = $this->getMetroByName($nameValue, $cityId);

        if ($currentMetro !== false) {
            return $currentMetro[static::PRIMARY_KEY_NAME];
        } else {
            $addMetroId = $this->addMetroByName($nameValue, $cityId);
            return $addMetroId;
        }
    }

    protected function getMetroByName($nameValue, $cityId)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_METRO_PROPERTY_VALUE;
        }

        $currentMetro = RealtyMetrosClassTable::getList(
            array(
                'filter' => array(
                    static::NAME_PROPERTY => $nameValue,
                    static::CITY_PROPERTY => $cityId
                ),
            )
        )->fetch();
        
        return $currentMetro;
    }

    protected function addMetroByName($nameValue, $cityId)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_METRO_PROPERTY_VALUE;
        }

        $addMetroValues = array(
            static::NAME_PROPERTY => $nameValue,
            static::NAME_ENG_PROPERTY => CUtil::translit($nameValue, "ru", $this->translitParamsName),
            static::CODE_PROPERTY => CUtil::translit($nameValue, "ru", $this->translitParamsCode),
            static::CITY_PROPERTY => $cityId,
        );

        try {
            $addResult = RealtyMetrosClassTable::add($addMetroValues);

            if ($addResult->isSuccess() === true) {
                return $addResult->getId();
            } else {
                MessageHelper::addMessageToLog('Metro Helper: error in add metro ' . $nameValue);
                return static::DEFAULT_METRO_PROPERTY_VALUE;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('Metro Helper: catch error in add metro ' . $nameValue);
            return static::DEFAULT_METRO_PROPERTY_VALUE;
        }

    }
}
?>
