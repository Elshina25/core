<?php

namespace Sprint\Migration;


class ADD_AWARDS_PROPERTY_TAGS20221129122548 extends Version
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
        $iblockId = $helper->Iblock()->getIblockIdIfExists('awards', 'about');
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Теги',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'TAGS',
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
      'VALUE' => 'Аренда',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => 'fae4e01574ca9c72844afb19e1018761',
    ),
    1 => 
    array (
      'VALUE' => 'Сделка года',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => 'c055a02d24eb2cfb46ed9dfb2c143a43',
    ),
    2 => 
    array (
      'VALUE' => 'Торговая недвижиость',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => 'a0eb93d420ab02eb315ac537b35b4eab',
    ),
  ),
));
    
    }

    public function down()
    {
        //your code ...
    }
}
