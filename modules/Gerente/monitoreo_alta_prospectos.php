<?
if (!defined('_IN_MAIN_INDEX')) {
  die ("No puedes acceder directamente a este archivo...");
}

global $db, $uid, $_site_title;
$_site_title = "Monitoreo alta de prospectos-SFG0012";

if($_REQUEST["fecha_ini"] != "" and $_REQUEST["fecha_fin"] != "")
{
    $rango_fechas = " and date_format(a.timestamp,'%Y-%m-%d') between '".$_REQUEST["fecha_ini"]."' and '".$_REQUEST["fecha_fin"]."'";
    $rango_fechas_c = " and date_format(a.fecha_importado,'%Y-%m-%d') between '".$_REQUEST["fecha_ini"]."' and '".$_REQUEST["fecha_fin"]."'";
}
elseif($_REQUEST["fecha_ini"] != "")
{
    $rango_fechas = " and date_format(a.timestamp,'%Y-%m-%d') >= '".$_REQUEST["fecha_ini"]."'";
    $rango_fechas_c = " and date_format(a.fecha_importado,'%Y-%m-%d') >= '".$_REQUEST["fecha_ini"]."'";
}
elseif($_REQUEST["fecha_fin"] != "")
{
    $rango_fechas = " and date_format(a.timestamp,'%Y-%m-%d') <= '".$_REQUEST["fecha_fin"]."'";
    $rango_fechas_c = " and date_format(a.fecha_importado,'%Y-%m-%d') <= '".$_REQUEST["fecha_fin"]."'";
}

$sql  = "SELECT gid, super FROM users WHERE uid='$uid'";
$result = $db->sql_query($sql) or die("Error");
list($gid, $super) = $db->sql_fetchrow($result);
$gid = sprintf("%04d", $gid);
if ($super > 6){
  	$_html = "<h1>Usted no es un Gerente</h1>";
} 
else
{
    $sql  = "SELECT uid, user FROM users WHERE gid='$gid' AND super = '8'";
    $result = $db->sql_query($sql) or die("Error2");
    while (list($uid, $user) = $db->sql_fetchrow($result))
    {
        if($class == "row1")
            $class = "row2";
        else
            $class = "row1";
	$sql2 = "SELECT COUNT(log_id) FROM crm_contactos_asignacion_log a WHERE uid = '$uid' $rango_fechas";
	$result2 = $db->sql_query($sql2) or die("Error2");
	list($cant_prospectos) = $db->sql_fetchrow($result2);
	$sql2 = "SELECT COUNT(uid) FROM crm_contactos a WHERE uid = '$uid' $rango_fechas_c";
	$result2 = $db->sql_query($sql2) or die("Error2");
	list($cant_asignados) = $db->sql_fetchrow($result2);

        $no_asignados_gte=$cant_asignados - $cant_prospectos;
        if($no_asignados_gte< 1 )$no_asignados_gte=0;

        $total=$no_asignados_gte + $cant_prospectos;
        $promedio=0.00;
        if($total > 0) $promedio=( ($cant_prospectos/$total) * 100);

	$_html .= "<tr class='$class'>
                        <td width='21%' class='tdleft'>$user</td>
                        <td width='21%' class='tdcenter'>$no_asignados_gte</td>
                        <td width='21%' class='tdcenter'>$cant_prospectos</td>
                        <td width='21%' class='tdcenter'>$total</td>
                        <td width='16%' class='tdcenter'>".number_format($promedio,2,'.',',')."</td>
                    </tr>";
    }
}

?>
