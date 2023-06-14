<?php
die();
use \Bitrix\Main\Config\Option;

ini_set("short_open_tag", 1);
$_SERVER["DOCUMENT_ROOT"] = "/home/bitrix/www";
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/realty/include.php");
ini_set('max_execution_time', 0);

//test images sort
/*
    url https://cbre.rentnow.ru/estate/office/burevestnik_bts/
    crm_id 1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1
    id 1352
    data crm https://9b6a502b-12be-4fd0-921f-c05e9278ead7.kubtri.com/?key=uchgLdYCbSS2tfOKoDihInaoPsV6cQPIh70DeHnpRMOqYqAadkSXPsuroAGQbagebjGKpyFA&entryPoint=rentnow_export&module=Buildings&action=get&id=%5B%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221b7042ee-0f4b-11e7-b27b-b8f6b1140ab1%22%7D%5D
    images "IMAGES":"919a6a04-75fe-819e-f829-5b339c7d87f9,aeb102d1-8bfd-effe-c1a9-5b339cff3ef6,779c32f0-671d-6004-4de1-5b339cafc961,94d6936e-712b-d55d-f7bb-5b339cb4577c,45c5cc75-c0cc-9fbf-3f75-5b339c5ca1c6,eb53d903-4b27-3fc5-fe3e-5b339cba8d80,842fc62e-de4e-72c1-4daf-5b339c62c1bb,a9a49dba-d34d-9215-cc61-5b339c117bb7,143da201-e197-baa0-6c2f-5b339c9d4a8b,3cfaad57-8863-c260-6fca-5b339c7f3f4a,c1a0e58b-0a1a-5c58-0551-5b339cb5806e,154b216d-9c85-e0da-fe82-5b339c2bbcaf,8fe5af6e-73b8-a8bd-7a21-5b339c6e8383,1b260e4c-7cde-dee6-b615-5b339c7de05a,d8911cd2-955a-dbd0-9a96-5b339c3a1a9c,98f970f5-1529-9bfe-75e3-5b339c7d367f"

    Array
    (
        [0] => Array
            (
                [id] => 110136
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/3cfaad57-8863-c260-6fca-5b339c7f3f4a.jpg
                [order] => 10
                [main] => 0
            )

        [1] => Array
            (
                [id] => 110137
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/c1a0e58b-0a1a-5c58-0551-5b339cb5806e.jpg
                [order] => 20
                [main] => 1
            )

        [2] => Array
            (
                [id] => 110138
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/154b216d-9c85-e0da-fe82-5b339c2bbcaf.jpg
                [order] => 30
                [main] => 0
            )

        [3] => Array
            (
                [id] => 110139
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/8fe5af6e-73b8-a8bd-7a21-5b339c6e8383.jpg
                [order] => 40
                [main] => 0
            )

        [4] => Array
            (
                [id] => 110140
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/1b260e4c-7cde-dee6-b615-5b339c7de05a.jpg
                [order] => 50
                [main] => 0
            )

        [5] => Array
            (
                [id] => 110141
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/d8911cd2-955a-dbd0-9a96-5b339c3a1a9c.jpg
                [order] => 60
                [main] => 0
            )

        [6] => Array
            (
                [id] => 110142
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/98f970f5-1529-9bfe-75e3-5b339c7d367f.jpg
                [order] => 70
                [main] => 0
            )

        [7] => Array
            (
                [id] => 110764
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/919a6a04-75fe-819e-f829-5b339c7d87f9.jpg
                [order] => 80
                [main] => 0
            )

        [8] => Array
            (
                [id] => 110765
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/779c32f0-671d-6004-4de1-5b339cafc961.jpg
                [order] => 90
                [main] => 0
            )

        [9] => Array
            (
                [id] => 110766
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/94d6936e-712b-d55d-f7bb-5b339cb4577c.jpg
                [order] => 100
                [main] => 0
            )

        [10] => Array
            (
                [id] => 110767
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/aeb102d1-8bfd-effe-c1a9-5b339cff3ef6.jpg
                [order] => 110
                [main] => 0
            )

        [11] => Array
            (
                [id] => 110768
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/45c5cc75-c0cc-9fbf-3f75-5b339c5ca1c6.jpg
                [order] => 120
                [main] => 0
            )

        [12] => Array
            (
                [id] => 110769
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/eb53d903-4b27-3fc5-fe3e-5b339cba8d80.jpg
                [order] => 127
                [main] => 0
            )

        [13] => Array
            (
                [id] => 110770
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/842fc62e-de4e-72c1-4daf-5b339c62c1bb.jpg
                [order] => 127
                [main] => 0
            )

        [14] => Array
            (
                [id] => 110772
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/143da201-e197-baa0-6c2f-5b339c9d4a8b.jpg
                [order] => 127
                [main] => 0
            )

        [15] => Array
            (
                [id] => 111722
                [object_id] => 1352
                [file] => /property/1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1/a9a49dba-d34d-9215-cc61-5b339c117bb7.jpg
                [order] => 127
                [main] => 0
            )

    )

*/
$crmApi = new CrmClass();
$propertyHelper = new BuildingPropertiesHelper($crmApi);
$picturesHelper = new PicturesHelper($propertyHelper);

$picturesHelper->fillBuildingImagesTest(1352, '1b7042ee-0f4b-11e7-b27b-b8f6b1140ab1', "919a6a04-75fe-819e-f829-5b339c7d87f9,aeb102d1-8bfd-effe-c1a9-5b339cff3ef6,779c32f0-671d-6004-4de1-5b339cafc961,94d6936e-712b-d55d-f7bb-5b339cb4577c,45c5cc75-c0cc-9fbf-3f75-5b339c5ca1c6,eb53d903-4b27-3fc5-fe3e-5b339cba8d80,842fc62e-de4e-72c1-4daf-5b339c62c1bb,a9a49dba-d34d-9215-cc61-5b339c117bb7,143da201-e197-baa0-6c2f-5b339c9d4a8b,3cfaad57-8863-c260-6fca-5b339c7f3f4a,c1a0e58b-0a1a-5c58-0551-5b339cb5806e,154b216d-9c85-e0da-fe82-5b339c2bbcaf,8fe5af6e-73b8-a8bd-7a21-5b339c6e8383,1b260e4c-7cde-dee6-b615-5b339c7de05a,d8911cd2-955a-dbd0-9a96-5b339c3a1a9c,98f970f5-1529-9bfe-75e3-5b339c7d367f");

//test curl
/*$httpClient = new UrlGetClass();

$file = $httpClient->getFile('https://9b6a502b-12be-4fd0-921f-c05e9278ead7.kubtri.com/?key=uchgLdYCbSS2tfOKoDihInaoPsV6cQPIh70DeHnpRMOqYqAadkSXPsuroAGQbagebjGKpyFA&entryPoint=rentnow_export&module=Buildings&action=get&id=%5B%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221a50ec92-0f4b-11e7-b866-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221a7b7570-0f4b-11e7-ae45-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221a8dbfc7-d8ee-ad71-5fd7-5b4741b6c026%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221a9b2fc8-0f4b-11e7-8ba7-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221afbbe1a-0f4b-11e7-a4b1-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221b0ca728-0f4b-11e7-a416-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221b1c90cc-0f4b-11e7-8336-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221b5f2f36-0f4b-11e7-9ffb-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221b7042ee-0f4b-11e7-b27b-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221b80ca10-0f4b-11e7-8658-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221b838b46-4414-6e9f-913f-5ba0ffc1de98%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221b9be4c6-0f4b-11e7-8439-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221bb6a194-0f4b-11e7-986a-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221bf22306-0f4b-11e7-9c62-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221bfc884c-0f4b-11e7-ac8d-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221c0ed3fa-0f4b-11e7-90d8-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221c1fcb9c-0f4b-11e7-80db-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221c2ff094-0f4b-11e7-839a-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221c35327f-bcba-711a-e841-5f1e8fb6ba18%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221c39af46-0f4b-11e7-8542-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221c914934-0f4b-11e7-9afe-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221c9b3174-0f4b-11e7-8c7c-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221ca50ab4-0f4b-11e7-82e0-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221cac50da-0f4b-11e7-a616-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221cbf3a4c-0f4b-11e7-a786-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221ce0e306-0f4b-11e7-9531-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221cf062de-0f4b-11e7-aa11-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221d13de3a-0f4b-11e7-86da-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221d1dad28-0f4b-11e7-b234-b8f6b1140ab1%22%7D%2C%7B%22module%22%3A%22BuildingOffices%22%2C%22id%22%3A%221d53c522-0f4b-11e7-b075-b8f6b1140ab1%22%7D%5D');

print_r($file);*/