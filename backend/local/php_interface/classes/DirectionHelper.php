<?php
class DirectionHelper
{
    const DEFAULT_DIRECTION_PROPERTY_VALUE = 0;
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

    public function getDirectionIdByName($nameValue)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_DIRECTION_PROPERTY_VALUE;
        }

        $currentDirection = $this->getDirectionByName($nameValue);

        if ($currentDirection !== false) {
            return $currentDirection[static::PRIMARY_KEY_NAME];
        } else {
            $addDirectionId = $this->addDirectionByName($nameValue);
            return $addDirectionId;
        }
    }

    protected function getDirectionByName($nameValue)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_DIRECTION_PROPERTY_VALUE;
        }

        $currentDirection = RealtyDirectionsClassTable::getList(
            array(
                'filter' => array(
                    static::NAME_PROPERTY => $nameValue
                ),
            )
        )->fetch();
        
        return $currentDirection;
    }

    protected function addDirectionByName($nameValue)
    {
        if (empty($nameValue) === true) {
            return static::DEFAULT_DIRECTION_PROPERTY_VALUE;
        }

        $addDirectionValues = array(
            static::NAME_PROPERTY => $nameValue,
            static::NAME_ENG_PROPERTY => CUtil::translit($nameValue, "ru", $this->translitParamsName),
            static::CODE_PROPERTY => CUtil::translit($nameValue, "ru", $this->translitParamsCode),
        );

        try {
            $addResult = RealtyDirectionsClassTable::add($addDirectionValues);

            if ($addResult->isSuccess() === true) {
                return $addResult->getId();
            } else {
                MessageHelper::addMessageToLog('Direction Helper: error in add direction ' . $nameValue);
                return static::DEFAULT_DIRECTION_PROPERTY_VALUE;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('Direction Helper: catch error in add direction ' . $nameValue);
            return static::DEFAULT_DIRECTION_PROPERTY_VALUE;
        }

    }
}
?>
