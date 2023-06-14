<?php
defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\EventManager;
use Bitrix\Main\ModuleManager;
use Bitrix\Highloadblock as HL;

Loc::loadMessages(__FILE__);

class ita_routes extends CModule
{
    public $MODULE_ID = 'ita.routes';
    public $MODULE_VERSION = '1.0';
    public $MODULE_VERSION_DATE = '2022-11-03 00:00:00';
    public $PARTNER_NAME = 'IT-AGENCY.RU';
    public $PARTNER_URI = 'https://www.it-agency.ru';

    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_GROUP_RIGHTS;
    
    public $errors, $module_path;

    public function __construct()
    {
        $this->MODULE_NAME =  Loc::getMessage('MODULE_ITA_ROUTES_NAME');
        $this->MODULE_DESCRIPTION = Loc::getMessage('MODULE_ITA_ROUTES_DESCRIPTION');
        $this->MODULE_GROUP_RIGHTS = 'N';
    }

    public function doInstall()
    {
        global $APPLICATION;
        
        
        if (!ModuleManager::isModuleInstalled($this->MODULE_ID)) {
            ModuleManager::registerModule($this->MODULE_ID);
            if (CModule::IncludeModule($this->MODULE_ID)) {
                $this->doInstallDB();
				$this->doInstallFiles();
				$this->doInstallEvents();
            } else {
                $APPLICATION->ThrowException(Loc::getMessage('ERROR_MODULE_REGISTER'));
                return FALSE;
            }
        }
    }

    public function doUninstall()
    {
        global $APPLICATION, $step;
		$this->doUnInstallFiles();
		$this->doUnInstallDB();
		$this->doUnInstallEvents();
        ModuleManager::unregisterModule($this->MODULE_ID);
    }
    
    public function doInstallDB()
    {
		
    }

    public function doUnInstallDB()
    {
        
    }

    public function doInstallFiles() {

    }

    public function doUnInstallFiles() {

    }

    public function doInstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->registerEventHandlerCompatible(
            'form',
            'onAfterResultAdd',
            $this->MODULE_ID,
            '\Ita\Routes\Handlers\Forms',
            'helpInSelectionAdd'
        );
        $eventManager->registerEventHandlerCompatible(
            'form',
            'onAfterResultAdd',
            $this->MODULE_ID,
            '\Ita\Routes\Handlers\Forms',
            'requestForViewAdd'
        );
        $eventManager->registerEventHandlerCompatible(
            'form',
            'onAfterResultAdd',
            $this->MODULE_ID,
            '\Ita\Routes\Handlers\Forms',
            'discussTaskAdd'
        );
        $eventManager->registerEventHandlerCompatible(
            'search',
            'BeforeIndex',
            $this->MODULE_ID,
            '\Ita\Routes\Handlers\Search',
            'beforeIndexHandler'
        );
    }

    public function doUnInstallEvents()
    {
        $eventManager = EventManager::getInstance();

        $eventManager->unRegisterEventHandler(
            'form',
            'onAfterResultAdd',
            $this->MODULE_ID,
            '\Ita\Routes\Handlers\Forms',
            'helpInSelectionAdd'
        );
        $eventManager->unRegisterEventHandler(
            'form',
            'onAfterResultAdd',
            $this->MODULE_ID,
            '\Ita\Routes\Handlers\Forms',
            'requestForViewAdd'
        );
        $eventManager->unRegisterEventHandler(
            'form',
            'onAfterResultAdd',
            $this->MODULE_ID,
            '\Ita\Routes\Handlers\Forms',
            'discussTaskAdd'
        );
        $eventManager->unRegisterEventHandler(
            'search',
            'BeforeIndex',
            $this->MODULE_ID,
            '\Ita\Routes\Handlers\Search',
            'beforeIndexHandler'
        );
    }

}
