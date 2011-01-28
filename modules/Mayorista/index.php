<?
	if(!defined('_IN_MAIN_INDEX'))
		die("No puedes accesar directamente a este archivo");
	
	global $db, $uid;
	$registros = array();
	$ventas = array();
	$confirmadas = array();
	$tabla_distribuidores = "";
	
	$sql  = "SELECT gid, super FROM users WHERE uid='$uid'";
	$result = $db->sql_query($sql) or die("Error");
	list($gid, $super) = $db->sql_fetchrow($result);
	if ($super > 6)
	{
	  $_html = "<h1>Usted no es un Gerente</h1>";
	}
	else
	{
		$sql = "SELECT gid, name FROM groups WHERE id_mayorista='$gid' AND active='1'";
		$result = $db->sql_query($sql) or die($sql);
		while(list($_gid, $name) = htmlize($db->sql_fetchrow($result)))
		{
			$registros[$_gid] = $name;
		}
		$sql_1 = "SELECT gid,(select count(*) from crm_prospectos_ventas where uid in (select uid from users u where u.gid=a.gid)) as ventas FROM users a GROUP BY gid";
		$result = $db->sql_query($sql_1);
		while (list($_gid, $vent_count)=htmlize($db->sql_fetchrow($result)))
		{
			$ventas[$_gid] = $vent_count;
		}
		$sql = "SELECT gid,(select count(*) from crm_prospectos_ventas where uid in (select uid from users u where u.gid=a.gid AND venta_confirmada='1')) as ventas FROM users a GROUP BY gid";
		$result = $db->sql_query($sql);
		while (list($_gid, $vent_count) = $db->sql_fetchrow($result))
		{
			$confirmadas[$_gid] = $vent_count;
		}
		$count=0;
		
		foreach($registros as $key=>$value)
		{
			$class = $count % 2 == 0 ? "class=row1" : "class=row2" ;
			$tabla_distribuidores .=
				"<tr $class>
				<td>".$value."&nbsp;</td>
				<td>".$ventas[$key]."</td>
				<td>".$confirmadas[$key]."</td>
				</tr>";
			$count++;
		}
	}

?>