<?php

namespace Sprint\Migration;


class BLOG_API_CODE20221201153148 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    public function up()
    {
        \Bitrix\Main\Loader::includeModule('iblock');
		$iblockIdBlog = \Bitrix\Iblock\IblockTable::getList([
			'filter' => ["CODE" =>'blog']
		])->fetch()["ID"];
		\Bitrix\Iblock\IblockTable::update($iblockIdBlog, [
			'API_CODE' => 'blog'
		]);
    }

    public function down()
    {
        //your code ...
    }
}
