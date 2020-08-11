<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__);

$arComponentDescription = array(
	"NAME" => Loc::getMessage('cust_delivery'),
	"DESCRIPTION" => Loc::getMessage('cust_delivery_deck'),
	"SORT" => 20,
	"PATH" => array(
		"ID" => 'ipg',
		// "NAME" => Loc::getMessage('ipg'),
		"SORT" => 10,
		// "CHILD" => array(
		// 	"ID" => 'deal.report',
		// 	"NAME" => Loc::getMessage('deal_report_dir'),
		// 	"SORT" => 10
		// )
	),
);

?>