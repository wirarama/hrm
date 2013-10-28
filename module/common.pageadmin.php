<?
//-----------------------------------------------------------------
//		admin header
//-----------------------------------------------------------------
function admin_header(){
	global $valid,$member;
?>
	<div id="Header">
	<h1>Test HRM</h1>
	<?
	if($valid==true){
	?>
	<ul id="jsddm">
	<li><a href="index.php">Home</a></li>
	<li><a href="#">Task</a>
		<ul>
		<li><a href="index.php?p=category_list">Category</a></li>
		<li><a href="index.php?p=subject_list">Subject</a></li>
		<li><a href="index.php?p=task_list">Task</a></li>
		<li><a href="index.php?p=task_view">Task View</a></li>
		</ul>
	</li>
	<li><a href="#">Staff</a>
		<ul>
		<li><a href="index.php?p=staff_group_list">Group</a></li>
		<li><a href="index.php?p=staff_list">Staff</a></li>
		<li><a href="index.php?p=shiftstaff_list">Staff Shift</a></li>
		</ul>
	</li>
	<li><a href="#">Client</a>
		<ul>
		<li><a href="index.php?p=client_category_list">Client Category</a></li>
		<li><a href="index.php?p=client_list">Client</a></li>
		</ul>
	</li>
	<li><a href="#">Personalia</a>
		<ul>
		<li><a href="index.php?p=annual_leave_list">Annual leave</a></li>
		<li><a href="index.php?p=annual_leave_approval_list">Annual leave Approval</a></li>
		<li><a href="index.php?p=overtime_list">Overtime</a></li>
		<li><a href="index.php?p=overtime_approval_list">Overtime Approval</a></li>
		</ul>
	</li>
	<li><a href="#" onClick="confirm('Are You Sure to Logout?',function(){ window.location.href = 'index.php?p=logout'; })">Logout</a></li>
	</ul>
	<? } ?>
	</div>
<?
}
//-----------------------------------------------------------------
//		admin content
//-----------------------------------------------------------------
function admin_content(){
?>
	<div id="general">
		<? page_structure('main'); ?>
		<div style="clear:both;"></div>
	</div>
<?
}
//-----------------------------------------------------------------
//		admin footer
//-----------------------------------------------------------------
function admin_footer(){
}
?>
