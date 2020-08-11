<?
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/local/php_interface/classes/IpgDealReport.php");
$date = $_REQUEST["date"];
$reportData = IpgDealReport::getDataForReport($date);

$users = $reportData["users_data"];
/*
echo "<pre>";
print_r($reportData);
echo "</pre>";
*/

function getIncompletCnt($arr){
	$cnt = 0;
	foreach($arr as $k=>$v){
		$cnt+=$v["CNT"];
	}
	return $cnt;
}
?>
<?if($_REQUEST["type"]=="html"){?>
<table class="report-table">
	<tr>
		<th>Наименование программы</th>
		<th>Кол-во заявок ВСЕГО</th>
		<th>Кол-во подтвержденных заявок</th>
		<th>В т.ч. оплаченных</th>
		<th>Неподтвержденные заявки </th>
	</tr>
	<?
	$summs = [
		"TOTAL"=>0,
		"WAIT_PAYMENT_AND_ORDER_MAKED"=>0,
		"PAYED_AND_MORE"=>0,
		"INCOMPLETE"=>0,
		];
	foreach($reportData["programs"] as $code=>$program){
	?>
	<tr>
		<td><?=$code?></td>
		<td><?=$program["TOTAL"]?></td>
		<td><?=$program["WAIT_PAYMENT_AND_ORDER_MAKED"]?></td>
		<td><?=$program["PAYED_AND_MORE"]?></td>
		<td><?=getIncompletCnt($program["INCOMPLETE"])?> <a href="#" onclick="showMoreInfo(this); return false;" data-id="hide_<?=md5($code);?>">
		<small>показать/скрыть</small></a>
			<div class="hideinfo" id="hide_<?=md5($code);?>">
				<?foreach($program["INCOMPLETE"] as $k=>$incompleteDeal){
				?>
					<a href="/crm/deal/details/<?=$incompleteDeal["DEAL_ID"]?>/"><?=$incompleteDeal["NAME"]?></a><br>
				<?
				}?>
			</div>
		</td>
	</tr>
	<?
	$summs["TOTAL"]+=$program["TOTAL"];
	$summs["WAIT_PAYMENT_AND_ORDER_MAKED"]+=$program["WAIT_PAYMENT_AND_ORDER_MAKED"];
	$summs["PAYED_AND_MORE"]+=$program["PAYED_AND_MORE"];
	$summs["INCOMPLETE"]+=getIncompletCnt($program["INCOMPLETE"]);
	?>
	<?}?>
	<tr class="darkback">
		<td>ВСЕГО ЗА ДЕНЬ по админам</td>
		<td><?=$summs["TOTAL"]?></td>
		<td><?=$summs["WAIT_PAYMENT_AND_ORDER_MAKED"]?></td>
		<td><?=$summs["PAYED_AND_MORE"]?></td>
		<td><?=$summs["INCOMPLETE"]?></td>
	</tr>
	<?
	foreach($reportData["admins"] as $id=>$data){
	?>
	<tr>
		<td><?=$users[$id]["LAST_NAME"]?> <?=$users[$id]["NAME"]?> <?=$users[$id]["SECOND_NAME"]?></td>
		<td><?=$data["TOTAL"]?></td>
		<td><?=$data["WAIT_PAYMENT_AND_ORDER_MAKED"]?></td>
		<td><?=$data["PAYED_AND_MORE"]?></td>
		<td><?=getIncompletCnt($data["INCOMPLETE"]);?><a href="#" onclick="showMoreInfo(this); return false;" data-id="a_hide_<?=md5($id);?>">
		<small>показать/скрыть</small></a>
			<div class="hideinfo" id="a_hide_<?=md5($id);?>">
				<?foreach($data["INCOMPLETE"] as $k=>$incompleteDeal){
				?>
					<a href="/crm/deal/details/<?=$incompleteDeal["DEAL_ID"]?>/"><?=$incompleteDeal["NAME"]?></a><br>
				<?
				}?>
			</div>
		
		</td>
	</tr>

	<?}?>
	
</table>

<?}elseif($_REQUEST["type"]=="excel"){?>
<?
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=Deal_report_".str_replace(".","_",$_REQUEST["date"]).".xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<table border="1">
	<thead>
	<tr>
		<th>Наименование программы</th>
		<th>Кол-во заявок ВСЕГО</th>
		<th>Кол-во подтвержденных заявок</th>
		<th>В т.ч. оплаченных</th>
		<th>Неподтвержденные заявки </th>
	</tr>
	</thead>
	<tbody>
	<?
	$summs = [
		"TOTAL"=>0,
		"WAIT_PAYMENT_AND_ORDER_MAKED"=>0,
		"PAYED_AND_MORE"=>0,
		"INCOMPLETE"=>0,
		];
	foreach($reportData["programs"] as $code=>$program){
	?>
	<tr>
		<td><?=$code?></td>
		<td><?=$program["TOTAL"]?></td>
		<td><?=$program["WAIT_PAYMENT_AND_ORDER_MAKED"]?></td>
		<td><?=$program["PAYED_AND_MORE"]?></td>
		<td><?=getIncompletCnt($program["INCOMPLETE"])?> 
			<?foreach($program["INCOMPLETE"] as $k=>$incompleteDeal){
			?>
				<a href="https://<?=PORTAL_DOMAIN?>/crm/deal/details/<?=$incompleteDeal["DEAL_ID"]?>/"><?=$incompleteDeal["NAME"]?></a><br>
			<?
			}?>
		</td>
	</tr>
	<?
	$summs["TOTAL"]+=$program["TOTAL"];
	$summs["WAIT_PAYMENT_AND_ORDER_MAKED"]+=$program["WAIT_PAYMENT_AND_ORDER_MAKED"];
	$summs["PAYED_AND_MORE"]+=$program["PAYED_AND_MORE"];
	$summs["INCOMPLETE"]+=getIncompletCnt($program["INCOMPLETE"]);
	?>
	<?}?>
	<tr>
		<td>ВСЕГО ЗА ДЕНЬ по админам</td>
		<td><?=$summs["TOTAL"]?></td>
		<td><?=$summs["WAIT_PAYMENT_AND_ORDER_MAKED"]?></td>
		<td><?=$summs["PAYED_AND_MORE"]?></td>
		<td><?=$summs["INCOMPLETE"]?></td>
	</tr>
	<?
	foreach($reportData["admins"] as $id=>$data){
	?>
	<tr>
		<td><?=$users[$id]["LAST_NAME"]?> <?=$users[$id]["NAME"]?> <?=$users[$id]["SECOND_NAME"]?></td>
		<td><?=$data["TOTAL"]?></td>
		<td><?=$data["WAIT_PAYMENT_AND_ORDER_MAKED"]?></td>
		<td><?=$data["PAYED_AND_MORE"]?></td>
		<td><?=getIncompletCnt($data["INCOMPLETE"]);?>

				<?foreach($data["INCOMPLETE"] as $k=>$incompleteDeal){
				?>
					<a href="https://bitrix24.doctornauchebe.ru/crm/deal/details/<?=$incompleteDeal["DEAL_ID"]?>/"><?=$incompleteDeal["NAME"]?></a><br>
				<?
				}?>		
		</td>
	</tr>

	<?}?>
	<tbody>
</table>

<?}elseif($_REQUEST["type"]=="excel5"){?>
<?


	require_once($_SERVER['DOCUMENT_ROOT'] .'/phpoffice/vendor/autoload.php');
	//ini_set("mbstring.func_overload", 1 );
	//require_once($_SERVER['DOCUMENT_ROOT'] . '/PHPExcel/Classes/PHPExcel.php');
	//require_once($_SERVER['DOCUMENT_ROOT'] . '/PHPExcel/Classes/PHPExcel/Writer/Excel5.php');
	//
	
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setTitle('Отчет по программам');
	$sheet->setCellValue('A1', 'Hello World !');

	//$writer = new Xlsx($spreadsheet);
	//$writer->save('hello world.xlsx');


	// Создаем объект класса PHPExcel
	//$xls = new PHPExcel();
	// Устанавливаем индекс активного листа
	//$xls->setActiveSheetIndex(0);
	// Получаем активный лист
	//$sheet = $xls->getActiveSheet();
	// Подписываем лист
	//$sheet->setTitle('Отчет по программам');

	$headers = [
		"Наименование программы",
		"Кол-во заявок ВСЕГО",
		"Кол-во подтвержденных заявок",
		"В т.ч. оплаченных",
		"Неподтвержденные заявки ",
	];
	$i = 1;
	for($j=1;$j<=5;$j++){
		$sheet->setCellValueByColumnAndRow($i,$j,$headers[$j-1]);
	}
	
	
	header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
	header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
	header ( "Cache-Control: no-cache, must-revalidate" );
	header ( "Pragma: no-cache" );
	header ( "Content-type: application/vnd.ms-excel" );
	header ( "Content-Disposition: attachment; filename=matrix.xls" );
	
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	 

	// Выводим содержимое файла
	 //$objWriter = new PHPExcel_Writer_Excel5($xls);
	 //$objWriter->save('php://output');
	 //$objWriter->save($_SERVER['DOCUMENT_ROOT'] . 'excel.xlsx');*/
	 
	/*
	// Вставляем текст в ячейку A1
	$sheet->setCellValue("A1", 'Таблица умножения');
	$sheet->getStyle('A1')->getFill()->setFillType(
	    PHPExcel_Style_Fill::FILL_SOLID);
	$sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');

	// Объединяем ячейки
	$sheet->mergeCells('A1:H1');

	// Выравнивание текста
	$sheet->getStyle('A1')->getAlignment()->setHorizontal(
	    PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

	for ($i = 2; $i < 10; $i++) {
		for ($j = 2; $j < 10; $j++) {
	        // Выводим таблицу умножения
	        $sheet->setCellValueByColumnAndRow(
	                                          $i - 2,
	                                          $j,
	                                          $i . "x" .$j . "=" . ($i*$j));
		    // Применяем выравнивание
		    $sheet->getStyleByColumnAndRow($i - 2, $j)->getAlignment()->
	                setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		}
	}
*/
	// Выводим HTTP-заголовки
	/*
	 header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
	 header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
	 header ( "Cache-Control: no-cache, must-revalidate" );
	 header ( "Pragma: no-cache" );
	 header ( "Content-type: application/vnd.ms-excel" );
	 header ( "Content-Disposition: attachment; filename=matrix.xls" );
	 

	// Выводим содержимое файла
	 $objWriter = new PHPExcel_Writer_Excel5($xls);
	 //$objWriter->save('php://output');
	 $objWriter->save($_SERVER['DOCUMENT_ROOT'] . 'excel.xlsx');*/
?>
<?}?>

