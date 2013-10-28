<?
require('common.connect.php');
require('module.client.php');
require('common.formlev2.php');
require_once "../plugin/excel-module/class.writeexcel_workbookbig.inc.php";
require_once "../plugin/excel-module/class.writeexcel_worksheet.inc.php";
$fname = tempnam('../plugin/excel-module/temp',"blueline");
$workbook =& new writeexcel_workbookbig($fname);
$worksheet =& $workbook->addworksheet('Client');
$worksheet->hide_gridlines(2);
$worksheet->set_landscape();
$worksheet->set_paper(8);
include("exel_style.php");
//
$head = array('Category','Name','Address','Telp','Mobile','E-mail');
$col_width = array(25,30,30,25,25,25);
for ($i=0;$i<6;$i++){
	$worksheet->set_column($i,$i,$col_width[$i]);
}
//
$judul = 'Client_Report_'.date("d-M-Y_h:i:A");
$title_col1 = array('Client Report','','','','','');
//
$worksheet->set_row(0,15);
$worksheet->set_selection('C3');
$worksheet->write(0,0,$title_col1,$merg_cel_titel);
//
$j=0;
foreach($head AS $head1){
	$worksheet->write(2,$j,$head1,$header2);
	$j++;
}
//
list($rows,$ids,$where) = client_data();
$i=3;
$no=1;
foreach($rows AS $d){
	$j=0;
	foreach($d AS $d1){
		$worksheet->write($i,$j,strip_tags($d1),$headerDt);
		$j++;
	}
	$i++;
}
$workbook->close();
//
header("Content-Type: application/x-msexcel; name=\"$judul.xls\"");
header("Content-Disposition: inline; filename=\"$judul.xls\"");
$fh=fopen($fname, "r");
fpassthru($fh);
unlink($fname);
?>
