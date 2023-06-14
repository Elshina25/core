<?php
class BusinessUnitsHelper
{
    const OBJECT_ID_PROPERTY = 'object_id';
    const BUSINESS_UNIT_PROPERTY = 'business_unit_id';

    public function processBuildingUnits($buildingId, $newBuildingUnits)
    {
        if (empty($buildingId) === true) {
            return false;
        }

        $currentBuildingUnits = $this->searchBuildingUnits($buildingId);
        $unitsToAdd = array();
        $unitsToDelete = array();

        foreach ($currentBuildingUnits as $buildingUnit) {
            if (in_array($buildingUnit[static::BUSINESS_UNIT_PROPERTY], $newBuildingUnits) === true) {
                $unitIndex = array_search($buildingUnit[static::BUSINESS_UNIT_PROPERTY], $newBuildingUnits);
                unset($newBuildingUnits[$unitIndex]);
            } else {
                $unitsToDelete[] = $buildingUnit;
            }
        }

        foreach ($newBuildingUnits as $buildingUnit) {
            $unitsToAdd[] = array(
                static::OBJECT_ID_PROPERTY => $buildingId,
                static::BUSINESS_UNIT_PROPERTY => $buildingUnit
            );
        }
         
        $this->addUnits($unitsToAdd);
        //$this->deleteUnits($unitsToDelete);
        // do NOT delete units for manually adding

        return true;
    }

    protected function deleteUnits($deleteUnits)
    {
        if (empty($deleteUnits) === true) {
            return false;
        }

        foreach ($deleteUnits as $deleteUnit) {
            try {
                $deleteResult = RealtyObjectsVsUnitsClassTable::delete($deleteUnit);
                if ($deleteResult->isSuccess() === false) {
                    MessageHelper::addMessageToLog('BusinessUnits Helper: can not delete unit ' . var_export($deleteResult->getErrorMessages(), true));
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageToLog('BusinessUnits Helper: error on delete unit ' . $deleteUnit[static::OBJECT_ID_PROPERTY]);
            }
        }

        return true;
    }

    protected function addUnits($addUnits)
    {
        if (empty($addUnits) === true) {
            return false;
        }

        foreach ($addUnits as $addUnit) {
            try {
                $addResult = RealtyObjectsVsUnitsClassTable::add($addUnit);

                if ($addResult->isSuccess() === false) {
                    MessageHelper::addMessageToLog('BusinessUnit Helper: can not add unit ' . var_export($addResult->getErrorMessages(), true));
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageToLog('BusinessUnit Helper: error on add unit ' . $addUnit[static::OBJECT_ID_PROPERTY]);
            }
        }

        return true;
    }

    protected function searchBuildingUnits($buildingId)
    {
        if (empty($buildingId) === true) {
            return array();
        }

        $buildingUnitsQuery = RealtyObjectsVsUnitsClassTable::getList(
            array(
                'filter' => array(
                    static::OBJECT_ID_PROPERTY => $buildingId,
                ),
            )
        );

        $buildingUnits = array();
        while ($buildingUnit = $buildingUnitsQuery->fetch()) {
            $buildingUnits[] = $buildingUnit;
        }

        return $buildingUnits;
    }
}
?>
