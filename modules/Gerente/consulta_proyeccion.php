<?php
  if (!defined('_IN_MAIN_INDEX')) {
    die ("No puedes acceder directamente a este archivo...");
}
global $db, $uid, $id_user,$_includesdir,$_modulesdir,$ano_id,$buscar_proyeccion,$_site_title;
include_once("funcion_metas.php");
$_site_title = "Proyeccion-SFG0004";
$select_ano  = Regresa_Combo_Anos($ano_id);

if($ano_id == '') $ano_id=date('Y');

$id_user=0;
$filtro='';
$sql  = "SELECT gid, super FROM users WHERE uid='$uid'";
$result = $db->sql_query($sql) or die("Error");
list($gid, $super) = $db->sql_fetchrow($result);
if ($super == "6")
{
    if($ano_id != '')
    {
        if($super == 8) $id_user=$uid;   #asigna a id_user el id del vendedor en caso que lo sea

        $array_vendedores=Regresa_Uids_Vendedores($db,$gid,$id_user);  #recupero los ids de los vendedores de la concesionaria
        $array_nms_vendedores=Regresa_Vendedores($db,$gid,$id_user);   #recupero los nombrede de los vendedeores de la con
        $array_meses=Regresa_Array_Meses();  #array con meses

        $buffer.="<p align='center' class='title'>
                   Objetivos de ventas del a&ntilde;o ".$ano_id."</p>
                   <table border='0' align='center' class='tablesorter' width='100%'>
                    <thead><tr>
                    <th class='tdcenter' width='9%'>Vendedor</th>";
        foreach($array_meses as $id_mes => $nm_mes)   #Pinto encabezados por mes
        {
            $buffer.="<th class='tdcenter'>".substr($nm_mes,0,3)."</th>";
        }
        $buffer.="<th class='tdcenter'>Total</th><th class='tdcenter'>Eliminar</th></tr></thead><tbody>";

        #recorro todos los vendedores
        $array_totales=array();
        foreach($array_vendedores as $_uid)
        {
            $total=0;
            $array_datos=Recupera_proyeccion($db,$gid,$_uid,$ano_id);  # veo si tiene asignadas metas
            if(count($array_datos) > 0)
            {
                $array_metas[$_uid]=normaliza_a_meses_totales($array_datos);
                $buffer.="<tr ><td class='tdleft'>".$array_nms_vendedores[$_uid]."</td>";
                foreach($array_meses as $mes_id => $valor)
                {
                    $mes_id=$mes_id + 0 ;
                    $total=$total + $array_metas[$_uid][$mes_id];
                    $array_totales[$mes_id] = $array_totales[$mes_id] + $array_metas[$_uid][$mes_id];
                    $buffer.="<td width='7%' class='tdright'><a href='index.php?_module=Gerente&_op=actualiza_proyeccion&uid=".$uid."&user_id=".$_uid."&ano=".$ano_id."&mes=".$mes_id."'>".number_format($array_metas[$_uid][$mes_id],0)."</a></td>";
                }
                $buffer.="<td class='tdright'>".number_format($total,0)."</td><td class='tdright'>
                    <a href=\"#\" onclick=\"elimina_meta('$gid','$_uid','$ano_id','0')\"><img src=\"img/del.gif\" alt=\"Ayuda\" title=\"Elimina Meta\"   border=\"0\"></a>
                    </td></tr>";
            }
        }
        $buffer.="</tbody><thead><tr><th class='tdleft'>Totales</th>";
        foreach($array_meses as $mes_id => $valor)
        {
            $mes_id=$mes_id + 0 ;
            $total_anual=$total_anual + $array_totales[$mes_id];
            $buffer.="<th width='7%' class='tdright'>".number_format($array_totales[$mes_id],0)."</th>";
        }
        $buffer.="<th class='tdright'>".number_format($total_anual,0)."</th><th>&nbsp;</th></tr></thead></table>
            <br><center>Para actualizar un objetivo de venta s&oacute;lo debe dar clic sobre la cantidad.";
        #saco las metas registradas
    }
}

    function Recupera_proyeccion($db,$gid,$_uid,$ano_id)
    {
        $array=array();
        $sql="SELECT month(b.fecha_inicio) AS mes,sum(b.cantidad) AS total
              FROM crm_proyeccion as b
              WHERE b.active = 1 AND year(b.fecha_inicio) ='".$ano_id."' AND b.gid='".$gid."' AND b.uid=".$_uid."
              GROUP BY substr(b.fecha_inicio,1,7)
              ORDER BY substr(b.fecha_inicio,1,7)";
        $res=$db->sql_query($sql) or die("Error:   ".$sql);
        $num=$db->sql_numrows($res);
        if( $num > 0)
        {
            $total=0;
            while(list($mes,$cantidad) = $db->sql_fetchrow($res))
            {
               $array[$mes]  = $cantidad;
            }
        }
        return $array;
    }

    
    function normaliza_a_meses_totales($array)
    {
        $array_regreso = array();
	if (count($array) > 0)
        {
            $array_regreso = inicializa_arreglo();
            $total = 0;
            foreach ($array_regreso as $clave => $valor)
            {
                $valor_array = $array[$clave] + 0;
		$array_regreso[$clave] = $valor_array;
		$total = $total + $valor_array;
            }
            $array_regreso[13] = $total;
        }
	return $array_regreso;
    }
    function inicializa_arreglo()
    {
        $max = 12;
	for ($pos = 1; $pos <= $max; $pos++) {
            $array_tmp[$pos] = 0;
	}
	return $array_tmp;
    }

?>