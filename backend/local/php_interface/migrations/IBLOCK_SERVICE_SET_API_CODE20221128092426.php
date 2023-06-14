<?php

namespace Sprint\Migration;


class IBLOCK_SERVICE_SET_API_CODE20221128092426 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
		$iblockIdService = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'services']
		])->fetch()["ID"];
		\Bitrix\Iblock\IblockTable::update($iblockIdService, [
			'API_CODE' => 'services'
		]);
    }

    public function down()
    {
        //your code ...
    }
}
