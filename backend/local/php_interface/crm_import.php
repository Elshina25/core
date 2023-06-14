<?php

use \Bitrix\Main\Config\Option;

ini_set("short_open_tag", 1);
//$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";
$_SERVER["DOCUMENT_ROOT"] = realpath(dirname(__FILE__)."/../..");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/realty/include.php");
ini_set('max_execution_time', 0);

define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log_import.txt");

while (ob_get_level()) {
    ob_end_flush();
}

//Раскомментировать, чтобы сбросить дату последнего обновления на 1.1.2017
// Option::set('CRM', 'last_full_import_date', '');
// Option::set('CRM', 'last_full_import_date', '2018-09-13 23:59:59');
// Option::set('CRM', 'last_full_import_date', '2019-01-22 00:06:12');

// Тут пишется дата минус день последней неудачной выгрузки
// затем скрипт можно запустить в консоли: php crm_import.php установив параметр $startedByCron = false;
//Option::set('CRM', 'last_full_import_date', '2022-05-01 00:06:12', 'S1');

//Если надо запустить импорт руками, то $startedByCron надо сделать false; 
//$startedByCron = false;
$startedByCron = true;
$date = Option::get('CRM', 'last_full_import_date', '');

if ($startedByCron) {

    $todayRetries = ImportHelper::getTodayRetries();
    $today = date("Y-m-d");
    $lastImportDate = date("Y-m-d", strtotime($date));

    if ($lastImportDate != $today) {
        $todayRetries = 0;
        ImportHelper::setTodayRetries(0);
    }
    if ($todayRetries < 3) {

        ImportHelper::setTodayRetries($todayRetries + 1);
        ImportHelper::runImport($date);
    } else {
        $header = "Content-type: text/plain; charset=\"utf-8\"";
        mail("dolgopolov@it-agency.ru", "Импорт на cbre.rentnow.ru 3 раза завершился неудачно", '', $header);
    }
} else {
    ImportHelper::runImport($date);
    $header = "Content-type: text/plain; charset=\"utf-8\"";
    mail("dolgopolov@it-agency.ru", "Импорт на cbre.rentnow.ru не по крон завершился", '', $header);
}


$sth = CRealtyObjects::GetList(Array(">active" => 0) );

while ($res = $sth->Fetch()) {
    $PropertyLease = $res["PropertyLease"];
    $PropertySale = $res["PropertySale"];

    $sthU = CRealtyUnits::GetList(Array("object_id" => $res["id"]) );
    $sale = 0;
    $rent = 0;
    while ($resU = $sthU->Fetch()) {
        if ($resU["UnitsLease"] > 0 ) {
            $rent = 1;
        }
        if ($resU["UnitSale"] > 0 ) {
            $sale = 1;
        }
    }

    if (($rent == 0) && ($sale == 0)) {
        $sale = $PropertySale;
        $rent = $PropertyLease;
    }

    $res["PropertySale"] = $sale;
    $res["PropertyLease"] = $rent;

    if(strtotime($res['ResearchCheckDate']) < strtotime('-1 year') && $res['active'] == '1' && $res['PropertyType'] == '3') {
        $res['active'] = 0;
        file_put_contents('/home/bitrix/www/bitrix/php_interface/log/logReasearch.log', date("d.m.y H:i:s")." - деактивирован элемент ##" . $res['id'] . '## с датой ' . $res['ResearchCheckDate'] . "\r\n", FILE_APPEND);
    }

    $arFields = $res;

    CRealtyObjects::Update( $arFields['id'],  $arFields);
    
}

echo "done", PHP_EOL;
?>
