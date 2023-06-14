<?php

namespace Sprint\Migration;


class IBLOCK_case_studies_fix_type20230109170449 extends Version
{
    protected $description = "Убрать землю и гостиницы";

    protected $moduleVersion = "4.1.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $iblockId = $helper->Iblock()->getIblockIdIfExists('case_studies', 'about');
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Тип проекта',
  'ACTIVE' => 'Y',
  'SORT' => '800',
  'CODE' => 'TYPE',
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
  'IS_REQUIRED' => 'Y',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
  'HINT' => '',
  'VALUES' => 
  array (
    0 => 
    array (
      'VALUE' => 'Офисы',
      'DEF' => 'Y',
      'SORT' => '100',
      'XML_ID' => 'office',
    ),
    1 => 
    array (
      'VALUE' => 'Торговая недвижимость',
      'DEF' => 'N',
      'SORT' => '200',
      'XML_ID' => 'sale',
    ),
    2 => 
    array (
      'VALUE' => 'Склады',
      'DEF' => 'N',
      'SORT' => '300',
      'XML_ID' => 'store',
    ),
    3 => 
    array (
      'VALUE' => 'Другое',
      'DEF' => 'N',
      'SORT' => '600',
      'XML_ID' => 'other',
    ),
  ),
));
    
    }

    public function down()
    {
        //your code ...
    }
}
