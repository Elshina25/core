<?php
define( 'INDEX_PAGE', TRUE );
define('NOT_SHOW_SLIDER', TRUE);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Международная консалтинговая компания CBRE");
$APPLICATION->SetPageProperty("keywords", "коммерческая недвижимость, торговые объекты, исследования рынка, управление активами, агентские услуги, консультации в строительстве, девелопмент, собственники и арендаторы, Офис аренда, Офис с отделкой аренда, Аренда офиса, Аренда офиса с отделкой, Склад аренда, Аренда склада, Офис субаренда, Субаренда офиса, Представление интересов арендаторов, Представление интересов собственников, Представление интересов арендодателей, Маркетинг объектов недвижимости, Перезаключение договоров аренды, управление недвижимостью, Управление строительством, Управление внутренней отделкой, Управление проектированием, Консалтинговые услуги в недвижимости, Оценка объектов недвижимости, Инвестиционный консалтинг, продажа офиса, продажа земельного участка, торговый центр в аренду");
$APPLICATION->SetPageProperty("title", "Аренда офисов и складов в Москве, продажа и аренда офисных и складских помещений - CBRE");
$APPLICATION->SetPageProperty("NOT_SHOW_NAV_CHAIN", "Y");
$APPLICATION->SetTitle("CBRE");
?>

<?php
    $APPLICATION->IncludeFile('/includes/main-page-tabs.php');
    $APPLICATION->IncludeFile('/includes/main-page-about.php');
    $APPLICATION->IncludeFile('/includes/main-page-reviews.php');
    $APPLICATION->IncludeComponent(
        "changed_form:form",
        "mainpage_offer",
        Array(
            "AJAX_MODE" => "N",
            "SEF_MODE" => "N",
            "WEB_FORM_ID" => "10",
            "RESULT_ID" => $_REQUEST["RESULT_ID"],
            "START_PAGE" => "new",
            "SHOW_LIST_PAGE" => "N",
            "SHOW_EDIT_PAGE" => "N",
            "SHOW_VIEW_PAGE" => "N",
            "SUCCESS_URL" => "",
            "SHOW_ANSWER_VALUE" => "N",
            "SHOW_ADDITIONAL" => "N",
            "SHOW_STATUS" => "Y",
            "EDIT_ADDITIONAL" => "N",
            "EDIT_STATUS" => "Y",
            "NOT_SHOW_FILTER" => array(),
            "NOT_SHOW_TABLE" => array(),
            "CHAIN_ITEM_TEXT" => "",
            "CHAIN_ITEM_LINK" => "",
            "IGNORE_CUSTOM_TEMPLATE" => "N",
            "USE_EXTENDED_ERRORS" => "N",
            "CACHE_TYPE" => "A",
            "CACHE_TIME" => "3600",
            "AJAX_OPTION_JUMP" => "N",
            "AJAX_OPTION_STYLE" => "Y",
            "AJAX_OPTION_HISTORY" => "N",
            "VARIABLE_ALIASES" => Array(
                "action" => "action"
            )
        )
    );
    $APPLICATION->IncludeFile('/includes/main-page-awards.php');
    $APPLICATION->IncludeFile('/includes/main-page-analytics.php');
    $APPLICATION->IncludeFile('/includes/main-page-services.php');

?>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
