<?php

namespace Sprint\Migration;


class ADD_PROPERTY_SHOW_ON_ALL_SERVICE_IN_DONE_PROJECTS_IBLOCK20221128151830 extends Version
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
        $iblockId = $helper->Iblock()->getIblockIdIfExists('case_studies', 'about');
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Показывать на общей странице услуг',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'SHOW_ON_ALL_SERVICE',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'L',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'C',
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
      'VALUE' => 'Да',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => 'true',
    ),
  ),
));
    
    }

    public function down()
    {
        //your code ...
    }
}
