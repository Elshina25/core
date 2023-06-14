<?php

use \Bitrix\Main\Web\Json;
use \Bitrix\Main\ArgumentException;

class CrmClass
{
    const ENTRY_POINT = "rentnow_export";
    //test crm
    /*
    const API_KEY = "3966a27b402446d3408e8d782cdb19314823ea85";
    const CRM_HOST = "dev3.kubtri.com";
    const CRM_PROTOCOL = "http";
    const CRM_URL = "/dev/cbre/release-2017-09-25/";
     */
    //end test crm
    //main crm
    const API_KEY = "uchgLdYCbSS2tfOKoDihInaoPsV6cQPIh70DeHnpRMOqYqAadkSXPsuroAGQbagebjGKpyFA";
    //const CRM_HOST = "9b6a502b-12be-4fd0-921f-c05e9278ead7.kubtri.com";
    //const CRM_HOST = "213.221.5.30";
    const CRM_HOST = "crm.local.rentnow.ru";
    const CRM_PROTOCOL = "https";
    const CRM_URL = "/";
    //const CRM_URL = "/cbre-test/";
    //end main crm
    const BUILDINGS_MODULE = "Buildings";
    const ROOMS_MODULE = "Rooms";
    const PARKINGS_MODULE = "Parkings";
    const ACTION_LIST_BUILDINGS = "list";
    const ACTION_LIST_ROOMS = "list";
    const ACTION_LIST_PARKINGS = "list";
    const ACTION_GET_BUILDINGS = "get";
    const ACTION_GET_ROOMS = "get";
    const ACTION_GET_PARKINGS = "get";
    const USERS_MODULE = "Users";
    const ACTION_GET_USERS = "get";
    const IMAGES_MODULE = "Images";
    const ACTION_GET_IMAGES = "get";
    const USERS_IMAGES_MODULE = "Photos";
    const ACTION_GET_USERS_IMAGES = "get";
    const DOCUMENTS_MODULE = "Documents";
    const ACTION_GET_DOCUMENTS = "get";
    const DELETED_PARAM = "deleted";
    const QUERY_LIST_SIZE = 30;
    const USER_ID_PROPERTY = "ID";

     protected $httpClient;
     protected $defaultQueryParams;
     protected $crmUrl;
     protected $possibleListActions;
     protected $mimeTypes = array(
        'txt' => 'text/plain',
        'htm' => 'text/html',
        'html' => 'text/html',
        'php' => 'text/html',
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'xml' => 'application/xml',
        'swf' => 'application/x-shockwave-flash',
        'flv' => 'video/x-flv',

        // images
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'bmp' => 'image/bmp',
        'bmp' => 'image/x-ms-bmp',
        'ico' => 'image/vnd.microsoft.icon',
        'tiff' => 'image/tiff',
        'tif' => 'image/tiff',
        'svg' => 'image/svg+xml',
        'svgz' => 'image/svg+xml',

        // archives
        'zip' => 'application/zip',
        'rar' => 'application/x-rar-compressed',
        'exe' => 'application/x-msdownload',
        'msi' => 'application/x-msdownload',
        'cab' => 'application/vnd.ms-cab-compressed',

        // audio/video
        'mp3' => 'audio/mpeg',
        'qt' => 'video/quicktime',
        'mov' => 'video/quicktime',

        // adobe
        'pdf' => 'application/pdf',
        'psd' => 'image/vnd.adobe.photoshop',
        'ai' => 'application/postscript',
        'eps' => 'application/postscript',
        'ps' => 'application/postscript',

        // ms office
        'doc' => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'rtf' => 'application/rtf',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.openxmlformats-officedocument.pres',

        // open office
        'odt' => 'application/vnd.oasis.opendocument.text',
        'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
    );

    public function __construct()
    {
        $this->crmUrl = 
            static::CRM_PROTOCOL
            . "://"
            . static::CRM_HOST
            . static::CRM_URL;

        $this->httpClient = new UrlGetClass();

        $this->defaultQueryParams = array(
            'key' => static::API_KEY,
            'entryPoint' => static::ENTRY_POINT,
        );

        $this->possibleListActions = array_unique(
            array(static::ACTION_LIST_BUILDINGS, static::ACTION_LIST_ROOMS, static::ACTION_LIST_PARKINGS)
        );

    }

    public function getAllOffices($date, $deleted = false)
    {
        MessageHelper::addMessageToLog("CrmClass: start get all offices");
        if (empty($date) === true) {
            $date = date(
                "Y-m-d H:i:s",
                strtotime("01.01.2017")
            );
        }

        $listBuildingsQuery = array(
            'module' => static::BUILDINGS_MODULE,
            'action' => static::ACTION_LIST_BUILDINGS,
            'id' => $date,
        );
        $getBuildingsQuery = array(
            'module' =>  static::BUILDINGS_MODULE,
            'action' => static::ACTION_GET_BUILDINGS,
        );

        if ($deleted === true) {
            $listBuildingsQuery[static::DELETED_PARAM] = 1;
        }

        return $this->getItemsForListQuery($listBuildingsQuery, $getBuildingsQuery);
    }

    public function getAllRooms($date, $deleted = false)
    {
        MessageHelper::addMessageToLog("CrmClass: start get all rooms");
        if (empty($date) === true) {
            $date = date(
                "Y-m-d H:i:s",
                strtotime("01.01.2017")
            );
        }

        $listRoomsQuery = array(
            'module' => static::ROOMS_MODULE,
            'action' => static::ACTION_LIST_ROOMS,
            'id' => $date,
        );
        $getRoomsQuery = array(
            'module' => static::ROOMS_MODULE,
            'action' => static::ACTION_GET_ROOMS,
        );

        if ($deleted === true) {
            $listRoomsQuery[static::DELETED_PARAM] = 1;
        }

        return $this->getItemsForListQuery($listRoomsQuery, $getRoomsQuery, true);
    }

    public function getAllParkings($date, $deleted = false)
    {
        MessageHelper::addMessageToLog("CrmClass: start get all parkings");
        if (empty($date) === true) {
            $date = date(
                "Y-m-d H:i:s",
                strtotime("01.01.2017")
            );
        }
        
        $listParkingsQuery = array(
            'module' => static::PARKINGS_MODULE,
            'action' => static::ACTION_LIST_PARKINGS,
            'id' => $date,
        );
        $getParkingsQuery = array(
            'module' => static::PARKINGS_MODULE,
            'action' => static::ACTION_GET_PARKINGS,
        );

        if ($deleted === true) {
            $listParkingsQuery[static::DELETED_PARAM] = 1;
        }

        return $this->getItemsForListQuery($listParkingsQuery, $getParkingsQuery, true);
    }

    public function getRoomsByIds($roomsIds)
    {
        if (empty($roomsIds) === true) {
            return array();
        }

        $getRoomsQuery = array(
            'module' => static::ROOMS_MODULE,
            'action' => static::ACTION_GET_ROOMS,
        );

        return $this->getItemsForGetQuery($roomsIds, $getRoomsQuery);
    }

    public function getParkingsByIds($parkingsIds)
    {
        if (empty($parkingsIds) === true) {
            return array();
        }

        $getParkingsQuery = array(
            'module' => static::PARKINGS_MODULE,
            'action' => static::ACTION_GET_PARKINGS,
        );

        return $this->getItemsForGetQuery($parkingsIds, $getParkingsQuery);
    }

    public function getUsersByIds($usersIds)
    {
        if (empty($usersIds) === true) {
            return array();
        }

        $getUsersQuery = array(
            'module' => static::USERS_MODULE,
            'action' => static::ACTION_GET_USERS,
        );

        $usersData = $this->getItemsForGetQuery($usersIds, $getUsersQuery);

        foreach ($usersData as $userCount => $userData) {
            if (empty($userData['CONTACT_FOLDER']) === false) {
                $usersData[$userCount]['IMAGE'] = $this->getUserImages($userData[static::USER_ID_PROPERTY]);
            }
        }

        return $usersData;
    }

    public function getUserImages($userContactFolder) 
    {
        if (empty($userContactFolder) === true) {
            return "";
        }

        $getUserImageQuery = array(
            'module' => static::USERS_IMAGES_MODULE,
            'action' => static::ACTION_GET_USERS_IMAGES,
        );

        return $this->getFileById($userContactFolder, $getUserImageQuery);
    }

    public function getImagesByIds($imagesIds)
    {
        if (empty($imagesIds) === true) {
            return array();
        }

        $getImagesQuery = array(
            'module' => static::IMAGES_MODULE,
            'action' => static::ACTION_GET_IMAGES,
        );

        $imagesArray = array();
        foreach ($imagesIds as $imageId) {
            $imagesArray[] = $this->getFileById($imageId, $getImagesQuery);
        }

        return $imagesArray;
    }

    public function getDocumentsByIds($documentsIds)
    {
        if (empty($documentsIds) === true) {
            return array();
        }

        $getDocumentsQuery = array(
            'module' => static::DOCUMENTS_MODULE,
            'action' => static::ACTION_GET_DOCUMENTS,
        );

        $documentsArray = array();
        foreach ($documentsIds as $documentId) {
            $documentsArray[] = $this->getFileById($documentId, $getDocumentsQuery);
        }

        return $documentsArray;
    }

    protected function getFileById($fileId, $getParams)
    {
        if (
            empty($fileId) === true
            || empty($getParams) === true
        ) {
            return false;
        }

        $queryParams = $this->buildQueryParams(
            array_merge(
                $getParams,
                array(
                    'id' => $fileId
                )
            )
        );
        $queryUrl = $this->buildQueryUrl($queryParams);
        $file = $this->httpClient->getFile($queryUrl);

        if ($file['response'] == "200" && empty($file['content']) === false) {
            
            $file['extension'] = $this->getFileExtensionByMimeType($file['type']);
            $file['name'] = $fileId;
        } else {
            $responseCode = $this->httpClient->getHttpCode();
            $dateResponse = date("Y-m-d H:i:s");
            $handle = fopen($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/log/errorCrm.txt", "a+");
            $dataResponse = $dateResponse.'; '.$responseCode.'; '.$queryUrl;
            fwrite($handle, $dataResponse . "\n");
            fclose($handle);
            $headerMailResponse = "Content-type: text/plain; charset=\"utf-8\"";
            mail("dolgopolov@it-agency.ru", "Импорт на cbre.rentnow.ru завершится некорректно из-за недоступности crm", $dataResponse, $headerMailResponse);
        }

        return $file;
    }

    protected function getItemIdsList($queryParams)
    {
        if (
            empty($queryParams['action']) === true
            || in_array($queryParams['action'], $this->possibleListActions) === false
        ) {
            MessageHelper::addMessageToLog("CrmClass: query params doesn't contain list action for import ids");
            return false;
        }

        $queryParams = $this->buildQueryParams($queryParams);
        MessageHelper::addMessageToLog("CrmClass: start get item ids with params " . var_export($queryParams, true));
        $queryUrl = $this->buildQueryUrl($queryParams);

        $itemIdsListJson = $this->httpClient->get($queryUrl);

        //check response
        $responseCode = $this->httpClient->getHttpCode();
        $dateResponse = date("Y-m-d H:i:s");
        if ($responseCode == "200") {
            $handle = fopen($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/log/successCrm.txt", "a+");
            $dataResponse = $dateResponse.'; '.$responseCode.'; '.$queryUrl;
            fwrite($handle, $dataResponse . "\n");
            fclose($handle);
        } else {
            $handle = fopen($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/log/errorCrm.txt", "a+");
            $dataResponse = $dateResponse.'; '.$responseCode.'; '.$queryUrl;
            fwrite($handle, $dataResponse . "\n");
            fclose($handle);
            $headerMailResponse = "Content-type: text/plain; charset=\"utf-8\"";
            mail("dolgopolov@it-agency.ru", "Импорт на cbre.rentnow.ru завершится некорректно из-за недоступности crm", $dataResponse, $headerMailResponse);
        }

        //MessageHelper::addMessageToLog("CrmClass: data json " . $itemIdsListJson);
        try {
            $itemIdsList = Json::decode($itemIdsListJson);
        } catch (ArgumentException $exception) {
            $itemIdsList = array();
            MessageHelper::addMessageToLog("Crm class: not valid json for url  {$queryUrl}");
        }

        return $itemIdsList;
    }

    protected function getItemsList($itemIds, $getParamsWithoutId)
    {
        if (
            empty($itemIds) === true
            || empty($getParamsWithoutId) === true
        ) {
            return array();
        }

        try{
            $itemIdsData = Json::encode($itemIds);
        } catch (ArgumentException $exception) {
            return array();
        }

        $fullGetParams = $this->buildQueryParams(
            array_merge(
                $getParamsWithoutId,
                array(
                    'id' => $itemIdsData,
                )
            )
        );
        $queryUrl = $this->buildQueryUrl($fullGetParams);

        $handle = fopen("/home/bitrix/www/bitrix/php_interface/log/resource.txt", "a+");
        fwrite($handle, $queryUrl . "\n");
        fclose($handle);

        $itemsListDataJson = $this->httpClient->get($queryUrl);

        //check response
        $responseCode = $this->httpClient->getHttpCode();
        $dateResponse = date("Y-m-d H:i:s");
        if ($responseCode == "200") {
            $handle = fopen($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/log/successCrm.txt", "a+");
            $dataResponse = $dateResponse.'; '.$responseCode.'; '.$queryUrl;
            fwrite($handle, $dataResponse . "\n");
            fclose($handle);
        } else {
            $handle = fopen($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/log/errorCrm.txt", "a+");
            $dataResponse = $dateResponse.'; '.$responseCode.'; '.$queryUrl;
            fwrite($handle, $dataResponse . "\n");
            fclose($handle);
            $headerMailResponse = "Content-type: text/plain; charset=\"utf-8\"";
            mail("dolgopolov@it-agency.ru", "Импорт на cbre.rentnow.ru завершится некорректно из-за недоступности crm", $dataResponse, $headerMailResponse);
        }

        try {
            $itemsListData = Json::decode($itemsListDataJson);
        } catch (ArgumentException $exception) {
            $itemsListData = array();
            MessageHelper::addMessageToLog("CrmClass: not valid json for url " . $queryUrl);
        }

        return $itemsListData;
    }

    protected function getIdsFromResult($resultIds)
    {
        if (
            empty($resultIds) === true
            || is_array($resultIds) === false
        ) {
            return array();
        }

        $onlyIdsFromResult = array();
        foreach ($resultIds as $resultData) {
            if (empty($resultData['id']) === false) {
                $onlyIdsFromResult[] = $resultData['id'];
            }
        }

        return $onlyIdsFromResult;
    }

    protected function getItemsForListQuery($listParams, $getParamsWithoutId, $getIdsFromResult = false)
    {
        if (empty($listParams) === true) {
            MessageHelper::addMessageToLog("CrmClass: get items for empty query");
            return array();
        }

        MessageHelper::addMessageToLog("Crm class: get items for query " . var_export($listParams, true));
        $itemIdsList = $this->getItemIdsList($listParams);

        if (empty($itemIdsList) === true) {
            MessageHelper::addMessageToLog("Crm Class: no items found for query " . var_export($listParams, true));
        }

        if (empty($listParams[static::DELETED_PARAM]) === false) {
            return $itemIdsList;
        }

        if ($getIdsFromResult === true) {
            $itemIdsList = $this->getIdsFromResult($itemIdsList);
        }

        $itemsData = $this->getItemsForGetQuery($itemIdsList, $getParamsWithoutId);

        return $itemsData;
    }

    protected function getItemsForGetQuery($itemIdsList, $getParamsWithoutId)
    {
        if (empty($getParamsWithoutId) === true) {
            return array();
        }

        $itemsData = array();
        $itemIdsPartedList = array_chunk($itemIdsList, static::QUERY_LIST_SIZE);

        $count = 0;
        foreach ($itemIdsPartedList as $itemIdsPart) {
            $itemsPartData = $this->getItemsList($itemIdsPart, $getParamsWithoutId);

            if (empty($itemsPartData) === false) {
                $itemsData = array_merge($itemsData, $itemsPartData);
            }

            $count++;
            //if ($count == 5) {
                //break;
            //}
        }

        return $itemsData;
    }

    protected function buildQueryParams($queryParams)
    {
        if (empty($queryParams) === true) {
            return $this->defaultQueryParams;
        }

        $queryParams = array_merge(
            $this->defaultQueryParams,
            $queryParams
        );

        $queryParams = array_merge($this->defaultQueryParams, $queryParams);

        return $queryParams;
    }

    protected function buildQueryUrl($queryParams)
    {
        if (empty($queryParams) === true) {
            return $this->crmUrl;
        }

        $url = (
            is_array($queryParams) === true
            ? $this->crmUrl . "?" . http_build_query($queryParams)
            : $this->crmUrl . "?" . $queryParams
        );

        return $url;
    }

    protected function getFileExtensionByMimeType($mimeType)
    {
        if (empty($mimeType) === true) {
            return "";
        }

        $extension = 'jpg';
        if (in_array($mimeType, $this->mimeTypes) === true) {
            $extension = array_search($mimeType, $this->mimeTypes);
        } else if (array_key_exists($mimeType, $this->mimeTypes) === true) {
            $extension = $mimeType;
        } else {
            MessageHelper::addMessageToLog("Crm class: can't determine expetion for mime type " . $mimeType);
        }

        return $extension;
    }
}
?>
