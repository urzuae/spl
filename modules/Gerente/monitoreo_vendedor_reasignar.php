<?
  if (!defined('_IN_MAIN_INDEX')) {
    die ("No puedes acceder directamente a este archivo...");
}
global $db, $how_many, $from, $campana_id, $nombre, $apellido_paterno, $apellido_materno, 
        $submit, $status_id, $ciclo_de_venta_id, $uid, $orderby;

$sql  = "SELECT gid, super FROM users WHERE uid='".$_COOKIE['_uid']."'";
$result = $db->sql_query($sql) or die("Error");
list($gid, $super) = $db->sql_fetchrow($result);
if ($super > 6)
{
  die("<h1>Usted no es un Gerente</h1>");
}
//hacer hiddens los chbx
$sql = "SELECT c.contacto_id" //buscar todos los que pudieran ser posibles
	." FROM crm_contactos AS c  WHERE (gid='$gid' )";//OR gid='0'
$result = $db->sql_query($sql) or die("Error al leer".print_r($db->sql_error()));

$hiddens .= "<input type=\"hidden\" name=\"asignar_a\" value=\"\">";
$hiddens .= "<input type=\"hidden\" name=\"penalizar\" value=\"\">";
if ($db->sql_numrows($result) > 0)
{
  while (list($contacto_id) = $db->sql_fetchrow($result)) //revisar si lo mandaron en el post ( => on)
  {
	$tmp = "chbx_".$contacto_id;
	if (array_key_exists("$tmp", $_POST))
	{
		$hiddens .= "<input type=\"hidden\" name=\"$tmp\" value=\"on\">";
		$cuantos_reasignar++;
	}
  }
  
}

//reasignar
global $asignar_a, $penalizar;
if ($asignar_a) //si se van a reasignar 
{
    //buscar a que campaña lo meteremos
    $sql = "SELECT c.campana_id FROM crm_campanas_groups AS g, crm_campanas AS c WHERE c.campana_id=g.campana_id AND g.gid='$gid' ORDER BY c.campana_id  LIMIT 1"; //la primera que sea parte de un ciclo
    $result = $db->sql_query($sql) or die("Error al leer" . print_r($db->sql_error()));
    list($campana_id) = $db->sql_fetchrow($result);
    $sql = "SELECT c.contacto_id" //buscar todos los que pudieran ser posibles
            . " FROM crm_contactos AS c  WHERE (gid='$gid' )"; //OR gid='0'
    $result = $db->sql_query($sql) or die("Error al leer" . print_r($db->sql_error()));

    if ($db->sql_numrows($result) > 0)
    {
        while (list($contacto_id) = $db->sql_fetchrow($result)) //revisar si lo mandaron en el post ( => on)
        {
            $tmp = "chbx_$contacto_id";
            if (array_key_exists("$tmp", $_POST))
            {
                //penalizar al vendedor y para loggear el movimiento
                $sql = "SELECT uid FROM crm_contactos WHERE contacto_id='$contacto_id' AND (gid='$gid' ) ";
                $r3 = $db->sql_query($sql) or die("Error al asignar" . print_r($db->sql_error()));
                list($penalizar_a) = $db->sql_fetchrow($r3);
                if ($penalizar)
                {
                    $sql = "UPDATE users SET score=score-'$penalizar' WHERE uid='$penalizar_a'";
                    $db->sql_query($sql) or die("Error al asignar" . print_r($db->sql_error()));
                    $sql = "INSERT INTO users_penalties (uid,uid_super,score) VALUES('$penalizar_a','$uid','-$penalizar')";
                    $db->sql_query($sql) or die($sql);
                }
                //cambiar al asignado
                $sql = "UPDATE crm_contactos SET uid='$asignar_a' WHERE contacto_id='$contacto_id' AND (gid='$gid' ) "; //OR gid='0'
                $db->sql_query($sql) or die("Error al asignar" . print_r($db->sql_error()));

                //ahora mandarlo a la primer campaña
                //checar primero si no está en alguna ya
                $sql = "SELECT id FROM crm_campanas_llamadas WHERE contacto_id='$contacto_id' LIMIT 1";
                $result2 = $db->sql_query($sql) or die("Error al leer" . print_r($db->sql_error()));
                if (list($llamada_id) = $db->sql_fetchrow($result2))
                    $sql = "UPDATE crm_campanas_llamadas SET campana_id='$campana_id' WHERE id='$llamada_id'";
                else
                    $sql = "INSERT INTO crm_campanas_llamadas (campana_id,status_id,fecha_cita)VALUES('$campana_id','-2','0000-00-00 00:00:00')";
                //comento para que no regrese a la primera del ciclo de venta, y se quede en donde estaba
                //$db->sql_query($sql) or die("Error al leer".print_r($db->sql_error()));
                //meter la asignación al log
                $sql = "INSERT INTO crm_contactos_asignacion_log (contacto_id,uid,from_uid,to_uid)VALUES('$contacto_id','-1','$penalizar_a','$asignar_a')";
                $db->sql_query($sql) or die("Error al leer" . print_r($db->sql_error()));
            }
        }
    }
    die("<html><head><script>alert('Contactos reasignados');location.href='index.php?_module=$_module&_op=monitoreo_vendedores';</script></head></html>"); //regresar
}


//lo que sigue es copy paste ed monitoreo vendedores
if (!$orderby) $orderby = "nombre";

$result = $db->sql_query("SELECT gid FROM users WHERE uid='$uid' LIMIT 1") or die("Error en grupo ".print_r($db->sql_error()));
list($gid) = $db->sql_fetchrow($result);
$campanas = array();
//buscar las campanas a las que tengo acceso
$letrero="<table style='border:0px;' align='left'>";
$sql = "SELECT c.campana_id, nombre FROM crm_campanas AS c, crm_campanas_groups  AS g WHERE c.campana_id=g.campana_id AND gid='$gid' ORDER BY $orderby";
$result = $db->sql_query($sql) or die("$sql<br>Error al consultar campañas ".print_r($db->sql_error()));
while (list($campana_id, $name) = htmlize($db->sql_fetchrow($result)))
{
    $campanas[$campana_id] = $name;
    $campanas[$campana_id] = $name;
    $columnas_tabla.="<th class='tdleft'>".strtoupper(substr($name,8,3))."</th>";
    $letrero.="<tr><td class='tdleft'>".strtoupper(substr($name,8,3))."</td><td class='tdleft'>".$name."</td></tr>";

}
$letrero.='</table>';

//obtenemos a los usuarios del grupo
$sql = "SELECT uid, name FROM users WHERE gid='$gid' and super=8";
$result = $db->sql_query($sql) or die("Error al consultar campañas ".print_r($db->sql_error()));
while (list($c_uid, $c_name) = htmlize($db->sql_fetchrow($result)))
	$users[$c_uid] = $c_name;

foreach ($users AS $c_uid=>$name)
{	
	$total = 0;
	//calcular contactos en ciclo
	$contactos[$c_uid] = array();
	foreach ($campanas AS $campana_id=>$name)
	{
		//a cada una calcularle sus contactos
		$sql = "SELECT (id) FROM crm_campanas_llamadas AS l, crm_contactos AS c WHERE c.contacto_id=l.contacto_id AND c.uid='$c_uid' AND campana_id='$campana_id'";
		$r2 = $db->sql_query($sql) or die($sql);
		$cuantos = $db->sql_numrows($r2);
		$contactos[$c_uid][$campana_id] = $cuantos;
		$total += $cuantos;
	}
}

$tabla_campanas .= "$letrero<br><table border=\"0\" style=\"width:100%\">\n";
$tabla_campanas .= "<thead><tr>"
                    ."<th rowspan=\"2\" >Usuario</th>"
                    ."<th colspan=\"".count($campanas)."\" class='tdcenter'>Ciclo</th>"
                    ."<th rowspan=\"2\">Total</th>"
                    ."<th rowspan=\"2\" colspan=\"2\" class=\"tdcenter\">Reasignar</th>"
                    ."</tr>"
                    ."<tr>".$columnas_tabla.""
                    ."</tr>"
                    ."</thead>\n";
foreach ($users AS $c_uid=>$uname)
{
	$tabla_campanas .= "<tr class=\"row".(($c++%2)+1)."\">";
	$tabla_campanas .= "<td>$uname</td>";
	$total_contactos = 0;
	foreach ($campanas AS $campana_id=>$name)
	{
		$tabla_campanas .= "<td>".($contactos[$c_uid][$campana_id])."</td>";
		$total_contactos += $contactos[$c_uid][$campana_id];
		$total_campanas[$campana_id] += $contactos[$c_uid][$campana_id];
	}
	$tabla_campanas .= "<td>$total_contactos</td>";
	$tabla_campanas .= "<td><a href=\"#\" onclick=\"document.seleccionar.asignar_a.value='$c_uid';document.seleccionar.submit();\"> Reasignar sin penalización</a></td>";
	$tabla_campanas .= "<td><a href=\"#\" onclick=\"document.seleccionar.penalizar.value='1';document.seleccionar.asignar_a.value='$c_uid';document.seleccionar.submit();\"> Reasignar con penalización</a></td>";	
	$tabla_campanas .= "</tr>";
}
$tabla_campanas .= "<thead><tr class=\"row".(($c++%2)+1)."\" style=\"font-weight:bold;\">
            <th  align=\"right\"><b>Total</b></th>";
foreach ($campanas AS $campana_id=>$name)
{
	$tabla_campanas .= "<th>".$total_campanas[$campana_id]."</th>";
	$total_total += $total_campanas[$campana_id];
}
$tabla_campanas .= "<th>$total_total</th>";
$tabla_campanas .= "<th></th>";
$tabla_campanas .= "<th></th>";
$tabla_campanas .= "</tr></thead>";
$tabla_campanas .= "</table>\n";


?>
