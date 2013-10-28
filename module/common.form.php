<?
//-----------------------------------------------------------------
//		form header
//-----------------------------------------------------------------
function form_header($text){
?>
	<div id="formcols1">
		<div id="formhead"><? echo $text; ?></div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form back
//-----------------------------------------------------------------
function form_back($link,$text){
?>
	<div>
		<div id="field"><a href="<? echo $link; ?>">
		&laquo; Back to <? echo $text; ?> List
		</a></div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form back
//-----------------------------------------------------------------
function form_plain($text){
?>
	<div id="formcols1">
		<div id="formback"><? echo $text; ?></div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form textfield
//-----------------------------------------------------------------
function form_text($label,$name,$value=null,$size=60,$maxlength=255,$note=null,$inputjs=null){
	switch($inputjs){
		case('angka'):
			$inputjsapp = ' onkeypress="return alpha(event,numbers)"';
			break;
		case('hurufangka'):
			$inputjsapp = ' onkeypress="return alpha(event,numbers+letters)"';
			break;
		case('huruf'):
			$inputjsapp = ' onkeypress="return alpha(event,letters)"';
			break;
		case('tanpaspasi'):
			$inputjsapp = ' onkeypress="return alpha(event,letters1+numbers+signs+mathsigns+custom)"';
			break;
		case('huruftanpaspasi'):
			$inputjsapp = ' onkeypress="return alpha(event,letters1)"';
			break;
		default:
			$inputjsapp = null;
			break;
	}
?>
	<div>
		<div id="label"><? echo $label; ?> : </div>
		<div id="field">
		<input type="text" name="<? echo $name; ?>" size="<? echo $size; ?>" maxlength="<? echo $maxlength; ?>" value="<? echo $value; ?>"<? echo $inputjsapp; ?>>
		<BR><? echo $note; ?>
		</div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form text only
//-----------------------------------------------------------------
function form_text_only($label,$value){
?>
	<div>
		<div id="label"><? echo $label; ?> : </div>
		<div id="field"><? echo $value; ?></div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form password
//-----------------------------------------------------------------
function form_password($label,$name,$value=null,$size=60,$maxlength=255,$note=null){
?>
	<div>
		<div id="label"><? echo $label; ?> : </div>
		<div id="field">
		<input type="password" name="<? echo $name; ?>" size="<? echo $size; ?>" maxlength="<? echo $maxlength; ?>" value="<? echo $value; ?>">
		<BR><? echo $note; ?>
		</div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form textarea
//-----------------------------------------------------------------
function form_textarea($label,$name,$h,$value=null,$note=null){
?>
	<div>
		<div id="label"><? echo $label; ?> : </div>
		<div id="field">
		<textarea name="<? echo $name; ?>" style="height:<? echo $h; ?>px;"><? echo $value; ?></textarea>
		<BR><? echo $note; ?>
		</div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form submit
//-----------------------------------------------------------------
function form_submit($label,$name){
?>
	<div>
		<div id="label"><input type="submit" name="<? echo $name; ?>" value="<? echo $label; ?>"></div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form submit search
//-----------------------------------------------------------------
function form_submit_search($label,$name){
?>
	<div>
		<div id="label">&nbsp;</div>
		<div id="field"><input type="submit" name="<? echo $name; ?>" value="<? echo $label; ?>"></div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form hidden
//-----------------------------------------------------------------
function form_hidden($name,$value){
?>
	<input type="hidden" name="<? echo $name; ?>" value="<? echo $value; ?>">
<?
}
//-----------------------------------------------------------------
//		form select db
//-----------------------------------------------------------------
function form_select_db($label,$name,$field,$value=null,$submit=null,$except=null){
	if(($_GET['p']=='task_edit' || $_GET['p']=='task_add') && preg_match('/staff/',$name)){ 
		$style1 = ' style="margin-left:50px"';
		$style2 = ' style="margin-left:50px"';
	}
?>
	<div>
		<div id="label"<?=$style1;?>><? echo $label; ?> : </div>
		<div id="field"<?=$style2;?>>
		<select name="<? echo $name; ?>" <? echo $submit; ?>>
			<option value=""></option>
			<?
			if(preg_match('/staff/',$name)){ 
				$name = 'staff';
				$field = 'first_name,last_name';
			}
			$query = mysql_query("SELECT id,".$field." FROM ".$name." ".$except." ORDER BY id");
			while($data = mysql_fetch_array($query)){
				if($data['id']==$value){
					$selected = 'selected';
				}else{
					$selected = null;
				}
				if(preg_match('/staff/',$name)){
					$field_view = $data['first_name'].' '.$data['last_name'];
				}else{
					$field_view = $data[$field];
				}
			?>
				<option value="<? echo $data[id]; ?>" <? echo $selected; ?>>
				<? echo $field_view; ?></option>
			<? } ?>
		</select>
		</div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form select array
//-----------------------------------------------------------------
function form_select_array($label,$name,$list_id,$list_value,$value=null,$submit=null){
?>
	<div>
		<div id="label"><? echo $label; ?> : </div>
		<div id="field">
		<select name="<? echo $name; ?>" <? echo $submit; ?>>
			<option value=""></option>
			<?
			$i=0;
			foreach($list_id as $list1){
				if($list1==$value){
					$selected = 'selected';
				}else{
					$selected = null;
				}
			?>
				<option value="<? echo $list1; ?>" <? echo $selected; ?>>
				<? echo $list_value[$i]; ?></option>
			<? $i++; } ?>
		</select>
		</div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form file
//-----------------------------------------------------------------
function form_file($label,$name,$value=null,$note=null){
?>
	<div>
		<div id="label"><? echo $label; ?> : </div>
		<div id="field">
		<input name="<? echo $name; ?>" type="file" size="40" value="<? echo $value; ?>">
		<BR><? echo $note; ?>
		</div>
	</div>
<?
}
//-----------------------------------------------------------------
//		form date
//-----------------------------------------------------------------
function form_date($label,$name,$date=null,$note=null)
{
?>
<div id="formcols">
	<div id="label"><? echo $label; ?> : </div>
	<div id="field">
	<? form_date_field($label,$name,$date,$note); ?>
	</div>
</div>
<?
}
//-----------------------------------------------------------------
//		form date search
//-----------------------------------------------------------------
function form_date_search($label,$name,$date=null,$note=null)
{
?>
<div style="margin:0px; padding:0px;">
	<div style="float:left; width:100px; text-align:right;"><b><? echo $must.$label; ?> : </b></div>
	<div><? form_date_field($label,$name,$date,$note); ?></div>
</div>
<?
}
//-----------------------------------------------------------------
//		form date field
//-----------------------------------------------------------------
function form_date_field($label,$name,$date=null,$note=null)
{
	global $enable_submit,$enable_time;
	if(in_array($_GET['p'],$enable_submit)){
		$submit = 'onchange="submit()"';
	}else{ $submit = null; }
	//----------------------
	$year_str = 'y'.$name;
	$_POST[$year_str] = $yy;
	//----------------------
	if(!empty($date) || preg_match('/_list/',$_GET['p'])){
		if(in_array($_GET['p'],$enable_time)){
			list($date1,$time) = explode(' ',$date);
			list($yy,$mm,$dd) = explode('-',$date1);
			list($hh,$ii,$ss) = explode(':',$time);
		}else{
			list($yy,$mm,$dd) = explode('-',$date);
		}
		$year_str = 'y'.$name;
		$_POST[$year_str] = $yy;
		$month_str = 'm'.$name;
		$_POST[$month_str] = $mm;
		$date_str = 'd'.$name;
		$_POST[$date_str] = $dd;
		if(in_array($_GET['p'],$enable_time)){
			$hour_str = 'h'.$name;
			$_POST[$hour_str] = $hh;
			$minute_str = 'i'.$name;
			$_POST[$minute_str] = $ii;
		}
	}else{
		$year_str = 'y'.$name;
		$yy = $_POST[$year_str];
		$month_str = 'm'.$name;
		$mm = $_POST[$month_str];
		$date_str = 'd'.$name;
		$dd = $_POST[$date_str];
		if(empty($yy)){
			if($label!='Expired'){
				$yy = date('Y');
			}else{ 
				$yy = date('Y')+1;
			}
		}
		if(empty($mm)){ $mm = date('m'); }
		if(empty($dd)){ $dd = date('d'); }
		if(in_array($_GET['p'],$enable_time)){
			$hour_str = 'h'.$name;
			$hh = $_POST[$hour_str];
			$minute_str = 'i'.$name;
			$ii = $_POST[$minute_str];
			if($hh==''){ $hh = date('H'); }
			if($ii==''){ $ii = date('i'); }
		}
	}
?>
	<select name="d<? echo $name; ?>" style="min-width:60px;" <?=$submit;?>>
		<option value="">day</option>
	<?
	for($i=1;$i<=31;$i++){
		if($i == $dd){
			$selected = 'selected';
		}else{
			$selected = null;
		}
	?>
		<option value="<? echo $i; ?>" <? echo $selected; ?>><? echo $i; ?></option>
	<? } ?>
	</select>
	<!-- Month -->
	<select name="m<? echo $name; ?>" style="min-width:80px;" <?=$submit;?>>
		<option value="">month</option>
	<?
	for($i=1;$i<=12;$i++){
		if($i == $mm){
			$selected = 'selected';
		}else{
			$selected = null;
		}
	?>
		<option value="<? echo $i; ?>" <? echo $selected; ?>><? echo $i; ?></option>
	<? } ?>
	</select>
	<!-- Year -->
	<select name="y<? echo $name; ?>" style="min-width:80px;" <?=$submit;?>>
		<option value="">year</option>
	<?
	$y = date('Y')-2; //tahun sekarang
	$yn = $y+4; //4 tahun kemudian
	for($i=$y;$i<=$yn;$i++){
		if($i == $yy){
			$selected = 'selected';
		}else{
			$selected = null;
		}
	?>
		<option value="<? echo $i; ?>" <? echo $selected; ?>><? echo $i; ?></option>
	<? } ?>
	</select>
	<?
	if(in_array($_GET['p'],$enable_time)){
	?>
		<select name="h<? echo $name; ?>" style="min-width:60px;" <?=$submit;?>>
		<?
		for($i=0;$i<=24;$i++){
			if($i == $hh){
				$selected = 'selected';
			}else{
				$selected = null;
			}
		?>
			<option value="<? echo $i; ?>" <? echo $selected; ?>><? echo $i; ?></option>
		<? } ?>
		</select>
		<!-- Minutes -->
		<select name="i<? echo $name; ?>" style="min-width:80px;" <?=$submit;?>>
		<?
		for($i=0;$i<=59;$i++){
			if($i == $ii){
				$selected = 'selected';
			}else{
				$selected = null;
			}
		?>
			<option value="<? echo $i; ?>" <? echo $selected; ?>><? echo $i; ?></option>
		<? } ?>
		</select>
	<?
	}
	?>
	<? echo '&nbsp;&nbsp;'.$note; ?>
<?
}
//-----------------------------------------------------------------
//		INPUT VERIFICATION
//-----------------------------------------------------------------
//-----------------------------------------------------------------
//		post verification
//-----------------------------------------------------------------
function post_verify($label,$value){
	if(empty($value)){
		return '<font style="color:#883636">'.$label.' cannot be empty!</font>';
	}else{
		return null;
	}
}
//-----------------------------------------------------------------
//		exist verification
//-----------------------------------------------------------------
function exist_verify($label,$table_name,$field_name,$value,$except=null){
	$exist = mysql_num_rows(mysql_query("SELECT ".$field_name." FROM ".$table_name." 
	WHERE ".$field_name."='".$value."' AND id!='".$except."'"));
	if($exist!=0){
		return '<font style="color:#883636">'.$label.' <b>'.$value.'</b> already exist!</font>';
	}else{
		return null;
	}
}
//-----------------------------------------------------------------
//		same verification
//-----------------------------------------------------------------
function same_verify($label,$value1,$value2){
	if($value1!=$value2){
		return '<font style="color:#883636">'.$label.' is not same with confirmation!</font>';
	}else{
		return null;
	}
}
//-----------------------------------------------------------------
//		mail verification
//-----------------------------------------------------------------
function mail_verify($label,$value){
	if(!empty($value)){
		if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$",$value)){
			return '<font style="color:#883636">'.$label.' is not valid e-mail format!</font>';
		}else{
			return null;
		}
	}
}
//-----------------------------------------------------------------
//		number verification
//-----------------------------------------------------------------
function number_verify($label,$value){
	if(!empty($value)){
		if(!eregi("[0-9]",$value)){
			return '<font style="color:#883636">'.$label.' is only allowed for number <i>(0 to 9)</i>!</font>';
		}else{
			return null;
		}
	}
}
?>
