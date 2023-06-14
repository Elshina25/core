<?php

namespace Sprint\Migration;


class MAIL_SUBSCRIBE_FOR_REPORT20221207164352 extends Version
{
    protected $description = "Почтовое событие для формы \"Подписка на отчёты\"";

    protected $moduleVersion = "4.1.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Event()->saveEventType('FORM_FILLING_SUBSCRIBE_FOR_REPORT', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Заполнена web-форма "SUBSCRIBE_FOR_REPORT"',
  'DESCRIPTION' => '#RS_FORM_ID# - ID формы
#RS_FORM_NAME# - Имя формы
#RS_FORM_SID# - SID формы
#RS_RESULT_ID# - ID результата
#RS_DATE_CREATE# - Дата заполнения формы
#RS_USER_ID# - ID пользователя
#RS_USER_EMAIL# - EMail пользователя
#RS_USER_NAME# - Фамилия, имя пользователя
#RS_USER_AUTH# - Пользователь был авторизован?
#RS_STAT_GUEST_ID# - ID посетителя
#RS_STAT_SESSION_ID# - ID сессии
#EMAIL# - Электронная почта
#EMAIL_RAW# - Электронная почта (оригинальное значение)
#SECTIONS# - Виды недвижимости
#SECTIONS_RAW# - Виды недвижимости (оригинальное значение)
',
  'SORT' => '100',
));
            $helper->Event()->saveEventType('FORM_FILLING_SUBSCRIBE_FOR_REPORT', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Web form filled "SUBSCRIBE_FOR_REPORT"',
  'DESCRIPTION' => '#RS_FORM_ID# - Form ID
#RS_FORM_NAME# - Form name
#RS_FORM_SID# - Form SID
#RS_RESULT_ID# - Result ID
#RS_DATE_CREATE# - Form filling date
#RS_USER_ID# - User ID
#RS_USER_EMAIL# - User e-mail
#RS_USER_NAME# - First and last user names
#RS_USER_AUTH# - User authorized?
#RS_STAT_GUEST_ID# - Visitor ID
#RS_STAT_SESSION_ID# - Session ID
#EMAIL# - Электронная почта
#EMAIL_RAW# - Электронная почта (original value)
#SECTIONS# - Виды недвижимости
#SECTIONS_RAW# - Виды недвижимости (original value)
',
  'SORT' => '100',
));
            $helper->Event()->saveEventMessage('FORM_FILLING_SUBSCRIBE_FOR_REPORT', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
  'EMAIL_TO' => 'victoria.frishter@core-xp.ru, victoria.tsukanova@core-xp.ru, egor.fyodorov@core-xp.ru',
  'SUBJECT' => '#SERVER_NAME#: заполнена web-форма [#RS_FORM_ID#] #RS_FORM_NAME#',
  'MESSAGE' => '#SERVER_NAME#

Заполнена web-форма: [#RS_FORM_ID#] #RS_FORM_NAME#
-------------------------------------------------------

Дата - #RS_DATE_CREATE#
Результат - #RS_RESULT_ID#
Пользователь - [#RS_USER_ID#] #RS_USER_NAME# #RS_USER_AUTH#
Посетитель - #RS_STAT_GUEST_ID#
Сессия - #RS_STAT_SESSION_ID#


Электронная почта
*******************************
#EMAIL#

Виды недвижимости
*******************************
#SECTIONS#


Для просмотра воспользуйтесь ссылкой:
http://#SERVER_NAME#/bitrix/admin/form_result_view.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#

-------------------------------------------------------
Письмо сгенерировано автоматически.
						',
  'BODY_TYPE' => 'text',
  'BCC' => '',
  'REPLY_TO' => '',
  'CC' => '',
  'IN_REPLY_TO' => '',
  'PRIORITY' => '',
  'FIELD1_NAME' => '',
  'FIELD1_VALUE' => '',
  'FIELD2_NAME' => '',
  'FIELD2_VALUE' => '',
  'SITE_TEMPLATE_ID' => '',
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => '',
  'EVENT_TYPE' => '[ FORM_FILLING_SUBSCRIBE_FOR_REPORT ] Заполнена web-форма "SUBSCRIBE_FOR_REPORT"',
));
        }

    public function down()
    {
        //your code ...
    }
}
