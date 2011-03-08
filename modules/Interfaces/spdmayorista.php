<?
	class spdmayorista
	{
		public function update_distributor($params)
		{
			$_dbhost = 'localhost';
			$_dbuname = 'root';
			$_dbpass = 'redsox';
			$_dbname = 'spl';
			$gid = "";
			include("../../includes/db/mysql.php");
			$db = new sql_db($_dbhost, $_dbuname, $_dbpass, $_dbname, false);
			
			
			$gid = $params[0];
			
			switch (strlen($gid))
			{
			case 1:
			 $gid = "000".$gid;
			 break;
			case 2:
			 $gid = "00".$gid;
			 break;
			case 3:
			 $gid = "0".$gid;
			 break;
			}
			$sql = "SELECT * FROM groups WHERE gid='$gid'";
			$result = $db->sql_query($sql);
			if ($db->sql_numrows($result) > 0)
			{
				$sql = "UPDATE groups SET id_mayorista='$gid' WHERE gid='$gid'";
				$db->sql_query($sql) or die($sql);
				$sql = "SELECT * FROM  groups_accesses WHERE gid='$gid' AND module='Mayorista'";
				$resultado = $db->sql_query($sql);
				$numro = $db->sql_numrows($resultado);
				if ($numro < 1)
					$db->sql_query("INSERT INTO groups_accesses (gid, module) VALUES ('$gid','Mayorista')") or die("Error al editar permisos");
				return true;
			}
			else
				return false;
		}
	}
	
	$soap=new SoapServer(null,array('uri'=>'http://localhost'));
	$soap->setClass('spdmayorista');
	$soap->handle();
	die();


?>