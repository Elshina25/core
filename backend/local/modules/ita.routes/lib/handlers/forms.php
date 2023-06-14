<?php

namespace Ita\Routes\Handlers;

use Bitrix\Main\{
    Loader,
    Entity,
    ORM\Query
};

class Forms {
    function helpInSelectionAdd($WEB_FORM_ID, $RESULT_ID)
    {
        Loader::includeModule("form");
        $formSID = "GET_HELP_IN_SELECTION";
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        if ($formId == $WEB_FORM_ID) {
            \CForm::GetResultAnswerArray($WEB_FORM_ID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $RESULT_ID));
            $answer = reset($arrAnswersVarname);
            $questions = [];
            $answers = [];
            foreach ($arrColumns as $qID => $q) {
                $questions[$qID] = $q["TITLE"];
                $ks = array_keys($arrAnswers[$RESULT_ID][$qID]);
                $ans = $arrAnswers[$RESULT_ID][$qID][$ks[0]];
                $answers[$qID] = $ans["USER_TEXT"] ? $ans["USER_TEXT"] : $ans["ANSWER_TEXT"];
            }
            $crmId = $answer["CRM_ID"][0]["USER_TEXT"];

            $type = "office";
            $emails = [
                "office" => "victoria.frishter@core-xp.ru, victoria.tsukanova@core-xp.ru",
                "industrial_logistics" => "victoria.frishter@core-xp.ru, aleksei.desiukevich@core-xp.ru, marina.koshelkina@core-xp.ru, anton.alyabyev@core-xp.ru, victoria.tsukanova@core-xp.ru",
                "retail" => "victoria.frishter@core-xp.ru, evgenia.prilutskaya@core-xp.ru, irina.razorenova@core-xp.ru, victoria.tsukanova@core-xp.ru"
            ];
            if ($crmId) {
                $type = static::getTypeByCrmId($crmId);
            } else {
                $page = $answer["PAGE"][0]["USER_TEXT"];
                $types = array_keys($emails);
                foreach ($types as $t) {
                    if (mb_stripos($page, $t)) {
                        $type = $t;
                        break;
                    }
                }
            }
            $mail = $emails[$type];
            static::sendFormEmail($RESULT_ID, $WEB_FORM_ID, $questions, $answers, $mail);
        }
    }

    function requestForViewAdd($WEB_FORM_ID, $RESULT_ID)
    {
        Loader::includeModule("form");
        $formSID = "REQUEST_FOR_VIEW";
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        if ($formId == $WEB_FORM_ID) {
            \CForm::GetResultAnswerArray($WEB_FORM_ID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $RESULT_ID));
            $answer = reset($arrAnswersVarname);
            $questions = [];
            $answers = [];
            foreach ($arrColumns as $qID => $q) {
                $questions[$qID] = $q["TITLE"];
                $ks = array_keys($arrAnswers[$RESULT_ID][$qID]);
                $ans = $arrAnswers[$RESULT_ID][$qID][$ks[0]];
                $answers[$qID] = $ans["USER_TEXT"] ? $ans["USER_TEXT"] : $ans["ANSWER_TEXT"];
            }
            $crmId = $answer["CRM_ID"][0]["USER_TEXT"];

            $type = "office";
            $emails = [
                "office" => "victoria.frishter@core-xp.ru, victoria.tsukanova@core-xp.ru",
                "industrial_logistics" => "victoria.frishter@core-xp.ru, aleksei.desiukevich@core-xp.ru, marina.koshelkina@core-xp.ru, anton.alyabyev@core-xp.ru, victoria.tsukanova@core-xp.ru",
                "retail" => "victoria.frishter@core-xp.ru, evgenia.prilutskaya@core-xp.ru, irina.razorenova@core-xp.ru, victoria.tsukanova@core-xp.ru"
            ];
            if ($crmId) {
                $type = static::getTypeByCrmId($crmId);
            }
            $mail = $emails[$type];
            static::sendFormEmail($RESULT_ID, $WEB_FORM_ID, $questions, $answers, $mail);
        }
    }

    function getTypeByCrmId(string $crmId) {
        $type = "office";
        $realty = \RealtyObjectsClassTable::getList([
            "filter" => ["PropertyID" => $crmId],
            "select" => [
                "*",
                "BUSINESS_UNIT_ID" => "BUSINESS_LINKED.business_unit_id"
            ],
            "runtime" => [
                new Entity\ReferenceField(
                    "BUSINESS_LINKED",
                    \RealtyObjectsVsUnitsClassTable::class,
                    Query\Join::on('this.id', 'ref.object_id'),
                    ["join_type" => Query\Join::TYPE_LEFT]
                ),
            ]
        ]);
        if ($object = $realty->fetch()) {
            switch ($object["BUSINESS_UNIT_ID"]) {
                case 1:
                    $type = "industrial_logistics";
                    break;
                case 2:
                    switch ($object["PropertyType"]) {
                        case 1:
                            $type = "industrial_logistics";
                            break;
                        case 2:
                            $type = "retail";
                            break;
                        case 3:
                            $type = "office";
                            break;
                        default:
                            $type = "office";
                            break;
                    }
                    break;
                case 3:
                    $type = "retail";
                    break;
                case 4:
                    $type = "office";
                    break;
                case 5:
                    $type = "office";
                    break;
            }
        }

        return $type;
    }

    function discussTaskAdd($WEB_FORM_ID, $RESULT_ID)
    {
        Loader::includeModule("form");
        Loader::includeModule("iblock");
        $formSID = "DISCUSS_TASK";
        $rsForm = \CForm::GetBySID($formSID);
        $arForm = $rsForm->Fetch();
        $formId = $arForm["ID"];
        if ($formId == $WEB_FORM_ID) {
            \CForm::GetResultAnswerArray($WEB_FORM_ID, $arrColumns, $arrAnswers, $arrAnswersVarname, array("RESULT_ID" => $RESULT_ID));
            $answer = reset($arrAnswersVarname);
            $questions = [];
            $answers = [];
            foreach ($arrColumns as $qID => $q) {
                $questions[$qID] = $q["TITLE"];
                $ks = array_keys($arrAnswers[$RESULT_ID][$qID]);
                $ans = $arrAnswers[$RESULT_ID][$qID][$ks[0]];
                $answers[$qID] = $ans["USER_TEXT"] ? $ans["USER_TEXT"] : $ans["ANSWER_TEXT"];
            }
            $interest = $answer["INTEREST"][0]["ANSWER_VALUE"];
            $type = $answer["TYPE"][0]["ANSWER_VALUE"];
            $page = $answer["PAGE"][0]["USER_TEXT"];
            $elements = \Bitrix\Iblock\Elements\ElementRulesTable::getList([
                'select' => ["*"],
                'filter' => [
                    "ACTIVE" => "Y",
                    "INTEREST.ITEM.XML_ID" => $interest,
                    "TYPE.ITEM.XML_ID" => $type,
                ],
                "order" => ["SORT" => "ASC"],
            ]);
            if ($element = $elements->fetch()) {
                $mail = $element["PREVIEW_TEXT"];
            } else {
                $elements = \Bitrix\Iblock\Elements\ElementRulesTable::getList([
                    'select' => ["*", "URL_VALUE" => "URL.VALUE"],
                    'filter' => [
                        "ACTIVE" => "Y",
                        "!URL.VALUE" => false,
                    ],
                    "order" => ["SORT" => "DESC"],
                ]);
                while ($element = $elements->fetch()) {
                    if (mb_stripos($page, $element["URL_VALUE"]) !== false) {
                        $mail = $element["PREVIEW_TEXT"];
                        break;
                    }
                }
            }

            if ($mail) {
                static::sendFormEmail($RESULT_ID, $WEB_FORM_ID, $questions, $answers, $mail);
            }
        }
    }

    function sendFormEmail(int $resultId, int $formId, array $questions, array $answers, string $emails)
    {
        Loader::includeModule("form");
        $rsResult = \CFormResult::GetByID($resultId);
        $arResult = $rsResult->Fetch();
        $fname = $arResult["NAME"];

        $admUrl = "http://".$_SERVER["SERVER_NAME"]."/bitrix/admin/form_result_edit.php?lang=ru&WEB_FORM_ID=".$formId."&RESULT_ID=".$resultId;

        $subj = "Заполнена форма \"".$fname."\" на сайте "."https://rentnow.ru/";
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
        $hdrs .= "From: ".\COption::GetOptionString("main", "email_from")."\r\n";
        bxmail($emails, $subj, $msg, $hdrs);
    }
}