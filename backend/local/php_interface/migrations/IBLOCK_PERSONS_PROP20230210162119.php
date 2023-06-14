<?php

namespace Sprint\Migration;


class IBLOCK_PERSONS_PROP20230210162119 extends Version
{
    protected $description = "Поле \"Опыт и история\" для ИБ сотрудников";

    protected $moduleVersion = "4.1.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $iblockId = $helper->Iblock()->getIblockIdIfExists('persons', 'persons');
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Опыт и история',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'EXPERIENCE_HISTORY',
  'DEFAULT_VALUE' => 
  array (
    'TEXT' => '',
    'TYPE' => 'HTML',
  ),
  'PROPERTY_TYPE' => 'S',
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
  'USER_TYPE' => 'HTML',
  'USER_TYPE_SETTINGS' => 
  array (
    'height' => 200,
  ),
  'HINT' => '',
));
            $helper->UserOptions()->saveElementForm($iblockId, array (
  'Элемент|edit1' => 
  array (
    'ACTIVE' => 'Активность',
    'NAME' => 'Фамилия Имя',
    'PROPERTY_P_DEPART' => 'Отдел',
    'PROPERTY_P_POST' => 'Должность',
    'PROPERTY_P_PHONE' => 'Телефон',
    'PROPERTY_P_EMAIL' => 'E-mail',
    'PROPERTY_TELEGRAM' => 'Telegram',
    'PROPERTY_VIBER' => 'Viber',
    'PROPERTY_WHATSAPP' => 'WhatsApp',
    'PROPERTY_P_NEWS' => 'Показывать в новостях',
    'PROPERTY_P_ANALYTICS' => 'Показывать в аналитике',
    'PROPERTY_P_SERVICES' => 'Показывать в услугах',
    'PROPERTY_P_CAREER' => 'Показывать в карьере',
    'PROPERTY_P_DIR' => 'Раздел услуг',
    'PROPERTY_P_ABOUT_COMP' => 'Выводить на странице "О компании"',
    'PROPERTY_POST_FULL' => 'Должность полностью',
    'PROPERTY_EXPERIENCE_HISTORY' => 'Опыт и история',
  ),
  'Анонс|edit5' => 
  array (
    'PREVIEW_PICTURE' => 'Фото',
  ),
  'Дополнительно|edit3' => 
  array (
    'SORT' => 'Сортировка',
  ),
));
        $helper->UserOptions()->saveElementList($iblockId, array (
  'page_size' => '20',
  'order' => 'asc',
  'by' => 'sort',
  'columns' => 
  array (
    0 => 'NAME',
    1 => 'PROPERTY_P_DEPART',
    2 => 'PROPERTY_P_POST',
    3 => 'PREVIEW_PICTURE',
    4 => 'PROPERTY_P_NEWS',
    5 => 'PROPERTY_P_ANALYTICS',
    6 => 'PROPERTY_P_SERVICES',
    7 => 'PROPERTY_P_CAREER',
    8 => 'PROPERTY_P_DIR',
  ),
));
    $helper->UserOptions()->saveElementGrid($iblockId, array (
  'views' => 
  array (
    'default' => 
    array (
      'columns' => 
      array (
        0 => 'TIMESTAMP_X',
        1 => 'ID',
        2 => 'SORT',
        3 => 'ACTIVE',
        4 => 'PROPERTY_P_ABOUT_COMP',
        5 => 'NAME',
      ),
      'columns_sizes' => 
      array (
        'expand' => 1,
        'columns' => 
        array (
        ),
      ),
      'sticked_columns' => 
      array (
      ),
      'page_size' => 200,
      'last_sort_by' => 'timestamp_x',
      'last_sort_order' => 'desc',
      'custom_names' => NULL,
    ),
  ),
  'filters' => 
  array (
  ),
  'current_view' => 'default',
));

    }

    public function down()
    {
        //your code ...
    }
}
