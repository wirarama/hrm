<?
//-----------------------------------------------------------------
//	HEAD
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	head_task_list
//-----------------------------------------------------------------
function head_task_list(){
	if(isset($_GET['delete'])){
		mysql_query("DELETE FROM task WHERE id='".$_GET['delete']."'");
		header('location:index.php?p=task_list');
	}
}
//-----------------------------------------------------------------
//	head_task_view
//-----------------------------------------------------------------
function head_task_view(){
}
//-----------------------------------------------------------------
//	head_task_add
//-----------------------------------------------------------------
function head_task_add(){
	if(isset($_POST['add'])){
		$start = $_POST['ystart'].'-'.$_POST['mstart'].'-'.$_POST['dstart'].
		'-'.$_POST['hstart'].'-'.$_POST['istart'];
		$stop = $_POST['ystop'].'-'.$_POST['mstop'].'-'.$_POST['dstop'].
		'-'.$_POST['hstop'].'-'.$_POST['istop'];
		$id = auto_number('task');
		mysql_query("INSERT INTO task VALUES('".$id."','".$_POST['title']."','".$_POST['category']."',
		'".$_POST['subject']."','".$_POST['client']."','".$_POST['description']."',
		'".$_POST['priority']."','".$_POST['status']."','".$start."','".$stop."')") 
		or die(mysql_error());
		for($i=1;$i<=$_POST['add_staff'];$i++){
			$id1 = auto_number('task_staff');
			$str_name = 'staff'.$i;
			mysql_query("INSERT INTO task_staff 
			VALUES('".$id1."','".$id."','".$_POST[$str_name]."')") 
			or die(mysql_error());
		}
		header('location:index.php?p=task_list');
	}
}
//-----------------------------------------------------------------
//	head_task_edit
//-----------------------------------------------------------------
function head_task_edit(){
	if(isset($_POST['edit'])){
		$start = $_POST['ystart'].'-'.$_POST['mstart'].'-'.$_POST['dstart'].
		'-'.$_POST['hstart'].'-'.$_POST['istart'];
		$stop = $_POST['ystop'].'-'.$_POST['mstop'].'-'.$_POST['dstop'].
		'-'.$_POST['hstop'].'-'.$_POST['istop'];
		mysql_query("UPDATE task SET 
		title='".$_POST['title']."',category='".$_POST['category']."',
		subject='".$_POST['subject']."',client='".$_POST['client']."',
		description='".$_POST['description']."',priority='".$_POST['priority']."',
		status='".$_POST['status']."',start='".$start."',stop='".$stop."' 
		WHERE id='".$_POST['edit']."'") or die(mysql_error());
		mysql_query("DELETE FROM task_staff WHERE task='".$_POST['edit']."'");
		for($i=1;$i<=$_POST['add_staff'];$i++){
			$id1 = auto_number('task_staff');
			$str_name = 'staff'.$i;
			mysql_query("INSERT INTO task_staff 
			VALUES('".$id1."','".$_POST['edit']."','".$_POST[$str_name]."')") 
			or die(mysql_error());
		}
		header('location:index.php?p=task_list');
	}
}
//-----------------------------------------------------------------
//	MAIN
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	main_task_list
//-----------------------------------------------------------------
function main_task_list(){
	$title = 'Task List';
	$head = array('Title','Category','Subject','Client','Staff','Priority','Status','Start','Stop');
	$width = array('17%','10%','10%','12%','19%','8%','8%','8%','8%');
	$link = '<a href="index.php?p=task_add">Add New Task &raquo;</a>';
	$content1 = 'index.php?p=task_edit&edit=';
	$content2 = 'index.php?p=task_list&delete=';
	list($rows,$ids,$where) = task_data();
	$add_title = array();
	$at = add_table_header('Export To : ','<a href="module/report.excel.task.php?where='.$where.'">Excel</a>');
	array_push($add_title,$at);
	table_output($title,$content1,$content2,$head,$width,$link,$rows,$ids,null,'task',null,null,$add_title);
}
//-----------------------------------------------------------------
//	task_data
//-----------------------------------------------------------------
function task_data(){
	if(empty($_GET['where'])){
		$where = task_search_input();
	}else{
		$where = stripslashes($_GET['where']);
	}
	$q = mysql_query("SELECT a.title,c.name,d.name,b.name,
	a.priority,a.status,a.start,a.stop,a.id FROM 
	task AS a,client AS b,category AS c,subject AS d 
	WHERE a.client=b.id AND a.category=c.id AND a.subject=d.id ".$where." 
	ORDER BY a.id DESC") or die(mysql_error());
	$rows = array();
	$ids = array();
	while($data = mysql_fetch_array($q)){
		$staff_list = staff_list($data[8]);
		$priority = priority_teks($data[4]);
		if(empty($_POST['staff'])){
			$d = array($data[0],$data[1],$data[2],$data[3],$staff_list,
			$priority,$data[5],$data[6],$data[7]);
			array_push($rows,$d);
			array_push($ids,$data[8]);
		}else{
			$search_staff = mysql_num_rows(mysql_query("SELECT staff FROM task_staff 
			WHERE task='".$data[8]."' AND staff='".$_POST['staff']."'"));
			if($search_staff!=0){
				$d = array($data[0],$data[1],$data[2],$data[3],$staff_list,
				$priority,$data[5],$data[6],$data[7]);
				array_push($rows,$d);
				array_push($ids,$data[8]);
			}
		}
	}
	$out = array($rows,$ids,$where);
	return $out;
}
//-----------------------------------------------------------------
//	main_task_view
//-----------------------------------------------------------------
function main_task_view(){
	if(empty($_GET['today'])){
		$today = mktime(0,0,0,date('m'),date('d'),date('Y'));
	}else{
		$today = $_GET['today'];
	}
	$yesterday = $today-86400;
	$tommorow = $today+86400;
	$vtoday = date('d-F-Y',$today);
	$vyesterday = date('d-F-Y',$yesterday);
	$vtommorow = date('d-F-Y',$tommorow);
	$where = " AND ((UNIX_TIMESTAMP(start)>='".$today."' AND UNIX_TIMESTAMP(start)<='".$tommorow."') OR 
	(UNIX_TIMESTAMP(stop)>='".$today."' AND UNIX_TIMESTAMP(stop)<='".$tommorow."') OR 
	(UNIX_TIMESTAMP(start)<='".$today."' AND UNIX_TIMESTAMP(stop)>='".$tommorow."'))";
	echo '<div id="taskmargin1"><a href="index.php?p=task_view&today='.$tommorow.'">'.$vtommorow.'</a></div>';
	echo '<div id="taskmargin">'.$vtoday.'</div>';
	task_list($where);
	echo '<div id="taskmargin1"><a href="index.php?p=task_view&today='.$yesterday.'">'.$vyesterday.'</a></div>';
}
//-----------------------------------------------------------------
//	task_js_form_head
//-----------------------------------------------------------------
function task_js_form_head(){
	$q = mysql_query("SELECT id,first_name,last_name FROM staff ORDER BY id");
	while($d = mysql_fetch_array($q)){
		$stafflist .= "<option value='".$d[0]."'>".$d[1].' '.$d[2]."</option>";
	}
?>
	<!--add form-->
	<script type="text/javascript" src="js/jquery.highlightFade.js"></script>
	<script type="text/javascript">
	function addFormField() {
		var id = document.getElementById("id").value;
		$("#divTxt").append("<div id='addform" + id + "' style='padding-left:10px;'><label for='staff" + id + "'>Staff " + id + "&nbsp;&nbsp;<select name='staff[]' id='staff" + id + "'><? echo $stafflist; ?></select>&nbsp;&nbsp<a href='#' onClick='removeFormField(\"#addform" + id + "\"); return false;'>Remove</a><p>");
		$('#addform' + id).highlightFade({
			speed:1000
		});
		id = (id - 1) + 2;
		document.getElementById("id").value = id;
	}
	function removeFormField(id) {
		$(id).remove();
	}
	</script>
<?
}
//-----------------------------------------------------------------
//	task_js_form
//-----------------------------------------------------------------
function task_js_form(){
?>
	<input type="hidden" id="id" value="1">
	<p><a href="#" onClick="addFormField(); return false;">Add</a></p>
	<div id="divTxt"></div>
<?
}
//-----------------------------------------------------------------
//	main_task_add
//-----------------------------------------------------------------
function main_task_add(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	form_header('Add New Task');
	form_back('index.php?p=task_list','Task');
	form_text('Title','title',$_POST['title'],60,255);
	category_subject_form();
	form_select_db('Client','client','name',$_POST['client']);
	staff_list_form();
	//task_js_form();
	form_textarea('Description','description',250,$_POST['description']);
	priority_status_form();
	form_date('Start','start',$_POST['start']);
	form_date('Stop','stop',$_POST['stop']);
	form_submit('Add','add');
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	main_task_edit
//-----------------------------------------------------------------
function main_task_edit(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	$data = mysql_fetch_array(mysql_query("SELECT * FROM task WHERE id='".$_GET['edit']."'"));
	//---------------------------------------------------------
	//	variable definition
	//---------------------------------------------------------
	if(empty($_POST['title'])) $_POST['title']=$data['title'];
	if(empty($_POST['category'])) $_POST['category']=$data['category'];
	if(empty($_POST['subject'])) $_POST['subject']=$data['subject'];
	if(empty($_POST['client'])) $_POST['client']=$data['client'];
	if(empty($_POST['description'])) $_POST['description']=$data['description'];
	if(empty($_POST['start'])) $_POST['start']=$data['start'];
	if(empty($_POST['stop'])) $_POST['stop']=$data['stop'];
	//---------------------------------------------------------
	form_header('Edit Task');
	form_back('index.php?p=task_list','Task');
	form_hidden('edit',$_GET['edit']);
	form_text('Title','title',$_POST['title'],60,255);
	category_subject_form();
	form_select_db('Client','client','name',$_POST['client']);
	staff_list_form();
	form_textarea('Description','description',250,$_POST['description']);
	priority_status_form();
	form_date('Start','start',$_POST['start']);
	form_date('Stop','stop',$_POST['stop']);
	form_submit('Edit','edit_send');
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	task_search
//-----------------------------------------------------------------
function task_search(){
?>
	<form action="" method="POST">
	<div id="formsearch">
<?
	form_text('Title/Description','title',$_POST['title'],60,255);
	category_subject_form();
	form_select_db('Client','client','name',$_POST['client']);
	form_select_db('Staff','staff','first_name',$_POST['staff']);
	priority_status_form();
	form_date('Start','start',$_POST['start']);
	form_date('Stop','stop',$_POST['stop']);
	form_submit_search('Search','search');
?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	task_search_input
//-----------------------------------------------------------------
function task_search_input(){
	$where = '';
	if(!empty($_POST['search'])){
		if(!empty($_POST['title'])){
			$where .= " AND (a.title='".$_POST['title']."' OR a.description='".$_POST['title']."')";
		}
		if(!empty($_POST['client'])){
			$where .= " AND a.client='".$_POST['client']."'";
		}
		if(!empty($_POST['priority'])){
			$where .= " AND a.priority='".$_POST['priority']."'";
		}
		if(!empty($_POST['status'])){
			$where .= " AND a.status='".$_POST['status']."'";
		}
		if(!empty($_POST['ystart']) && !empty($_POST['ystop'])){
			$start = mktime($_POST['hstart'],$_POST['istart'],0,$_POST['mstart'],
			$_POST['dstart'],$_POST['ystart']);
			$stop = mktime($_POST['hstop'],$_POST['istop'],0,$_POST['mstop'],
			$_POST['dstop'],$_POST['ystop']);
			$where .= " AND ((UNIX_TIMESTAMP(start)>='".$start."' 
			AND UNIX_TIMESTAMP(start)<='".$stop."') OR 
			(UNIX_TIMESTAMP(stop)>='".$start."' AND UNIX_TIMESTAMP(stop)<='".$stop."') OR 
			(UNIX_TIMESTAMP(start)<='".$start."' AND UNIX_TIMESTAMP(stop)>='".$stop."'))";
		}
	}
	return $where;
}
?>
