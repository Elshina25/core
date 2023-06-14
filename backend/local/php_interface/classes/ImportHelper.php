<?php

use \Bitrix\Main\Config\Option;

class ImportHelper
{
    public static function getTodayRetries() {

        list($date, $retries) = explode(
            "|",
            Option::get('CRM', 'import_retries', '', "s1")
        );

        $today = date("Y-m-d");

        if ($date !== $today) {
            return 0;
        }

        return $retries;
    }


    public static function setTodayRetries($num) {

        $date = date("Y-m-d");
        Option::set('CRM', 'import_retries', $date . "|" . $num, "s1");
    }


    public static function runImport($date) {

        if (file_exists(LOG_FILENAME)) {
            unlink(LOG_FILENAME);
        }

        $buildingImport = new BuildingImport();
        $buildingImport->importBuildings($date);

        $roomsImport = new RoomImport();
        $roomsImport->importRooms($date);

        $calculateHelper = new CalculateBuildingHelper();
        $calculateHelper->calculateValuesForAllBuildings();

        $parkignsImport = new ParkingImport('');
        $parkignsImport->importParkings($date);

        //#9171152 stop import users
        /*$usersImport = new UserImport('','');
        $usersImport->updateUsers();*/

        $header = "Content-type: text/plain; charset=\"utf-8\"";
        mail("dolgopolov@it-agency.ru", "Импорт на cbre.rentnow.ru завершен", '', $header);

        Option::set('CRM', 'last_full_import_date', date("Y-m-d H:i:s"), 's1');

        BXClearCache(true);
    }
}