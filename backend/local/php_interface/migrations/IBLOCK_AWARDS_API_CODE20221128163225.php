<?php

namespace Sprint\Migration;


class IBLOCK_AWARDS_API_CODE20221128163225 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
		$iblockIdAwards = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'awards']
		])->fetch()["ID"];
		\Bitrix\Iblock\IblockTable::update($iblockIdAwards, [
			'API_CODE' => 'awards'
		]);
    }

    public function down()
    {
        //your code ...
    }
}
