<?php

namespace Sprint\Migration;


class FORM_SIMPLE_FORM_220221206123444 extends Version
{
    protected $description = "Настройки для формы \"Обратная связь\"";

    protected $moduleVersion = "4.1.1";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $formHelper = $helper->Form();
        $formId = $formHelper->saveForm(array (
  'NAME' => 'Обратная связь',
  'SID' => 'SIMPLE_FORM_2',
  'BUTTON' => 'Отправить',
  'C_SORT' => '200',
  'FIRST_SITE_ID' => NULL,
  'IMAGE_ID' => NULL,
  'USE_CAPTCHA' => 'N',
  'DESCRIPTION' => 'Заполните необходимые поля в приведенной ниже форме и нажмите кнопку "Отправить". 
Мы свяжемся с вами удобным для вас способом, в удобное для вас время.
Спасибо за ваш интерес к проекту.',
  'DESCRIPTION_TYPE' => 'text',
  'FORM_TEMPLATE' => '<?
/*** WEBFORM 2 ***/
	require_once($_SERVER["DOCUMENT_ROOT"]."/includes/forms_translate.php");
?> 						<span class="fut upper f11"><?= frm_msg($FORM->ShowInputCaption("NAME")) ?></span> 
<br />
 						 
<div class="z_input_wrap" nic="z_name b20"><?=$FORM->ShowInput(\'NAME\')?><span class="z_error"></span></div>
 
<br />
 						<span class="fut upper f11"><?= frm_msg($FORM->ShowInputCaption("PHONE"))?></span> 
<br />
 						 
<div class="z_input_wrap" nic="z_tel b20" style="width: 360px;"><?=$FORM->ShowInput(\'PHONE\')?><span class="z_error"><?= $GLOBALS["FRM_FORMAT"]["PHONE"][LANGUAGE_ID] ?></span><span class="tel_more_info"> 
    <div><?= $GLOBALS["FRM_FORMAT"]["PHONE_MORE"][LANGUAGE_ID] ?></div>
   </span></div>
 
<br />
 						<span class="fut upper f11"><?= frm_msg($FORM->ShowInputCaption("EMAIL"))?></span> 
<br />
 						 
<div class="z_input_wrap z_mail_wrap" nic="z_mail b20"><?=$FORM->ShowInput(\'EMAIL\')?><span class="z_mail_pl">address@domain.com</span><span class="z_error"><?= $GLOBALS["FRM_FORMAT"]["EMAIL"][LANGUAGE_ID] ?></span></div>
 
<br />
 						<span class="fut upper f11"><?= frm_msg($FORM->ShowInputCaption("QUESTIONS"))?></span> 
<br />
 						 
<div nic="z_text b20"><?=$FORM->ShowInput(\'QUESTIONS\')?></div>
 						 
<p class="grey_a f11"><font color="red"><span class="form-required starrequired">*</span></font> - <?= frm_msg("Обязательные поля") ?></p>
 						<span class="h_send fx fut upper f12 white"><span></span><?= frm_msg("отправить заявку") ?></span> 						 
<div style="display: none;" id="callback_request"> <?=$FORM->ShowSubmitButton("","")?> </div>
 						<input type="hidden" value="Y" name="web_form_apply" /> 						 						 
<script type="text/javascript">
							$(function() {
								prepareWebForm(\'contFrm\', \'h_send\');
							});
						</script>
 					',
  'USE_DEFAULT_TEMPLATE' => 'N',
  'SHOW_TEMPLATE' => NULL,
  'MAIL_EVENT_TYPE' => 'FORM_FILLING_SIMPLE_FORM_2',
  'SHOW_RESULT_TEMPLATE' => NULL,
  'PRINT_RESULT_TEMPLATE' => NULL,
  'EDIT_RESULT_TEMPLATE' => NULL,
  'FILTER_RESULT_TEMPLATE' => '',
  'TABLE_RESULT_TEMPLATE' => '',
  'USE_RESTRICTIONS' => 'N',
  'RESTRICT_USER' => '0',
  'RESTRICT_TIME' => '0',
  'RESTRICT_STATUS' => '',
  'STAT_EVENT1' => 'form',
  'STAT_EVENT2' => 'feedback',
  'STAT_EVENT3' => '',
  'LID' => NULL,
  'C_FIELDS' => '0',
  'QUESTIONS' => '5',
  'STATUSES' => '1',
  'arSITE' => 
  array (
    0 => 's1',
  ),
  'arMENU' => 
  array (
    'ru' => 'Обратная связь',
    'en' => 'Feedback',
  ),
  'arGROUP' => 
  array (
  ),
  'arMAIL_TEMPLATE' => 
  array (
    0 => '56',
  ),
));
        $formHelper->saveStatuses($formId, array (
  0 => 
  array (
    'CSS' => 'statusgreen',
    'C_SORT' => '100',
    'ACTIVE' => 'Y',
    'TITLE' => 'DEFAULT',
    'DESCRIPTION' => NULL,
    'DEFAULT_VALUE' => 'Y',
    'HANDLER_OUT' => NULL,
    'HANDLER_IN' => NULL,
  ),
));
        $formHelper->saveFields($formId, array (
  0 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Как к вам обращаться',
    'TITLE_TYPE' => 'text',
    'SID' => 'NAME',
    'C_SORT' => '100',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => 'Your name',
    'FILTER_TITLE' => '',
    'RESULTS_TABLE_TITLE' => 'Как к вам обращаться',
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => '',
        'FIELD_TYPE' => 'text',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  1 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Контактный телефон',
    'TITLE_TYPE' => 'text',
    'SID' => 'PHONE',
    'C_SORT' => '200',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => 'Phone',
    'FILTER_TITLE' => '',
    'RESULTS_TABLE_TITLE' => 'Контактный телефон',
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => '',
        'FIELD_TYPE' => 'text',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  2 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Контактный e-mail',
    'TITLE_TYPE' => 'text',
    'SID' => 'EMAIL',
    'C_SORT' => '300',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => 'Email',
    'FILTER_TITLE' => '',
    'RESULTS_TABLE_TITLE' => 'Контактный e-mail',
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => '',
        'FIELD_TYPE' => 'text',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  3 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Ваше сообщение',
    'TITLE_TYPE' => 'text',
    'SID' => 'QUESTIONS',
    'C_SORT' => '600',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'Y',
    'IN_FILTER' => 'Y',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => 'text',
    'IMAGE_ID' => NULL,
    'COMMENTS' => 'Your request',
    'FILTER_TITLE' => '',
    'RESULTS_TABLE_TITLE' => 'Ваше сообщение',
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => '',
        'FIELD_TYPE' => 'textarea',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '0',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
  4 => 
  array (
    'ACTIVE' => 'Y',
    'TITLE' => 'Страница, с которой отправлен запрос',
    'TITLE_TYPE' => 'text',
    'SID' => 'PAGE',
    'C_SORT' => '700',
    'ADDITIONAL' => 'N',
    'REQUIRED' => 'N',
    'IN_FILTER' => 'N',
    'IN_RESULTS_TABLE' => 'Y',
    'IN_EXCEL_TABLE' => 'Y',
    'FIELD_TYPE' => '',
    'IMAGE_ID' => NULL,
    'COMMENTS' => '',
    'FILTER_TITLE' => '',
    'RESULTS_TABLE_TITLE' => '',
    'ANSWERS' => 
    array (
      0 => 
      array (
        'MESSAGE' => ' ',
        'VALUE' => '',
        'FIELD_TYPE' => 'text',
        'FIELD_WIDTH' => '0',
        'FIELD_HEIGHT' => '0',
        'FIELD_PARAM' => '',
        'C_SORT' => '100',
        'ACTIVE' => 'Y',
      ),
    ),
    'VALIDATORS' => 
    array (
    ),
  ),
));
    }

    public function down()
    {
        //your code ...
    }
}

