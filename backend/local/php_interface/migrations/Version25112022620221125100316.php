<?php

namespace Sprint\Migration;


class Version25112022620221125100316 extends Version
{
    protected $description = "Почтовое событие формы \"Запросить стоимость\"";

    protected $moduleVersion = "4.1.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->Event()->saveEventType('FORM_FILLING_REQUEST_PRICE', array (
  'LID' => 'ru',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Заполнена web-форма "REQUEST_PRICE"',
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
#NAME# - Как вас зовут?
#NAME_RAW# - Как вас зовут? (оригинальное значение)
#COMPANY_NAME# - Название компании
#COMPANY_NAME_RAW# - Название компании (оригинальное значение)
#PHONE# - Телефон
#PHONE_RAW# - Телефон (оригинальное значение)
#EMAIL# - Эл. почта
#EMAIL_RAW# - Эл. почта (оригинальное значение)
#COMMENT# - Комментарий
#COMMENT_RAW# - Комментарий (оригинальное значение)
#PAGE# - Web-страница
#PAGE_RAW# - Web-страница (оригинальное значение)
',
  'SORT' => '100',
));
            $helper->Event()->saveEventType('FORM_FILLING_REQUEST_PRICE', array (
  'LID' => 'en',
  'EVENT_TYPE' => 'email',
  'NAME' => 'Web form filled "REQUEST_PRICE"',
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
#NAME# - Как вас зовут?
#NAME_RAW# - Как вас зовут? (original value)
#COMPANY_NAME# - Название компании
#COMPANY_NAME_RAW# - Название компании (original value)
#PHONE# - Телефон
#PHONE_RAW# - Телефон (original value)
#EMAIL# - Эл. почта
#EMAIL_RAW# - Эл. почта (original value)
#COMMENT# - Комментарий
#COMMENT_RAW# - Комментарий (original value)
#PAGE# - Web-страница
#PAGE_RAW# - Web-страница (original value)
',
  'SORT' => '100',
));
            $helper->Event()->saveEventMessage('FORM_FILLING_REQUEST_PRICE', array (
  'LID' => 
  array (
    0 => 's1',
  ),
  'ACTIVE' => 'Y',
  'EMAIL_FROM' => '#DEFAULT_EMAIL_FROM#',
  'EMAIL_TO' => '#DEFAULT_EMAIL_FROM#',
  'SUBJECT' => '#SERVER_NAME#: заполнена web-форма [#RS_FORM_ID#] #RS_FORM_NAME#',
  'MESSAGE' => '#SERVER_NAME#

Заполнена web-форма: [#RS_FORM_ID#] #RS_FORM_NAME#
-------------------------------------------------------

Дата - #RS_DATE_CREATE#
Результат - #RS_RESULT_ID#
Пользователь - [#RS_USER_ID#] #RS_USER_NAME# #RS_USER_AUTH#
Посетитель - #RS_STAT_GUEST_ID#
Сессия - #RS_STAT_SESSION_ID#


Как вас зовут?
*******************************
#NAME#

Название компании
*******************************
#COMPANY_NAME#

Телефон
*******************************
#PHONE#

Эл. почта
*******************************
#EMAIL#

Комментарий
*******************************
#COMMENT#

Web-страница
*******************************
#PAGE#


Для просмотра воспользуйтесь ссылкой:
http://#SERVER_NAME#/bitrix/admin/form_result_view.php?lang=ru&WEB_FORM_ID=#RS_FORM_ID#&RESULT_ID=#RS_RESULT_ID#

-------------------------------------------------------
Письмо сгенерировано автоматически.
						',
  'BODY_TYPE' => 'text',
  'BCC' => NULL,
  'REPLY_TO' => NULL,
  'CC' => NULL,
  'IN_REPLY_TO' => NULL,
  'PRIORITY' => NULL,
  'FIELD1_NAME' => NULL,
  'FIELD1_VALUE' => NULL,
  'FIELD2_NAME' => NULL,
  'FIELD2_VALUE' => NULL,
  'SITE_TEMPLATE_ID' => NULL,
  'ADDITIONAL_FIELD' => 
  array (
  ),
  'LANGUAGE_ID' => NULL,
  'EVENT_TYPE' => '[ FORM_FILLING_REQUEST_PRICE ] Заполнена web-форма "REQUEST_PRICE"',
));
        }

    public function down()
    {
        //your code ...
    }
}
