<?
function get_link($type,$pg){
	$pg = ereg_replace(' ','_',$pg);
	if($type == 'product'){
		$link = 'index.php?p=product&pg='.$pg;
	}elseif($type == 'article'){
		$link = 'index.php?p=article&pg='.$pg;
	}elseif($type == 'advance'){
		$link = $pg;
	}
	return $link;
}
//-----------------------------------------------------------------
//		user header
//-----------------------------------------------------------------
function user_header(){
	global $valid;
?>
	<div id="Header">
	<h1>Test HRM</h1>
		<ul id="jsddm">
		</ul>
	</div>
<?
}
//-----------------------------------------------------------------
//		user content
//-----------------------------------------------------------------
function user_content(){
?>
	<div id="general">
		<? page_structure('main'); ?>
		<div style="clear:both;"></div>
	</div>
<?
}
//-----------------------------------------------------------------
//		user footer
//-----------------------------------------------------------------
function user_footer(){
}
?>
