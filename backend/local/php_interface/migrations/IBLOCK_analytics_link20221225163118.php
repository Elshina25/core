<?php

namespace Sprint\Migration;


class IBLOCK_analytics_link20221225163118 extends Version
{
    protected $description = "Привязка к типам отчётов для исследований";

    protected $moduleVersion = "4.1.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $iblockId = $helper->Iblock()->getIblockIdIfExists('analitics', 'analytics');
        $helper->Iblock()->saveProperty($iblockId, array (
  'NAME' => 'Тип исследования',
  'ACTIVE' => 'Y',
  'SORT' => '500',
  'CODE' => 'TYPE',
  'DEFAULT_VALUE' => '',
  'PROPERTY_TYPE' => 'E',
  'ROW_COUNT' => '1',
  'COL_COUNT' => '30',
  'LIST_TYPE' => 'L',
  'MULTIPLE' => 'N',
  'XML_ID' => NULL,
  'FILE_TYPE' => '',
  'MULTIPLE_CNT' => '5',
  'LINK_IBLOCK_ID' => 'analytics:research_types',
  'WITH_DESCRIPTION' => 'N',
  'SEARCHABLE' => 'N',
  'FILTRABLE' => 'N',
  'IS_REQUIRED' => 'Y',
  'VERSION' => '1',
  'USER_TYPE' => NULL,
  'USER_TYPE_SETTINGS' => NULL,
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
    'PROPERTY_TYPE' => 'Тип исследования',
    'PREVIEW_PICTURE' => 'Картинка для анонса',
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
