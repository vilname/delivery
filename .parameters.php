<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main;
use Bitrix\Main\Localization\Loc as Loc;

Loc::loadMessages(__FILE__); 

$arComponentParameters = array(
	'GROUPS' => array(
	),
	'PARAMETERS' => array(
		// "COUNT" => Array(
		// 	'NAME' => Loc::getMessage("IPG_REESTR_COUNT_PAGE"),
    //   'TYPE' => 'STRING',
    //   'VALUES' => ''
		// ),
	)
);

?>