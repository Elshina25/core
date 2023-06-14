<?php

namespace Sprint\Migration;


class BLOG_PROPERTIES_20221201172230 extends Version
{
    protected $description = "";

    protected $moduleVersion = "4.1.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $iblockId = $helper->Iblock()->getIblockIdIfExists('blog', 'blog');
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Типы недвижимости',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'IMMOVABLES',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'L',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => NULL,
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
  'VALUES' => 
  array (
    0 => 
    array (
      'VALUE' => 'Иная недвижимость',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '512171e973bd219518e2a30987362a71',
    ),
    1 => 
    array (
      'VALUE' => 'Коворкинги',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '71b2c42950d9c19087c221a9f9d21db7',
    ),
    2 => 
    array (
      'VALUE' => 'Офисы',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '9e85046a96c5cafc694fb71bdad39edc',
    ),
    3 => 
    array (
      'VALUE' => 'Склады',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '9b38cec399575ba35f329e2f190eaf3e',
    ),
    4 => 
    array (
      'VALUE' => 'Торговая',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '4434a43960640d609ab8bf2197a59b0d',
    ),
  ),
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Типы предложения',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'OFFERS',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'L',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => NULL,
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => '0',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
  'VALUES' => 
  array (
    0 => 
    array (
      'VALUE' => 'Аренда',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => 'ea15179498f0ea24602cabd91cfc972b',
    ),
    1 => 
    array (
      'VALUE' => 'Инвестиции',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '90f49dcf7d68b2b3391b5752f4d1308a',
    ),
    2 => 
    array (
      'VALUE' => 'Покупка',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '31461a819acbb8c53aefb18e0153080f',
    ),
    3 => 
    array (
      'VALUE' => 'Продажа',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '3bd7fc063022158f1e85e8dccae01b21',
    ),
  ),
));
    
    }

    public function down()
    {
        //your code ...
    }
}
