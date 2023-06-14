<?php
class PicturesHelper
{
    const PRIMARY_KEY_PROPERTY = 'id';
    const OBJECT_ID_PROPERTY = 'object_id';
    const FILE_PROPERTY = 'file';
    const MAIN_PROPERTY = 'main';
    const ORDER_PROPERTY = 'order';
    const MAIN_PROPERTY_VALUE = 1;
    const ORDER_STEP = 10;

    protected $propertyHelper;

    public function __construct($propertyHelper)
    {
        if (empty($propertyHelper) === false) {
            $this->propertyHelper = $propertyHelper;
        } else {
            $this->propertyHelper = new BuildingPropertiesHelper();
        }
    }

    public function fillBuildingImages($buildingId, $buildingName, $buildingImagesIds)
    {
        if (
            empty($buildingName) === true
            || empty($buildingId) === true
        ) {
            return false;
        }

        $buildingImages = $this->propertyHelper->getBuildingFiles($buildingImagesIds, $buildingName, 'images'); 
        //$addImages = $this->processBuildingImages($buildingId, $buildingImages);
        //эта функция добавляет main для 1ой картинки
        $addImages = $this->processBuildingImagesTest($buildingId, $buildingImages);
        
        return true;
    }

    protected function processBuildingImages($buildingId, $newBuildingImages)
    {
        if (empty($buildingId) === true) {
            return false;
        }

        $currentBuildingImages = $this->searchBuildingImages($buildingId);
        $imagesToAdd = array();
        $imagesToDelete = array();
        $hasMainImage = false;
        $maxOrder = 0;

        foreach ($currentBuildingImages as $buildingImage) {
            if (in_array($buildingImage[static::FILE_PROPERTY], $newBuildingImages) === true) {
                $imageIndex = array_search($buildingImage[static::FILE_PROPERTY], $newBuildingImages);
                unset($newBuildingImages[$imageIndex]);

                if ($buildingImage[static::MAIN_PROPERTY] == static::MAIN_PROPERTY_VALUE) {
                    $hasMainImage = true;
                }

                if ($buildingImage[static::ORDER_PROPERTY] > $maxOrder) {
                    $maxOrder = $buildingImage[static::ORDER_PROPERTY];
                }
            } else {
                $imagesToDelete[] = $buildingImage;
            }
        }

        $order = $maxOrder + static::ORDER_STEP;
        foreach ($newBuildingImages as $buildingImage) {
            $imagesToAdd[] = array(
                static::OBJECT_ID_PROPERTY => $buildingId,
                static::FILE_PROPERTY => $buildingImage,
                static::ORDER_PROPERTY => $order,
                static::MAIN_PROPERTY => (
                    $hasMainImage === false
                    ? static::MAIN_PROPERTY_VALUE
                    : 0
                )
            );

            $order += static::ORDER_STEP;
            if ($hasMainImage === false) {
                $hasMainImage = true;
            }
        }

        $this->deleteImages($imagesToDelete);
        $this->addImages($imagesToAdd);

        return true;
    }

    public function fillBuildingImagesTest($buildingId, $buildingName, $buildingImagesIds)
    {
        if (
            empty($buildingName) === true
            || empty($buildingId) === true
        ) {
            return false;
        }

        //$buildingImages = $this->propertyHelper->getBuildingFiles($buildingImagesIds, $buildingName, 'images'); 
        
        $buildingImages = array(
            0 => '/property/0f276922-0f4b-11e7-80e6-b8f6b1140ab1/8c2e9aee-0f55-11e7-8bcc-92ab5d1ca458.jpg',
            1 => '/property/0f276922-0f4b-11e7-80e6-b8f6b1140ab1/8c772232-0f55-11e7-8bcc-92ab5d1ca458.jpg',
            2 => '/property/0f276922-0f4b-11e7-80e6-b8f6b1140ab1/8c45e1c2-0f55-11e7-8bcc-92ab5d1ca458.jpg',
            3 => '/property/0f276922-0f4b-11e7-80e6-b8f6b1140ab1/8ca25dee-0f55-11e7-8bcc-92ab5d1ca458.jpg',
            4 => '/property/0f276922-0f4b-11e7-80e6-b8f6b1140ab1/8ce34778-0f55-11e7-8bcc-92ab5d1ca458.jpg',
            5 => '/property/0f276922-0f4b-11e7-80e6-b8f6b1140ab1/8bce7538-0f55-11e7-8bcc-92ab5d1ca458.jpg',
        );
        
        $addImages = $this->processBuildingImagesTest($buildingId, $buildingImages);

        return true;
    }

    public function processBuildingImagesTest($buildingId, $newBuildingImages)
    {
        if (empty($buildingId) === true) {
            return false;
        }

        $currentBuildingImages = $this->searchBuildingImages($buildingId);
        $imagesToAdd = array();
        $imagesToDelete = array();
        $hasMainImage = false;
        $maxOrder = 0;
        $newBuildingImagesTmp = $newBuildingImages;

        foreach ($currentBuildingImages as $buildingImage) {
            if (in_array($buildingImage[static::FILE_PROPERTY], $newBuildingImages) === true) {
                $imageIndex = array_search($buildingImage[static::FILE_PROPERTY], $newBuildingImages);
                unset($newBuildingImages[$imageIndex]);

                if ($buildingImage[static::MAIN_PROPERTY] == static::MAIN_PROPERTY_VALUE) {
                    $hasMainImage = true;
                }

                if ($buildingImage[static::ORDER_PROPERTY] > $maxOrder) {
                    $maxOrder = $buildingImage[static::ORDER_PROPERTY];
                }
            } else {
                $imagesToDelete[] = $buildingImage;
            }
        }

        $order = $maxOrder + static::ORDER_STEP;
        foreach ($newBuildingImages as $buildingImage) {
            $imagesToAdd[] = array(
                static::OBJECT_ID_PROPERTY => $buildingId,
                static::FILE_PROPERTY => $buildingImage,
                static::ORDER_PROPERTY => $order,
                static::MAIN_PROPERTY => (
                    $hasMainImage === false
                    ? static::MAIN_PROPERTY_VALUE
                    : 0
                )
            );

            $order += static::ORDER_STEP;
            if ($hasMainImage === false) {
                $hasMainImage = true;
            }
        }

        $this->deleteImages($imagesToDelete);
        $this->addImages($imagesToAdd);

        $sortImages = array();
        $currentBuildingImagesAfterUpdate = $this->searchBuildingImages($buildingId);

        foreach($currentBuildingImagesAfterUpdate as $buildingImageAfterUpdate) {
            if (in_array($buildingImageAfterUpdate[static::FILE_PROPERTY], $newBuildingImagesTmp) === true) {
                $imageIndex = array_search($buildingImageAfterUpdate[static::FILE_PROPERTY], $newBuildingImagesTmp);

                $sortImages[$imageIndex] = $buildingImageAfterUpdate;

                $sortImages[$imageIndex][static::ORDER_PROPERTY] = $imageIndex*static::ORDER_STEP + static::ORDER_STEP;
                $sortImages[$imageIndex][static::MAIN_PROPERTY] = $imageIndex == 0 ? static::MAIN_PROPERTY_VALUE : 0;
            }
        }

        ksort($sortImages, SORT_NUMERIC);
        $this->updateImages($sortImages);

        return true;
    }

    protected function updateImages($updateImages)
    {
        if (empty($updateImages) === true) {
            return false;
        }

        foreach ( $updateImages as $updateImage ) {
            try {
                $updateResult = RealtyPicturesClassTable::update($updateImage[static::PRIMARY_KEY_PROPERTY], $updateImage);

                if ($updateResult->isSuccess() === true) {
                    MessageHelper::addMessageToLog('Pictures Helper: update image ' . $updateImage[static::PRIMARY_KEY_PROPERTY]);
                } else {
                    MessageHelper::addMessageToLog('Pictures Helper: can not update image ' . var_export($updateResult->getErrorMessages(), true));
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageToLog('Pictures Helper: error on update image ' . $updateImage[static::PRIMARY_KEY_PROPERTY]);
            }
        }

        return true;
    }

    protected function addImages($addImages)
    {
        if (empty($addImages) === true) {
            return false;
        }

        foreach ($addImages as $addImage) {
            try {
                $addResult = RealtyPicturesClassTable::add($addImage);

                if ($addResult->isSuccess() === false) {
                    MessageHelper::addMessageToLog('Pictures Helper: can not add image ' . var_export($addResult->getErrorMessages(), true));
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageToLog('Pictures Helper: error on add image ' . $addImage[static::PRIMARY_KEY_PROPERTY]);
            }
        }

        return true;
    }

    protected function deleteImages($deleteImages)
    {
        if (empty($deleteImages) === true) {
            return false;
        }

        foreach ($deleteImages as $deleteImage) {
            if (empty($deleteImage[static::PRIMARY_KEY_PROPERTY]) === false) {
                try {
                    $deleteResult = RealtyPicturesClassTable::delete($deleteImage[static::PRIMARY_KEY_PROPERTY]);
                    if ($deleteResult->isSuccess() === false) {
                        MessageHelper::addMessageToLog('Pictures Helper: can not delete image ' . var_export($deleteResult->getErrorMessages(), true));
                    }
                } catch (\Exception $exception) {
                    MessageHelper::addMessageToLog('Pictures Helper: error on delete image ' . $deleteImage[static::PRIMARY_KEY_PROPERTY]);
                }
            }
        }

        return true;
    }

    protected function searchBuildingImages($buildingId)
    {
        if (empty($buildingId) === true) {
            return array();
        }

        $buildingImagesQuery = RealtyPicturesClassTable::getList(
            array(
                'filter' => array(
                    static::OBJECT_ID_PROPERTY => $buildingId,
                ),
            )
        );

        $buildingImages = array();
        while ($buildingImage = $buildingImagesQuery->fetch()) {
            $buildingImages[] = $buildingImage;
        }

        return $buildingImages;
    }
}
?>
