<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $APPLICATION;

global $SUBSCRIBE_TEMPLATE_RUBRIC;
$SUBSCRIBE_TEMPLATE_RUBRIC=$arRubric;

global $SUBSCRIBE_TEMPLATE_RESULT;
$SUBSCRIBE_TEMPLATE_RESULT = false;
?>
<STYLE type=text/css>
.text {font-family: Verdana, Arial, Helvetica, sans-serif; font-size:12px; color: #1C1C1C; font-weight: normal;}
.newsdata{font-family: Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; color: #346BA0; text-decoration:none;}
H1 {font-family: Verdana, Arial, Helvetica, sans-serif; color:#346BA0; font-size:15px; font-weight:bold; line-height: 16px; margin-bottom: 1mm;}
</STYLE>

<P><?= GetMessage("news_template_name") ?></P>

<P>
<?
$SUBSCRIBE_TEMPLATE_RESULT = $APPLICATION->IncludeComponent(
	"bitrix:subscribe.news",
	"analytics",
	Array(
		"SITE_ID" => "s1",
		"IBLOCK_TYPE" => "analytics",
		"ID" => 24,
		"SORT_BY" => "ACTIVE_FROM",
		"SORT_ORDER" => "DESC",
	),
	null,
	array(
		"HIDE_ICONS" => "Y",
	)
);

$SUBSCRIBE_TEMPLATE_RESULT += $APPLICATION->IncludeComponent(
	"bitrix:subscribe.news",
	"analytics",
	Array(
		"SITE_ID" => "s2",
		"IBLOCK_TYPE" => "analytics",
		"ID" => 50,
		"SORT_BY" => "ACTIVE_FROM",
		"SORT_ORDER" => "DESC",
	),
	null,
	array(
		"HIDE_ICONS" => "Y",
	)
);
?>

<P>Unsubscribe link:
<?
	$lnk = "http://cbre.rentnow.ru/analytics/subscribe.php?ID=#ID#&CONFIRM_CODE=#CONFIRM_CODE#&action=unsubscribe";
?>
<a href="<?= $lnk ?>"><?= $lnk ?></a>
</P>

<P>Best Regards!</P>
<?
if($SUBSCRIBE_TEMPLATE_RESULT)
	return array(
		"SUBJECT"=>$SUBSCRIBE_TEMPLATE_RUBRIC["NAME"],
		"BODY_TYPE"=>"html",
		"CHARSET"=>"UTF-8",
		"DIRECT_SEND"=>"Y",
		"FROM_FIELD"=>$SUBSCRIBE_TEMPLATE_RUBRIC["FROM_FIELD"],
	);
else
	return false;
?>