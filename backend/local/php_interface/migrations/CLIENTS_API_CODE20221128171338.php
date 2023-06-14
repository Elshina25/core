<?php

namespace Sprint\Migration;


class CLIENTS_API_CODE20221128171338 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
		$iblockIdClients = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'clients']
		])->fetch()["ID"];
		\Bitrix\Iblock\IblockTable::update($iblockIdClients, [
			'API_CODE' => 'clients'
		]);
    }

    public function down()
    {
        //your code ...
    }
}
