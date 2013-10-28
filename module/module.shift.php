<?
//-----------------------------------------------------------------
//	head_task_list
//-----------------------------------------------------------------
function head_shiftstaff_list(){
	$hari = array('Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu');
	$name_of_today = date('w');
	if($name_of_today!=1){
		if($name_of_today>1){
			$to_get2 = $name_of_today-1;
			$date_unix = mktime(0,0,0,date('m'),date('d')-$to_get2,date('Y'));
		}else{
			$to_get2 = 1-$name_of_today;
			$date_unix = mktime(0,0,0,date('m'),date('d')+$to_get2,date('Y'));
		}
	}else{
		$date_unix = mktime(0,0,0,date('m'),date('d'),date('Y'));
	}
	if(!empty($_GET['new'])) $date_unix = $date_unix+(($_GET['new']*7)*86400);
	$date = date('Y-m-d',$date_unix);
	$hari_unix = array($date);
	for($h=1;$h<=6;$h++){
		$date_unix+=86400;
		array_push($hari_unix,date('Y-m-d',$date_unix));
	}
	if(!empty($_POST['save'])){
		insert_shift($hari_unix);
	}
	return $hari_unix;
}
//-----------------------------------------------------------------
//	main_shiftstaff_list
//-----------------------------------------------------------------
function main_shiftstaff_list(){
	global $out;
	$hari_unix = $out;
	?>
	<style>
		#neshift{
			background-color:#BBB;
		}
		#neshift th{
			background-color:#CCC;
			padding:5px;
		}
		#neshift td{
			background-color:#FFF;
			text-align:center;
			padding:5px;
		}
	</style>
	<form action="" method="POST">
	<table cellpadding="2px" cellspacing="1px" id="neshift" width="100%">
		<? if(!empty($_GET['added'])){ ?>
		<tr>
		    	<td colspan="100%" style="background-color:#c9ff82;">
				<b>Shift added succesfully!!</b>
		        </td>
		</tr>
		<? } ?>
		<tr>
			<td colspan="100%">
				<a href="index.php?p=shiftstaff_list&new=<? echo $_GET['new']-1; ?>">&laquo; Last Week</a> | 
				<a href="index.php?p=shiftstaff_list&new=<? echo $_GET['new']+1; ?>">Next Week &raquo;</a>
			</td>
		</tr>
		<tr>
			<?
			$w = 100/8;
			?>
			<th width="<? echo $w; ?>%">&nbsp;</th>
			<?
			$i=0;
			foreach($hari_unix AS $hari_unix1){
				if($hari_unix1==date('Y-m-d')){
					$today_style = 'style="background-color:#aafff2;"';
				}else{
					$today_style = null;
				}
			?>
			<th width="<? echo $w; ?>%" <? echo $today_style; ?>>
			<? echo '<b>'.$hari[$i].'</b><br>'.$hari_unix1; ?></th>
			<? $i++; } ?>
		</tr>
		<? shift_staff_list($hari_unix); ?>
	</table>
	</form>
	<?
}
//-----------------------------------------------------------------
//	shift_staff_list
//-----------------------------------------------------------------
function shift_staff_list($hari_unix){
	$q = mysql_query("SELECT id,first_name,last_name 
	FROM staff ORDER BY first_name ASC") or die(mysql_error());
	while($d = mysql_fetch_row($q)){
		?>
		<tr>
			<td><? echo $d[1]; ?></td>
			<?
			$i=1;
			foreach($hari_unix AS $hari_unix1){
				if($hari_unix1==date('Y-m-d')){
                                	$today_style = 'style="background-color:#aafff2;"';
                        	}else{
                              		$today_style = null;
                        	}
                	?>
                	<? 
				menu_shift($d[0],$hari_unix1,$i);
				if($i!=7){
					$i++;
				}else{
					$i=7;
				}
			} 
			?>
		</tr>
	<?
	}
	?>
	<tr>
		<td colspan="100%" style="background-color:#BBB;">
		<input type="submit" name="save" value="Save"></td>
	</tr>
	<?
}
//-----------------------------------------------------------------
//	menu_shift
//-----------------------------------------------------------------
function menu_shift($staff,$hari,$week){
	$d = mysql_fetch_row(mysql_query("SELECT shift FROM shift 
	WHERE staff='".$staff."' AND date='".$hari."' ORDER BY id DESC LIMIT 0,1"));
	if(empty($d[0])){
		$d = mysql_fetch_row(mysql_query("SELECT shift FROM shift 
        	WHERE staff='".$staff."' AND week='".$week."' ORDER BY id DESC LIMIT 0,1"));
		if(empty($d[0])){
			if($week==6 || $week==7){
				$d[0]='off';
			}else{
				$d[0]='reguler';
			}
		}
	}
	switch($d[0]){
		case('off'):
                        $shift_style='#ff685d;';
                       	break;
		case('shift1'):
			$shift_style='#a1f7ff;';
			break;
		case('shift2'):
                        $shift_style='#fff36a;';
                       	break;
		case('reguler'):
                        $shift_style='#cfcfcf;';
                        break;
	}
	$shift_style = 'style="background-color:'.$shift_style.'"';
	?>
	<td <? echo $shift_style; ?>>
	<select name="shift<? echo $staff.'x'.$hari; ?>">
		<?
		$shift = array('off','shift1','reguler','shift2');
		foreach($shift AS $shift1){
			if($shift1==$d[0]){
				$selected = 'selected';
			}else{
				$selected = null;
			}
			?>
			<option value="<? echo $shift1; ?>" <? echo $selected; ?>><? echo $shift1; ?></option>
			<?
		}
		?>
	</select>
	<input name="date<? echo $staff.'x'.$hari; ?>" type="hidden" value="<? echo $hari; ?>">
	<input name="week<? echo $staff.'x'.$hari; ?>" type="hidden" value="<? echo $week; ?>">
	</td>
	<?
}
//-----------------------------------------------------------------
//	insert_shift
//-----------------------------------------------------------------
function insert_shift($hari_unix){
	$q = mysql_query("SELECT id FROM staff ORDER BY first_name ASC") or die(mysql_error());
	while($d = mysql_fetch_row($q)){
		$i=1;
		foreach($hari_unix AS $hari_unix1){
                	$strshift = 'shift'.$d[0].'x'.$hari_unix1;
			$strdate = 'date'.$d[0].'x'.$hari_unix1;
			$strweek = 'week'.$d[0].'x'.$hari_unix1;
			//delete last data
			mysql_query("DELETE FROM shift 
			WHERE staff='".$d[0]."' AND date='".$_POST[$strdate]."'");
			//add new
			mysql_query("INSERT INTO shift 
			VALUES(null,'".$d[0]."','".$_POST[$strshift]."',
			'".$_POST[$strdate]."','".$_POST[$strweek]."')") 
			or die(mysql_error());
               		$i++; 
		}
	}
	//echo "<script>location.replace('index.php?p=shiftstaff_list&added=1')</script>";
	header('location:index.php?p=shiftstaff_list&added=1');
}
?>
