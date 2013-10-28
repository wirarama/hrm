<?
//-----------------------------------------------------------------
//	HEAD
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	head_annual_leave_list
//-----------------------------------------------------------------
function head_annual_leave_list(){
	if(isset($_GET['delete'])){
		mysql_query("DELETE FROM annual_leave WHERE id='".$_GET['delete']."'");
		header('location:index.php?p=annual_leave_list');
	}
}
//-----------------------------------------------------------------
//	head_annual_leave_approval_list
//-----------------------------------------------------------------
function head_annual_leave_approval_list(){
	if(isset($_GET['approve'])){
		mysql_query("UPDATE annual_leave SET status='approved' WHERE id='".$_GET['approve']."'");
		header('location:index.php?p=annual_leave_approval_list');
	}
}
//-----------------------------------------------------------------
//	head_annual_leave_add
//-----------------------------------------------------------------
function head_annual_leave_add(){
	if(isset($_POST['add'])){
		$start = $_POST['ystart'].'-'.$_POST['mstart'].'-'.$_POST['dstart'];
		$stop = $_POST['ystop'].'-'.$_POST['mstop'].'-'.$_POST['dstop'];
		$id = auto_number('annual_leave');
		mysql_query("INSERT INTO annual_leave VALUES('".$id."','".$_POST['staff']."','".$start."',
		'".$stop."','".$_POST['amount']."','".$_POST['description']."','".$_POST['status']."')") 
		or die(mysql_error());
		header('location:index.php?p=annual_leave_list');
	}
}
//-----------------------------------------------------------------
//	head_annual_leave_edit
//-----------------------------------------------------------------
function head_annual_leave_edit(){
	if(isset($_POST['edit_send'])){
		$start = $_POST['ystart'].'-'.$_POST['mstart'].'-'.$_POST['dstart'];
		$stop = $_POST['ystop'].'-'.$_POST['mstop'].'-'.$_POST['dstop'];
		mysql_query("UPDATE annual_leave SET 
		staff='".$_POST['staff']."',start='".$start."',
		stop='".$stop."',amount='".$_POST['amount']."',
		description='".$_POST['description']."',status='".$_POST['status']."' 
		WHERE id='".$_POST['edit']."'") or die(mysql_error());
		header('location:index.php?p=annual_leave_list');
	}
}
//-----------------------------------------------------------------
//	MAIN
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	main_annual_leave_list
//-----------------------------------------------------------------
function main_annual_leave_list(){
	$title = 'Annual Leave List';
	$head = array('Staff','Start','Stop','Amount','Description','Status');
	$width = array('20%','15%','15%','8%','32%','10%');
	$link = '<a href="index.php?p=annual_leave_add">Add New Annual Leave &raquo;</a>';
	$content1 = 'index.php?p=annual_leave_edit&edit=';
	$content2 = 'index.php?p=annual_leave_list&delete=';
	list($rows,$ids,$where) = annual_leave_data();
	$add_title = array();
	$at = add_table_header('Export To : ',
	'<a href="module/report.excel.annual_leave.php?where='.$where.'">Excel</a>');
	array_push($add_title,$at);
	table_output($title,$content1,$content2,$head,$width,
	$link,$rows,$ids,null,'annual_leave',null,null,$add_title);
}
//-----------------------------------------------------------------
//	annual_leave_data
//-----------------------------------------------------------------
function annual_leave_data(){
	if(empty($_GET['where'])){
		$where = annual_leave_search_input();
	}else{
		$where = stripslashes($_GET['where']);
	}
	$q = mysql_query("SELECT b.first_name,b.last_name,
	a.start,a.stop,a.amount,a.description,a.status,a.id FROM 
	annual_leave AS a,staff AS b WHERE a.staff=b.id ".$where." 
	ORDER BY a.id DESC") or die(mysql_error());
	$rows = array();
	$ids = array();
	while($data = mysql_fetch_array($q)){
		$staff_list = staff_list($data[7]);
		$amount = $data[4].'d';
		$d = array($data[0].' '.$data[1],$data[2],$data[3],$amount,$data[5],$data[6]);
		array_push($rows,$d);
		array_push($ids,$data[7]);
	}
	$out = array($rows,$ids,$where);
	return $out;
}
//-----------------------------------------------------------------
//	main_annual_leave_approval_list
//-----------------------------------------------------------------
function main_annual_leave_approval_list(){
	$title = 'Annual Leave Approval List';
	$head = array('Staff','Start','Stop','Amount','Description','Status');
	$width = array('20%','15%','15%','8%','32%','10%');
	$q = mysql_query("SELECT b.first_name,b.last_name,
	a.start,a.stop,a.amount,a.description,a.status,a.id FROM 
	annual_leave AS a,staff AS b,staff_group AS c WHERE a.staff=b.id 
	AND b.staff_group=c.id AND c.head='".$_COOKIE['login']."' 
	AND a.status='not approved' ORDER BY a.id DESC") or die(mysql_error());
	$rows = array();
	$ids = array();
	while($data = mysql_fetch_array($q)){
		$staff_list = staff_list($data[7]);
		$amount = $data[4].'d';
		$d = array($data[0].' '.$data[1],$data[2],$data[3],$amount,$data[5],$data[6]);
		array_push($rows,$d);
		array_push($ids,$data[7]);
	}
	table_output($title,$content1,$content2,$head,$width,null,
	$rows,$ids,null,null,null,null,null,null,1);
}
//-----------------------------------------------------------------
//	main_annual_leave_add
//-----------------------------------------------------------------
function main_annual_leave_add(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	form_header('Add New Annual Leave');
	form_back('index.php?p=annual_leave_list','Annual Leave');
	form_select_db('Staff','staff','first_name',$_POST['staff']);
	list($amount,$free_amount) = amount_calc();
	form_hidden('amount',$amount);
	form_date('Start','start',$_POST['start']);
	form_date('Stop','stop',$_POST['stop']);
	form_text_only('Amount','<b>'.$amount.' Days</b>, <i>Free '.$free_amount.' Days</i>');
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
//	main_annual_leave_edit
//-----------------------------------------------------------------
function main_annual_leave_edit(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	$data = mysql_fetch_array(mysql_query("SELECT * FROM annual_leave WHERE id='".$_GET['edit']."'"));
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
	list($amount,$free_amount) = amount_calc();
	form_hidden('amount',$amount);
	form_text_only('Amount','<b>'.$amount.' Days</b>, <i>Free '.$free_amount.' Days</i>');
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
//	annual_leave_search
//-----------------------------------------------------------------
function annual_leave_search(){
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
//	annual_leave_search_input
//-----------------------------------------------------------------
function annual_leave_search_input(){
	$where = '';
	if(!empty($_POST['staff'])){
		$where .= " AND a.staff='".$_POST['staff']."'";
	}
	if(!empty($_POST['description'])){
		$where .= " AND a.description='".$_POST['description']."'";
	}
	if(!empty($_POST['ystart']) && !empty($_POST['ystop'])){
		$start = mktime(0,0,0,$_POST['mstart'],$_POST['dstart'],$_POST['ystart']);
		$stop = mktime(23,50,50,$_POST['mstop'],$_POST['dstop'],$_POST['ystop']);
		$where .= " AND (UNIX_TIMESTAMP(start)<='".$start."' AND UNIX_TIMESTAMP(stop)>='".$stop."')";
	}
	return $where;
}
?>
