<?php
class FireSecurityHelper
{
    const OBJECT_ID_PROPERTY = 'object_id';
    const FIRE_SECURITY_PROPERTY = 'fire_security_id';

    public function processBuildingFireSecurity($buildingId, $fireSecurityId)
    {
        if (empty($buildingId) === true) {
            return false;
        }

        $currentBuildingFireSecurity = $this->searchBuildingFireSecurity($buildingId);
        $fireSecuritiesToDelete = array();
        $isFireSecurityAdded = false;

        foreach ($currentBuildingFireSecurity as $buildingFireSecurity) {
            if ($buildingFireSecurity[static::FIRE_SECURITY_PROPERTY] == $fireSecurityId) {
                $isFireSecurityAdded = true;
            } else {
                $fireSecuritiesToDelete[] = $buildingFireSecurity;
            }
        }

        if ($isFireSecurityAdded === false && $fireSecurityId != 0) {
            $this->addFireSecurity($buildingId, $fireSecurityId);
        }

        $this->deleteFireSecurities($fireSecuritiesToDelete);

        return true;
    }

    protected function deleteFireSecurities($deleteFireSecurities)
    {
        if (empty($deleteFireSecurities) === true) {
            return false;
        }

        foreach ($deleteFireSecurities as $deleteFireSecurity) {
            try {
                $deleteResult = RealtyObjectsVsFireClassTable::delete($deleteFireSecurity);
                if ($deleteResult->isSuccess() === false) {
                    MessageHelper::addMessageToLog('FireSecurity Helper: can not delete fire security ' . var_export($deleteResult->getErrorMessages(), true));
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageToLog('FireSecurity Helper: catch error on delete fire security ' . $deleteFireSecurity[static::OBJECT_ID_PROPERTY]);
            }
        }

        return true;
    }

    protected function addFireSecurity($buildingId, $fireSecurityId)
    {
        if (
            empty($buildingId) === true
            || empty($fireSecurityId) === true
        ) {
            return false;
        }

        try {
            $addResult = RealtyObjectsVsFireClassTable::add(
                array(
                    static::OBJECT_ID_PROPERTY => $buildingId,
                    static::FIRE_SECURITY_PROPERTY => $fireSecurityId,
                )
            );

            if ($addResult->isSuccess() === false) {
                MessageHelper::addMessageToLog('FireSecurity Helper: can not add fire security ' . var_export($addResult->getErrorMessages(), true));
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('FireSecurity Helper: catch error on add fire security ' . $buildingId);
        }

        return true;
    }

    protected function searchBuildingFireSecurity($buildingId)
    {
        if (empty($buildingId) === true) {
            return array();
        }

        $buildingFireSecuritiesQuery = RealtyObjectsVsFireClassTable::getList(
            array(
                'filter' => array(
                    static::OBJECT_ID_PROPERTY => $buildingId,
                ),
            )
        );

        $buildingFireSecurities = array();
        while ($buildingFireSecurity = $buildingFireSecuritiesQuery->fetch()) {
            $buildingFireSecurities[] = $buildingFireSecurity;
        }

        return $buildingFireSecurities;
    }
}
?>
