<?
use \Bitrix\Main\Web\Json;
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
header('Content-Type: application/json; charset=utf-8');
$error = [
    "status" => "error",
    "data" => null,
    "errors" => [
        [
            "message" => "Wrong route string. Could not be found",
            "code" => 404,
            "customData" => null
        ]
    ]
];
echo Json::encode($error);
?>