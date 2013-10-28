<?
//-----------------------------------------------------------------
//	HEAD
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	head_client_list
//-----------------------------------------------------------------
function head_client_list(){
	if(isset($_GET['delete'])){
		delete_pictures($c,$no);
		mysql_query("DELETE FROM client WHERE id='".$_GET['delete']."'");
		header('location:index.php?p=client_list');
	}
}
//-----------------------------------------------------------------
//	head_client_add
//-----------------------------------------------------------------
function head_client_add(){
	if(isset($_POST['add'])){
		mysql_query("INSERT INTO client 
		VALUES(null,'".$_POST['client_category']."','".$_POST['name']."',
		'".$_POST['address']."','".$_POST['telp']."',
		'".$_POST['mobile']."','".$_POST['email']."')");
		header('location:index.php?p=client_list');
	}
}
//-----------------------------------------------------------------
//	head_client_edit
//-----------------------------------------------------------------
function head_client_edit(){
	if(isset($_POST['edit'])){
		mysql_query("UPDATE client SET 
		category='".$_POST['client_category']."',
		name='".$_POST['name']."',
		telp='".$_POST['telp']."',
		mobile='".$_POST['mobile']."',
		email='".$_POST['email']."'  
		WHERE id='".$_POST['edit']."'") or die(mysql_error());
		header('location:index.php?p=client_list');
	}
}
//-----------------------------------------------------------------
//	MAIN
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	main_client_list
//-----------------------------------------------------------------
function main_client_list(){
	$title = 'Client List';
	$head = array('Category','Name','Address','Telp','Mobile','E-mail');
	$width = array('10%','30%','30%','10%','10%','10%');
	$link = '<a href="index.php?p=client_add">Add New Client &raquo;</a>';
	$content1 = 'index.php?p=client_edit&edit=';
	$content2 = 'index.php?p=client_list&delete=';
	list($rows,$ids,$where) = client_data();
	$add_title = array();
	$at = add_table_header('Export To : ',
	'<a href="module/report.excel.client.php?where='.$where.'">Excel</a>');
	array_push($add_title,$at);
	table_output($title,$content1,$content2,$head,$width,$link,$rows,$ids,null,'client',null,null,$add_title);
}
//-----------------------------------------------------------------
//	client_data
//-----------------------------------------------------------------
function client_data(){
	if(empty($_GET['where'])){
		$where = client_search_input();
	}else{
		$where = stripslashes($_GET['where']);
	}
	$q = mysql_query("SELECT b.name,a.name,a.address,a.telp,a.mobile,a.email,a.id 
	FROM client AS a,client_category AS b WHERE a.category=b.id ".$where." ORDER BY b.id DESC");
	$rows = array();
	$ids = array();
	while($data = mysql_fetch_array($q)){
		$d = array($data[0],$data[1],$data[2],$data[3],$data[4],$data[5]);
		array_push($rows,$d);
		array_push($ids,$data[6]);
	}
	$out = array($rows,$ids,$where);
	return $out;
}
//-----------------------------------------------------------------
//	main_client_add
//-----------------------------------------------------------------
function main_client_add(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	form_header('Add New Client');
	form_back('index.php?p=client_list','Client');
	form_select_db('Category','client_category','name',$_POST['client_category']);
	form_text('Name','name',$_POST['name'],50,255);
	form_text('Address','address',$_POST['address'],60,255);
	form_text('Telp','telp',$_POST['telp'],40,255,null,'angka');
	form_text('Mobile','mobile',$_POST['mobile'],40,255,null,'angka');
	form_text('E-mail','email',$_POST['email'],40,255);
	form_submit('Add','add');
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	main_client_edit
//-----------------------------------------------------------------
function main_client_edit(){
?>
	<form action="" method="POST">
	<div id="formborder">
	<?
	$data = mysql_fetch_array(mysql_query("SELECT * FROM client WHERE id='".$_GET['edit']."'"));
	form_header('Edit Client');
	form_back('index.php?p=client_list','Client');
	form_hidden('edit',$_GET['edit']);
	form_select_db('Category','client_category','name',$data['category']);
	form_text('Name','name',$data['name'],50,255);
	form_text('Address','address',$data['address'],60,255);
	form_text('Telp','telp',$data['telp'],40,255,null,'angka');
	form_text('Mobile','mobile',$data['mobile'],40,255,null,'angka');
	form_text('E-mail','email',$data['email'],40,255);
	form_submit('Edit','edit_send');
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	client_search
//-----------------------------------------------------------------
function client_search(){
?>
	<form action="" method="POST">
	<div id="formsearch">
<?
	form_select_db('Category','client_category','name',$_POST['client_category']);
	form_text('Name','name',$_POST['name'],50,255);
	form_text('Address','address',$_POST['address'],60,255);
	form_text('Telp','telp',$_POST['telp'],40,255,null,'angka');
	form_text('Mobile','mobile',$_POST['mobile'],40,255,null,'angka');
	form_text('E-mail','email',$_POST['email'],40,255);
	form_submit_search('Search','search');
?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	client_search_input
//-----------------------------------------------------------------
function client_search_input(){
	$where = '';
	if(!empty($_POST['client_category'])){
		$where .= " AND a.category='".$_POST['client_category']."'";
	}
	if(!empty($_POST['name'])){
		$where .= " AND a.name like'%".$_POST['name']."%'";
	}
	if(!empty($_POST['address'])){
		$where .= " AND a.address like'%".$_POST['address']."%'";
	}
	if(!empty($_POST['telp'])){
		$where .= " AND a.telp like'%".$_POST['telp']."%'";
	}
	if(!empty($_POST['mobile'])){
		$where .= " AND a.mobile like'%".$_POST['mobile']."%'";
	}
	if(!empty($_POST['email'])){
		$where .= " AND a.email like'%".$_POST['email']."%'";
	}
	return $where;
}
?>
