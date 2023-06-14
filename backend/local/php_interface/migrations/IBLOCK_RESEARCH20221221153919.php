<?php

namespace Sprint\Migration;


class IBLOCK_RESEARCH20221221153919 extends Version
{
    protected $description = "Настройки инфоблока исследования (с фактоидами)";

    protected $moduleVersion = "4.1.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $iblockId = $helper->Iblock()->saveIblock(array (
  'IBLOCK_TYPE_ID' => 'analytics',
  'LID' => 
  array (
    0 => 's1',
  ),
  'CODE' => 'analitics',
  'API_CODE' => 'research',
  'NAME' => 'Аналитика',
  'ACTIVE' => 'Y',
  'SORT' => '10',
  'LIST_PAGE_URL' => '/analytics/',
  'DETAIL_PAGE_URL' => '/analytics/#CODE#',
  'SECTION_PAGE_URL' => '/analytics/#SECTION_CODE#/',
  'PICTURE' => NULL,
  'DESCRIPTION' => '',
  'DESCRIPTION_TYPE' => 'text',
  'RSS_TTL' => '24',
  'RSS_ACTIVE' => 'Y',
  'RSS_FILE_ACTIVE' => 'N',
  'RSS_FILE_LIMIT' => NULL,
  'RSS_FILE_DAYS' => NULL,
  'RSS_YANDEX_ACTIVE' => 'N',
  'XML_ID' => NULL,
  'INDEX_ELEMENT' => 'Y',
  'INDEX_SECTION' => 'N',
  'WORKFLOW' => 'N',
  'BIZPROC' => 'N',
  'SECTION_CHOOSER' => 'L',
  'LIST_MODE' => '',
  'RIGHTS_MODE' => 'S',
  'SECTION_PROPERTY' => 'N',
  'VERSION' => '1',
  'LAST_CONV_ELEMENT' => '0',
  'SOCNET_GROUP_ID' => NULL,
  'EDIT_FILE_BEFORE' => '',
  'EDIT_FILE_AFTER' => '',
  'SECTIONS_NAME' => 'Темы',
  'SECTION_NAME' => 'Тема',
  'ELEMENTS_NAME' => 'Документы',
  'ELEMENT_NAME' => 'Документ',
  'PROPERTY_INDEX' => 'N',
  'CANONICAL_PAGE_URL' => '',
  'REST_ON' => 'N',
  'EXTERNAL_ID' => NULL,
  'LANG_DIR' => '/',
  'SERVER_NAME' => 'rentnow.ru',
  'IPROPERTY_TEMPLATES' => 
  array (
  ),
  'ELEMENT_ADD' => 'Добавить документ',
  'ELEMENT_EDIT' => 'Изменить документ',
  'ELEMENT_DELETE' => 'Удалить документ',
  'SECTION_ADD' => 'Добавить тему',
  'SECTION_EDIT' => 'Изменить тему',
  'SECTION_DELETE' => 'Удалить тему',
));
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Фактоиды (заголовки)',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'FACT_TITLE',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'S',
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
));
            $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Фактоиды (описания)',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'FACT_DESC',
  'DEFAULT_VALUE' => 
  array (
    'TEXT' => '',
    'TYPE' => 'HTML',
  ),
  'PROPERTY_TYPE' => 'S',
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
  'USER_TYPE' => 'HTML',
  'USER_TYPE_SETTINGS' => 
  array (
    'height' => 200,
  ),
  'HINT' => '',
));
            $helper->UserOptions()->saveElementForm($iblockId, array (
  'Документ|edit1' => 
  array (
    'ACTIVE' => 'Активность',
    'ACTIVE_FROM' => 'Начало активности',
    'NAME' => 'Название',
    'CODE' => 'Ссылка',
    'PROPERTY_P_FILE' => 'Файл',
    'SORT' => 'Сортировка',
    'SECTIONS' => 'Разделы',
    'PROPERTY_P_CLOSE' => 'Закрыть отчеть ?',
    'PREVIEW_PICTURE' => 'Картинка для анонса',
    'PROPERTY_SPECIAL' => 'Специальный отчет',
    'PROPERTY_AUTHOR' => 'Авторы',
    'PROPERTY_FACT_TITLE' => 'Фактоиды (заголовки)',
    'PROPERTY_FACT_DESC' => 'Фактоиды (описания)',
  ),
  'Подробно|edit6' => 
  array (
    'PREVIEW_TEXT' => 'Описание для анонса',
    'DETAIL_TEXT' => 'Детальное описание',
  ),
));
        $helper->UserOptions()->saveSectionForm($iblockId, array (
  'Раздел|edit1' => 
  array (
    'ID' => 'ID',
    'ACTIVE' => 'Раздел активен',
    'NAME' => 'Название',
    'CODE' => 'Символьный код',
  ),
  'Дополнительно|edit2' => 
  array (
    'SORT' => 'Сортировка',
  ),
  'Доп. поля|user_fields_tab' => 
  array (
    'USER_FIELDS_ADD' => 'Добавить пользовательское свойство',
    'UF_CLASS_PREFIX' => 'Префикс css класса для главной',
    'UF_SHORT_NAME' => 'Короткое название',
  ),
));
    $helper->UserOptions()->saveElementGrid($iblockId, array (
  'views' => 
  array (
    'default' => 
    array (
      'columns' => 
      array (
        0 => 'NAME',
        1 => 'ACTIVE',
        2 => 'SORT',
        3 => 'TIMESTAMP_X',
        4 => 'CODE',
        5 => 'ID',
        6 => 'PROPERTY_P_FILE',
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
      'page_size' => 100,
      'custom_names' => NULL,
      'last_sort_by' => 'timestamp_x',
      'last_sort_order' => 'desc',
    ),
  ),
  'filters' => 
  array (
  ),
  'current_view' => 'default',
));
    $helper->UserOptions()->saveSectionGrid($iblockId, array (
  'views' => 
  array (
    'default' => 
    array (
      'columns' => 
      array (
        0 => '',
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
