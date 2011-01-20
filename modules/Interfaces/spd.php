<?
	/*if(!defined('_IN_MAIN_INDEX'))
		die("No puedes accesar");
	
	global $db, $data, $_theme, $_html;
	
	$_theme = "";
	$_html = "";

	/*if (isset($_POST['data']))
	{
		//$data = json_decode($_POST['data']);
		$data = unserialize($data);
		
		//print_r($data);
		
		foreach ($data as $key=>$value)
			$params[]=$value;
		
		$user = $params[0];
		$password = $params[1];
		$name = $params[2];
		$email = $params[3];
		$group = $user;
		//$group = $params[4];
		$mayorista = "0001";
		
		$n =$db->sql_numrows($db->sql_query("SELECT name FROM groups WHERE name='$group'"));
    if ($n != 0)
      die("No se pudo crear la distribuidora por que ya existe otra con el nombre \"$new\"");
    else
    {
			$modules = array("Bienvenida", "Gerente", "Noticias", "Directorio", "Campanas","Estadisticas");
			// Se añade a groups
			$db->sql_query("INSERT INTO groups (gid, name,active,id_mayorista)VALUES('','$group','1','$mayorista')") or die("No se pudo crear");
			$gid_sig=$db->sql_nextid();
			$db->sql_query("INSERT INTO groups_ubications (gid, name,nivel_id,nombre_nivel)VALUES('$gid_sig','$group','1','Básico')") or die("No se pudo crear la concesionaria");
			$db->sql_query("INSERT INTO crm_niveles_concesionarias (gid, nombre,nivel_id)VALUES('$gid_sig','Básico','1')") or die("No se pudo crear la concesionaria");
    
			// Creamos los modulos para la concesionaria
			$gid=$gid_sig;
			foreach($modules AS $module)
			{
				$sql = "SELECT gid FROM groups_accesses WHERE gid='$gid' AND module='$module' LIMIT 1";
				$result2 = $db->sql_query($sql) or die("Error<br>$sql<br>".print_r($db->sql_error()));
				if ($db->sql_numrows($result2) < 1 )
					$db->sql_query("INSERT INTO groups_accesses (gid,module)VALUES('$gid','$module')") or die("Error<br>".print_r($db->sql_error()));
			}
			// Creamos sus ciclo de venta de la concesionaria
			$sql="SELECT campana_id,etapa_ciclo_id,nombre,next_campana_id FROM crm_campanas WHERE campana_id<=10 order by etapa_ciclo_id;";
			$res=$db->sql_query($sql);
			$contador=0;
			$num = $db->sql_numrows($res);
			if($num > 0)
			{
				while(list($campana_id,$etapa_ciclo,$nombre,$next_campana_id) = $db->sql_fetchrow($res))
				{
					$contador++;
					$campana_id=str_pad($gid,4,'0',STR_PAD_LEFT).str_pad($etapa_ciclo,2,'0',STR_PAD_LEFT);
					$nombre=str_pad($gid,4,'0',STR_PAD_LEFT).'-'.$etapa_ciclo.' '.substr($nombre,6,strlen($nombre));
					$next_campana_id=str_pad($gid,4,'0',STR_PAD_LEFT).str_pad($next_campana_id,2,'0',STR_PAD_LEFT);
					if($contador == $num) $next_campana_id=0;
					$ins="INSERT INTO crm_campanas (campana_id,etapa_ciclo_id,nombre,next_campana_id)
								VALUES ('$campana_id','$etapa_ciclo','$nombre','$next_campana_id');";
					if($db->sql_query($ins) or die ("Error en el insert del ciclo de venta ".$ins))
					{
							$ins2="INSERT INTO crm_campanas_groups (campana_id,gid) values ('".$campana_id."','".str_pad($gid,4,'0',STR_PAD_LEFT)."');";
							$db->sql_query($ins2);
					}
        }
      }
			// Creamos el usuario de gerente de vtas
			$user = str_pad($gid,4,'0',STR_PAD_LEFT)."GERENTE";
			$sql = "INSERT INTO users (gid,super,user,password,name,email) VALUES ('$gid','6','$user',PASSWORD('$user'),'GERENTE DISTRIBUIDORA $group','$email')";
			$db->sql_query($sql) or die("Error<br>$sql<br>".print_r($db->sql_error()));
			
			/*$region_id = $region_id+0;
			$zona_id   = $zona_id+0;
			$entidad_id = $entidad_id +0;
			$plaza_id = $plaza_id + 0;
			$id_nivel= $id_nivel + 0;
			$tit='Básico';

$msg =<<<EOBODY
    <html>
      <head>
        <title>Claves de acceso para el gerente</title>
      </head>
      <body>
				<img style="margin-left:5px;" src="http://www.pcsmexico.com/salesfunnel/activation/images/sf_small.png" /><br/><br/>
				<br/>
				<b>Se ha dado de alta la Distribuidora {$group}, las claves de acceso para entrar a su sesi&oacute;n de Gerente son las siguientes:</b>
				<p>Usuario: <b>{$user}</b> </p>
				<p>Contrase&ntilde;a: <b>{$user}</b> </p>
				<p>Al ingresar por primera con su nueva cuenta, se le solicitar&aacute; que actualize sus datos.</p>
      </body>
    </html>
EOBODY;*/

			//$updateGroupsUbications="UPDATE groups_ubications SET region_id=".$region_id.",zona_id=".$zona_id.", entidad_id=".$entidad_id.", plaza_id=".$plaza_id.",nivel_id=".$id_nivel.",nombre_nivel='".$tit."' WHERE gid=".$gid.";";
			//$db->sql_query($updateGroupsUbications) or die("Error al actualizar la tabla de ubicaciones de concesionarias".$updateGroupsUbications);
			/*$eol="\n";
			$headers = 'From: salesfunnel@pcsmexico.com' . $eol;
			$headers .= 'Reply-To: <salesfunnel@pcsmexico.com>' . $eol;
			$headers .= 'Return-Path: <salesfunnel@pcsmexico.com>' . $eol;
			$headers .= 'Content-Type: text/html; charset=iso-8859-1';
			
			mail( $email, "Alta de distribuidora", $msg, $headers);*/
			//header("location: index.php?_module=Concesionarias");    

			/*	
			print_r($params);
			
			if ($db->sql_numrows($db->sql_query("SELECT name FROM users WHERE user='$user'")) > 0)
				$error = "<br>Ese usuario ya esta registrado en el sistema, intenta otro nombre de usuario";
			else
			{
				$password = strtoupper($password);
				$res = $db->sql_query("INSERT INTO users (`user`, `name`, `gid`, `super`, `password`,`email`) VALUES('$user', '$name', '$gid', '$super_val', PASSWORD('$password'), '$email')")
						or die("No se pudo agregar el usuario ".print_r($db->sql_error()));
				
				$uid = $db->sql_nextid();
				
				//ahora seteamos la configuración por default (copiando la de uid=0 en la db)
				$result = $db->sql_query("SELECT * FROM users_configs WHERE uid='0' LIMIT 1")
				or die("Error al cargar la configuracion por default");
				$row = $db->sql_fetchrow($result);
				//el primer valor sera el uid, así ke lo kambiamos por el nuevo
				$values = "'$uid'";
				$i = 1;
				while (isset($row[$i]))
				{
						$values .= ", '".$row[$i++]."'";
				}
				$sql = "INSERT INTO users_configs VALUES($values)";
				$db->sql_query($sql) or die("Error al crear configuración por default");
			}
			return true;
		}
		return true;
	}
	else
		return false;*/
	
	class spd
	{
		public function new_distributor($param)
		{
			if ($param)
			{
				$_dbhost = 'localhost';
				$_dbuname = 'root';
				$_dbpass = 'redsox';
				$_dbname = 'spl';
				include("../../includes/db/mysql.php");
				$db = new sql_db($_dbhost, $_dbuname, $_dbpass, $_dbname, false);

				
				$data = $param;
				$params = array();
				foreach ($data as $key=>$value)
					$params[]=$value;
				
				$user = $params[0];
				$password = $params[1];
				$name = $params[2];
				$email = $params[3];
				$group = $user;
				//$group = $params[4];
				$mayorista = "0001";
				
				$n =$db->sql_numrows($db->sql_query("SELECT name FROM groups WHERE name='$group'"));
				if ($n != 0)
					die("No se pudo crear la distribuidora por que ya existe otra con el nombre \"$new\"");
				else
				{
					$modules = array("Bienvenida", "Gerente", "Noticias", "Directorio", "Campanas","Estadisticas");
					// Se añade a groups
					$db->sql_query("INSERT INTO groups (gid, name,active,id_mayorista)VALUES('','$group','1','$mayorista')") or die("No se pudo crear");
					$gid_sig=$db->sql_nextid();
					$db->sql_query("INSERT INTO groups_ubications (gid, name,nivel_id,nombre_nivel)VALUES('$gid_sig','$group','1','Básico')") or die("No se pudo crear la concesionaria");
					$db->sql_query("INSERT INTO crm_niveles_concesionarias (gid, nombre,nivel_id)VALUES('$gid_sig','Básico','1')") or die("No se pudo crear la concesionaria");
				
					// Creamos los modulos para la concesionaria
					$gid=$gid_sig;
					foreach($modules AS $module)
					{
						$sql = "SELECT gid FROM groups_accesses WHERE gid='$gid' AND module='$module' LIMIT 1";
						$result2 = $db->sql_query($sql) or die("Error<br>$sql<br>".print_r($db->sql_error()));
						if ($db->sql_numrows($result2) < 1 )
							$db->sql_query("INSERT INTO groups_accesses (gid,module)VALUES('$gid','$module')") or die("Error<br>".print_r($db->sql_error()));
					}
					// Creamos sus ciclo de venta de la concesionaria
					$sql="SELECT campana_id,etapa_ciclo_id,nombre,next_campana_id FROM crm_campanas WHERE campana_id<=10 order by etapa_ciclo_id;";
					$res=$db->sql_query($sql);
					$contador=0;
					$num = $db->sql_numrows($res);
					if($num > 0)
					{
						while(list($campana_id,$etapa_ciclo,$nombre,$next_campana_id) = $db->sql_fetchrow($res))
						{
							$contador++;
							$campana_id=str_pad($gid,4,'0',STR_PAD_LEFT).str_pad($etapa_ciclo,2,'0',STR_PAD_LEFT);
							$nombre=str_pad($gid,4,'0',STR_PAD_LEFT).'-'.$etapa_ciclo.' '.substr($nombre,6,strlen($nombre));
							$next_campana_id=str_pad($gid,4,'0',STR_PAD_LEFT).str_pad($next_campana_id,2,'0',STR_PAD_LEFT);
							if($contador == $num) $next_campana_id=0;
							$ins="INSERT INTO crm_campanas (campana_id,etapa_ciclo_id,nombre,next_campana_id)
										VALUES ('$campana_id','$etapa_ciclo','$nombre','$next_campana_id');";
							if($db->sql_query($ins) or die ("Error en el insert del ciclo de venta ".$ins))
							{
									$ins2="INSERT INTO crm_campanas_groups (campana_id,gid) values ('".$campana_id."','".str_pad($gid,4,'0',STR_PAD_LEFT)."');";
									$db->sql_query($ins2);
							}
						}
					}
					// Creamos el usuario de gerente de vtas
					$user = str_pad($gid,4,'0',STR_PAD_LEFT)."GERENTE";
					$sql = "INSERT INTO users (gid,super,user,password,name,email) VALUES ('$gid','6','$user',PASSWORD('$user'),'GERENTE DISTRIBUIDORA $group','$email')";
					$db->sql_query($sql) or die("Error<br>$sql<br>".print_r($db->sql_error()));
					
					/*$region_id = $region_id+0;
					$zona_id   = $zona_id+0;
					$entidad_id = $entidad_id +0;
					$plaza_id = $plaza_id + 0;
					$id_nivel= $id_nivel + 0;
					$tit='Básico';
		
		$msg =<<<EOBODY
				<html>
					<head>
						<title>Claves de acceso para el gerente</title>
					</head>
					<body>
						<img style="margin-left:5px;" src="http://www.pcsmexico.com/salesfunnel/activation/images/sf_small.png" /><br/><br/>
						<br/>
						<b>Se ha dado de alta la Distribuidora {$group}, las claves de acceso para entrar a su sesi&oacute;n de Gerente son las siguientes:</b>
						<p>Usuario: <b>{$user}</b> </p>
						<p>Contrase&ntilde;a: <b>{$user}</b> </p>
						<p>Al ingresar por primera con su nueva cuenta, se le solicitar&aacute; que actualize sus datos.</p>
					</body>
				</html>
EOBODY;
		
					//$updateGroupsUbications="UPDATE groups_ubications SET region_id=".$region_id.",zona_id=".$zona_id.", entidad_id=".$entidad_id.", plaza_id=".$plaza_id.",nivel_id=".$id_nivel.",nombre_nivel='".$tit."' WHERE gid=".$gid.";";
					//$db->sql_query($updateGroupsUbications) or die("Error al actualizar la tabla de ubicaciones de concesionarias".$updateGroupsUbications);
					/*$eol="\n";
					$headers = 'From: salesfunnel@pcsmexico.com' . $eol;
					$headers .= 'Reply-To: <salesfunnel@pcsmexico.com>' . $eol;
					$headers .= 'Return-Path: <salesfunnel@pcsmexico.com>' . $eol;
					$headers .= 'Content-Type: text/html; charset=iso-8859-1';
					
					mail( $email, "Alta de distribuidora", $msg, $headers);*/
					//header("location: index.php?_module=Concesionarias");    
				}
				return $gid;
			}
			else
				return false;
		}
		
		public function update_distributor($param)
		{
			$_dbhost = 'localhost';
			$_dbuname = 'root';
			$_dbpass = 'redsox';
			$_dbname = 'spl';
			include("../../includes/db/mysql.php");
			$db = new sql_db($_dbhost, $_dbuname, $_dbpass, $_dbname, false);
			
			$data = $param;
			$params = array();
			
			foreach ($data as $key=>$value)
				$params[]=$value;
			
			$gid = $params[0];
			
			$sql = "SELECT * FROM groups WHERE gid='$gid'";
			$result = $db->sql_query($sql);
			if ($db->sql_numrows($result) > 0)
			{
				$sql = "UPDATE groups SET id_mayorista='$gid' WHERE gid='$gid'";
				return true;
			}
			else
				return false;
		}
	}
	
	$soap=new SoapServer(null,array('uri'=>'http://localhost'));
	$soap->setClass('spd');
	$soap->handle();
	die();

?>