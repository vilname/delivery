<?
use Bitrix\Main\Localization\Loc;
// use Bitrix\Main\Entity;
// use Bitrix\Main\ORM\Query;
use \Bitrix\Main\Service\GeoIp;

\Bitrix\Main\Loader::includeModule('sale');

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true){
	die();
}

Loc::loadMessages(__FILE__);

class ReestrList extends CBitrixComponent{

	// public function onPrepareComponentParams($params)
	// {
		
	// }

	protected function getDelivery($code = ''){

		

		if($code){
		  $obItem =	\Bitrix\Sale\Delivery\DeliveryLocationTable::getList([
				'filter' => ['LOCATION_CODE' => $code, 'LOCATION_TYPE' => 'LE', '!LOCATION_TYPE' => 'L']
			]);

			while($arItem = $obItem->fetch()){
				$res[] = $arItem['DELIVERY_ID'];
			}

		
		}

		$obItem = Bitrix\Sale\Delivery\Services\Table::getList([
			'filter' => $res ? ['!ID' => $res] : ['*']
		]);

		while($arItem = $obItem->fetch()){
			$result[] = $arItem;
		}

		return $result;
	}

	public function getGeo(){
		$ipAddress = GeoIp\Manager::getRealIp();
		$result = GeoIp\Manager::getDataResult($ipAddress, "ru")->getGeoData();

		$arFilter = [
			'CITY_NAME' => $result->cityName,
			'REGION_NAME' => $result->regionName,
		];

    $arFilter['COUNTRY_LID'] = 'ru';

    $db_vars = CSaleLocation::GetList(
      array(),
      $arFilter,
      false,
      array('nTopCount' => 1),
      array('ID', 'CITY_NAME', 'CODE')
    );
    while ($vars = $db_vars->Fetch()){
			$resultItem['CODE'] = $vars['CODE'];
		}
		
		return $resultItem;
	}
	
	public function executeComponent(){
		$postList = Bitrix\Main\Context::getCurrent()->getRequest()->getPostList();

		if($postList['CITY']){
			$code = $postList['CITY'];
		}else{
			$code = $this->getGeo();
		}
		


		$this->arResult = $this->getDelivery($code);
		

		$this->includeComponentTemplate();
		
	}
}