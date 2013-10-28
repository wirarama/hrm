<?
//-----------------------------------------------------------------
//	HEAD
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	head_staff_list
//-----------------------------------------------------------------
function head_staff_list(){
	if(isset($_GET['delete'])){
		mysql_query("DELETE FROM staff WHERE id='".$_GET['delete']."'");
		header('location:index.php?p=staff_list');
	}
}
//-----------------------------------------------------------------
//	head_staff_add
//-----------------------------------------------------------------
function head_staff_add(){
	if(isset($_POST['add'])){
		$no = auto_number('staff');
		mysql_query("INSERT INTO staff 
		VALUES('".$no."','".$_POST['staff_group']."','".$_POST['username']."',
		MD5('".$_POST['password']."'),'".$_POST['first_name']."','".$_POST['last_name']."',
		'".$_POST['address']."','".$_POST['telp']."',
		'".$_POST['mobile']."','".$_POST['email']."','".$_POST['status']."',null)");
		insert_pictures('staff',$no);
		header('location:index.php?p=staff_list');
	}
}
//-----------------------------------------------------------------
//	head_staff_edit
//-----------------------------------------------------------------
function head_staff_edit(){
	if(isset($_POST['edit_send'])){
		mysql_query("UPDATE staff SET 
		staff_group='".$_POST['staff_group']."',
		first_name='".$_POST['first_name']."',
		last_name='".$_POST['last_name']."',
		telp='".$_POST['telp']."',
		mobile='".$_POST['mobile']."',
		email='".$_POST['email']."',
		status='".$_POST['status']."' 
		WHERE id='".$_POST['edit']."'") or die('error di edit : '.mysql_error());
		if(!empty($_POST['password'])){
			mysql_query("UPDATE staff SET 
			password=MD5('".$_POST['password']."') 
			WHERE id='".$_POST['edit']."'") or die(mysql_error());
		}
		insert_pictures('staff',$_POST['edit']);
		header('location:index.php?p=staff_list');
	}
}
//-----------------------------------------------------------------
//	MAIN
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//	main_staff_list
//-----------------------------------------------------------------
function main_staff_list(){
	$title = 'Group List';
	$head = array('Group','Name','Address','Telp','Mobile','E-mail');
	$width = array('10%','30%','30%','10%','10%','10%');
	$link = '<a href="index.php?p=staff_add">Add New Staff &raquo;</a>';
	$content1 = 'index.php?p=staff_edit&edit=';
	$content2 = 'index.php?p=staff_list&delete=';
	list($rows,$ids,$where) = staff_data();
	$add_title = array();
	$at = add_table_header('Export To : ',
	'<a href="module/report.excel.staff.php?where='.$where.'">Excel</a>');
	array_push($add_title,$at);
	table_output($title,$content1,$content2,$head,$width,
	$link,$rows,$ids,null,'staff',null,null,$add_title);
}
//-----------------------------------------------------------------
//	staff_data
//-----------------------------------------------------------------
function staff_data(){
	if(empty($_GET['where'])){
		$where = staff_search_input();
	}else{
		$where = stripslashes($_GET['where']);
	}
	$q = mysql_query("SELECT b.name,a.first_name,a.last_name,a.address,a.telp,a.mobile,a.email,a.id 
	FROM staff AS a,staff_group AS b WHERE a.staff_group=b.id ".$where." ORDER BY b.id DESC");
	$rows = array();
	$ids = array();
	while($data = mysql_fetch_array($q)){
		$d = array($data[0],$data[1].' '.$data[2],$data[3],$data[4],$data[5],$data[6]);
		array_push($rows,$d);
		array_push($ids,$data[7]);
	}
	$out = array($rows,$ids,$where);
	return $out;
}
//-----------------------------------------------------------------
//	main_staff_add
//-----------------------------------------------------------------
function main_staff_add(){
?>
	<form action="" method="POST" enctype="multipart/form-data">
	<div id="formborder">
	<?
	form_header('Add New Staff');
	form_back('index.php?p=staff_list','Staff');
	form_select_db('Group','staff_group','name',$_POST['staff_group']);
	form_text('User Name','username',$_POST['username'],40,255,null,'huruftanpaspasi');
	form_password('Password','password',$_POST['password'],40,255);
	form_password('Password Confirm','password_confirm',$_POST['password_confirm'],40,255);
	form_text('First Name','first_name',$_POST['first_name'],50,255);
	form_text('Last Name','last_name',$_POST['last_name'],50,255);
	form_text('Address','address',$_POST['address'],60,255);
	form_text('Telp','telp',$_POST['telp'],40,255,null,'angka');
	form_text('Mobile','mobile',$_POST['mobile'],40,255,null,'angka');
	form_text('E-mail','email',$_POST['email'],40,255);
	$status = array('admin','user');
	form_select_array('Status','status',$status,$status,$_POST['status']);
	form_file('Pictures','pictures');
	form_submit('Add','add');
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	main_staff_edit
//-----------------------------------------------------------------
function main_staff_edit(){
?>
	<form action="" method="POST" enctype="multipart/form-data">
	<div id="formborder">
	<?
	$data = mysql_fetch_array(mysql_query("SELECT * FROM staff WHERE id='".$_GET['edit']."'"));
	form_header('Edit Staff');
	form_back('index.php?p=staff_list','Staff');
	form_hidden('edit',$_GET['edit']);
	form_select_db('Group','staff_group','name',$data['staff_group']);
	form_password('Password','password','',40,255);
	form_password('Password Confirm','password_confirm','',40,255);
	form_text('First Name','first_name',$data['first_name'],50,255);
	form_text('Last Name','last_name',$data['last_name'],50,255);
	form_text('Address','address',$data['address'],60,255);
	form_text('Telp','telp',$data['telp'],40,255,null,'angka');
	form_text('Mobile','mobile',$data['mobile'],40,255,null,'angka');
	form_text('E-mail','email',$data['email'],40,255);
	$status = array('admin','user');
	form_select_array('Status','status',$status,$status,$data['status']);
	form_file('Pictures','pictures');
	form_submit('Edit','edit_send');
	?>
	</div>
	</form>
<?
}
//-----------------------------------------------------------------
//	staff_search
//-----------------------------------------------------------------
function staff_search(){
?>
	<form action="" method="POST">
	<div id="formsearch">
<?
	form_select_db('Group','staff_group','name',$_POST['staff_group']);
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
//	staff_search_input
//-----------------------------------------------------------------
function staff_search_input(){
	$where = '';
	if(!empty($_POST['staff_group'])){
		$where .= " AND a.group='".$_POST['staff_group']."'";
	}
	if(!empty($_POST['name'])){
		$where .= " AND (a.first_name='".$_POST['name']."' OR a.last_name='".$_POST['name']."')";
	}
	if(!empty($_POST['address'])){
		$where .= " AND a.address='".$_POST['address']."'";
	}
	if(!empty($_POST['telp'])){
		$where .= " AND a.telp='".$_POST['telp']."'";
	}
	if(!empty($_POST['mobile'])){
		$where .= " AND a.mobile='".$_POST['mobile']."'";
	}
	if(!empty($_POST['email'])){
		$where .= " AND a.email='".$_POST['email']."'";
	}
	return $where;
}
?>
