<?
//-----------------------------------------------------------------
//	HEAD
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	head_overtime_list
//-----------------------------------------------------------------
function head_overtime_list(){
	if(isset($_GET['delete'])){
		mysql_query("DELETE FROM overtime WHERE id='".$_GET['delete']."'");
		header('location:index.php?p=overtime_list');
	}
}
//-----------------------------------------------------------------
//	head_overtime_approval_list
//-----------------------------------------------------------------
function head_overtime_approval_list(){
	if(isset($_GET['approve'])){
		mysql_query("UPDATE overtime SET status='approved' WHERE id='".$_GET['approve']."'");
		header('location:index.php?p=overtime_approval_list');
	}
}
//-----------------------------------------------------------------
//	head_overtime_add
//-----------------------------------------------------------------
function head_overtime_add(){
	if(isset($_POST['add'])){
		$start = $_POST['ystart'].'-'.$_POST['mstart'].'-'.$_POST['dstart'].
		'-'.$_POST['hstart'].'-'.$_POST['istart'];
		$stop = $_POST['ystop'].'-'.$_POST['mstop'].'-'.$_POST['dstop'].
		'-'.$_POST['hstop'].'-'.$_POST['istop'];
		$id = auto_number('overtime');
		mysql_query("INSERT INTO overtime VALUES('".$id."','".$_POST['staff']."','".$start."',
		'".$stop."','".$_POST['amount']."','".$_POST['description']."','".$_POST['status']."')") 
		or die(mysql_error());
		header('location:index.php?p=overtime_list');
	}
}
//-----------------------------------------------------------------
//	head_overtime_edit
//-----------------------------------------------------------------
function head_overtime_edit(){
	if(isset($_POST['edit_send'])){
		$start = $_POST['ystart'].'-'.$_POST['mstart'].'-'.$_POST['dstart'].
		'-'.$_POST['hstart'].'-'.$_POST['istart'];
		$stop = $_POST['ystop'].'-'.$_POST['mstop'].'-'.$_POST['dstop'].
		'-'.$_POST['hstop'].'-'.$_POST['istop'];
		mysql_query("UPDATE overtime SET 
		staff='".$_POST['staff']."',start='".$start."',
		stop='".$stop."',amount='".$_POST['amount']."',
		description='".$_POST['description']."',status='".$_POST['status']."' 
		WHERE id='".$_POST['edit']."'") or die(mysql_error());
		header('location:index.php?p=overtime_list');
	}
}
//-----------------------------------------------------------------
//	MAIN
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	main_overtime_list
//-----------------------------------------------------------------
function main_overtime_list(){
	$title = 'Overtime List';
	$head = array('Staff','Start','Stop','Amount','Description','Status');
	$width = array('20%','15%','15%','8%','32%','10%');
	$link = '<a href="index.php?p=overtime_add">Add New Overtime &raquo;</a>';
	$content1 = 'index.php?p=overtime_edit&edit=';
	$content2 = 'index.php?p=overtime_list&delete=';
	list($rows,$ids,$where) = overtime_data();
	$add_title = array();
	$at = add_table_header('Export To : ',
	'<a href="module/report.excel.overtime.php?where='.$where.'">Excel</a>');
	array_push($add_title,$at);
	table_output($title,$content1,$content2,$head,$width,$link,$rows,$ids,null,'overtime',null,null,$add_title);
}
//-----------------------------------------------------------------
//	overtime_data
//-----------------------------------------------------------------
function overtime_data(){
	if(empty($_GET['where'])){
		$where = overtime_search_input();
	}else{
		$where = stripslashes($_GET['where']);
	}
	$q = mysql_query("SELECT b.first_name,b.last_name,
	a.start,a.stop,a.amount,a.description,a.status,a.id FROM 
	overtime AS a,staff AS b WHERE a.staff=b.id ".$where." 
	ORDER BY a.id DESC") or die(mysql_error());
	$rows = array();
	$ids = array();
	while($data = mysql_fetch_array($q)){
		$staff_list = staff_list($data[7]);
		list($h,$i) = explode(':',$data[4]);
		$amount = $h.'h '.$i.'m';
		$d = array($data[0].' '.$data[1],$data[2],$data[3],$amount,$data[5],$data[6]);
		array_push($rows,$d);
		array_push($ids,$data[7]);
	}
	$out = array($rows,$ids,$where);
	return $out;
}
//-----------------------------------------------------------------
//	main_overtime_approval_list
//-----------------------------------------------------------------
function main_overtime_approval_list(){
	$title = 'Overtime Approval List';
	$head = array('Staff','Start','Stop','Amount','Description','Status');
	$width = array('20%','15%','15%','8%','32%','10%');
	$q = mysql_query("SELECT b.first_name,b.last_name,
	a.start,a.stop,a.amount,a.description,a.status,a.id FROM 
	overtime AS a,staff AS b,staff_group AS c WHERE a.staff=b.id 
	AND staff_group=c.id AND c.head='".$_COOKIE['login']."' 
	AND a.status='not approved' ORDER BY a.id DESC") 
	or die(mysql_error());
	$rows = array();
	$ids = array();
	while($data = mysql_fetch_array($q)){
		$staff_list = staff_list($data[7]);
		list($h,$i) = explode(':',$data[4]);
		$amount = $h.'h '.$i.'m';
		$d = array($data[0].' '.$data[1],$data[2],$data[3],$amount,$data[5],$data[6]);
		array_push($rows,$d);
		array_push($ids,$data[7]);
	}
	table_output($title,$content1,$content2,$head,$width,null,
	$rows,$ids,null,null,null,null,null,null,1);
}
//-----------------------------------------------------------------
//	main_overtime_add
//-----------------------------------------------------------------
function main_overtime_add(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	form_header('Add New Overtime');
	form_back('index.php?p=overtime_list','Overtime');
	form_select_db('Staff','staff','first_name',$_POST['staff']);
	list($hour,$minutes) = amount_calc_hour();
	form_hidden('amount',$hour.':'.$minutes);
	form_date('Start','start',$_POST['start']);
	form_date('Stop','stop',$_POST['stop']);
	form_text_only('Amount','<b>'.$hour.' Hours</b> <i>'.$minutes.' Minutes</i>');
	form_textarea('Description','description',150,$_POST['description']);
	$status = array('approved','not approved');
	form_select_array('Status','status',$status,$status,$_POST['status']);
	form_submit('Add','add');
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	main_overtime_edit
//-----------------------------------------------------------------
function main_overtime_edit(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	$data = mysql_fetch_array(mysql_query("SELECT * FROM overtime WHERE id='".$_GET['edit']."'"));
	//---------------------------------------------------------
	//	variable definition
	//---------------------------------------------------------
	if(empty($_POST['staff'])) $_POST['staff']=$data['staff'];
	if(empty($_POST['ystart'])) $_POST['start']=$data['start'];
	if(empty($_POST['ystop'])) $_POST['stop']=$data['stop'];
	if(empty($_POST['description'])) $_POST['description']=$data['description'];
	if(empty($_POST['status'])) $_POST['status']=$data['status'];
	//---------------------------------------------------------
	form_hidden('edit',$_GET['edit']);
	form_select_db('Staff','staff','first_name',$_POST['staff']);
	form_date('Start','start',$_POST['start']);
	form_date('Stop','stop',$_POST['stop']);
	list($hour,$minutes) = amount_calc_hour();
	form_hidden('amount',$hour.':'.$minutes);
	form_text_only('Amount','<b>'.$hour.' Hours</b> <i>'.$minutes.' Minutes</i>');
	form_textarea('Description','description',150,$_POST['description']);
	$status = array('approved','not approved');
	form_select_array('Status','status',$status,$status,$_POST['status']);
	form_submit('Edit','edit_send');
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	overtime_search
//-----------------------------------------------------------------
function overtime_search(){
?>
	<form action="" method="POST">
	<div id="formsearch">
<?
	form_select_db('Staff','staff','first_name',$_POST['staff']);
	form_text('Description','description',$_POST['description'],50,255);
	form_date('Start','start',$_POST['start']);
	form_date('Stop','stop',$_POST['stop']);
	form_submit_search('Search','search');
?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	overtime_search_input
//-----------------------------------------------------------------
function overtime_search_input(){
	$where = '';
	if(!empty($_POST['staff'])){
		$where .= " AND a.staff='".$_POST['staff']."'";
	}
	if(!empty($_POST['description'])){
		$where .= " AND a.description='".$_POST['description']."'";
	}
	if(!empty($_POST['ystart']) && !empty($_POST['ystop'])){
		$start = mktime($_POST['hstart'],$_POST['istart'],0,$_POST['mstart'],$_POST['dstart'],$_POST['ystart']);
		$stop = mktime($_POST['hstop'],$_POST['istop'],0,$_POST['mstop'],$_POST['dstop'],$_POST['ystop']);
		$where .= " AND (UNIX_TIMESTAMP(a.start)<='".$start."' AND UNIX_TIMESTAMP(a.stop)>='".$stop."')";
		//exit($where);
	}
	return $where;
}
?>
