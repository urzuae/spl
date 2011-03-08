<?php
if (!defined('_IN_MAIN_INDEX'))
{
    die ("No puedes acceder directamente a este archivo...");
}
global $db,$uid,$_includesdir,$cantidad,$guarda_proyeccion,$id_user,$select_ano,$select_mes,$meses_id,$ano_id,$_site_name,$_host_name,$obligatorio,$_site_title;
include_once("funcion_metas.php");
$_site_title = "Proyeccion-SFG0003";
$select_ano  = Regresa_Combo_Anos($ano_id);
$select_meses= Regresa_Combo_Meses($meses_id);
$array_meses=Regresa_Array_Meses();
$sql  = "SELECT gid, super FROM users WHERE uid='".$uid."'";
$result = $db->sql_query($sql) or die("Error");
list($gid, $super) = $db->sql_fetchrow($result);
if($super == 6)
{
    $array_no_registrados=array();
    $array_errores_fechas=array();
    $array_errores_vendedores=array();

    $array_insertados=array();
    $array_uids=Regresa_Vendedores($db,$gid,0);
    $combo_vendedores=Regresa_Combo_Vendedores($db,$gid,$id_user);
    if($guarda_proyeccion)
    {
        if(count($id_user)>0)
        {
            $no_dias=0;
            $array_dias_meses=Regresa_Combo_Dias();
            $contador_errores=0;
            $contador_insertados=0;
            $contador_errores_fechas=0;
            $contador_errores_vendedores=0;
            foreach ($id_user as $_usuario_id)
            {
                if(count($meses_id) >0)
                {
                    foreach($meses_id as $mes_id)
                    {
                        if(Checa_Meses_Anteriores($db,$gid,$ano_id,$mes_id,$_usuario_id)==1)
                        {
                            if(Desactiva_Meta_Existente($db,$gid,$_usuario_id,$ano_id,$mes_id)==0)
                            {
                                $fecha_ini=$ano_id.'-'.$mes_id."-01 00:01:01";
                                $fecha_fin=$ano_id.'-'.$mes_id."-".$array_dias_meses[$mes_id]." 23:59:59";
                                $signos = array('$',',');
                                $cantidad = str_replace($signos,'',$cantidad);
                                if(Busca_Meta($db,$gid,$_usuario_id,$fecha_ini,$fecha_fin,$cantidad) == 0)
                                {
                                    if(Inserta_Nueva_Meta($db,$gid,$_usuario_id,$fecha_ini,$fecha_fin,$cantidad)>0)
                                    {
                                        $contador_insertados++;
                                        $array_insertados[]=$array_meses[$mes_id];
                                    }
                                }
                                else
                                {
                                    $contador_errores_fechas++;
                                    $array_errores_fechas[]=$array_meses[$mes_id];

                                }
                            }
                            else
                            {
                                $contador_errores++;
                                $array__error_registrados[]=$array_meses[$mes_id];
                            }
                        }
                        else
                        {
                            $contador_errores_vendedores++;
                            $array_errores_vendedores[]=$array_meses[$mes_id];
                        }
                    }
                }
            }
            $msg='';
            if($contador_insertados > 0 )
            {
                $tmp="Mes ".implode(',',$array_insertados)." se establecieron correctamente";
                if($contador_insertados > 1)
                    $tmp="Meses ".implode(',',$array_insertados)." se establecio correctamente";
                #$msg.=$tmp;
                $msg.="\nLos objetivos de metas se han establecidos";
            }
            if($contador_errores > 0 )
            {
                $tmp="<br>Alertas:  Mes ".implode(',',$array__error_registrados)." ya cuenta con objetivo de venta registrado.";;
                if($contador_errores > 1)
                    $tmp="<br>Meses ".implode(',',$array__error_registrados)." ya cuentan con el objetivo de venta registrado.";
                #$msg.=$tmp;
                $msg.="\nLos objetivos de metas ya estan registradas";
            }
            if($contador_errores_fechas > 0)
            {
                $tmp="<br>Alertas".implode(',',$array_errores_fechas)." contiene errores en las fechas.";
                if($contador_errores_fechas > 1)
                    $tmp="<br>Meses ".implode(',',$array_errores_fechas)." contienen errores en las fechas.";
                #$msg.=$tmp;
                $msg.="\nLos objetivos de metas contienen errores en las fechas";
            }
            if($contador_errores_vendedores > 0)
            {
                $tmp="<br>Alertas:  Mes ".implode(',',$array_errores_vendedores)." no se establecio, por que el mes anterior no tiene objetivo de venta.";
                if($contador_errores_vendedores > 1)
                $tmp="<br>Meses ".implode(',',$array_errores_vendedores)." no se establecieron por que existen meses anteriores sin registrar.";
                #$msg.=$tmp;
                $msg.="\nLos objetivos de metas no se establecieron por que existen meses anteriores sin registrar.";
            }
        }
        else
        {
            $msg="Por favor, capture todos los campos";
        }
        echo $msg;
        die();
    }
}

function Busca_Meta($db,$gid,$id,$fecha_inicio,$fecha_final,$cantidad)
{
    $reg=0;
    $sql="SELECT id FROM crm_proyeccion WHERE gid='".$gid."' AND uid='".$id."' AND fecha_inicio='".$fecha_inicio."' AND fecha_concluye='".$fecha_final."' AND cantidad='".$cantidad."';";
    $res=$db->sql_query($sql) or die("Error en la consulta de vendedores:  ".$sql);
    if($db->sql_numrows($res) > 0)
    {
        $reg=1;
    }
    return $reg;
}
function Inserta_Nueva_Meta($db,$gid,$uid,$fecha_inicio,$fecha_final,$cantidad)
{
    # calculamos el numero de dias;
    $reg=0;
    $sql="select TIMESTAMPDIFF(DAY,'".$fecha_inicio."','".$fecha_final."') as dias;";
    $res=$db->sql_query($sql) or die ("Error en la consulta:  ".$sql);
    list($no_dias)= $db->sql_fetchrow($res);
    if($no_dias >= 0)
    {
        $no_dias=$no_dias+1;
        $fecha_actual=date('Y-m-d H:i:s');
        $ins="INSERT INTO crm_proyeccion (gid,uid,fecha_inicio,fecha_concluye,cantidad,no_dias,timestamp,active)
          VALUES ('".$gid."','".$uid."','".$fecha_inicio."','".$fecha_final."','".$cantidad."','".$no_dias."','".$fecha_actual."','1');";
        if($db->sql_query($ins) or die ("Error en el update:  ".$ins))
            $reg=1;
    }
    return $reg;

}

function Desactiva_Meta_Existente($db,$gid,$id,$ano_id,$mes_id)
{
    $reg=0;
    $sql="SELECT id FROM crm_proyeccion WHERE gid='".$gid."' AND uid='".$id."' AND YEAR(fecha_inicio) ='".$ano_id."' AND MONTH(fecha_inicio) ='".$mes_id."';";
    $res=$db->sql_query($sql) or die ("Error en el update:  ".$sql);
    if($db->sql_numrows($res)>0)
        $reg=1;
    return $reg;
}

function Checa_Meses_Anteriores($db,$gid,$ano_id,$mes_id,$_usuario_id)
{
    $reg=1;
    $tmp_mes=$mes_id + 0;

    if($tmp_mes>1)
    {
        if($tmp_mes==2)
        {
            $inicio=($tmp_mes - 1);
            $final=($tmp_mes - 1);
        }
        else
        {
            $inicio=($tmp_mes - 1);
            $final=($tmp_mes - 2);
        }
        for($j=$inicio; $j >=$final; $j--)
        {
            if(Revisa_Mes_Metas($db,$gid,$j,$ano_id,$_usuario_id)==0)
            {
                $reg=0;
            }
        }
    }
    return $reg;
}
function Revisa_Mes_Metas($db,$gid,$j,$ano_id,$id)
{
    $reg=0;
    $sql="SELECT id FROM crm_proyeccion WHERE gid='".$gid."' AND uid='".$id."' AND YEAR(fecha_inicio) ='".$ano_id."' AND MONTH(fecha_inicio) ='".$j."';";
      $res=$db->sql_query($sql) or die ("Error en la consulta:  ".$sql);
    if($db->sql_numrows($res)>0)
        $reg=1;
    return $reg;
}
?>