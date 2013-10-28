<?
//-----------------------------------------------------------------
//	HEAD
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	head_client_category_list
//-----------------------------------------------------------------
function head_client_category_list(){
	if(isset($_GET['delete'])){
		mysql_query("DELETE FROM client_category WHERE id='".$_GET['delete']."'");
		header('location:index.php?p=client_category_list');
	}
}
//-----------------------------------------------------------------
//	head_client_category_add
//-----------------------------------------------------------------
function head_client_category_add(){
	if(isset($_POST['add'])){
		mysql_query("INSERT INTO client_category 
		VALUES(null,'".$_POST['name']."','".$_POST['description']."')");
		header('location:index.php?p=client_category_list');
	}
}
//-----------------------------------------------------------------
//	head_client_category_edit
//-----------------------------------------------------------------
function head_client_category_edit(){
	if(isset($_POST['edit'])){
		mysql_query("UPDATE client_category SET 
		name='".$_POST['name']."',
		description='".$_POST['description']."' 
		WHERE id='".$_POST['edit']."'") or die(mysql_error());
		header('location:index.php?p=client_category_list');
	}
}
//-----------------------------------------------------------------
//	MAIN
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	main_client_category_list
//-----------------------------------------------------------------
function main_client_category_list(){
	$title = 'Client Category List';
	$head = array('Name','Description');
	$width = array('40%','60%');
	$link = '<a href="index.php?p=client_category_add">Add New Client Category &raquo;</a>';
	$content1 = 'index.php?p=client_category_edit&edit=';
	$content2 = 'index.php?p=client_category_list&delete=';
	$q = mysql_query("SELECT * FROM client_category ORDER BY id DESC");
	$rows = array();
	$ids = array();
	while($data = mysql_fetch_array($q)){
		$d = array($data['name'],$data['description']);
		array_push($rows,$d);
		array_push($ids,$data['id']);
	}
	table_output($title,$content1,$content2,$head,$width,$link,$rows,$ids);
}
//-----------------------------------------------------------------
//	main_client_category_add
//-----------------------------------------------------------------
function main_client_category_add(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	form_header('Add New Client Category');
	form_back('index.php?p=client_category_list','Client Category');
	form_text('Name','name',$_POST['name'],60,255);
	form_textarea('Description','description',150,$_POST['description']);
	form_submit('Add','add',$zebra);
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	main_client_category_edit
//-----------------------------------------------------------------
function main_client_category_edit(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	$data = mysql_fetch_array(mysql_query("SELECT * FROM client_category WHERE id='".$_GET['edit']."'"));
	form_header('Edit Client Category');
	form_hidden('edit',$_GET['edit']);
	form_back('index.php?p=client_category_list','Client Category');
	form_text('Name','name',$data['name'],60,255);
	form_textarea('Description','description',150,$data['description']);
	form_submit('Edit','edit_send');
	?>
	</div>
	</form>
<?
}
?>
