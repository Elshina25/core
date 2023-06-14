<?php

namespace Sprint\Migration;


class IBLOCK_SERVICE_PROPS_AND_FIELDS20221125170143 extends Version
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
        $helper->Iblock()->saveIblockType(array (
  'ID' => 'services',
  'SECTIONS' => 'Y',
  'EDIT_FILE_BEFORE' => '',
  'EDIT_FILE_AFTER' => '',
  'IN_RSS' => 'N',
  'SORT' => '30',
  'LANG' => 
  array (
    'ru' => 
    array (
      'NAME' => 'Услуги',
      'SECTION_NAME' => 'Раздел',
      'ELEMENT_NAME' => 'Элемент',
    ),
    'en' => 
    array (
      'NAME' => 'Services',
      'SECTION_NAME' => 'Subject',
      'ELEMENT_NAME' => 'Element',
    ),
  ),
));
        $iblockId = $helper->Iblock()->getIblockIdIfExists('services', 'services');
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Для кого услуги',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'FOR_WHOM_SERVICES',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'L',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'Y',
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
      'VALUE' => 'Арендаторам',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '470a761a8e60069c55567fca05af39c0',
    ),
    1 => 
    array (
      'VALUE' => 'Инвесторам',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '7b79ab7817895fb874cba4f8b7516603',
    ),
    2 => 
    array (
      'VALUE' => 'Собственникам',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '72d8c1c81d3cf68efb2734fd52486ce9',
    ),
  ),
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Тип',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'TYPE',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'L',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'Y',
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
      'VALUE' => 'Гостиницы',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '6821c16614df17fa91cc38df72c51f33',
    ),
    1 => 
    array (
      'VALUE' => 'Земля',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '291780519c5f0aab64f448dac989a0cb',
    ),
    2 => 
    array (
      'VALUE' => 'Иная',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '4518a69caacd8533200dd5d928c6c359',
    ),
    3 => 
    array (
      'VALUE' => 'Офисы',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '390d4c1710f11dd74573b218041ba4fb',
    ),
    4 => 
    array (
      'VALUE' => 'Склады',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '94735e76cbe78764e9b275365b959c1b',
    ),
    5 => 
    array (
      'VALUE' => 'Торговая',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => '19d64f515572ffb05faed4329ceab275',
    ),
  ),
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Сотрудники',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'WORKERS',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'E',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'Y',
  'XML_ID' => NULL,
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => 'persons:persons',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'N',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
));
    
    }

    public function down()
    {
        //your code ...
    }
}
