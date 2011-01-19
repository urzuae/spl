<?
	if(!defined('_IN_MAIN_INDEX'))
		die("No puedes accesar");
	
	global $db, $data, $_theme;
	
	$_theme = "";
	
	$params = array();
	if (isset($_POST['data']))
	{
		//$data = json_decode($_POST['data']);
		$data = unserialize($data);
		
		print_r($data);
		
		foreach ($data as $key=>$value)
			$params[]=$value;
		
		$name = $params[0];
		$u_id = $params[1];
		
		$sql = "SELECT gid FROM crm_users WHERE name='$group' AND uid='$u_id'";
		list($gid) = $db->sql_fetchrow($db->sql_query($sql) or die()) or die();
		$sql = "UPDATE groups SET id_mayorista='$gid' WHERE gid='$gid'";
		
?>