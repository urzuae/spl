<?php
if (!defined('_IN_MAIN_INDEX')) {
    die ("No puedes acceder directamente a este archivo...");
}
global $db,$gid, $ano_id,$mes_id,$dia;
include_once("funcion_metas.php");
if( ($ano_id == 0 ) || ($ano_id == ''))
    $ano_id=date('Y');
if( ($mes_id == 0) || ($mes_id == ''))
    $mes_id=date('m');

$sql="SELECT fecha_notificacion FROM crm_notificaciones WHERE gid='".$gid."' AND YEAR(fecha_notificacion)='".$ano_id."' AND MONTH(fecha_notificacion)='".$mes_id."' ORDER BY id;";
$res=$db->sql_query($sql);
if($db->sql_numrows($res) > 0)
{
    while(list($fecha_notificacion) = $db->sql_fetchrow($res))
    {
        $array_dias[]=$fecha_notificacion;
    }
}
else
{
    $array_dias= Viernes_del_mes($dia,$ano_id,$mes_id);
}

// recorremos el array
if(count($array_dias) > 0)
{
    $date=date("Y-m-d H:i:s");
    $buffer="
        <table cellspacing='2' cellpadding='2' border='0' align='center' width='80%'>
            <thead>
                <tr height='40'>
                    <th colspan='2' align='center'>Seleccione las fechas del mes, en los cuales el sistema le enviar&aacute; las notificaciones</th>
                </tr>
            </thead>
            <tbody>
            <tr class='row2' height='50'>
                <td colspan='2' align='center'>
                    <table width='50%' align='center' style='border:0px;'>
                    <tr>
                    <td colspan='2' class='tdleft'>
                        <b>Se sugiere que las notificaciones sean enviadas los d&iacute;as viernes.</b>
                    </td></tr>";
    foreach($array_dias as $ind => $fecha)
    {
        $fecha_inv='';
        $retraso=0;
        $i=$ind + 1;
        $disabled="";
        $sql="select TIMESTAMPDIFF(DAY,'".$fecha."','".$date."') as retrasos;";
        $res=$db->sql_query($sql) or die("Error en la consulta:  ".$sql);
        list($retraso)= $db->sql_fetchrow($res);
        if($retraso > 0)
            $disabled=" readonly='true'";
        if($fecha != '')
            $fecha_inv=substr($fecha,8,2).'-'.substr($fecha,5,2).'-'.substr($fecha,0,4);
        $buffer.="
        <tr class='row2' height='30'>
            <td width='30%' align='right'>Fecha ".$i."</td>
            <td width='70%'>
                <input type='text' name='fecha_".$i."' id='fecha_".$i."' value='".$fecha_inv."' ".$disabled.">";
        if($retraso <= 0)
        {
            $buffer.="<img src='img/calendar.gif' id='f_trigger_".$i."' style='border: 1px solid white; cursor: pointer;' title='Fecha' onmouseover=\"this.style.background='white';\" onmouseout=\"this.style.background=''\">";
        }
        $buffer.="</td></tr>";
        if($retraso <= 0)
        {
            $buffer.="
                <script>
                    Calendar.setup(
                    {
                        inputField :'fecha_".$i."',
                        ifFormat :'%d-%m-%Y',
                        button : 'f_trigger_".$i."'
                    });
            </script>";
        }
    }
    $buffer.="
            </table></td></tr>
        </tbody>
    </table>";
}
echo $buffer;
die();
?>