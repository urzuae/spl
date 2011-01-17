<?php

	if(!defined('_IN_MAIN_INDEX'))
		die("No puedes accesar directamente a este archivo");
	
	global $db, $_theme, $data;
	
	$_theme = "";
	
	if (isset($_POST['data'])) {
		$data = unserialize($data);
	}
	
	$key;
	
	$sql = "SELECT contacto_id, uid, venta_confirmada FROM crm_prospectos_ventas WHERE chasis='".$key."'";
	
	$result = $db->sql_query($sql) or die($sql);
	
	if(list($contacto_id, $uid, $venta_c) = $db->sql_fetchrow($result))
	{
		if($venta_c == 1)
		{
			$mensaje = "Licencia ya fue activada anteriormente";
		}
		else
		{
			$sql = "UPDATE crm_prospectos_ventas SET venta_confirmada='1' WHERE chasis='".$key."'";
			$result = $db->sql_query($sql) or die($sql);
			$mensje = 
		}
	}

?>