<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die(); ?>
<?
use Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);
$this->setFrameMode(true);
CJSCore::Init(array('jquery'));
?>

<form method="post">
	<select name='CITY'>
		<option value="0000812044" selected>Екатеринбург</option>
		<option value="0000854968">Челябинск</option>
		<option value="0000670178">Пермь</option>
	</select>

	<input type="submit" value="Отправить" />
</form>

<?

global $USER;
if($USER->isAdmin()){
echo "<pre>";
print_r($arResult);
echo "</pre>";
}


?>