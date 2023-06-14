<?php
class UserImport
{
    const PRIMARY_KEY_PROPERTY = 'id';
    const IMPORT_PROPERTY = 'crm_id';
    const OBJECT_ID_PROPERTY = 'object_id';
    const ORDER_PROPERTY = 'order';
    const IMAGE_PROPERTY = 'ContactFolder';
    const SYS_LOCKED_PROPERTY = 'sys_locked';
    const ACTIVE_PROPERTY = 'active';
    const NAME_ENG_PROPERTY = 'NameEng';
    const NAME_RUS_PROPERTY = 'NameRus';
    const POST_ENG_PROPERTY = 'PostEng';
    const POST_RUS_PROPERTY = 'PostRus';
    const BUSINESS_UNIT_PROPERTY = 'ContactBusinessUnit';
    const CODE_PROPERTY = 'code';
    const CONTACT_ID_PROPERTY = 'contact_id';
    const IMPORT_PROPERTY_CRM = 'ID';
    const IMAGE_PROPERTY_CRM = 'IMAGE';
    const IMAGE_NAME_PROPERTY_CRM = 'CONTACT_FOLDER';
    const BUSINESS_UNIT_PROPERTY_CRM = 'CONTACT_BUSINESS_UNIT';
    const NAME_RUS_PROPERTY_CRM = 'NAME_RUS';
    const NAME_ENG_PROPERTY_CRM = 'NAME';
    const POST_RUS_PROPERTY_CRM = 'POST';
    const SYS_LOCKED_NEED_VALUE = 0;
    const OBJECT_DEFAULT_ID = 0;
    const USER_DEFAULT_ID = 0;
    const BUSINESS_UNIT_DEFAULT_ID = 0;
    const ACTIVE_DEFAULT_VALUE = 1;
    const ORDER_DEFAULT_VALUE = 0;
    const ORDER_STEP = 10;
    const FILE_PATH = '/upload/contact_photos/';

    protected $crmApi;
    protected $propertyHelper;
    protected $referenceForUsers = array(
        'ID' => array(
            'name' => 'crm_id',
            'default' => '',
        ),
        'NAME' => array(
            'name' => 'NameEng',
            'default' => '',
        ),
        'NAME_RUS' => array(
            'name' => 'NameRus',
            'default' => '',
        ),
        'POST' => array(
            'name' => 'PostRus',
            'default' => '',
        ),
        'CONTACT_MOBILE_PHONE' => array(
            'name' => 'ContactMobilePhone',
            'default' => '',
        ),
        'CONTACT_EMAIL' => array(
            'name' => 'ContactEmail',
            'default' => '',
        ),
    );
    protected $translitParams = array(
        'change_case' => false,
        'replace_space' => ' ',
    );

    public function __construct($crmApi, $propertyHelper)
    {
        if (empty($crmApi) === false) {
            $this->crmApi = $crmApi;
        } else {
            $this->crmApi = new CrmClass();
        }

        if (empty($propertyHelper) === false) {
            $this->propertyHelper = $propertyHelper;
        } else {
            $this->propertyHelper = new BuildingPropertiesHelper('');
        }
    }

    public function updateUsers()
    {
        $usersQuery = RealtyUsersClassTable::getList(
            array(
                'filter' => array(
                    '!=' . static::IMPORT_PROPERTY => false
                ),
            )
        );

        $usersIds = array();
        while ($userData = $usersQuery->fetch()) {
            if (empty($userData[static::IMPORT_PROPERTY]) === false) {
                $usersIds[] = $userData[static::IMPORT_PROPERTY];
            }
        }

        if (empty($usersIds) === false) {
            $usersData = $this->crmApi->getUsersByIds($usersIds);

            foreach ($usersData as $userData) {
                $this->processUserData($userData);
            }
        }

        return true;
    }

    public function importUsersForObject($objectId, $usersIds)
    {
        if (empty($objectId) === true) {
            return false;
        }

        $buildingUsersData = $this->crmApi->getUsersByIds($usersIds);
        $buildingUsersIds = array();

        foreach ($buildingUsersData as $buildingUserData) {
            $userId = $this->processUserData($buildingUserData);
            if ($userId != static::USER_DEFAULT_ID) {
                $buildingUsersIds[] = $userId;
            }
        }

        return $this->updateObjectUsers($objectId, $buildingUsersIds);
    }

    protected function updateObjectUsers($objectId, $objectUserIds)
    {
        if (empty($objectId) === true) {
            return false;
        }

        $currentObjectUsers = $this->searchBuildingUsers($objectId);
        $maxOrder = 0;

        foreach ($currentObjectUsers as $objectUser) {
            if (in_array($objectUser[static::CONTACT_ID_PROPERTY], $objectUserIds) === true) {
                $userIndex = array_search($objectUser[static::CONTACT_ID_PROPERTY], $objectUserIds);
                unset($objectUserIds[$userIndex]);

                if ($objectUser[static::ORDER_PROPERTY] > $maxOrder) {
                    $maxOrder = $objectUser[static::ORDER_PROPERTY];
                }
            } else {
                unset($objectUser[static::ORDER_PROPERTY]);
                $this->deleteUserForObject($objectUser);
            }
        }

        $order = $maxOrder + static::ORDER_STEP;
        foreach ($objectUserIds as $objectUserId) {
            $objectUser = array(
                static::OBJECT_ID_PROPERTY => $objectId,
                static::CONTACT_ID_PROPERTY => $objectUserId,
                static::ORDER_PROPERTY => $order,
            );

            $this->addUserForObject($objectUser);

            $order += static::ORDER_STEP;
        }

        return true;
    }

    protected function deleteUserForObject($objectUserData)
    {
        if (empty($objectUserData) === true) {
            return false;
        }

        try {
            $deleteResult = RealtyObjectsVsUsersClassTable::delete($objectUserData);

            if ($deleteResult->isSuccess() === true) {
                return true;
            } else {
                MessageHelper::addMessageToLog('User Import: error in delete user for object ' . var_export($deleteResult->getErrorMessages(), true));
                return false;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('User Import: catch exception in delete user for object ' . $objectUserData[static::CONTACT_ID_PROPERTY]);
            return false;
        }
    }

    protected function addUserForObject($objectUserData)
    {
        if (empty($objectUserData) === true) {
            return false;
        }

        try {
            $addResult = RealtyObjectsVsUsersClassTable::add($objectUserData);

            if ($addResult->isSuccess() === true) {
                return true;
            } else {
                MessageHelper::addMessageToLog('User Import: error in add user for object ' . var_export($addResult->getErrorMessages(), true));
                return false;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('User Import: catch exception in add user for object ' . $objectUserData[static::CONTACT_ID_PROPERTY]);
            return false;
        }
    }

    protected function processUserData($userData)
    {
        if (empty($userData) === true) {
            return false;
        }

        $currentUser = $this->searchUserByCrmId($userData[static::IMPORT_PROPERTY_CRM]);

        if ($currentUser !== false) {
            $userId = $this->updateUserData($userData, $currentUser);
        } else {
            $userId = $this->addUserData($userData);
        }

        return $userId;
    }

    protected function addUserData($userData)
    {
        if (empty($userData) === true) {
            return static::USER_DEFAULT_ID;
        }

        $currentUserData = $this->getUserDataFromArray($userData);

        if (empty($currentUserData) === false) {
            try {
                $addResult = RealtyUsersClassTable::add($currentUserData);

                if ($addResult->isSuccess() === true) {
                    return $addResult->getId();
                } else {
                    MessageHelper::addMessageToLog('User Import: error in add user ' . var_export($addResult->getErrorMessages(), true));
                    return static::USER_DEFAULT_ID;
                }
            } catch (\Exception $exception) {
                MessageHelper::addMessageToLog('User Import: catch exception in add user ' . $currentUserData[static::IMPORT_PROPERTY]);
                return static::USER_DEFAULT_ID;
            }
        }
    }

    protected function updateUserData($newUserData, $currentUser)
    {
        if (empty($newUserData) === true) {
            return static::USER_DEFAULT_ID;
        }

        if ($currentUser[static::SYS_LOCKED_PROPERTY] == static::SYS_LOCKED_NEED_VALUE) {
            $currentUserData = $this->getUserDataFromArray($newUserData, $currentUser);

            if (empty($currentUserData) === false) {
                try {
                    $updateResult = RealtyUsersClassTable::update($currentUserData[static::PRIMARY_KEY_PROPERTY], $currentUserData);

                    if ($updateResult->isSuccess() === true) {
                        return $currentUserData[static::PRIMARY_KEY_PROPERTY];
                    } else {
                        MessageHelper::addMessageToLog('User Import: error in update user ' . var_export($addResult->getErrorMessages(), true));
                        return $currentUserData[static::PRIMARY_KEY_PROPERTY];
                    }
                } catch (\Exception $exception) {
                    MessageHelper::addMessageToLog('User Import: catch exception in update user ' . $currentUserData[static::IMPORT_PROPERTY]);
                    return $currentUserData[static::PRIMARY_KEY_PROPERTY];
                }
            } else {
                return $currentUser[static::PRIMARY_KEY_PROPERTY];
            }
        } else {
            return $currentUser[static::PRIMARY_KEY_PROPERTY];
        }
    }

    protected function getUserDataFromArray($userData, $currentUser = array())
    {
        if (empty($userData) === true) {
            return array();
        }

        $currentUserData = $currentUser;
        $currentUserData = $this->getSimpleUserData($userData, $currentUserData);
        $currentUserData = $this->getComplicatedUserData($userData, $currentUserData);

        return $currentUserData;
    }

    protected function getComplicatedUserData($userData, $currentUserData = array()) 
    {
        if (empty($userData) === true) {
            return array();
        }

        $newUserData = $currentUserData;
        $newUserData[static::ORDER_PROPERTY] = static::ORDER_DEFAULT_VALUE;
        $newUserData[static::OBJECT_ID_PROPERTY] = static::OBJECT_DEFAULT_ID;
        $newUserData[static::SYS_LOCKED_PROPERTY] = static::SYS_LOCKED_NEED_VALUE;
        $newUserData[static::ACTIVE_PROPERTY] = static::ACTIVE_DEFAULT_VALUE;
        $newUserData[static::IMAGE_PROPERTY] = $this->getUserImage($userData);
        $newUserData[static::BUSINESS_UNIT_PROPERTY] = $this->getUserBusinessUnitId($userData[static::BUSINESS_UNIT_PROPERTY_CRM]);

        if (empty($userData[static::NAME_ENG_PROPERTY_CRM]) === false) {
            $newUserData[static::NAME_ENG_PROPERTY] = $userData[static::NAME_ENG_PROPERTY_CRM];
        }

        if (mb_strlen(trim($userData[static::NAME_RUS_PROPERTY_CRM])) === 0) {
            $newUserData[static::NAME_RUS_PROPERTY] = $userData[static::NAME_ENG_PROPERTY_CRM];
        }

        if (empty($newUserData[static::POST_ENG_PROPERTY]) === true) {
            $newUserData[static::POST_ENG_PROPERTY] = CUtil::translit($userData[static::POST_RUS_PROPERTY_CRM], "ru", $this->translitParams);
        }

        return $newUserData;
    }

    protected function getSimpleUserData($userData, $currentUserData = array())
    {
        if (empty($userData) === true) {
            return array();
        }

        $newUserData = $currentUserData;
        foreach ($this->referenceForUsers as $userPropertyCrm => $propertySettings) {
            if (empty($propertySettings['name']) === false) {
                if (empty($userData[$userPropertyCrm]) === false) {
                    $newUserData[$propertySettings['name']] = $userData[$userPropertyCrm];
                } else if (isset($propertySettings['default']) === true) {
                    $newUserData[$propertySettings['name']] = $propertySettings['default'];
                }
            }
        }

        return $newUserData;
    }

    protected function getUserImage($userData)
    {
        if (empty($userData[static::IMAGE_PROPERTY_CRM]) === true) {
            return "";
        }

        $fileName = static::FILE_PATH . $userData[static::IMAGE_NAME_PROPERTY_CRM] . '.' . $userData[static::IMAGE_PROPERTY_CRM]['extension'];
        $isUserImageSaved = $this->propertyHelper->saveFiles($fileName, $userData[static::IMAGE_PROPERTY_CRM]['content']);

        if ($isUserImageSaved === true) {
            return $fileName;
        } else {
            return "";
        }
    }

    protected function getUserBusinessUnitId($businessUnitName)
    {
        if (empty($businessUnitName) === true) {
            return static::BUSINESS_UNIT_DEFAULT_ID;
        }

        $businessUnit = $this->searchBusinessUnitByName($businessUnitName);

        if ($businessUnit === false) {
            $businessUnitToAdd = array(
                static::NAME_RUS_PROPERTY => $businessUnitName,
                static::NAME_ENG_PROPERTY => CUtil::translit($businessUnitName, "ru", $this->translitParams),
                static::CODE_PROPERTY => CUtil::translit($businessUnitName, "ru"),
                static::SYS_LOCKED_PROPERTY => static::SYS_LOCKED_NEED_VALUE,

            );

            return $this->addBusinessUnit($businessUnitToAdd);
        } else {
            return $businessUnit[static::PRIMARY_KEY_PROPERTY];
        }
    }

    protected function addBusinessUnit($businessUnitData)
    {
        if (empty($businessUnitData) === true) {
            return static::BUSINESS_UNIT_DEFAULT_ID;
        }

        try {
            $addResult = RealtyUsersUnitsClassTable::add($businessUnitData);

            if ($addResult->isSuccess() === true) {
                return $addResult->getId();
            } else {
                MessageHelper::addMessageToLog('User Import: error in add user business unit ' . var_export($addResult->getErrorMessages(), true));
                return static::BUSINESS_UNIT_DEFAULT_ID;
            }
        } catch (\Exception $exception) {
            MessageHelper::addMessageToLog('User Import: catch exception in add user business unit ' . $businessUnitToAdd[static::NAME_RUS_PROPERTY]);
            return static::BUSINESS_UNIT_DEFAULT_ID;
        }
    }

    protected function searchBusinessUnitByName($businessUnitName)
    {
        if (empty($businessUnitName) === true) {
            return false;
        }

        $businessUnit = RealtyUsersUnitsClassTable::getList(
            array(
                'filter' => array(
                    static::NAME_RUS_PROPERTY => $businessUnitName,
                ),
            )
        )->fetch();

        return $businessUnit;
    }

    protected function searchUserByCrmId($crmId)
    {
        if (empty($crmId) === true) {
            return false;
        }

        $currentUser = RealtyUsersClassTable::getList(
            array(
                'filter' => array(
                    static::IMPORT_PROPERTY => $crmId,
                    static::OBJECT_ID_PROPERTY => static::OBJECT_DEFAULT_ID,
                )
            )
        )->fetch();

        return $currentUser;
    }

    protected function searchBuildingUsers($objectId)
    {
        if (empty($objectId) === true) {
            return array();
        }

        $usersQuery = RealtyObjectsVsUsersClassTable::getList(
            array(
                'filter' => array(
                    static::OBJECT_ID_PROPERTY => $objectId,
                ),
            )
        );

        $buildingUsers = array();

        while ($buildingUser = $usersQuery->fetch()) {
            $buildingUsers[] = $buildingUser;
        }

        return $buildingUsers;
    }
}
?>
