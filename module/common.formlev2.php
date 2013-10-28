<?
//-----------------------------------------------------------------
//	add_table_header
//-----------------------------------------------------------------
function add_table_header($label,$value){
	$at = '
	<div id="headtable">
		<div id="hlabel"><b>'.$label.'</b></div>
		<div id="hvalue">'.$value.'</div>
	</div>';
	return $at;
}
//-----------------------------------------------------------------
//	staff_list
//-----------------------------------------------------------------
function staff_list($task_id){
	$q = mysql_query("SELECT a.first_name,a.last_name 
	FROM staff AS a,task_staff AS b WHERE 
	a.id=b.staff AND b.task='".$task_id."'");
	$staff_list = array();
	while($d = mysql_fetch_row($q)){
		array_push($staff_list,$d[0].' '.$d[1]);
	}
	$staff_list1 = implode(', ',$staff_list);
	return $staff_list1;
}
//-----------------------------------------------------------------
//	category_subject_form
//-----------------------------------------------------------------
function category_subject_form(){
	if(empty($_POST['category']) && $_GET['p']!='task_list'){
		$d = mysql_fetch_row(mysql_query("SELECT min(id) FROM category"));
		$_POST['category'] = $d[0];
	}
	form_select_db('Category','category','name',$_POST['category'],'onchange="submit()"');
	form_select_db('Subject','subject','name',$_POST['subject'],
	null,"WHERE category='".$_POST['category']."'");
}
//-----------------------------------------------------------------
//	staff_list_form
//-----------------------------------------------------------------
function staff_list_form(){
	$number = array();
	for($i=1;$i<=6;$i++){
		array_push($number,$i);
	}
	if(empty($_GET['edit'])){
		if(empty($_POST['add_staff'])) $_POST['add_staff']=1;
	}else{
		$q = mysql_query("SELECT staff FROM task_staff WHERE task='".$_GET['edit']."'");
		$j=1;
		while($d = mysql_fetch_row($q)){
			$str_name = 'staff'.$j;
			$_POST[$str_name] = $d[0];
			$j++;
		}
		$sq = mysql_num_rows($q);
		if(empty($_POST['add_staff'])) $_POST['add_staff']=$sq;
	}
	form_select_array('Add Staff','add_staff',$number,$number,
	$_POST['add_staff'],'onchange="submit()"');
	for($i=1;$i<=$_POST['add_staff'];$i++){
		$str_name = 'staff'.$i;
		form_select_db('Staff '.$i,$str_name,'first_name',$_POST[$str_name]);
	}
}
//-----------------------------------------------------------------
//	priority_status_form
//-----------------------------------------------------------------
function priority_status_form(){
	$priority1 = array('1','2','3');
	$priority2 = array('high','middle','low');
	form_select_array('Priority','priority',$priority1,$priority2,$_POST['priority']);
	$status = array('closed','on progress','pending');
	form_select_array('Status','status',$status,$status,$_POST['status']);
}
//-----------------------------------------------------------------
//	priority_teks
//-----------------------------------------------------------------
function priority_teks($p){
	if($p=='1'){
		return 'high';
	}elseif($p=='2'){
		return 'middle';
	}else{
		return 'low';
	}
}
//-----------------------------------------------------------------
//	amount_calc
//-----------------------------------------------------------------
function amount_calc(){
	list($start_num,$stop_num) = start_stop();
	$num = $start_num;
	$amount=0;
	$free_amount=0;
	$holydays=array('0','6');
	while($num<=$stop_num){
		if(!in_array(date("w",$num),$holydays)){
			$amount++;
		}else{
			$free_amount++;
		}
		$num+=86400;
	}
	$out = array($amount,$free_amount);
	return $out;
}
//-----------------------------------------------------------------
//	amount_calc_hour
//-----------------------------------------------------------------
function amount_calc_hour(){
	list($start_num,$stop_num) = start_stop();
	$num = $stop_num-$start_num;
	$hour = floor($num/3600);
	$minutes = ($num%3600)/60;
	$out = array($hour,$minutes);
	return $out;
}
//-----------------------------------------------------------------
//	start_stop
//-----------------------------------------------------------------
function start_stop(){
	global $enable_time;
	if(in_array($_GET['p'],$enable_time)){
		$start_num = mktime($_POST['hstart'],$_POST['istart'],0,
		$_POST['mstart'],$_POST['dstart'],$_POST['ystart']);
		$stop_num = mktime($_POST['hstop'],$_POST['istop'],0,
		$_POST['mstop'],$_POST['dstop'],$_POST['ystop']);
	}else{
		$start_num = mktime(0,0,0,$_POST['mstart'],$_POST['dstart'],$_POST['ystart']);
		$stop_num = mktime(23,50,50,$_POST['mstop'],$_POST['dstop'],$_POST['ystop']);
	}
	if($start_num>$stop_num){
		if(in_array($_GET['p'],$enable_time)){
			$start_num=$stop_num+3600;
			$_POST['hstart']=$_POST['hstop'];
			$_POST['istart']=$_POST['istop'];
			$_POST['hstop']=$_POST['hstop']+1;
		}else{
			$start_num=$stop_num;
		}
		$_POST['mstart']=$_POST['mstop'];
		$_POST['dstart']=$_POST['dstop'];
		$_POST['ystart']=$_POST['ystop'];
	}
	$out = array($start_num,$stop_num);
	return $out;
}
?>
