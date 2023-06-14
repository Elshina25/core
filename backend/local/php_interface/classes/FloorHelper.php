<?php
class FloorHelper
{
    const DEFAULT_FLOOR_PROPERTY_VALUE = 0;
    const PRIMARY_KEY_NAME = 'id';
    const NAME_PROPERTY = 'NameRus';
    const NAME_ENG_PROPERTY = 'NameEng';
    const CODE_PROPERTY = 'code';

    protected $translitParamsName = array(
        'change_case' => false,
        'replace_space' => ' ',
    );
    protected $translitParamsCode = array(
        'change_case' => 'L',
        'replace_space' => '_',
    );

    public function getFloorIdByName($nameValue)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_FLOOR_PROPERTY_VALUE;
        }

        $currentFloor = $this->getFloorByName($nameValue);

        if ($currentFloor !== false) {
            return $currentFloor[static::PRIMARY_KEY_NAME];
        } else {
            $addFloorId = $this->addFloorByName($nameValue);
            return $addFloorId;
        }
    }

    protected function getFloorByName($nameValue)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_FLOOR_PROPERTY_VALUE;
        }

        $currentFloor = RealtyFloorsClassTable::getList(
            array(
                'filter' => array(
                    static::NAME_PROPERTY => $nameValue
                ),
            )
        )->fetch();
        
        return $currentFloor;
    }

    protected function addFloorByName($nameValue)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_FLOOR_PROPERTY_VALUE;
        }

        $addFloorValues = array(
            static::NAME_PROPERTY => $nameValue,
            static::NAME_ENG_PROPERTY => CUtil::translit($nameValue, "ru", $this->translitParamsName),
            static::CODE_PROPERTY => CUtil::translit($nameValue, "ru", $this->translitParamsCode),
        );

        try {
            $addResult = RealtyFloorsClassTable::add($addFloorValues);

            if ($addResult->isSuccess() === true) {
                return $addResult->getId();
            } else {
                MessageHelper::addMessageToLog('Floor Helper: error in add floor ' . $nameValue);
                return static::DEFAULT_FLOOR_PROPERTY_VALUE;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('Floor Helper: catch error in add floor ' . $nameValue);
            return static::DEFAULT_FLOOR_PROPERTY_VALUE;
        }

    }
}
?>
