<?
use Bitrix\Main\{
	Loader,
	Entity,
	ORM\Query
};
define('SEO_TAGS_IBLOCK_ID', 72);

Loader::registerAutoLoadClasses(
    null,
    array(
        'RealtyObjectsClassTable' => '/local/php_interface/orm/RealtyObjectsClassTable.php',
        'RealtyStatusesClassTable' => '/local/php_interface/orm/RealtyStatusesClassTable.php',
        'RealtyLocationsClassTable' => '/local/php_interface/orm/RealtyLocationsClassTable.php',
        'RealtyCitiesClassTable' => '/local/php_interface/orm/RealtyCitiesClassTable.php',
        'RealtyDirectionsClassTable' => '/local/php_interface/orm/RealtyDirectionsClassTable.php',
        'RealtyMetrosClassTable' => '/local/php_interface/orm/RealtyMetrosClassTable.php',
        'RealtyFloorsClassTable' => '/local/php_interface/orm/RealtyFloorsClassTable.php',
        'RealtyMarketsClassTable' => '/local/php_interface/orm/RealtyMarketsClassTable.php',
        'RealtyPicturesClassTable' => '/local/php_interface/orm/RealtyPicturesClassTable.php',
        'RealtyObjectsVsUnitsClassTable' => '/local/php_interface/orm/RealtyObjectsVsUnitsClassTable.php',
        'RealtyObjectsVsFireClassTable' => '/local/php_interface/orm/RealtyObjectsVsFireClassTable.php',
        'RealtyParkingsClassTable' => '/local/php_interface/orm/RealtyParkingsClassTable.php',
        'RealtyParkingTypesClassTable' => '/local/php_interface/orm/RealtyParkingTypesClassTable.php',
        'RealtyUsersClassTable' => '/local/php_interface/orm/RealtyUsersClassTable.php',
        'RealtyUsersUnitsClassTable' => '/local/php_interface/orm/RealtyUsersUnitsClassTable.php',
        'RealtyObjectsVsUsersClassTable' => '/local/php_interface/orm/RealtyObjectsVsUsersClassTable.php',
        'RealtyRoomsClassTable' => '/local/php_interface/orm/RealtyRoomsClassTable.php',
        'RealtyRoomsTypesClassTable' => '/local/php_interface/orm/RealtyRoomsTypesClassTable.php',
        'RealtyRoomsConditionsClassTable' => '/local/php_interface/orm/RealtyRoomsConditionsClassTable.php',
        'StatusHelper' => '/local/php_interface/classes/StatusHelper.php',
        'CityHelper' => '/local/php_interface/classes/CityHelper.php',
        'FloorHelper' => '/local/php_interface/classes/FloorHelper.php',
        'DirectionHelper' => '/local/php_interface/classes/DirectionHelper.php',
        'MetroHelper' => '/local/php_interface/classes/MetroHelper.php',
        'SubmarketHelper' => '/local/php_interface/classes/SubmarketHelper.php',
        'PicturesHelper' => '/local/php_interface/classes/PicturesHelper.php',
        'BusinessUnitsHelper' => '/local/php_interface/classes/BusinessUnitsHelper.php',
        'FireSecurityHelper' => '/local/php_interface/classes/FireSecurityHelper.php',
        'BuildingPropertiesHelper' => '/local/php_interface/classes/BuildingPropertiesHelper.php',
        'MessageHelper' => '/local/php_interface/classes/MessageHelper.php',
        'CrmClass' => '/local/php_interface/classes/CrmClass.php',
        'UrlGetClass' => '/local/php_interface/classes/UrlGetClass.php',
        'ParkingImport' => '/local/php_interface/classes/ParkingImport.php',
        'UserImport' => '/local/php_interface/classes/UserImport.php',
        'RoomImport' => '/local/php_interface/classes/RoomImport.php',
        'BuildingImport' => '/local/php_interface/classes/BuildingImport.php',
        'CalculateBuildingHelper' => '/local/php_interface/classes/CalculateBuildingHelper.php',
        'ImportHelper' => '/local/php_interface/classes/ImportHelper.php',
		'RealtyCountyClassTable' => '/local/php_interface/orm/RealtyCountyClassTable.php',
		'RealtyRaionClassTable' => '/local/php_interface/orm/RealtyRaionClassTable.php'
    )
);

//включить логи define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/logs.txt");
// редирект на https админку
if ( ( substr ($_SERVER["REQUEST_URI"], 0, 14) == "/bitrix/admin/") && (!$_SERVER["HTTPS"]) ) {
	// header('location: https://cbre.rentnow.ru'.$_SERVER['REQUEST_URI'], 301);
 //    exit();
}

// Подключение функций, общих для сайта:
require_once($_SERVER["DOCUMENT_ROOT"]."/includes/common_functions.php");

if (!isset($_SESSION['VIEWED_OBJECTS'])) {
    $_SESSION['VIEWED_OBJECTS'] = array();
}


//////////////////////Добавление страницы фильтра в кастомную табличку///////////////
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "insertElementInCustomTable");

function insertElementInCustomTable(&$arFields) {
	if ($arFields["IBLOCK_ID"] == 79) {
		global $DB;
		$h1Value = array_shift($arFields['PROPERTY_VALUES'][262]);
		$h1Value['VALUE'] = str_replace("'", "\'", $h1Value['VALUE']);

		$titleValue = array_shift($arFields['PROPERTY_VALUES'][261]);
		$titleValue['VALUE'] = str_replace("'", "\'", $titleValue['VALUE']);

		$descriptionValue = array_shift($arFields['PROPERTY_VALUES'][263]);
		$descriptionValue['VALUE'] = str_replace("'", "\'", $descriptionValue['VALUE']);

		$seotextValue = array_shift($arFields['PROPERTY_VALUES'][264]);
		$seotextValue['VALUE'] = str_replace("'", "\'", $seotextValue['VALUE']);

		$DB->query("INSERT INTO `ml_realty_seo_pages` (`url`, `realty_type_id`, `object_class`, `metro_station_id`,
									`submarket_id`, `direction_id`, `city_id`, `highway_id`, `deal_type`,
									`seo_title`, `h1_title`, `seo_description`, `seo_text`, `language`, `obj_count`)
								VALUES ('{$arFields['CODE']}', 4, '', 0, 0, 0, 0, 0, 1, '{$titleValue['VALUE']}',
										'{$h1Value['VALUE']}', '{$descriptionValue['VALUE']}',
										'{$seotextValue['VALUE']}', 'ru', 0)");
	}
}

/*Обработка заполнения фактоидов*/
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", 'beforeFactoidsUpdate');
AddEventHandler("iblock", "OnBeforeIBlockElementAdd", 'beforeFactoidsUpdate');
function beforeFactoidsUpdate(&$arFields) {
	\Bitrix\Main\Loader::includeModule('iblock');
	$iblockIdFactoids = \Bitrix\Iblock\IblockTable::getList([
		'filter' => ["CODE" => 'factoids']
	])->fetch()["ID"];
	if($iblockIdFactoids == $arFields["IBLOCK_ID"]) {
		if(mb_strlen($arFields["NAME"]) > 50 || mb_strlen($arFields["PREVIEW_TEXT"]) > 50) {
			global $APPLICATION;
            $APPLICATION->throwException("Введены слишком длинные строки!");
			return false;
		}
	}
}
/*Обработка заполнения фактоидов конец*/

//////////////////////Изменение страницы фильтра в кастомной табличке///////////////
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "updateElementInCustomTable");

function updateElementInCustomTable(&$arFields) {
	if ($arFields["IBLOCK_ID"] == 79) {
		global $DB;
		//AddMessage2Log($arFields);
		$h1Value = array_shift($arFields['PROPERTY_VALUES'][262]);
		$h1Value['VALUE'] = str_replace("'", "\'", $h1Value['VALUE']);

		$titleValue = array_shift($arFields['PROPERTY_VALUES'][261]);
		$titleValue['VALUE'] = str_replace("'", "\'", $titleValue['VALUE']);

		$descriptionValue = array_shift($arFields['PROPERTY_VALUES'][263]);
		$descriptionValue['VALUE'] = str_replace("'", "\'", $descriptionValue['VALUE']);

		$seotextValue = array_shift($arFields['PROPERTY_VALUES'][264]);
		$seotextValue['VALUE'] = str_replace("'", "\'", $seotextValue['VALUE']);

		$DB->query("UPDATE `ml_realty_seo_pages`
						SET `seo_title` = '{$titleValue['VALUE']}', `h1_title` = '{$h1Value['VALUE']}',
							`seo_description` = '{$descriptionValue['VALUE']}', `seo_text` = '{$seotextValue['VALUE']}'
						WHERE `url` = '{$arFields['CODE']}' AND `language` = 'ru';");
	}
}

//////////////////////Удаление страницы фильтра из кастомной таблички///////////////
AddEventHandler("iblock", "OnAfterIBlockElementDelete", "deleteElementFromCustomTable");

function deleteElementFromCustomTable(&$arFields) {
	if ($arFields["IBLOCK_ID"] == 79) {
		global $DB;
		$DB->query("DELETE FROM `ml_realty_seo_pages` WHERE `url` = '{$arFields['CODE']}' AND `language` = 'ru';");
	}
}
/////////////////////////////////////////////////////////////////////

// Антиспам
/*define("ANTISPAM_SALT_KEY", "c4v8U-ZhJeW.7c7");
AddEventHandler("form", "onBeforeResultAdd", "antispam_onBeforeResultAdd");

function antispam_onBeforeResultAdd($WEB_FORM_ID, $arFields, $arrVALUES) {
	global $APPLICATION;

	if ($_POST["asc"] != md5(bitrix_sessid().ANTISPAM_SALT_KEY)) {
		$APPLICATION->ThrowException("Спасибо! Ваша заявка принята");
	}
}*/
// /Антиспам

/////////////////////////////////////////////////////////////////////

// Добавление новой функции {=first_par строка} в сео-шаблоны, которая выцепляет содержимое первого параграфа <p>...</p>, и обрезает по предложениям до 200 знаков.
if (\Bitrix\Main\Loader::includeModule('iblock')) {
	//регистрируем обработчик события
	\Bitrix\Main\EventManager::getInstance()->addEventHandler(
		"iblock",
		"OnTemplateGetFunctionClass",
		array("FunctionFirstPar", "eventHandler")
	);
	//подключаем файл с определением класса FunctionBase
	//это пока требуется т.к. класс не описан в правилах автозагрузки
	include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/lib/template/functions/fabric.php");
	class FunctionFirstPar extends \Bitrix\Iblock\Template\Functions\FunctionBase {
		//Обработчик события на вход получает имя требуемой функции
		//парсер её нашел в строке SEO
		public static function eventHandler($event) {
			$parameters = $event->getParameters();
			$functionName = $parameters[0];
			if ($functionName === "first_par") {
				//обработчик должен вернуть SUCCESS и имя класса
				//который будет отвечать за вычисления
				return new \Bitrix\Main\EventResult(1, "\\FunctionFirstPar");
			}
		}
		//собственно функция выполняющая "магию"
		public function calculate($parameters) {
			$result = $this->parametersToArray($parameters);
			$str = str_replace("&ndash;", "-", $result[0]);
			preg_match_all("/<(p|div).*?\>([\d\D]*?)<\/(p|div)>/u", $str, $m);
			$str = strip_tags($m[2][0]);
			$a = split_sentence($str);
			$short = '';
			while (mb_strlen($short) < 200 and count($a) > 0) {
				$short .= array_shift($a).' ';
			}

			return print_r(trim($short), true);
		}
	}
}

// Режет текст по предложениям
function split_sentence($str) {
	$new = array();
	preg_match_all('#.+[\.!?](\s|$)#SUu',$str,$match);

	$count = count($match[0]);
	$rand = 1;

	for ($start = 0; $start<=$count;$start += $rand) {
		$new[] = implode(' ', array_slice($match[0],$start, $rand));
		$rand = 1;
	}

	return $new;
}


function sendMailAttachment($mailTo, $from, $subject, $message, $file = false)
{
	$separator = "---"; // разделитель в письме
	// Заголовки для письма
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From: $from\nReply-To: $from\n"; // задаем от кого письмо
	$headers .= "Content-Type: multipart/mixed; boundary=\"$separator\""; // в заголовке указываем разделитель
	// если письмо с вложением
	if ($file) {
		$bodyMail = "--$separator\n"; // начало тела письма, выводим разделитель
		$bodyMail .= "Content-type: text/html; charset='utf-8'\n"; // кодировка письма
		$bodyMail .= "Content-Transfer-Encoding: quoted-printable"; // задаем конвертацию письма
		$bodyMail .= "Content-Disposition: attachment; filename==?utf-8?B?" . base64_encode(basename($file)) . "?=\n\n"; // задаем название файла
		$bodyMail .= $message . "\n"; // добавляем текст письма
		$bodyMail .= "--$separator\n";
		$fileRead = fopen($file, "r"); // открываем файл
		$contentFile = fread($fileRead, filesize($file)); // считываем его до конца
		fclose($fileRead); // закрываем файл
		$bodyMail .= "Content-Type: application/octet-stream; name==?utf-8?B?" . base64_encode(basename($file)) . "?=\n";
		$bodyMail .= "Content-Transfer-Encoding: base64\n"; // кодировка файла
		$bodyMail .= "Content-Disposition: attachment; filename==?utf-8?B?" . base64_encode(basename($file)) . "?=\n\n";
		$bodyMail .= chunk_split(base64_encode($contentFile)) . "\n"; // кодируем и прикрепляем файл
		$bodyMail .= "--" . $separator . "--\n";
		// письмо без вложения
	} else {
		$bodyMail = $message;
	}
	$result = mail($mailTo, $subject, $bodyMail, $headers); // отправка письма
	return $result;
}

// Уведомление после заполнения формы
AddEventHandler("form", "onAfterResultAdd", "notify_onAfterResultAdd");

function notify_onAfterResultAdd($WEB_FORM_ID, $RESULT_ID) {
	global $APPLICATION;
	$bids = array("ru" => 33, "en" => 55);

	$_SESSION["WAITING_MSG"] = "Y"; // ждём вывода сообщения
if($WEB_FORM_ID == 24){
	if (CModule::IncludeModule("form") && CModule::IncludeModule("iblock")) {
		CForm::GetResultAnswerArray($WEB_FORM_ID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $RESULT_ID));
		$questions = array();
		$answers = array();
		foreach ($arrColumns as $qID => $q) {
			$questions[$qID] = $q["TITLE"];
			$ks = array_keys($arrAnswers[$RESULT_ID][$qID]);
			$ans = $arrAnswers[$RESULT_ID][$qID][$ks[0]];
			$answers[$qID] = $ans["USER_TEXT"] ? $ans["USER_TEXT"] : $ans["ANSWER_TEXT"];
			$mail = "";
		}
		if (!empty($answers[118])) {
			$mail = $answers[118];
		}

		//file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/upload/z.txt", $answers[118]);
		//$eml = 'r.panfilov@amio.ru';

		if (!empty($mail)) {

			$rsResult = CFormResult::GetByID($RESULT_ID);
			$arResult = $rsResult->Fetch();
			$fname = $arResult["NAME"];



			$admUrl = "http://".$_SERVER["SERVER_NAME"]."/bitrix/admin/form_result_edit.php?lang=ru&WEB_FORM_ID=".$WEB_FORM_ID."&RESULT_ID=".$RESULT_ID;

			$subj = "Заполнена форма \"".$fname."\" на сайте ".$_SERVER["SERVER_NAME"];



			$file = "/home/bitrix/www/Moscow_Office_MarketView_2020_Q3_RUS.pdf"; // файл
			//$mailTo = "best-rs@mail.ru"; // кому
			$mailTo = $mail; // кому
			$from = COption::GetOptionString("main", "email_from"); // от кого
			//$from = 'bitrix@d193.colo.logol.ru';

			$subject = "АНАЛИТИЧЕСКИЙ ОБЗОР ОФИСНОГО РЫНКА ЗА Q3 2020"; // тема письма
			$message = '<div class="content__main content__main--social dyn-7_5" data-slick-slide-count="2">
<div class="content-block__featured-flag-title" style="display:none" data-auto="content-block__featured-flag-title">Featured</div>
<div class="content-block__related-flag-title" style="display:none" data-auto="content-block__related-flag-title">Related</div>
<div class="content-block__title">
<h3 class="content-block__main-title">
<span class="" rel="">Офисная недвижимость Москвы, III кв. 2020</span>
</h3>
</div>
<div class="content-block__description" data-auto="content-block-desc"><ul>
<li>В III квартале 2020 года объем нового предложения составил 54 245 кв. м офисных площадей, большая часть из которого ожидалась к вводу в эксплуатацию в предыдущем квартале.</li>
<li>Доля свободных офисных площадей в целом на рынке увеличилась на 0,4 п. п. с конца июня и на 1,1 п. п. с конца марта и составила 10,4% по итогам III квартала.</li>
<li>Объем арендованных и приобретенных офисных площадей в III квартале 2020 года снизился более чем в 2 раза по сравнению со значением в сопоставимом периоде 2019 года и составил 227 000 кв. м против 518 200 кв. м.</li>
<li>За девять месяцев 2020 года объем новых сделок превысил 870 000 кв. м, что на 24% ниже значения в аналогичном периоде прошлого года.</li>
<li>Наибольшую активность в III квартале проявляли представители сферы строительства и недвижимости (прежде всего операторы гибких офисных пространств, которые расширяют сети своих площадок) и финансового сектора.</li>
</ul>
</div>
<br>
</div>'; // текст письма
			$r = sendMailAttachment($mailTo, $from, $subject, $message, $file); // отправка письма c вложением

		}
	}
}
	//if ($WEB_FORM_ID == 3 || $WEB_FORM_ID == 4) { 
	if ($WEB_FORM_ID == 4) { // только формы с "Отделами"
		if (CModule::IncludeModule("form") && CModule::IncludeModule("iblock")) {
			CForm::GetResultAnswerArray($WEB_FORM_ID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $RESULT_ID));
			$questions = array();
			$answers = array();
			foreach ($arrColumns as $qID => $q) {
				$questions[$qID] = $q["TITLE"];
				$ks = array_keys($arrAnswers[$RESULT_ID][$qID]);
				$ans = $arrAnswers[$RESULT_ID][$qID][$ks[0]];
				$answers[$qID] = $ans["USER_TEXT"] ? $ans["USER_TEXT"] : $ans["ANSWER_TEXT"];

				if ("Отдел компании" === $q["TITLE"]) {
					// вытащим email отдела для отправки письма.
					// обрежем preffix и postfix
					$dname = $answers[$qID];

					if ($GLOBALS["DEP_PREFFIX"][LANGUAGE_ID])
						$dname = preg_replace("/^(".$GLOBALS["DEP_PREFFIX"][LANGUAGE_ID].")/im", "", $dname);
					if ($GLOBALS["DEP_POSTFIX"][LANGUAGE_ID])
						$dname = preg_replace("/(".$GLOBALS["DEP_POSTFIX"][LANGUAGE_ID].")$/im", "", $dname);

					$eml = null;
					$dFilter = array(
						"IBLOCK_ID" => $bids[LANGUAGE_ID],
						"ACTIVE" => "Y",
					);
					if (LANGUAGE_ID == "en")
						$dFilter["NAME"] = $dname;
					else
						$dFilter["PROPERTY_P_NAME1"] = $dname;

					$res = CIBlockElement::GetList(array("SORT" => "ASC"), $dFilter, false, false, array("ID", "PROPERTY_P_EMAIL"));
					if ($el = $res->GetNextElement()) {
						$flds = $el->GetFields();
						$eml = $flds["PROPERTY_P_EMAIL_VALUE"];
					}
					if (is_null($eml)) {
						$dFilter = array(
							"IBLOCK_ID" => $bids['ru'],
							"ID" => 439
						);
						$res = CIBlockElement::GetList(array("SORT" => "ASC"), $dFilter, false, false, array("ID", "PROPERTY_P_EMAIL"));
						if ($el = $res->GetNextElement()) {
							$flds = $el->GetFields();
							$eml = $flds["PROPERTY_P_EMAIL_VALUE"];
						}
					}
				}
			}

			//file_put_contents($_SERVER["DOCUMENT_ROOT"]."/upload/z.txt", $data);
			//временно переопределяем почту
            //$eml = 'victoria.frishter@core-xp.ru,victoria.tsukanova@core-xp.ru,nikolay.maklygin@core-xp.ru,info@core-xp.ru';

			if ($eml) {
				$rsResult = CFormResult::GetByID($RESULT_ID);
				$arResult = $rsResult->Fetch();
				$fname = $arResult["NAME"];

				$admUrl = "http://".$_SERVER["SERVER_NAME"]."/bitrix/admin/form_result_edit.php?lang=ru&WEB_FORM_ID=".$WEB_FORM_ID."&RESULT_ID=".$RESULT_ID;

				$subj = "Заполнена форма \"".$fname."\" на сайте ".$_SERVER["SERVER_NAME"];
				$msg = "<p>".$subj.".</p>\n";
				$msg .= "<table><tr style=\"border-bottom: 1px solid black\"><td style=\"width:330px;padding:10px 0;\"><b>Вопрос</b></td><td style=\"padding:10px 0;\"><b>Ответ</b></td></tr>\n";
				foreach ($questions as $i => $q) {
					$msg .= "<tr style=\"border-bottom: 1px solid black\"><td style=\"width:330px;padding:10px 0;\">".$q."</td>";
					$msg .= "<td style=\"padding:10px 0;\">".str_replace("\r\n", '<br>', htmlspecialchars($answers[$i]))."</td></tr>\n";
				}
				$msg .= "</table>\n";
				$msg .= "<p>Ссылка на ответ: <a href=\"".$admUrl."\">".$admUrl."</a></p>\n";
				$msg .= "<p><br><br>------<br>Это письмо отправлено автоматически.</p>\n";

				$hdrs = "MIME-Version: 1.0\r\n";
				$hdrs .= "Content-type: text/html; charset=UTF-8\r\n";
				$hdrs .= "From: ".COption::GetOptionString("main", "email_from")."\r\n";

				//$eml = $eml.' ,Irina.Shpigareva@cbre.com,mail@officenavigator.ru'; //added mails

				bxmail($eml, $subj, $msg, $hdrs);

				if (strstr($arrAnswers[$RESULT_ID][55][87]["USER_TEXT"], 'estate/retail') != false) {
					$eml2 = '';
					bxmail($eml2, $subj, $msg, $hdrs);
				}
			}
		}
	}
	if ($WEB_FORM_ID == 9) { // только формы с "Просмотра справа"
		if (CModule::IncludeModule("form") && CModule::IncludeModule("iblock")) {
			CForm::GetResultAnswerArray($WEB_FORM_ID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $RESULT_ID));
			$questions = array();
			$answers = array();
			foreach ($arrColumns as $qID => $q) {
				$questions[$qID] = $q["TITLE"];
				$ks = array_keys($arrAnswers[$RESULT_ID][$qID]);
				$ans = $arrAnswers[$RESULT_ID][$qID][$ks[0]];
				$answers[$qID] = $ans["USER_TEXT"] ? $ans["USER_TEXT"] : $ans["ANSWER_TEXT"];
				$mails = "";
				$arType = array(
					//"/office/" => "officeagency@cbre.com,elena.denisova@cbre.com,margarita.kabalkina@cbre.com",
					"/office/" => "Anna.Rostovskaya@cbre.com,Elena.Denisova@cbre.com,OfficeAgency@cbre.com,Irina.Shpigareva@cbre.com,mail@officenavigator.ru",
					"/retail/" => "retail@cbre.com,magomed.akhkuev@cbre.com,oksana.taspanchik@cbre.com,Irina.Shpigareva@cbre.com,mail@officenavigator.ru",
                    "/industrial_logistics/" => "industrial@cbre.com,vasiliy.grigoriev@cbre.com,anton.alyabyev@cbre.com,dmitry.khorokhordin@cbre.com, oksana.taspanchik@cbre.com,Irina.Shpigareva@cbre.com,mail@officenavigator.ru",
                    "/regional/" => "RU.Regions@cbre.com,Irina.Shpigareva@cbre.com,mail@officenavigator.ru"
				);

				// $arType = array(
				// 	"/office/" => "smagin@skobeeff.ru,sergey@skobeeff.ru",
				// 	"/industrial_logistics/" => "smagin@skobeeff.ru,sergey@skobeeff.ru",
				// 	"/retail/" => "smagin@skobeeff.ru,sergey@skobeeff.ru"
				// );
				foreach ($arType as $key => $value) {
					if (strpos($_SERVER["REQUEST_URI"], $key) !== false) {
						$mails = $arType[$key];
					}
				}

			}

			//file_put_contents($_SERVER["DOCUMENT_ROOT"]."/upload/z.txt", $data);
            //$eml = 'r.panfilov@amio.ru';

			if (!empty($mails)) {
				$rsResult = CFormResult::GetByID($RESULT_ID);
				$arResult = $rsResult->Fetch();
				$fname = $arResult["NAME"];

				$admUrl = "http://".$_SERVER["SERVER_NAME"]."/bitrix/admin/form_result_edit.php?lang=ru&WEB_FORM_ID=".$WEB_FORM_ID."&RESULT_ID=".$RESULT_ID;

				$subj = "Заполнена форма \"".$fname."\" на сайте ".$_SERVER["SERVER_NAME"];
				$msg = "<p>".$subj.".</p>\n";
				$msg .= "<table><tr style=\"border-bottom: 1px solid black\"><td style=\"width:330px;padding:10px 0;\"><b>Информация:</b></td><td style=\"padding:10px 0;\"><b>Ответ</b></td></tr>\n";
				foreach ($questions as $i => $q) {
					$msg .= "<tr style=\"border-bottom: 1px solid black\"><td style=\"width:330px;padding:10px 0;\">".$q."</td>";
					$msg .= "<td style=\"padding:10px 0;\">".str_replace("\r\n", '<br>', htmlspecialchars($answers[$i]))."</td></tr>\n";
				}
				$msg .= "</table>\n";
				$msg .= "<p>Ссылка на ответ: <a href=\"".$admUrl."\">".$admUrl."</a></p>\n";
				$msg .= "<p><br><br>------<br>Это письмо отправлено автоматически.</p>\n";

				$hdrs = "MIME-Version: 1.0\r\n";
				$hdrs .= "Content-type: text/html; charset=UTF-8\r\n";
				$hdrs .= "From: ".COption::GetOptionString("main", "email_from")."\r\n";
				bxmail($mails, $subj, $msg, $hdrs);
			}
		}
	}
}
// /Уведомление

/////////////////////////////////////////////////////////////////////

// Серверная проверка расширения файла формы "Рассказать о себе"
AddEventHandler("form", "onAfterResultAdd", "ext_onAfterResultAdd");

function ext_onAfterResultAdd($WEB_FORM_ID, $RESULT_ID) {
	global $APPLICATION;

	if ($WEB_FORM_ID == 5) { // только форма с "Рассказать о себе"
		if ( count( $_FILES ) > 0 ) {
			foreach( $_FILES as $_FILE ) {
				$ext = end( explode( '.', strtolower( $_FILE[ 'name' ] ) ) );
				if ( !in_array( $ext, array( 'pdf', 'doc', 'docx' ) ) ) {
					$APPLICATION->ThrowException("Неверное расширение файла");
				}
			}
		}
	}
}
// /Серверная проверка расширения файла

/////////////////////////////////////////////////////////////////////

// Обработка ссылки для отписки от рассылки
//AddEventHandler("subscribe", "BeforePostingSendMail", "digest_BeforePostingSendMail");

function digest_BeforePostingSendMail($arFields) {
	$USER_SECTS = null;
	$bGoOn = true;

	$USER_NAME = "Подписчик";
	//Попробуем найти подписчика и его "секторы".
	$rs = CSubscription::GetByEmail($arFields["EMAIL"]);
	if ($ar = $rs->Fetch())  {
		if (intval($ar["USER_ID"]) > 0) {
			$rsUser = CUser::GetByID($ar["USER_ID"]);
			if ($arUser = $rsUser->Fetch()) {
				$USER_NAME = $arUser["LAST_NAME"]." ".$arUser["NAME"]." ".$arUser["SECOND_NAME"];
				$USER_SECTS = $arUser["UF_SUB_AN_SECT"];
			}
		}
	}

	if ($USER_SECTS && is_array($USER_SECTS)) {
		$n = 0;
		if (sizeof($USER_SECTS) > 0) {
			preg_match_all("/<!--\[SECT=(\d+)\]-->/im", $arFields["BODY"], $m);
			$PRESENT_SECTS = array_unique($m[1]);
			$n = sizeof($PRESENT_SECTS);
		}
		if ($n > 0) {
			foreach($PRESENT_SECTS as $s) {
				if (!in_array($s, $USER_SECTS)) {
					$arFields["BODY"] = preg_replace("/<!--\[SECT=".$s."\]-->(.+?)<!--\[ENDSECT\]-->/ims", "", $arFields["BODY"]);
					$n--;
				}
			}
			$bGoOn = ($n > 0);
		}
	}

$fl = fopen($_SERVER["DOCUMENT_ROOT"]."/upload/z.txt", "a+");
fwrite($fl, "arFields:\n".print_r($arFields, true)."\n");
/*fwrite($fl, "eml = ".$arFields["EMAIL"]."\n");
fwrite($fl, "USER_SECTS:\n".print_r($USER_SECTS, true)."\n");
fwrite($fl, "PRESENT_SECTS:\n".print_r($PRESENT_SECTS, true)."\n");
fwrite($fl, "bGoOn = $bGoOn\n\n");
fwrite($fl, "BODY:\n".print_r($arFields["BODY"], true)."\n");*/
fclose($fl);

	if ($bGoOn) {
		$arFields["BODY"] = str_replace("#NAME#", $USER_NAME, $arFields["BODY"]);
		$arFields["BODY"] = str_replace("#CONFIRM_CODE#", $ar["CONFIRM_CODE"], $arFields["BODY"]);
		$arFields["BODY"] = str_replace("#ID#", $ar["ID"], $arFields["BODY"]);
		return $arFields;
	} else {
		return null; // Если вернуть false, то отправка будет помечена как "с ошибкой".
	}
}

/////////////////////////////////////////////////////////////////////

// 404 страница для случая, когда элемент не найден:
AddEventHandler("main", "OnEpilog", "_ShowError404", 1);

function _ShowError404()
{
	if (defined('ERROR_404') && ERROR_404=='Y' && !defined('ADMIN_SECTION'))
	{
		GLOBAL $APPLICATION;
		$APPLICATION->RestartBuffer();
		require $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/simple/header.php';
		if (LANGUAGE_ID == "en") {
         require $_SERVER['DOCUMENT_ROOT'].'/en/404_error.php';
		} else {
         require $_SERVER['DOCUMENT_ROOT'].'/404_error.php';
		}
		require $_SERVER['DOCUMENT_ROOT'].'/404_error.php';
		require $_SERVER['DOCUMENT_ROOT'].'/bitrix/templates/simple/footer.php';
	}
}

function multiLanguageVote($str, &$result, $flag) {
    $arStr = explode('|', $str);
	if (count($arStr) > 1 ) {
        if($flag === false) {
            $flag = true;
        }
        if (strstr($_SERVER['REQUEST_URI'], '/en/')) {
            $result = $arStr[1];
        } else {
            $result = $arStr[0];
        }
	} else {
        $result = $arStr[0];
    }

    return $flag;
}

function isDevelopIP(){
	$show = false;
	if($_SERVER["REMOTE_ADDR"] == "185.48.37.95"){ $show = true; }
	if($_SERVER["REMOTE_ADDR"] == "185.48.36.49"){ $show = true; }

	return $show;
}

function pre($data)
{
	$res = "";
	$res.= "<pre>";
	if(is_array($data)){
		$res.= print_r($data, true);
	} else {
		$res.= $data;
	}
	$res.= "</pre>";
	echo $res;
}

AddEventHandler("main", "OnEndBufferContent", "ChangeContent");

function ChangeContent(&$content)
{
	global $USER, $APPLICATION;
	if(strpos($APPLICATION->GetCurDir(), "/bitrix/") === false) {
		$content = str_replace('#get_departments_list#', get_departments_list(true), $content);
	}
}

$eventManager = \Bitrix\Main\EventManager::getInstance();
$eventManager->addEventHandler(
	"main",
	"OnBeforeProlog",
	function () {
		\Bitrix\Main\Page\Asset::getInstance()->addJs("/local/assets/js/visual_editor_fix.js");
	}
);
?>
