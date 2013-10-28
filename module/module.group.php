<?
//-----------------------------------------------------------------
//	HEAD
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	head_staff_group_list
//-----------------------------------------------------------------
function head_staff_group_list(){
	if(isset($_GET['delete'])){
		mysql_query("DELETE FROM staff_group WHERE id='".$_GET['delete']."'");
		header('location:index.php?p=staff_group_list');
	}
}
//-----------------------------------------------------------------
//	head_staff_group_add
//-----------------------------------------------------------------
function head_staff_group_add(){
	if(isset($_POST['add'])){
		mysql_query("INSERT INTO staff_group 
		VALUES(null,'".$_POST['name']."','".$_POST['description']."','".$_POST['staff']."')");
		header('location:index.php?p=staff_group_list');
	}
}
//-----------------------------------------------------------------
//	head_staff_group_edit
//-----------------------------------------------------------------
function head_staff_group_edit(){
	if(isset($_POST['edit'])){
		mysql_query("UPDATE staff_group SET 
		name='".$_POST['name']."',
		description='".$_POST['description']."',
		head='".$_POST['staff']."' 
		WHERE id='".$_POST['edit']."'") or die(mysql_error());
		header('location:index.php?p=staff_group_list');
	}
}
//-----------------------------------------------------------------
//	MAIN
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	main_staff_group_list
//-----------------------------------------------------------------
function main_staff_group_list(){
	$title = 'Group List';
	$head = array('Name','Head','Description');
	$width = array('30%','30%','40%');
	$link = '<a href="index.php?p=staff_group_add">Add New Group &raquo;</a>';
	$content1 = 'index.php?p=staff_group_edit&edit=';
	$content2 = 'index.php?p=staff_group_list&delete=';
	$q = mysql_query("SELECT a.name,b.first_name,b.last_name,a.description,a.id 
	FROM staff_group AS a,staff AS b WHERE a.head=b.id ORDER BY a.id DESC") or die(mysql_error());
	$rows = array();
	$ids = array();
	while($data = mysql_fetch_row($q)){
		$d = array($data[0],$data[1].' '.$data[2],$data[3]);
		array_push($rows,$d);
		array_push($ids,$data[4]);
	}
	table_output($title,$content1,$content2,$head,$width,$link,$rows,$ids);
}
//-----------------------------------------------------------------
//	main_staff_group_add
//-----------------------------------------------------------------
function main_staff_group_add(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	form_header('Add New Group');
	form_back('index.php?p=staff_group_list','Group');
	form_text('Name','name',$_POST['name'],60,255);
	form_select_db('Head','staff','first_name',$_POST['head']);
	form_textarea('Description','description',150,$_POST['description']);
	form_submit('Add','add',$zebra);
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	main_staff_group_edit
//-----------------------------------------------------------------
function main_staff_group_edit(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	$data = mysql_fetch_array(mysql_query("SELECT * FROM staff_group WHERE id='".$_GET['edit']."'"));
	form_header('Edit Group');
	form_hidden('edit',$_GET['edit']);
	form_back('index.php?p=staff_group_list','Group');
	form_text('Name','name',$data['name'],60,255);
	form_select_db('Head','staff','first_name',$data['head']);
	form_textarea('Description','description',150,$data['description']);
	form_submit('Edit','edit_send');
	?>
	</div>
	</form>
<?
}
?>
