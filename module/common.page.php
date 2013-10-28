<?
//-----------------------------------------------------------------
//		page
//-----------------------------------------------------------------
function page(){
	global $valid;
	//define
	if($valid==true){
		$c = 'admin';
	}else{
		$c = 'user';
	}
	//function string
	$header = $c.'_header';
	$content = $c.'_content';
	$footer = $c.'_footer';
	//execute function
	$header();
	$content();
	$footer();
	confirm_box();
}
//-----------------------------------------------------------------
//		page structure
//-----------------------------------------------------------------
function page_structure($content){
	global $valid;
	if($valid==true){
		switch($_GET['p']){ //page list for admin
			//client category
			case ('client_category_list'):
				$function_name = $content.'_client_category_list';
				break;
			case ('client_category_add'):
				$function_name = $content.'_client_category_add';
				break;
			case ('client_category_edit'):
				$function_name = $content.'_client_category_edit';
				break;
			//client
			case ('client_list'):
				$function_name = $content.'_client_list';
				break;
			case ('client_add'):
				$function_name = $content.'_client_add';
				break;
			case ('client_edit'):
				$function_name = $content.'_client_edit';
				break;
			//category
			case ('category_list'):
				$function_name = $content.'_category_list';
				break;
			case ('category_add'):
				$function_name = $content.'_category_add';
				break;
			case ('category_edit'):
				$function_name = $content.'_category_edit';
				break;
			//subject
			case ('subject_list'):
				$function_name = $content.'_subject_list';
				break;
			case ('subject_add'):
				$function_name = $content.'_subject_add';
				break;
			case ('subject_edit'):
				$function_name = $content.'_subject_edit';
				break;
			//task
			case ('task_list'):
				$function_name = $content.'_task_list';
				break;
			case ('task_add'):
				$function_name = $content.'_task_add';
				break;
			case ('task_edit'):
				$function_name = $content.'_task_edit';
				break;
			case ('task_view'):
				$function_name = $content.'_task_view';
				break;
			//staff_group
			case ('staff_group_list'):
				$function_name = $content.'_staff_group_list';
				break;
			case ('staff_group_add'):
				$function_name = $content.'_staff_group_add';
				break;
			case ('staff_group_edit'):
				$function_name = $content.'_staff_group_edit';
				break;
			//staff
			case ('staff_list'):
				$function_name = $content.'_staff_list';
				break;
			case ('staff_add'):
				$function_name = $content.'_staff_add';
				break;
			case ('staff_edit'):
				$function_name = $content.'_staff_edit';
				break;
			//overtime
			case ('overtime_list'):
				$function_name = $content.'_overtime_list';
				break;
			case ('overtime_add'):
				$function_name = $content.'_overtime_add';
				break;
			case ('overtime_edit'):
				$function_name = $content.'_overtime_edit';
				break;
			case ('overtime_approval_list'):
				$function_name = $content.'_overtime_approval_list';
				break;
			//annual_leave
			case ('annual_leave_list'):
				$function_name = $content.'_annual_leave_list';
				break;
			case ('annual_leave_add'):
				$function_name = $content.'_annual_leave_add';
				break;
			case ('annual_leave_edit'):
				$function_name = $content.'_annual_leave_edit';
				break;
			case ('annual_leave_approval_list'):
				$function_name = $content.'_annual_leave_approval_list';
				break;
			//shift
			case ('shiftstaff_list'):
				$function_name = $content.'_shiftstaff_list';
				break;
			//logout
			case ('logout'):
				$function_name = $content.'_logout';
				break;
			case ('login_list'):
				$function_name = $content.'_login_list';
				break;
			default:
				$function_name = $content.'_home';
				break;
		}
	}else{
		$function_name = $content.'_login';
	}
	$out = $function_name();
	return $out;
}
?>
