<?php
if (!defined('_IN_MAIN_INDEX'))
{
    die ("No puedes acceder directamente a este archivo...");
}
global $db,$uid,$_includesdir,$ano_id,$mes_id,$fecha_1,$fecha_2,$fecha_3,$fecha_4,$fecha5,$guarda_notificacion,$_site_name,$_modulesdir,$_site_title;
include_once("funcion_metas.php");
$_site_title = "Proyeccion-SFG0005";
$msg="";
$select_ano  = Regresa_Combo_Anos($ano_id);
$select_meses= Regresa_Combo_Meses_Simple($meses_id);
$sql  = "SELECT gid, super FROM users WHERE uid='".$uid."'";
$result = $db->sql_query($sql) or die("Error");
list($gid, $super) = $db->sql_fetchrow($result);
if($super == 6)
{
    if($guarda_notificacion)
    {
        sleep(2);
        $id_mes=$mes_id + 0;
        $del="DELETE FROM crm_notificaciones WHERE gid='".$gid."' AND YEAR(fecha_notificacion)='".$ano_id."' AND MONTH(fecha_notificacion)='".$id_mes."';";
        $db->sql_query($del) or die("Error en el delete  ".$del);
        $date=date("Y-m-d H:i:s");
        if($fecha_1)
        {
            $fecha_1=substr($fecha_1,6,4).'-'.substr($fecha_1,3,2).'-'.substr($fecha_1,0,2);
            $db->sql_query("INSERT INTO crm_notificaciones (gid,fecha_notificacion,fecha_registro) VALUES ('".$gid."','".$fecha_1."','".$date."');");
        }
        if($fecha_2)
        {
            $fecha_2=substr($fecha_2,6,4).'-'.substr($fecha_2,3,2).'-'.substr($fecha_2,0,2);
            $db->sql_query("INSERT INTO crm_notificaciones (gid,fecha_notificacion,fecha_registro) VALUES ('".$gid."','".$fecha_2."','".$date."');");
        }
        if($fecha_3)
        {
            $fecha_3=substr($fecha_3,6,4).'-'.substr($fecha_3,3,2).'-'.substr($fecha_3,0,2);
            $db->sql_query("INSERT INTO crm_notificaciones (gid,fecha_notificacion,fecha_registro) VALUES ('".$gid."','".$fecha_3."','".$date."');");
        }
        if($fecha_4)
        {
            $fecha_4=substr($fecha_4,6,4).'-'.substr($fecha_4,3,2).'-'.substr($fecha_4,0,2);
            $db->sql_query("INSERT INTO crm_notificaciones (gid,fecha_notificacion,fecha_registro) VALUES ('".$gid."','".$fecha_4."','".$date."');");
        }
        if($fecha_5)
        {
            $fecha_5=substr($fecha_5,6,4).'-'.substr($fecha_5,3,2).'-'.substr($fecha_5,0,2);
            $db->sql_query("INSERT INTO crm_notificaciones (gid,fecha_notificacion,fecha_registro) VALUES ('".$gid."','".$fecha_5."','".$date."');");
        }
    }
}
?>