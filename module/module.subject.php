<?
//-----------------------------------------------------------------
//	HEAD
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	head_subject_list
//-----------------------------------------------------------------
function head_subject_list(){
	if(isset($_GET['delete'])){
		mysql_query("DELETE FROM subject WHERE id='".$_GET['delete']."'");
		header('location:index.php?p=subject_list');
	}
}
//-----------------------------------------------------------------
//	head_subject_add
//-----------------------------------------------------------------
function head_subject_add(){
	if(isset($_POST['add'])){
		mysql_query("INSERT INTO subject 
		VALUES(null,'".$_POST['category']."',
		'".$_POST['name']."','".$_POST['description']."')");
		header('location:index.php?p=subject_list');
	}
}
//-----------------------------------------------------------------
//	head_subject_edit
//-----------------------------------------------------------------
function head_subject_edit(){
	if(isset($_POST['edit'])){
		mysql_query("UPDATE subject SET 
		name='".$_POST['name']."',
		description='".$_POST['description']."',
		category='".$_POST['category']."' 
		WHERE id='".$_POST['edit']."'") or die(mysql_error());
		header('location:index.php?p=subject_list');
	}
}
//-----------------------------------------------------------------
//	MAIN
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	main_subject_list
//-----------------------------------------------------------------
function main_subject_list(){
	$title = 'Subject List';
	$head = array('Name','Category','Description');
	$width = array('30%','30%','40%');
	$link = '<a href="index.php?p=subject_add">Add New Subject &raquo;</a>';
	$content1 = 'index.php?p=subject_edit&edit=';
	$content2 = 'index.php?p=subject_list&delete=';
	$q = mysql_query("SELECT a.name,b.name,a.description,a.id 
	FROM subject AS a,category AS b WHERE a.category=b.id ORDER BY a.id DESC");
	$rows = array();
	$ids = array();
	while($data = mysql_fetch_row($q)){
		$d = array($data[0],$data[1],$data[2]);
		array_push($rows,$d);
		array_push($ids,$data[3]);
	}
	table_output($title,$content1,$content2,$head,$width,$link,$rows,$ids);
}
//-----------------------------------------------------------------
//	main_subject_add
//-----------------------------------------------------------------
function main_subject_add(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	form_header('Add New Subject');
	form_back('index.php?p=subject_list','Subject');
	form_select_db('Category','category','name',$_POST['category']);
	form_text('Name','name',$_POST['name'],60,255);
	form_textarea('Description','description',150,$_POST['description']);
	form_submit('Add','add',$zebra);
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	main_subject_edit
//-----------------------------------------------------------------
function main_subject_edit(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	$data = mysql_fetch_array(mysql_query("SELECT * FROM subject WHERE id='".$_GET['edit']."'"));
	form_header('Edit Subject');
	form_back('index.php?p=subject_list','Subject');
	form_select_db('Category','category','name',$data['category']);
	form_text('Name','name',$data['name'],60,255);
	form_textarea('Description','description',150,$data['description']);
	form_submit('Edit','edit_send');
	?>
	</div>
	</form>
<?
}
?>
